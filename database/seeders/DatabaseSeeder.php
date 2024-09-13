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
            'name' => 'cc-lengkap-cc',
            'username' => 'cc',
            'email' => 'cc@cc.cc',
            'role' => '1',
            'active' => '1',
            'password' => Hash::make('cc'),
        ]);
        User::factory()->create([
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'admin@cc.cc',
            'role' => '0',
            'active' => '1',
            'password' => Hash::make('admin'),
        ]);
    }
}
