<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaketPekerjaan;
use App\Models\Satker;
use App\Models\Ppk;
use App\Models\Penyedia;

class PaketPekerjaanController extends Controller
{
    public function index(Request $request)
    {
        $query = PaketPekerjaan::with(['satker', 'ppk', 'penyedia']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_paket', 'like', "%{$search}%")
                  ->orWhere('nomor_kontrak', 'like', "%{$search}%");
        }

        if (auth()->user()->role !== 'admin' && auth()->user()->satker_id) {
            $query->where('satker_id', auth()->user()->satker_id);
        }

        $pakets = $query->orderBy('tanggal_kontrak', 'desc')->get();
        $satkers = Satker::all();
        $ppks = Ppk::all();
        $penyedias = Penyedia::all();

        return view('paket.index', compact('pakets', 'satkers', 'ppks', 'penyedias'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'atasan') abort(403, 'Anda tidak memiliki hak akses untuk mengubah data.');

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang dapat menambah Paket Pekerjaan.');
        }

        $validated = $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'ppk_id' => 'required|exists:ppks,id',
            'penyedia_id' => 'required|exists:penyedias,id',
            'nama_paket' => 'required|string|max:255',
            'nomor_kontrak' => 'required|string|max:255',
            'tanggal_kontrak' => 'required|date',
            'nilai_kontrak' => 'required|numeric|min:0',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        PaketPekerjaan::create($validated);

        return redirect()->route('paket-pekerjaans.index')->with('success', 'Data Paket Pekerjaan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role === 'atasan') abort(403, 'Anda tidak memiliki hak akses untuk mengubah data.');

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang dapat mengubah Paket Pekerjaan.');
        }

        $paket = PaketPekerjaan::findOrFail($id);

        $validated = $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'ppk_id' => 'required|exists:ppks,id',
            'penyedia_id' => 'required|exists:penyedias,id',
            'nama_paket' => 'required|string|max:255',
            'nomor_kontrak' => 'required|string|max:255',
            'tanggal_kontrak' => 'required|date',
            'nilai_kontrak' => 'required|numeric|min:0',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $paket->update($validated);

        return redirect()->route('paket-pekerjaans.index')->with('success', 'Data Paket Pekerjaan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (auth()->user()->role === 'atasan') abort(403, 'Anda tidak memiliki hak akses untuk mengubah data.');

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang dapat menghapus Paket Pekerjaan.');
        }

        $paket = PaketPekerjaan::findOrFail($id);
        $paket->delete();

        return redirect()->route('paket-pekerjaans.index')->with('success', 'Data Paket Pekerjaan berhasil dihapus.');
    }
}
