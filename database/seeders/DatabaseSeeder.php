<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'pegawai',
            'username' => 'pegawai',
            'email' => 'pegawai@gmail.com',
            'role' => '1',
            'active' => '1',
            'password' => Hash::make('12345'),
        ]);
        User::factory()->create([
            'name' => 'direktur',
            'username' => 'direktur',
            'email' => 'direktur@gmail.com',
            'role' => '0',
            'active' => '1',
            'password' => Hash::make('12345'),
        ]);
        User::factory()->create([
            'name' => 'penerima',
            'username' => 'penerima',
            'email' => 'penerima@gmail.com',
            'role' => '2',
            'active' => '1',
            'password' => Hash::make('12345'),
        ]);
    }
}
