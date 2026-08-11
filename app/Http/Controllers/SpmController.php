<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Spm;
use App\Models\Satker;
use App\Models\Ppk;
use App\Models\User;
use App\Notifications\SpmSubmitted;
use App\Notifications\SpmStatusUpdated;
use Illuminate\Support\Facades\Notification;

class SpmController extends Controller
{
    public function index(Request $request)
    {
        $query = Spm::with('uploader', 'verifier', 'satker', 'ppk');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nomor_spm', 'like', "%{$search}%")
                  ->orWhere('uraian', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_spm')) {
            $query->where('jenis_spm', $request->jenis_spm);
        }

        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        if ($request->filled('ppk_id')) {
            $query->where('ppk_id', $request->ppk_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_spm', [$request->start_date, $request->end_date]);
        }

        $spms = $query->latest()->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('spm.partials.table', compact('spms'))->render();
        }

        $stats = [
            'total' => Spm::count(),
            'draft' => Spm::where('status', 'Draft')->count(),
            'pending' => Spm::where('status', 'Menunggu Verifikasi')->count(),
            'verified' => Spm::where('status', 'Terverifikasi')->count(),
        ];

        $satkers = Satker::all();
        $ppks = Ppk::all();

        return view('spm.index', compact('spms', 'stats', 'satkers', 'ppks'));
    }

    public function exportCsv(Request $request)
    {
        $query = Spm::with('uploader', 'verifier', 'satker', 'ppk');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nomor_spm', 'like', "%{$search}%")
                  ->orWhere('uraian', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_spm')) {
            $query->where('jenis_spm', $request->jenis_spm);
        }

        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_spm', [$request->start_date, $request->end_date]);
        }

        $spms = $query->latest()->get();

        $filename = "Export_SPM_" . date('Y-m-d_H-i-s') . ".csv";

