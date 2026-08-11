<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Satker;
use App\Models\Ppk;
use App\Models\Penyedia;
use App\Models\Dipa;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Data Satker
        $satker1 = Satker::firstOrCreate(
            ['kode_satker' => '498210'],
            ['nama_satker' => 'Pelaksanaan Jalan Nasional Wilayah I Provinsi Jawa Timur']
        );
        $satker2 = Satker::firstOrCreate(
            ['kode_satker' => '498211'],
            ['nama_satker' => 'Pelaksanaan Jalan Nasional Wilayah II Provinsi Jawa Timur']
        );
        $satker3 = Satker::firstOrCreate(
            ['kode_satker' => '498212'],
            ['nama_satker' => 'Perencanaan dan Pengawasan Jalan Nasional Provinsi Jatim']
        );

        // 2. Data PPK
        $ppk1 = Ppk::firstOrCreate(
            ['nip' => '198001012005011001'],
            ['satker_id' => $satker1->id, 'nama' => 'PPK 1.1 (Tuban - Babat - Lamongan - Gresik)']
        );
        $ppk2 = Ppk::firstOrCreate(
            ['nip' => '198202022006021002'],
            ['satker_id' => $satker1->id, 'nama' => 'PPK 1.2 (Surabaya - Waru - Sidoarjo)']
        );
        $ppk3 = Ppk::firstOrCreate(
            ['nip' => '198503032007031003'],
            ['satker_id' => $satker2->id, 'nama' => 'PPK 2.1 (Malang - Kepanjen - Blitar)']
        );

        // 3. Data Penyedia Jasa (Kontraktor)
        Penyedia::firstOrCreate(
            ['npwp' => '01.234.567.8-901.000'],
            [
                'nama_perusahaan' => 'PT. Waskita Karya (Persero) Tbk',
                'nama_direktur' => 'Ir. Destiawan Soewardjono',
                'bank' => 'Bank Mandiri',
                'no_rekening' => '142-00-1234567-8'
            ]
        );
        Penyedia::firstOrCreate(
            ['npwp' => '02.345.678.9-012.000'],
            [
                'nama_perusahaan' => 'PT. Wijaya Karya (Persero) Tbk',
                'nama_direktur' => 'Ir. Agung Budi Waskito',
                'bank' => 'Bank BNI',
                'no_rekening' => '0987654321'
            ]
        );
        Penyedia::firstOrCreate(
            ['npwp' => '03.456.789.0-123.000'],
            [
                'nama_perusahaan' => 'PT. Pembangunan Perumahan (Persero) Tbk',
                'nama_direktur' => 'Ir. Novel Arsyad',
                'bank' => 'Bank BRI',
                'no_rekening' => '0011-01-000123-30-1'
            ]
        );
        Penyedia::firstOrCreate(
            ['npwp' => '04.567.890.1-234.000'],
            [
                'nama_perusahaan' => 'CV. Bina Marga Jaya',
                'nama_direktur' => 'Budi Santoso, ST',
                'bank' => 'Bank Jatim',
                'no_rekening' => '045-1234-567'
            ]
        );

        // 4. Data Pagu Anggaran (DIPA) Tahun 2026 (biar ada isinya)
        Dipa::firstOrCreate(
            ['satker_id' => $satker1->id, 'tahun_anggaran' => '2026'],
            [
                'nomor_dipa' => 'SP DIPA-033.04.1.498210/2026',
                'tanggal_dipa' => '2025-12-05',
                'nilai_pagu' => 250000000000 // 250 Miliar
            ]
        );
        
        // 5. Data Paket Pekerjaan
        $penyedia1 = Penyedia::where('npwp', '01.234.567.8-901.000')->first();
        $paket1 = \App\Models\PaketPekerjaan::firstOrCreate(
            ['nomor_kontrak' => 'HK.02.01/Bb.12/PJN.I/01/2026'],
            [
                'satker_id' => $satker1->id,
                'ppk_id' => $ppk1->id,
                'penyedia_id' => $penyedia1->id,
                'nama_paket' => 'Preservasi Jalan Bts. Kab. Tuban - Babat - Lamongan',
                'tanggal_kontrak' => '2026-02-15',
                'nilai_kontrak' => 15000000000,
                'tanggal_mulai' => '2026-02-20',
                'tanggal_selesai' => '2026-12-15',
            ]
        );

        // 6. Data BAST & Penagihan
        \App\Models\Bast::firstOrCreate(
            ['nomor_bast' => 'BAST/PJN.I/001/2026'],
            [
                'paket_pekerjaan_id' => $paket1->id,
                'tanggal_bast' => '2026-07-20',
                'nomor_penagihan' => 'INV-WK-001/2026',
                'tanggal_penagihan' => '2026-07-22',
                'nilai_penagihan' => 3000000000, // 3 Miliar (Termin 1 / 20%)
                'keterangan' => 'Tagihan Termin 1 20%',
                'status' => 'Menunggu Verifikasi',
                'uploaded_by' => 1 // Asumsi admin user ID = 1
            ]
        );
    }
}
