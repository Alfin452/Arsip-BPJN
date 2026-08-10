<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Spm;
use App\Models\Satker;
use App\Models\Ppk;
use App\Models\User;
use App\Notifications\SpmSubmittedNotification;
use Illuminate\Support\Facades\Notification;

class SpmController extends Controller
{
    public function index()
    {
        $spms = Spm::with('uploader', 'verifier', 'satker', 'ppk')->latest()->paginate(10);
        return view('spm.index', compact('spms'));
    }

    public function create()
    {
        $satkers = Satker::all();
        $ppks = Ppk::all();
        return view('spm.create', compact('satkers', 'ppks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_spm' => 'required|unique:spms',
            'tanggal_spm' => 'required|date',
            'nilai_spm' => 'required|numeric',
            'tahun_anggaran' => 'required|string|max:4',
            'jenis_spm' => 'required|string',
            'satker_id' => 'required|exists:satkers,id',
            'ppk_id' => 'required|exists:ppks,id',
            'uraian' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'file_spm' => 'required|file|mimes:pdf|max:10240',
            'file_kuitansi' => 'nullable|file|mimes:pdf|max:10240',
            'file_surat_tugas' => 'nullable|file|mimes:pdf|max:10240',
            'file_laporan' => 'nullable|file|mimes:pdf|max:10240',
            'file_dokumentasi' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10240',
        ]);

        $spm = Spm::create([
            'nomor_spm' => $validated['nomor_spm'],
            'tanggal_spm' => $validated['tanggal_spm'],
            'nilai_spm' => $validated['nilai_spm'],
            'tahun_anggaran' => $validated['tahun_anggaran'],
            'jenis_spm' => $validated['jenis_spm'],
            'satker_id' => $validated['satker_id'],
            'ppk_id' => $validated['ppk_id'],
            'uraian' => $validated['uraian'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
            'status' => 'Draft',
            'uploaded_by' => auth()->id(),
        ]);

        $types = ['spm', 'kuitansi', 'surat_tugas', 'laporan', 'dokumentasi'];
        foreach($types as $type) {
            $inputName = 'file_' . $type;
            if ($request->hasFile($inputName)) {
                $path = $request->file($inputName)->store('public/spms');
                $spm->attachments()->create([
                    'tipe_file' => $type,
                    'file_path' => str_replace('public/', '', $path)
                ]);
            }
        }

        $spm->histories()->create([
            'user_id' => auth()->id(),
            'status' => 'Draft',
            'catatan' => 'SPM dibuat pertama kali'
        ]);

        $atasanUsers = User::where('role', 'atasan')->get();
        Notification::send($atasanUsers, new SpmSubmittedNotification($spm));

        return redirect()->route('spm.index')->with('success', 'Dokumen SPM beserta lampirannya berhasil diunggah.');
    }

    public function show($id)
    {
        $spm = Spm::with('uploader', 'verifier', 'satker', 'ppk', 'attachments', 'histories.user')->findOrFail($id);
        return view('spm.show', compact('spm'));
    }
}
