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
            'name' => 'admin',
            'username' => ' admin',
            'email' => '    admin@gmail.com',
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
            'name' => 'penerima Sales Enginer',
            'username' => 'penerima Sales Enginer',
            'email' => 'penerimase1@gmail.com',
            'role' => '2',
            'division' => 'Sales Enginer',
            'active' => '1',
            'password' => Hash::make('12345'),
        ]);
        User::factory()->create([
            'name' => 'penerima Sales Enginer 2',
            'username' => 'penerima Sales Enginer 2',
            'email' => 'penerimase2@gmail.com',
            'role' => '2',
            'division' => 'Sales Enginer',
            'active' => '1',
            'password' => Hash::make('12345'),
        ]);
        User::factory()->create([
            'name' => 'penerima Sales Enginer 3',
            'username' => 'penerima Sales Enginer 3',
            'email' => 'penerimase3@gmail.com',
            'role' => '2',
            'division' => 'Sales Enginer',
            'active' => '1',
            'password' => Hash::make('12345'),
        ]);
        User::factory()->create([
            'name' => 'penerima Aplication Service',
            'username' => 'penerima Aplication Service',
            'email' => 'penerimaap@gmail.com',
            'role' => '2',
            'division' => 'Aplication Service',
            'active' => '1',
            'password' => Hash::make('12345'),
        ]);
        
        User::factory()->create([
            'name' => 'penerima Administration',
            'username' => 'penerima Administration',
            'email' => 'penerimaadm@gmail.com',
            'role' => '2',
            'division' => 'Administration',
            'active' => '1',
            'password' => Hash::make('12345'),
        ]);
        User::factory()->create([
            'name' => 'penerima Administration 2',
            'username' => 'penerima Administration 2',
            'email' => 'penerimaadm2@gmail.com',
            'role' => '2',
            'division' => 'Administration',
            'active' => '1',
            'password' => Hash::make('12345'),
        ]);
        User::factory()->create([
            'name' => 'penerima Administration 3',
            'username' => 'penerima Administration 3',
            'email' => 'penerimaadm3@gmail.com',
            'role' => '2',
            'division' => 'Administration',
            'active' => '1',
            'password' => Hash::make('12345'),
        ]);
        User::factory()->create([
            'name' => 'penerima Manager',
            'username' => 'penerima Manager',
            'email' => 'penerimamng@gmail.com',
            'role' => '2',
            'division' => 'Manager',
            'active' => '1',
            'password' => Hash::make('12345'),
        ]);

        $this->call([
            CalculationSeeder::class,
        ]);
    }
}
