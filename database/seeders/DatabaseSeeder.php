<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@bpjn.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->call([
            MasterDataSeeder::class,
            SpmSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Atasan',
            'email' => 'atasan@bpjn.com',
            'password' => bcrypt('password'),
            'role' => 'atasan',
        ]);

        User::factory()->create([
            'name' => 'Karyawan',
            'email' => 'karyawan@bpjn.com',
            'password' => bcrypt('password'),
            'role' => 'karyawan',
        ]);
    }
}
