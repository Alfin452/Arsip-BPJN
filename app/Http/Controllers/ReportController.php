<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spm;
use App\Models\Sp2d;
use App\Models\Bast;
use App\Models\PaketPekerjaan;
use App\Models\Penyedia;
use App\Models\Ppk;
use App\Models\Satker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReportController extends Controller
{
    // 1. Realisasi Pagu Per Satker
    public function realisasiPagu(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        
        $satkers = Satker::all();
        $labels = [];
        $dataPagu = [];
        $dataRealisasi = [];
        $tableData = [];

        foreach ($satkers as $satker) {
            $labels[] = Str::limit($satker->nama_satker, 20);
            
            $pagu = DB::table('dipas')
                ->where('satker_id', $satker->id)
                ->where('tahun_anggaran', $tahun)
                ->sum('nilai_pagu');
                
            $realisasi = DB::table('sp2ds')
                ->join('spms', 'sp2ds.spm_id', '=', 'spms.id')
                ->where('spms.satker_id', $satker->id)
                ->whereYear('sp2ds.tanggal_sp2d', $tahun)
                ->where('sp2ds.status', 'Terverifikasi')
                ->sum('sp2ds.nilai_sp2d');

            $dataPagu[] = (float)$pagu;
            $dataRealisasi[] = (float)$realisasi;

            $tableData[] = [
                'satker' => $satker->nama_satker,
                'pagu' => $pagu,
                'realisasi' => $realisasi,
                'sisa' => $pagu - $realisasi,
                'persentase' => $pagu > 0 ? round(($realisasi / $pagu) * 100, 2) : 0
            ];
        }

        if ($request->query('export') === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.realisasi-pagu', compact('tableData', 'tahun'));
            return $pdf->stream('laporan-realisasi-pagu-'.$tahun.'.pdf');
        }

        return view('reports.realisasi-pagu', compact('labels', 'dataPagu', 'dataRealisasi', 'tableData', 'tahun'));
    }

    // 2. Laporan Kinerja Waktu Pemrosesan Dokumen
    public function waktuProses(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataSla = array_fill(0, 12, 0);
        $countSla = array_fill(0, 12, 0);

        $sp2ds = DB::table('sp2ds')
            ->join('spms', 'sp2ds.spm_id', '=', 'spms.id')
            ->whereYear('sp2ds.tanggal_sp2d', $tahun)
            ->where('sp2ds.status', 'Terverifikasi')
            ->select('sp2ds.tanggal_sp2d', 'spms.tanggal_spm')
            ->get();
            
        $tableData = [];

        foreach ($sp2ds as $doc) {
            $m = (int) date('n', strtotime($doc->tanggal_sp2d)) - 1;
            $start = Carbon::parse($doc->tanggal_spm);
            $end = Carbon::parse($doc->tanggal_sp2d);
            $diff = $start->diffInDays($end);
            
            $dataSla[$m] += $diff;
            $countSla[$m]++;
        }

        foreach ($months as $i => $month) {
            $avg = $countSla[$i] > 0 ? round($dataSla[$i] / $countSla[$i], 1) : 0;
            $dataSla[$i] = $avg;
            
            $tableData[] = [
                'bulan' => $month,
                'rata_rata' => $avg,
                'jumlah_dokumen' => $countSla[$i]
            ];
        }

        if ($request->query('export') === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.waktu-proses', compact('tableData', 'tahun'));
            return $pdf->stream('laporan-kinerja-sla-'.$tahun.'.pdf');
        }

        return view('reports.waktu-proses', compact('months', 'dataSla', 'tableData', 'tahun'));
    }

    // 3. Tren Pencairan
    public function trenPencairan(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataTrend = array_fill(0, 12, 0);
        
        $sp2ds = Sp2d::whereYear('tanggal_sp2d', $tahun)
                    ->where('status', 'Terverifikasi')
                    ->get();
                    
        foreach ($sp2ds as $sp2d) {
            $m = (int) date('n', strtotime($sp2d->tanggal_sp2d)) - 1;
            $dataTrend[$m] += $sp2d->nilai_sp2d;
        }
        
        $tableData = [];
        foreach ($months as $i => $month) {
            $tableData[] = [
                'bulan' => $month,
                'total' => $dataTrend[$i]
            ];
        }

        if ($request->query('export') === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.tren-pencairan', compact('tableData', 'tahun'));
            return $pdf->stream('laporan-tren-pencairan-'.$tahun.'.pdf');
        }

        return view('reports.tren-pencairan', compact('months', 'dataTrend', 'tableData', 'tahun'));
    }

    // 4. Serapan Paket
    public function serapanPaket(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        
        $pakets = PaketPekerjaan::whereYear('tanggal_kontrak', $tahun)->get();
        $labels = [];
        $dataKontrak = [];
        $dataSerapan = [];
        $tableData = [];

        foreach ($pakets as $paket) {
            $labels[] = Str::limit($paket->nama_paket, 20);
            $dataKontrak[] = $paket->nilai_kontrak;
            
            $serapan = Bast::where('paket_pekerjaan_id', $paket->id)
                           ->where('status', 'Terverifikasi')
                           ->sum('nilai_penagihan');
                           
            $dataSerapan[] = $serapan;
            
            $tableData[] = [
                'paket' => $paket->nama_paket,
                'nilai_kontrak' => $paket->nilai_kontrak,
                'serapan' => $serapan,
                'persentase' => $paket->nilai_kontrak > 0 ? round(($serapan / $paket->nilai_kontrak) * 100, 2) : 0
            ];
        }

        if ($request->query('export') === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.serapan-paket', compact('tableData', 'tahun'));
            return $pdf->stream('laporan-serapan-paket-'.$tahun.'.pdf');
        }

        return view('reports.serapan-paket', compact('labels', 'dataKontrak', 'dataSerapan', 'tableData', 'tahun'));
    }

    // 5. Distribusi Penyedia
    public function distribusiPenyedia(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        
        $penyedias = DB::table('paket_pekerjaans')
            ->join('penyedias', 'paket_pekerjaans.penyedia_id', '=', 'penyedias.id')
            ->whereYear('paket_pekerjaans.tanggal_kontrak', $tahun)
            ->select('penyedias.nama_perusahaan', DB::raw('SUM(paket_pekerjaans.nilai_kontrak) as total_kontrak'), DB::raw('COUNT(paket_pekerjaans.id) as jumlah_paket'))
            ->groupBy('penyedias.id', 'penyedias.nama_perusahaan')
            ->orderByDesc('total_kontrak')
            ->take(10)
            ->get();
            
        $labels = $penyedias->pluck('nama_perusahaan')->toArray();
        $data = $penyedias->pluck('total_kontrak')->map(fn($v) => (float)$v)->toArray();
        $tableData = $penyedias;

        if ($request->query('export') === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.distribusi-penyedia', compact('tableData', 'tahun'));
            return $pdf->stream('laporan-distribusi-penyedia-'.$tahun.'.pdf');
        }

        return view('reports.distribusi-penyedia', compact('labels', 'data', 'tableData', 'tahun'));
    }

    // 6. Status Dokumen
    public function statusDokumen(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        
        $statuses = DB::table('spms')
            ->where('tahun_anggaran', $tahun)
            ->select('status', DB::raw('COUNT(id) as total'))
            ->groupBy('status')
            ->get();
            
        $labels = $statuses->pluck('status')->toArray();
        $data = $statuses->pluck('total')->map(fn($v) => (float)$v)->toArray();
        $tableData = $statuses;

        if ($request->query('export') === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.status-dokumen', compact('tableData', 'tahun'));
            return $pdf->stream('laporan-status-dokumen-'.$tahun.'.pdf');
        }

        return view('reports.status-dokumen', compact('labels', 'data', 'tableData', 'tahun'));
    }

    // 7. Tagihan Outstanding
    public function tagihanOutstanding(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        
        $basts = Bast::with(['paketPekerjaan.satker'])
            ->whereYear('tanggal_bast', $tahun)
            ->where('status', 'Menunggu Verifikasi')
            ->get();
            
        $satkerData = [];
        foreach($basts as $bast) {
            $satkerName = $bast->paketPekerjaan->satker->nama_satker ?? 'Unknown';
            if(!isset($satkerData[$satkerName])) {
                $satkerData[$satkerName] = 0;
            }
            $satkerData[$satkerName] += $bast->nilai_penagihan;
        }
        
        $labels = array_keys($satkerData);
        $data = array_values($satkerData);
        
        $tableData = [];
        foreach($satkerData as $satker => $total) {
            $tableData[] = ['satker' => $satker, 'total' => $total];
        }

        if ($request->query('export') === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.tagihan-outstanding', compact('tableData', 'tahun'));
            return $pdf->stream('laporan-tagihan-outstanding-'.$tahun.'.pdf');
        }

        return view('reports.tagihan-outstanding', compact('labels', 'data', 'tableData', 'tahun'));
    }

    // 8. Kinerja PPK
    public function kinerjaPpk(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        
        $ppks = Ppk::all();
        $labels = [];
        $data = [];
        $tableData = [];

        foreach($ppks as $ppk) {
            $realisasi = DB::table('basts')
                ->join('paket_pekerjaans', 'basts.paket_pekerjaan_id', '=', 'paket_pekerjaans.id')
                ->where('paket_pekerjaans.ppk_id', $ppk->id)
                ->whereYear('basts.tanggal_bast', $tahun)
                ->where('basts.status', 'Terverifikasi')
                ->sum('basts.nilai_penagihan');
                
            $labels[] = Str::limit($ppk->nama, 20);
            $data[] = (float)$realisasi;
            
            $tableData[] = [
                'ppk' => $ppk->nama,
                'realisasi' => $realisasi
            ];
        }

        if ($request->query('export') === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.kinerja-ppk', compact('tableData', 'tahun'));
            return $pdf->stream('laporan-kinerja-ppk-'.$tahun.'.pdf');
        }

        return view('reports.kinerja-ppk', compact('labels', 'data', 'tableData', 'tahun'));
    }

    private function formatDurationHuman($days)
    {
        $days = (int) round($days);
        if ($days <= 0) return '0 Hari';
        
        $years = (int) floor($days / 365);
        $rem = $days % 365;
        $months = (int) floor($rem / 30);
        $remDays = $rem % 30;

        $parts = [];
        if ($years > 0) $parts[] = $years . ' Thn';
        if ($months > 0) $parts[] = $months . ' Bln';
        if ($remDays > 0 || empty($parts)) $parts[] = $remDays . ' Hari';

        return implode(' ', $parts);
    }

    // 9. Sisa Waktu Kontrak
    public function sisaWaktuKontrak(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        
        $pakets = PaketPekerjaan::with(['satker', 'ppk'])
                    ->whereYear('tanggal_kontrak', $tahun)
                    ->get();

        $labels = [];
        $dataWaktuBerjalan = [];
        $dataWaktuSisa = [];
        $tableData = [];

        foreach ($pakets as $paket) {
            $labels[] = Str::limit($paket->nama_paket, 20);
            
            $start = Carbon::parse($paket->tanggal_mulai);
            $end = Carbon::parse($paket->tanggal_selesai);
            $now = Carbon::now();
            
            $totalDays = (int) round($start->diffInDays($end)) ?: 1;
            
            if ($now < $start) {
                $passedDays = 0;
            } elseif ($now > $end) {
                $passedDays = $totalDays;
            } else {
                $passedDays = (int) round($start->diffInDays($now));
            }
            
            $remainingDays = max(0, $totalDays - $passedDays);
            
            $dataWaktuBerjalan[] = $passedDays;
            $dataWaktuSisa[] = $remainingDays;
            
            $tableData[] = [
                'paket' => $paket->nama_paket,
                'satker' => $paket->satker ? $paket->satker->nama_satker : '-',
                'ppk' => $paket->ppk ? $paket->ppk->nama : '-',
                'total_hari' => $totalDays,
                'total_hari_fmt' => $this->formatDurationHuman($totalDays),
                'hari_berjalan' => $passedDays,
                'hari_berjalan_fmt' => $this->formatDurationHuman($passedDays),
                'sisa_hari' => $remainingDays,
                'sisa_hari_fmt' => $this->formatDurationHuman($remainingDays),
                'persentase' => round(($passedDays / $totalDays) * 100, 1)
            ];
        }

        if ($request->query('export') === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.sisa-waktu', compact('tableData', 'tahun'));
            return $pdf->stream('laporan-sisa-waktu-'.$tahun.'.pdf');
        }

        return view('reports.sisa-waktu-kontrak', compact('labels', 'dataWaktuBerjalan', 'dataWaktuSisa', 'tableData', 'tahun'));
    }

    // 10. Komposisi Jenis SPM
    public function komposisiJenisSpm(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        
        $mapJenis = [
            'LS' => 'LS (Pembayaran Langsung)',
            'UP' => 'UP (Uang Persediaan)',
            'TUP' => 'TUP (Tambahan Uang Persediaan)',
            'GUP' => 'GUP (Ganti Uang Persediaan)',
            'PTUP' => 'PTUP (Pertanggungjawaban TUP)',
        ];

        $jenis = DB::table('spms')
            ->where('tahun_anggaran', $tahun)
            ->whereNotNull('jenis_spm')
            ->select('jenis_spm', DB::raw('COUNT(id) as total'))
            ->groupBy('jenis_spm')
            ->get()
            ->map(function ($item) use ($mapJenis) {
                $code = strtoupper(trim($item->jenis_spm));
                $item->nama_jenis = $mapJenis[$code] ?? $item->jenis_spm;
                return $item;
            });
            
        $labels = $jenis->pluck('nama_jenis')->toArray();
        $data = $jenis->pluck('total')->map(fn($v) => (float)$v)->toArray();
        
        $tableData = $jenis;

        if ($request->query('export') === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.komposisi-spm', compact('tableData', 'tahun'));
            return $pdf->stream('laporan-komposisi-spm-'.$tahun.'.pdf');
        }

        return view('reports.komposisi-jenis-spm', compact('labels', 'data', 'tableData', 'tahun'));
    }
}