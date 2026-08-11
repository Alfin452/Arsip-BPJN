<?php

namespace App\Http\Controllers;

use App\Models\Satker;
use Illuminate\Http\Request;

class SatkerController extends Controller
{
    public function index(Request $request)
    {
        $query = Satker::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_satker', 'like', "%{$search}%")
                  ->orWhere('kode_satker', 'like', "%{$search}%");
        }

        $satkers = $query->orderBy('kode_satker', 'asc')->get();
        return view('satker.index', compact('satkers'));
    }

    public function create()
    {
        return view('satker.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_satker' => 'required|unique:satkers,kode_satker|max:20',
            'nama_satker' => 'required|string|max:255',
        ]);

        Satker::create($validated);

        return redirect()->route('satker.index')->with('success', 'Satuan Kerja berhasil ditambahkan.');
    }

    public function edit(Satker $satker)
    {
        return view('satker.form', compact('satker'));
    }

    public function update(Request $request, Satker $satker)
    {
        $validated = $request->validate([
            'kode_satker' => 'required|max:20|unique:satkers,kode_satker,' . $satker->id,
            'nama_satker' => 'required|string|max:255',
        ]);

        $satker->update($validated);

        return redirect()->route('satker.index')->with('success', 'Satuan Kerja berhasil diperbarui.');
    }

    public function destroy(Satker $satker)
    {
        $satker->delete();
        return redirect()->route('satker.index')->with('success', 'Satuan Kerja berhasil dihapus.');
    }
}
