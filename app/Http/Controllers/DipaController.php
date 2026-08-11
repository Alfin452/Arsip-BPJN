<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dipa;
use App\Models\Satker;

class DipaController extends Controller
{
    public function index(Request $request)
    {
        $query = Dipa::with('satker');

        // Filter Tahun Anggaran
        if ($request->filled('tahun')) {
            $query->where('tahun_anggaran', $request->tahun);
        } else {
            // Default tahun saat ini
            $query->where('tahun_anggaran', date('Y'));
        }

        // Filter Satker (Untuk Admin)
        if ($request->filled('satker_id') && auth()->user()->role === 'admin') {
            $query->where('satker_id', $request->satker_id);
        }

        // Filter untuk user biasa (hanya Satker yang di-assign)
        if (auth()->user()->role !== 'admin' && auth()->user()->satker_id) {
            $query->where('satker_id', auth()->user()->satker_id);
        }

        $dipas = $query->orderBy('tahun_anggaran', 'desc')->get();
        $satkers = Satker::all();
        
        // Tahun unik untuk filter dropdown
        $tahuns = Dipa::select('tahun_anggaran')->distinct()->orderBy('tahun_anggaran', 'desc')->pluck('tahun_anggaran');

        return view('dipa.index', compact('dipas', 'satkers', 'tahuns'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang dapat menambah DIPA.');
        }

        $validated = $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'tahun_anggaran' => 'required|digits:4|integer',
            'nomor_dipa' => 'required|string',
            'tanggal_dipa' => 'required|date',
            'nilai_pagu' => 'required|numeric|min:0',
        ]);

        // Cek duplicate
        $exists = Dipa::where('satker_id', $validated['satker_id'])
                      ->where('tahun_anggaran', $validated['tahun_anggaran'])
                      ->exists();
        
        if ($exists) {
            return back()->withErrors(['tahun_anggaran' => 'DIPA untuk Satker dan Tahun Anggaran ini sudah ada.'])->withInput();
        }

        Dipa::create($validated);

        return redirect()->route('dipas.index')->with('success', 'Data DIPA berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang dapat mengubah DIPA.');
        }

        $dipa = Dipa::findOrFail($id);

        $validated = $request->validate([
            'nomor_dipa' => 'required|string',
            'tanggal_dipa' => 'required|date',
            'nilai_pagu' => 'required|numeric|min:0',
        ]);

        $dipa->update($validated);

        return redirect()->route('dipas.index')->with('success', 'Data DIPA berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang dapat menghapus DIPA.');
        }

        $dipa = Dipa::findOrFail($id);
        $dipa->delete();

        return redirect()->route('dipas.index')->with('success', 'Data DIPA berhasil dihapus.');
    }
}