        return response()->streamDownload(function () use ($spms) {
            $handle = fopen('php://output', 'w');
            // Output BOM untuk excel agar mengenali UTF-8
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['No', 'Nomor SPM', 'Tanggal SPM', 'Jenis SPM', 'Nilai SPM', 'Satker', 'PPK', 'Status', 'Pengunggah', 'Tanggal Verifikasi', 'Uraian'], ';');

            foreach ($spms as $index => $spm) {
                fputcsv($handle, [
                    $index + 1,
                    $spm->nomor_spm,
                    $spm->tanggal_spm,
                    $spm->jenis_spm,
                    $spm->nilai_spm,
                    $spm->satker ? $spm->satker->nama_satker : '-',
                    $spm->ppk ? $spm->ppk->nama : '-',
                    $spm->status,
                    $spm->uploader ? $spm->uploader->name : '-',
                    $spm->verified_at ? \Carbon\Carbon::parse($spm->verified_at)->format('Y-m-d H:i:s') : '-',
                    $spm->uraian
                ], ';');
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function create()
    {
        if (auth()->user()->role === 'atasan') abort(403, 'Anda tidak memiliki hak akses untuk mengubah data.');

        $satkers = Satker::all();
        $ppks = Ppk::all();
        return view('spm.create', compact('satkers', 'ppks'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'atasan') abort(403, 'Anda tidak memiliki hak akses untuk mengubah data.');

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
            'status' => $request->has('is_draft') ? 'Draft' : 'Menunggu Verifikasi',
            'uploaded_by' => auth()->id(),
        ]);

        $types = ['spm', 'kuitansi', 'surat_tugas', 'laporan', 'dokumentasi'];
        foreach($types as $type) {
            $inputName = 'file_' . $type;
            if ($request->hasFile($inputName)) {
                $path = $request->file($inputName)->store('spms', 'public');
                
                $spm->attachments()->updateOrCreate(
                    ['tipe_file' => $type],
                    ['file_path' => $path]
                );
            }
        }

        $statusAwal = $request->has('is_draft') ? 'Draft' : 'Menunggu Verifikasi';
        $spm->histories()->create([
            'user_id' => auth()->id(),
            'status' => $statusAwal,
            'catatan' => 'SPM dibuat pertama kali'
        ]);

        if ($statusAwal === 'Menunggu Verifikasi') {
            $atasanUsers = User::where('role', 'admin')->get();
            Notification::send($atasanUsers, new SpmSubmitted($spm));
        }

        return redirect()->route('spm.index')->with('success', 'Dokumen SPM beserta lampirannya berhasil diunggah.');
    }

    public function show(Request $request, $id)
    {
        $spm = Spm::with('uploader', 'verifier', 'satker', 'ppk', 'attachments', 'histories.user')->findOrFail($id);
        
        if ($request->ajax()) {
            return view('spm.partials.modal_detail', compact('spm'));
        }

        return redirect()->route('spm.index', ['show' => $id]);
    }

    public function streamFile($id)
    {
        $attachment = \App\Models\SpmAttachment::findOrFail($id);
        $path = storage_path('app/public/' . $attachment->file_path);
        
        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file($path);
    }

    public function printReceipt($id)
    {
        $spm = Spm::with('uploader', 'verifier', 'satker', 'ppk')->findOrFail($id);
        
        return view('spm.receipt', compact('spm'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:Terverifikasi,Ditolak',
            'catatan' => 'nullable|string'
        ]);

        $spm = Spm::findOrFail($id);
        $spm->status = $validated['status'];
        $spm->verified_by = auth()->id();
        $spm->verified_at = now();
        $spm->save();

        // Rekam riwayat
        $spm->histories()->create([
            'user_id' => auth()->id(),
            'status' => $spm->status,
            'catatan' => $validated['catatan'] ?? 'Status diubah menjadi ' . $spm->status
        ]);

        if ($spm->uploader) {
            $spm->uploader->notify(new SpmStatusUpdated($spm));
        }

        // Hapus notifikasi "Menunggu Verifikasi" untuk SPM ini dari semua admin
        \Illuminate\Support\Facades\DB::table('notifications')
            ->where('type', 'App\Notifications\SpmSubmitted')
            ->whereJsonContains('data->spm_id', $spm->id)
            ->delete();

        return response()->json([
            'success' => true, 
            'message' => 'Status SPM berhasil diperbarui menjadi ' . $spm->status
        ]);
    }

    public function edit($id)
    {
        if (auth()->user()->role === 'atasan') abort(403, 'Anda tidak memiliki hak akses untuk mengubah data.');

        $spm = Spm::findOrFail($id);
        
        // Hanya bisa edit jika masih draft, ditolak, atau menunggu verifikasi
        if (!in_array($spm->status, ['Draft', 'Menunggu Verifikasi', 'Ditolak'])) {
            return redirect()->route('spm.index')->with('error', 'SPM yang sudah diverifikasi tidak dapat diedit.');
        }

        $satkers = Satker::all();
        $ppks = Ppk::all();
        return view('spm.edit', compact('spm', 'satkers', 'ppks'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role === 'atasan') abort(403, 'Anda tidak memiliki hak akses untuk mengubah data.');

        $spm = Spm::findOrFail($id);
        
        if (!in_array($spm->status, ['Draft', 'Menunggu Verifikasi', 'Ditolak'])) {
            return redirect()->route('spm.index')->with('error', 'SPM ini tidak dapat diedit.');
        }

        $validated = $request->validate([
            'nomor_spm' => 'required|unique:spms,nomor_spm,' . $id,
            'tanggal_spm' => 'required|date',
            'nilai_spm' => 'required|numeric',
            'tahun_anggaran' => 'required|string|max:4',
            'jenis_spm' => 'required|string',
            'satker_id' => 'required|exists:satkers,id',
            'ppk_id' => 'required|exists:ppks,id',
            'uraian' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'file_spm' => 'nullable|file|mimes:pdf|max:10240',
            'file_kuitansi' => 'nullable|file|mimes:pdf|max:10240',
            'file_surat_tugas' => 'nullable|file|mimes:pdf|max:10240',
            'file_laporan' => 'nullable|file|mimes:pdf|max:10240',
            'file_dokumentasi' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10240',
        ]);
        $statusSebelumnya = $spm->status;
        $statusBaru = $request->has('is_draft') ? 'Draft' : 'Menunggu Verifikasi';

        $spm->update([
            'nomor_spm' => $validated['nomor_spm'],
            'tanggal_spm' => $validated['tanggal_spm'],
            'nilai_spm' => $validated['nilai_spm'],
            'tahun_anggaran' => $validated['tahun_anggaran'],
            'jenis_spm' => $validated['jenis_spm'],
            'satker_id' => $validated['satker_id'],
            'ppk_id' => $validated['ppk_id'],
            'uraian' => $validated['uraian'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
            'status' => $statusBaru,
        ]);

        // Update file jika ada yang baru diunggah
        $types = ['spm', 'kuitansi', 'surat_tugas', 'laporan', 'dokumentasi'];
        foreach($types as $type) {
            $inputName = 'file_' . $type;
            if ($request->hasFile($inputName)) {
                // Cari dan hapus file lama jika ada
                $oldAttachment = $spm->attachments()->where('tipe_file', $type)->first();
                if ($oldAttachment) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldAttachment->file_path);
                    $oldAttachment->delete();
                }

                // Simpan file baru
                $path = $request->file($inputName)->store('spms', 'public');
                $spm->attachments()->create([
                    'tipe_file' => $type,
                    'file_path' => $path
                ]);
            }
        }

        $spm->histories()->create([
            'user_id' => auth()->id(),
            'status' => $spm->status,
            'catatan' => 'Data SPM diperbarui'
        ]);

        if (in_array($statusSebelumnya, ['Draft', 'Ditolak']) && $statusBaru === 'Menunggu Verifikasi') {
            $atasanUsers = User::where('role', 'admin')->get();
            Notification::send($atasanUsers, new SpmSubmitted($spm));
        }

        return redirect()->route('spm.index')->with('success', 'Dokumen SPM berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (auth()->user()->role === 'atasan') abort(403, 'Anda tidak memiliki hak akses untuk mengubah data.');

        $spm = Spm::findOrFail($id);

        if (!in_array($spm->status, ['Draft', 'Menunggu Verifikasi'])) {
            return redirect()->route('spm.index')->with('error', 'SPM yang sudah diverifikasi tidak dapat dihapus.');
        }

        // Hapus file fisik
        foreach ($spm->attachments as $attachment) {
            \Illuminate\Support\Facades\Storage::delete('public/' . $attachment->file_path);
        }

        $spm->delete();

        return redirect()->route('spm.index')->with('success', 'Dokumen SPM berhasil dihapus.');
    }
}
