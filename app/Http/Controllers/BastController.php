<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bast;
use App\Models\PaketPekerjaan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BastController extends Controller
{
    public function index(Request $request)
    {
        $query = Bast::with(['paketPekerjaan.penyedia', 'paketPekerjaan.satker', 'paketPekerjaan.ppk']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_bast', 'like', "%{$search}%")
                  ->orWhere('nomor_penagihan', 'like', "%{$search}%")
                  ->orWhereHas('paketPekerjaan', function($q) use ($search) {
                      $q->where('nama_paket', 'like', "%{$search}%")
                        ->orWhereHas('penyedia', function($q) use ($search) {
                            $q->where('nama_perusahaan', 'like', "%{$search}%");
                        });
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('paket_pekerjaan_id')) {
            $query->where('paket_pekerjaan_id', $request->paket_pekerjaan_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_bast', [$request->start_date, $request->end_date]);
        }

        $basts = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();
        $paketPekerjaans = PaketPekerjaan::all();

        return view('bast.index', compact('basts', 'paketPekerjaans'));
    }

    public function show(Request $request, $id)
    {
        $bast = Bast::with(['paketPekerjaan.satker', 'paketPekerjaan.ppk', 'paketPekerjaan.penyedia', 'uploader', 'verifier'])->findOrFail($id);
        
        if ($request->ajax()) {
            return view('bast.partials.modal_detail', compact('bast'));
        }
        
        return view('bast.show', compact('bast')); // Optional fallback
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'atasan') abort(403, 'Anda tidak memiliki hak akses untuk mengubah data.');

        $request->validate([
            'paket_pekerjaan_id' => 'required|exists:paket_pekerjaans,id',
            'nomor_bast' => 'required|unique:basts,nomor_bast',
            'tanggal_bast' => 'required|date',
            'nomor_penagihan' => 'nullable|string',
            'tanggal_penagihan' => 'nullable|date',
            'nilai_penagihan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'file_dokumen' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = $request->except('file_dokumen');
        $data['uploaded_by'] = auth()->id();
        $data['status'] = 'Menunggu Verifikasi';

        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('basts', $filename, 'public');
            $data['file_dokumen'] = 'basts/' . $filename;
        }

        Bast::create($data);

        return redirect()->route('basts.index')->with('success', 'Data BAST & Penagihan berhasil ditambahkan.');
    }

    public function update(Request $request, Bast $bast)
    {
        if (auth()->user()->role === 'atasan') abort(403, 'Anda tidak memiliki hak akses untuk mengubah data.');

        $request->validate([
            'paket_pekerjaan_id' => 'required|exists:paket_pekerjaans,id',
            'nomor_bast' => 'required|unique:basts,nomor_bast,' . $bast->id,
            'tanggal_bast' => 'required|date',
            'nomor_penagihan' => 'nullable|string',
            'tanggal_penagihan' => 'nullable|date',
            'nilai_penagihan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'file_dokumen' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = $request->except('file_dokumen');

        if ($request->hasFile('file_dokumen')) {
            if ($bast->file_dokumen) {
                Storage::disk('public')->delete($bast->file_dokumen);
            }
            $file = $request->file('file_dokumen');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('basts', $filename, 'public');
            $data['file_dokumen'] = 'basts/' . $filename;
        }

        $bast->update($data);

        return redirect()->route('basts.index')->with('success', 'Data BAST & Penagihan berhasil diperbarui.');
    }

    public function destroy(Bast $bast)
    {
        if (auth()->user()->role === 'atasan') abort(403, 'Anda tidak memiliki hak akses untuk mengubah data.');

        if ($bast->file_dokumen) {
            Storage::disk('public')->delete($bast->file_dokumen);
        }
        
        $bast->delete();

        return redirect()->route('basts.index')->with('success', 'Data BAST & Penagihan berhasil dihapus.');
    }

    public function updateStatus(Request $request, Bast $bast)
    {
        $request->validate([
            'status' => 'required|in:Menunggu Verifikasi,Terverifikasi,Ditolak',
        ]);

        $bast->status = $request->status;
        
        if ($request->status === 'Terverifikasi') {
            $bast->verified_by = auth()->id();
            $bast->verified_at = now();
        }

        $bast->save();

        return redirect()->route('basts.index')->with('success', 'Status BAST berhasil diperbarui.');
    }

    public function streamFile($id)
    {
        $bast = Bast::findOrFail($id);
        
        if (!$bast->file_dokumen || !Storage::disk('public')->exists($bast->file_dokumen)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }
        
        return response()->file(Storage::disk('public')->path($bast->file_dokumen));
    }
}
