<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = \App\Models\User::first();
        $satkers = \App\Models\Satker::all();
        
        // Pastikan satker dan user ada
        if(!$admin || $satkers->isEmpty()) return;

        $jenisSpm = ['UP', 'TUP', 'GUP', 'PTUP', 'LS'];
        $status = ['Draft', 'Menunggu Verifikasi', 'Terverifikasi', 'Ditolak'];

        for ($i = 1; $i <= 5; $i++) {
            $satker = $satkers->random();
            $ppk = $satker->ppks->first();
            
            if(!$ppk) continue;

            $spm = \App\Models\Spm::create([
                'nomor_spm' => 'SPM-BPJN-' . date('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'tanggal_spm' => now()->subDays(rand(1, 30))->format('Y-m-d'),
                'nilai_spm' => rand(10000000, 500000000), // 10 juta s/d 500 juta
                'tahun_anggaran' => date('Y'),
                'jenis_spm' => $jenisSpm[array_rand($jenisSpm)],
                'satker_id' => $satker->id,
                'ppk_id' => $ppk->id,
                'uraian' => 'Pembayaran tagihan termin ke-' . $i . ' untuk pemeliharaan jalan.',
                'keterangan' => 'Data dummy hasil seeder.',
                'status' => $status[array_rand($status)],
                'uploaded_by' => $admin->id,
            ]);

            // Buat history awal
            $spm->histories()->create([
                'user_id' => $admin->id,
                'status' => 'Draft',
                'catatan' => 'SPM dibuat pertama kali'
            ]);

            // Jika status bukan Draft, tambah history perubahan status
            if ($spm->status != 'Draft') {
                $spm->histories()->create([
                    'user_id' => $admin->id,
                    'status' => $spm->status,
                    'catatan' => 'Dokumen diubah statusnya menjadi ' . $spm->status
                ]);
            }
        }
    }
}
