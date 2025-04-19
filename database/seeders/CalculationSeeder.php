<?php

namespace Database\Seeders;

use App\Models\CalculationM;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CalculationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CalculationM::factory()->create([
            'it' => 0.20,
            'se' => 0.70,
            'as' =>0.10,
            'adm' => 0.10,
            'mng' =>0.10,
            'jenis' => 0,
        ]);
        CalculationM::factory()->create([
            'it' => 0.30,
            'se' => 0.70,
            'as' =>0.10,
            'adm' => 0.10,
            'mng' =>0.10,
            'jenis' => 1,
        ]);
    }
}
