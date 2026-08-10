<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $satker1 = \App\Models\Satker::create([
            'kode_satker' => '400501',
            'nama_satker' => 'Pelaksanaan Jalan Nasional Wilayah I Provinsi XXX'
        ]);

        $satker2 = \App\Models\Satker::create([
            'kode_satker' => '400502',
            'nama_satker' => 'Pelaksanaan Jalan Nasional Wilayah II Provinsi XXX'
        ]);

        \App\Models\Ppk::create([
            'satker_id' => $satker1->id,
            'nip' => '198001012005011001',
            'nama' => 'Ir. Budi Santoso, ST, MT',
        ]);

        \App\Models\Ppk::create([
            'satker_id' => $satker2->id,
            'nip' => '198202022006022002',
            'nama' => 'Siti Aminah, ST, M.Sc',
        ]);
    }
}
