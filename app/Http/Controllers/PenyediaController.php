<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penyedia;

class PenyediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Penyedia::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('npwp', 'like', "%{$search}%");
        }

        $penyedias = $query->orderBy('nama_perusahaan', 'asc')->get();

        return view('penyedia.index', compact('penyedias'));
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'atasan'])) {
            abort(403, 'Hanya Admin dan Atasan yang dapat menambah Penyedia Jasa.');
        }

        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'nama_direktur' => 'nullable|string|max:255',
            'bank' => 'nullable|string|max:100',
            'no_rekening' => 'nullable|string|max:100',
        ]);

        Penyedia::create($validated);

        return redirect()->route('penyedias.index')->with('success', 'Data Penyedia Jasa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'atasan'])) {
            abort(403, 'Hanya Admin dan Atasan yang dapat mengubah Penyedia Jasa.');
        }

        $penyedia = Penyedia::findOrFail($id);

        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'nama_direktur' => 'nullable|string|max:255',
            'bank' => 'nullable|string|max:100',
            'no_rekening' => 'nullable|string|max:100',
        ]);

        $penyedia->update($validated);

        return redirect()->route('penyedias.index')->with('success', 'Data Penyedia Jasa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'atasan'])) {
            abort(403, 'Hanya Admin dan Atasan yang dapat menghapus Penyedia Jasa.');
        }

        $penyedia = Penyedia::findOrFail($id);
        $penyedia->delete();

        return redirect()->route('penyedias.index')->with('success', 'Data Penyedia Jasa berhasil dihapus.');
    }
}
