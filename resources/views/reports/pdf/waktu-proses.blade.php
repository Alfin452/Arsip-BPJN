<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kinerja Waktu Pemrosesan (SLA)</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12px; color: #000; }
        .kop-surat { width: 100%; text-align: center; margin-bottom: 2px; }
        .kop-surat h1 { font-size: 16px; font-weight: bold; margin: 0; }
        .kop-surat h2 { font-size: 18px; font-weight: bold; margin: 5px 0 0 0; }
        .kop-surat h3 { font-size: 16px; font-weight: bold; margin: 5px 0 0 0; }
        .kop-surat p { font-size: 11px; margin: 5px 0 0 0; }
        .garis-tebal { border-bottom: 3px solid black; margin-bottom: 1px; }
        .garis-tipis { border-bottom: 1px solid black; margin-bottom: 20px; }
        .report-title { text-align: center; font-size: 14px; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }
        .report-subtitle { text-align: center; font-size: 12px; margin-bottom: 20px; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table.data-table th, table.data-table td { border: 1px solid #000; padding: 8px 10px; }
        table.data-table th { background-color: #e2e8f0; font-weight: bold; text-align: center; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .ttd-box { width: 100%; margin-top: 40px; page-break-inside: avoid; }
        .ttd-box td { border: none; padding: 0; }
    </style>
</head>
<body>
    <!-- KOP SURAT RESMI -->
    <table class="kop-surat" style="border: none;">
        <tr>
            <td style="text-align: center; border: none;">
                <h1>KEMENTERIAN PEKERJAAN UMUM DAN PERUMAHAN RAKYAT</h1>
                <h2>DIREKTORAT JENDERAL BINA MARGA</h2>
                <h3>BALAI PELAKSANAAN JALAN NASIONAL JAWA TIMUR</h3>
                <p>Jl. Raya Waru No. 20, Sidoarjo, Jawa Timur 61256</p>
            </td>
        </tr>
    </table>
    <div class="garis-tebal"></div>
    <div class="garis-tipis"></div>

    <div class="report-title">LAPORAN KINERJA WAKTU PEMROSESAN DOKUMEN (SLA)</div>
    <div class="report-subtitle">Tahun Anggaran {{ $tahun }}</div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-left">Bulan</th>
                <th class="text-center">Jumlah Dokumen SP2D Terbit</th>
                <th class="text-center">Rata-rata Waktu Proses SPM ke SP2D</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tableData as $row)
            <tr>
                <td class="text-left"><strong>{{ $row['bulan'] }} {{ $tahun }}</strong></td>
                <td class="text-center">{{ number_format($row['jumlah_dokumen'], 0, ',', '.') }} Dokumen</td>
                <td class="text-center">{{ $row['rata_rata'] }} Hari</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Belum ada data transaksi untuk tahun {{ $tahun }}.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="ttd-box">
        <tr>
            <td style="width: 65%;"></td>
            <td style="width: 35%; text-align: left;">
                <p style="margin: 0 0 5px 0;">Surabaya, {{ now()->translatedFormat('d F Y') }}</p>
                <p style="margin: 0;">Kepala Sub Bagian Tata Usaha,</p>
                <br><br><br><br>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">(........................................)</p>
                <p style="margin: 5px 0 0 0;">NIP. ........................................</p>
            </td>
        </tr>
    </table>
</body>
</html>
