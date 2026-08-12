<?php

namespace App\Http\Controllers;

use App\Models\Sp2d;
use App\Models\Spm;
use App\Models\Satker;
use App\Models\Ppk;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Sp2dController extends Controller
{
    public function index(Request $request)
    {
        $query = Sp2d::with(['spm.satker', 'spm.ppk', 'uploader', 'verifier']);

        // Filter Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_sp2d', 'like', "%{$search}%")
                  ->orWhereHas('spm', function ($q2) use ($search) {
                      $q2->where('nomor_spm', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Date Range
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_sp2d', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_sp2d', '<=', $request->end_date);
        }

        // Admin and Atasan filters (Satker & PPK based on parent SPM)
        if (in_array(auth()->user()->role, ['admin', 'atasan'])) {
            if ($request->filled('satker_id')) {
                $query->whereHas('spm', function ($q) use ($request) {
                    $q->where('satker_id', $request->satker_id);
                });
            }
            if ($request->filled('ppk_id')) {
                $query->whereHas('spm', function ($q) use ($request) {
                    $q->where('ppk_id', $request->ppk_id);
                });
            }
        }

        // Stats
        $stats = [
            'total' => Sp2d::count(),
            'menunggu' => Sp2d::where('status', 'Menunggu Verifikasi')->count(),
            'terverifikasi' => Sp2d::where('status', 'Terverifikasi')->count(),
            'total_cair' => Sp2d::where('status', 'Terverifikasi')->sum('nilai_sp2d')
        ];

        $sp2ds = $query->latest()->paginate(10);
        $satkers = Satker::all();
        $ppks = Ppk::all();

        if ($request->ajax()) {
            return view('sp2d.partials.table', compact('sp2ds'))->render();
        }

        return view('sp2d.index', compact('sp2ds', 'stats', 'satkers', 'ppks'));
    }

    public function create(Request $request)
    {


        // Hanya SPM yang Terverifikasi dan BELUM memiliki SP2D
        $spms = Spm::with(['satker', 'ppk'])
                   ->where('status', 'Terverifikasi')
                   ->doesntHave('sp2d')
                   ->get();

        $selectedSpmId = $request->query('spm_id');

        return view('sp2d.create', compact('spms', 'selectedSpmId'));
    }

    public function store(Request $request)
    {


        $validated = $request->validate([
            'spm_id' => 'required|exists:spms,id',
            'nomor_sp2d' => 'required|unique:sp2ds',
            'tanggal_sp2d' => 'required|date',
            'nilai_sp2d' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'file_pdf' => 'required|file|mimes:pdf|max:10240',
        ]);

        $spm = Spm::findOrFail($validated['spm_id']);

        // Validasi nominal SP2D tidak melebihi nominal SPM
        if ($validated['nilai_sp2d'] > $spm->nilai_spm) {
            return back()->withErrors(['nilai_sp2d' => 'Nilai SP2D tidak boleh lebih besar dari Nilai SPM (Rp ' . number_format($spm->nilai_spm, 0, ',', '.') . ').'])->withInput();
        }

        if ($request->hasFile('file_pdf')) {
            $validated['file_pdf'] = $request->file('file_pdf')->store('sp2ds', 'public');
        }

        $validated['uploaded_by'] = auth()->id();
        $validated['status'] = 'Menunggu Verifikasi';

        $sp2d = Sp2d::create($validated);

        // Notifikasi ke semua Admin dan Atasan
        $admins = \App\Models\User::whereIn('role', ['admin', 'atasan'])->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\Sp2dSubmitted($sp2d));

        return redirect()->route('sp2d.index')->with('success', 'Dokumen SP2D berhasil diunggah dan menunggu verifikasi Admin.');
    }

    public function show(Request $request, $id)
    {
        $sp2d = Sp2d::with(['spm.satker', 'spm.ppk', 'uploader', 'verifier'])->findOrFail($id);
        
        if ($request->ajax()) {
            return view('sp2d.partials.modal_detail', compact('sp2d'));
        }
        
        return view('sp2d.show', compact('sp2d'));
    }

    public function streamFile($id)
    {
        $sp2d = Sp2d::findOrFail($id);
        $path = storage_path('app/public/' . $sp2d->file_pdf);
        
        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file($path);
    }

    public function edit($id)
    {


        $sp2d = Sp2d::findOrFail($id);
        
        // Prevent editing if already verified
        if ($sp2d->status === 'Terverifikasi' && !in_array(auth()->user()->role, ['admin', 'atasan'])) {
            return redirect()->route('sp2d.index')->with('error', 'SP2D yang sudah diverifikasi tidak dapat diedit.');
        }

        $spms = Spm::where('status', 'Terverifikasi')
                   ->where(function($query) use ($sp2d) {
                       $query->doesntHave('sp2d')
                             ->orWhere('id', $sp2d->spm_id);
                   })->get();
                   
        return view('sp2d.edit', compact('sp2d', 'spms'));
    }

    public function update(Request $request, $id)
    {


        $sp2d = Sp2d::findOrFail($id);

        if ($sp2d->status === 'Terverifikasi' && !in_array(auth()->user()->role, ['admin', 'atasan'])) {
            return redirect()->route('sp2d.index')->with('error', 'SP2D yang sudah diverifikasi tidak dapat diedit.');
        }

        $validated = $request->validate([
            'spm_id' => 'required|exists:spms,id',
            'nomor_sp2d' => 'required|string|max:255',
            'tanggal_sp2d' => 'required|date',
            'nilai_sp2d' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'file_pdf' => 'nullable|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file_pdf')) {
            // Delete old file
            if ($sp2d->file_pdf && \Illuminate\Support\Facades\Storage::disk('public')->exists($sp2d->file_pdf)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($sp2d->file_pdf);
            }
            $validated['file_pdf'] = $request->file('file_pdf')->store('sp2d_files', 'public');
        }

        // If it was rejected, reset it to pending
        if ($sp2d->status === 'Ditolak') {
            $validated['status'] = 'Menunggu Verifikasi';
            $validated['verified_by'] = null;
            $validated['verified_at'] = null;
        }

        $sp2d->update($validated);

        return redirect()->route('sp2d.index')->with('success', 'Dokumen SP2D berhasil diperbarui.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Terverifikasi,Ditolak',
        ]);

        $sp2d = Sp2d::findOrFail($id);
        $sp2d->status = $request->status;
        
        if ($request->status == 'Terverifikasi') {
            $sp2d->verified_by = auth()->id();
            $sp2d->verified_at = Carbon::now();
            
            // Auto Update Parent SPM Status
            if ($sp2d->spm) {
                $sp2d->spm->update(['status' => 'Tercairkan (SP2D)']);
            }
        } elseif ($request->status == 'Ditolak') {
            $sp2d->verified_by = auth()->id();
            $sp2d->verified_at = Carbon::now();
        }

        $sp2d->save();

        // Notifikasi ke uploader SP2D
        if ($sp2d->uploader && $sp2d->uploader->id !== auth()->id()) {
            $sp2d->uploader->notify(new \App\Notifications\Sp2dStatusUpdated($sp2d));
        }

        return back()->with('success', 'Status SP2D berhasil diperbarui.');
    }

    public function exportCsv(Request $request)
    {
        $query = Sp2d::with(['spm.satker', 'spm.ppk', 'uploader', 'verifier']);
        $sp2ds = $query->latest()->get();

        $filename = "Export_SP2D_" . date('Y-m-d_H-i-s') . ".csv";

        return response()->streamDownload(function () use ($sp2ds) {
            $handle = fopen('php://output', 'w');
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['No', 'Nomor SP2D', 'Tanggal SP2D', 'Nilai SP2D (Rp)', 'Nomor SPM Induk', 'Satker', 'PPK', 'Status', 'Pengunggah', 'Tanggal Verifikasi', 'Keterangan'], ';');

            foreach ($sp2ds as $index => $sp2d) {
                fputcsv($handle, [
                    $index + 1,
                    $sp2d->nomor_sp2d,
                    $sp2d->tanggal_sp2d,
                    $sp2d->nilai_sp2d,
                    $sp2d->spm ? $sp2d->spm->nomor_spm : '-',
                    ($sp2d->spm && $sp2d->spm->satker) ? $sp2d->spm->satker->nama_satker : '-',
                    ($sp2d->spm && $sp2d->spm->ppk) ? $sp2d->spm->ppk->nama : '-',
                    $sp2d->status,
                    $sp2d->uploader ? $sp2d->uploader->name : '-',
                    $sp2d->verified_at ? Carbon::parse($sp2d->verified_at)->format('Y-m-d H:i:s') : '-',
                    $sp2d->keterangan
                ], ';');
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function printReceipt($id)
    {
        $sp2d = Sp2d::with(['spm.satker', 'spm.ppk'])->findOrFail($id);
        
        if ($sp2d->status !== 'Terverifikasi') {
            abort(403, 'Hanya SP2D Terverifikasi yang dapat dicetak.');
        }

        return view('sp2d.receipt', compact('sp2d'));
    }

    public function destroy($id)
    {


        $sp2d = Sp2d::findOrFail($id);

        // Hanya admin/atasan atau pembuat yang boleh menghapus
        if (!in_array(auth()->user()->role, ['admin', 'atasan']) && $sp2d->uploaded_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus file fisik PDF jika ada
        if ($sp2d->file_pdf) {
            $path = storage_path('app/public/' . $sp2d->file_pdf);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        // Jika SP2D terkait dengan SPM, kembalikan status SPM menjadi Terverifikasi
        if ($sp2d->spm && $sp2d->status === 'Terverifikasi') {
            $sp2d->spm->update(['status' => 'Terverifikasi']);
        }

        $sp2d->delete();

        return redirect()->route('sp2d.index')->with('success', 'Dokumen SP2D berhasil dihapus.');
    }
}
