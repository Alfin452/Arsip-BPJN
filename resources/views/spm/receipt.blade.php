<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Terima SPM - {{ $spm->nomor_spm }}</title>
    <!-- Use Tailwind via CDN for standalone print view to ensure styles load correctly -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
                background-color: white !important;
            }
            .no-print {
                display: none !important;
            }
            .paper {
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                max-width: 100% !important;
                width: 100% !important;
            }
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f3f4f6;
        }
        .paper {
            background-color: white;
            max-width: 800px;
            margin: 2rem auto;
            padding: 3rem 4rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            box-sizing: border-box;
        }
        .signature-block {
            page-break-inside: avoid;
        }
    </style>
</head>
<body class="text-gray-900">

    <div class="text-center mb-6 mt-4 no-print">
        <button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold shadow hover:bg-blue-700 transition">
            🖨️ Cetak Dokumen
        </button>
        <button onclick="window.close()" class="px-6 py-2 bg-gray-500 text-white rounded-lg font-bold shadow hover:bg-gray-600 transition ml-2">
            Tutup
        </button>
    </div>

    <div class="paper">
        <!-- Kop Surat / Header -->
        <div class="border-b-4 border-double border-gray-800 pb-3 mb-6 flex items-center">
            <img src="{{ asset('logo/Logo_Kementerian_Pekerjaan_Umum_Republik_Indonesia.svg') }}" alt="Logo PUPR" class="w-24 h-24 object-contain mr-6 shrink-0">
            <div class="text-center flex-1 pr-12">
                <h1 class="text-xl font-bold uppercase tracking-wider">Kementerian Pekerjaan Umum dan Perumahan Rakyat</h1>
                <h2 class="text-lg font-bold uppercase">Direktorat Jenderal Bina Marga</h2>
                <h3 class="text-md font-semibold">Balai Pelaksanaan Jalan Nasional</h3>
                <p class="text-sm mt-1">Jl. Pattimura No.20, Kebayoran Baru, Jakarta Selatan 12110</p>
            </div>
        </div>

        <!-- Judul -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold uppercase underline mb-1">Tanda Terima Dokumen</h2>
            <p class="text-md">Nomor Registrasi: REG-{{ $spm->id }}-{{ date('Ymd') }}</p>
        </div>

        <!-- Isi -->
        <div class="mb-6 text-justify">
            <p class="mb-4">Pada hari ini <strong>{{ \Carbon\Carbon::now()->translatedFormat('l') }}</strong> tanggal <strong>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</strong>, telah diterima dokumen Surat Perintah Membayar (SPM) beserta lampirannya melalui sistem elektronik dengan rincian sebagai berikut:</p>
            
            <table class="w-full mb-6">
                <tbody>
                    <tr>
                        <td class="py-2 w-1/3 align-top">Nomor SPM</td>
                        <td class="py-2 w-4 align-top">:</td>
                        <td class="py-2 font-bold">{{ $spm->nomor_spm }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 w-1/3 align-top">Tanggal SPM</td>
                        <td class="py-2 w-4 align-top">:</td>
                        <td class="py-2">{{ \Carbon\Carbon::parse($spm->tanggal_spm)->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 w-1/3 align-top">Jenis SPM</td>
                        <td class="py-2 w-4 align-top">:</td>
                        <td class="py-2">{{ $spm->jenis_spm }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 w-1/3 align-top">Tahun Anggaran</td>
                        <td class="py-2 w-4 align-top">:</td>
                        <td class="py-2">{{ $spm->tahun_anggaran }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 w-1/3 align-top">Satuan Kerja</td>
                        <td class="py-2 w-4 align-top">:</td>
                        <td class="py-2">{{ $spm->satker->kode_satker ?? '-' }} - {{ $spm->satker->nama_satker ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 w-1/3 align-top">Pejabat Pembuat Komitmen</td>
                        <td class="py-2 w-4 align-top">:</td>
                        <td class="py-2">{{ $spm->ppk->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 w-1/3 align-top">Nilai SPM</td>
                        <td class="py-2 w-4 align-top">:</td>
                        <td class="py-2 font-bold">Rp {{ number_format($spm->nilai_spm, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 w-1/3 align-top">Uraian / Keterangan</td>
                        <td class="py-2 w-4 align-top">:</td>
                        <td class="py-2">{{ $spm->keterangan ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>

            <p class="mb-4">Dokumen elektronik tersebut telah direkam ke dalam sistem dan saat ini memiliki status <strong>{{ $spm->status }}</strong>.</p>
        </div>

        <!-- Tanda Tangan -->
        <div class="flex justify-between mt-12 px-8 signature-block">
            <div class="text-center w-48">
                <p class="mb-20">Pengunggah Dokumen,</p>
                <p class="font-bold underline">{{ $spm->uploader->name ?? 'Sistem' }}</p>
                <p class="text-sm">{{ $spm->uploader->email ?? '-' }}</p>
            </div>
            
            <div class="text-center w-48">
                <p>Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p class="mb-12">Penerima Dokumen (Sistem),</p>
                <p class="font-bold underline">Sistem e-SPM BPJN</p>
                <p class="text-sm">Dokumen tercatat elektronik</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 pt-4 border-t border-gray-400 text-xs text-gray-500 text-center signature-block">
            <p>Tanda terima ini dicetak secara otomatis dari sistem e-SPM pada {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}.</p>
            <p>Dokumen ini sah tanpa tanda tangan basah karena telah diverifikasi oleh sistem.</p>
        </div>
    </div>

    <!-- Auto Print Script -->
    <script>
        window.onload = function() {
            // Uncomment baris di bawah ini jika ingin otomatis memunculkan dialog print saat halaman dibuka
            // window.print();
        }
    </script>
</body>
</html>
