<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Sp2dController extends Controller
{
    public function index()
    {
        $sp2ds = \App\Models\Sp2d::with('uploader', 'verifier', 'spm')->latest()->paginate(10);
        return view('sp2d.index', compact('sp2ds'));
    }

    public function create()
    {
        return view('sp2d.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_sp2d' => 'required|unique:sp2ds',
            'tanggal_sp2d' => 'required|date',
            'nilai_sp2d' => 'required|numeric',
            'spm_id' => 'nullable|exists:spms,id',
            'keterangan' => 'nullable|string',
            'file_pdf' => 'required|file|mimes:pdf|max:10240', // max 10MB
        ]);

        if ($request->hasFile('file_pdf')) {
            $path = $request->file('file_pdf')->store('public/sp2ds');
            $validated['file_pdf'] = str_replace('public/', '', $path);
        }

        $validated['uploaded_by'] = auth()->id();
        $validated['status'] = 'pending';

        \App\Models\Sp2d::create($validated);

        return redirect()->route('sp2d.index')->with('success', 'Dokumen SP2D berhasil diunggah.');
    }

    public function show($id)
    {
        $sp2d = \App\Models\Sp2d::with('uploader', 'verifier', 'spm')->findOrFail($id);
        return view('sp2d.show', compact('sp2d'));
    }
}
