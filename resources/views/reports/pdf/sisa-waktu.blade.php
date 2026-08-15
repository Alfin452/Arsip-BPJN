<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Sisa Waktu Pelaksanaan Kontrak</title>
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

    <div class="report-title">Laporan Sisa Waktu Pelaksanaan Kontrak</div>
    <div class="report-subtitle">Tahun Anggaran {{ $tahun }}</div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-left">Nama Paket Pekerjaan</th>
                <th class="text-center">PPK / Satker</th>
                <th class="text-center">Waktu Pelaksanaan</th>
                <th class="text-center">Sisa Waktu</th>
                <th class="text-center">Progress Berjalan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tableData as $row)
            <tr>
                <td class="text-left"><strong>{{ $row['paket'] }}</strong></td>
                <td class="text-center font-medium">{{ $row['ppk'] }}<br><small style="color: #64748b;">({{ $row['satker'] }})</small></td>
                <td class="text-center">{{ $row['total_hari_fmt'] }}</td>
                <td class="text-center"><strong>{{ $row['sisa_hari_fmt'] }}</strong></td>
                <td class="text-center"><strong>{{ $row['persentase'] }}%</strong></td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada data kontrak untuk tahun {{ $tahun }}.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="ttd-box">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; text-align: center;">
                <p style="margin: 0 0 4px 0;">Surabaya, {{ now()->translatedFormat("d F Y") }}</p>
                <p style="margin: 0 0 6px 0; font-weight: bold;">Kepala Sub Bagian Tata Usaha,</p>
                
                <div style="margin: 6px 0;">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path("images/qr-ttd.png"))) }}" style="width: 85px; height: 85px; border: 1px solid #cbd5e1; padding: 4px; background: #ffffff; border-radius: 4px;">
                </div>

                <p style="margin: 4px 0 0 0; font-weight: bold; text-decoration: underline;">SUTRISNO, ST, MT</p>
                <p style="margin: 2px 0 0 0; font-size: 10px;">NIP 196809262002121006</p>
                <p style="margin: 3px 0 0 0; font-size: 8px; color: #475569; font-style: italic;">Dokumen ini telah ditandatangani secara elektronik (BSrE - BSSN)</p>
            </td>
        </tr>
    </table>
</body>
</html>
