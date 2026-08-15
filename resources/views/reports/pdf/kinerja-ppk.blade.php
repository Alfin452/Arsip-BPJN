<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kinerja Penyerapan per PPK</title>
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

    <div class="report-title">Laporan Kinerja Penyerapan per PPK</div>
    <div class="report-subtitle">Tahun Anggaran {{ $tahun }}</div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-left">Nama Pejabat Pembuat Komitmen (PPK)</th>
                <th class="text-right">Total Realisasi Serapan (Rp)</th>
            </tr>
        </thead>
        <tbody>
@php $grandTotal = 0; @endphp
            @forelse($tableData as $row)
            @php $grandTotal += $row['realisasi']; @endphp
            <tr>
                <td class="text-left"><strong>{{ $row['ppk'] }}</strong></td>
                <td class="text-right">{{ number_format($row['realisasi'], 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center">Belum ada data realisasi PPK untuk tahun {{ $tahun }}.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
<tr>
                <th class="text-right">TOTAL REALISASI</th>
                <th class="text-right">{{ number_format($grandTotal, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
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