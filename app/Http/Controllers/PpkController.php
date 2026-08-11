<?php

namespace App\Http\Controllers;

use App\Models\Ppk;
use App\Models\Satker;
use Illuminate\Http\Request;

class PpkController extends Controller
{
    public function index(Request $request)
    {
        $query = Ppk::with('satker');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
        }

        $ppks = $query->orderBy('nama', 'asc')->get();
        $satkers = Satker::all();
        
        return view('ppk.index', compact('ppks', 'satkers'));
    }

    public function create()
    {
        $satkers = Satker::all();
        return view('ppk.form', compact('satkers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|unique:ppks,nip|max:50',
            'nama' => 'required|string|max:255',
            'satker_id' => 'required|exists:satkers,id',
        ]);

        Ppk::create($validated);

        return redirect()->route('ppk.index')->with('success', 'Data PPK berhasil ditambahkan.');
    }

    public function edit(Ppk $ppk)
    {
        $satkers = Satker::all();
        return view('ppk.form', compact('ppk', 'satkers'));
    }

    public function update(Request $request, Ppk $ppk)
    {
        $validated = $request->validate([
            'nip' => 'required|max:50|unique:ppks,nip,' . $ppk->id,
            'nama' => 'required|string|max:255',
            'satker_id' => 'required|exists:satkers,id',
        ]);

        $ppk->update($validated);

        return redirect()->route('ppk.index')->with('success', 'Data PPK berhasil diperbarui.');
    }

    public function destroy(Ppk $ppk)
    {
        $ppk->delete();
        return redirect()->route('ppk.index')->with('success', 'Data PPK berhasil dihapus.');
    }
}
