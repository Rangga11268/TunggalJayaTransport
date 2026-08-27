<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Conductor;

class ConductorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conductors = [
            [
                'name' => 'Mang Asep',
                'employee_id' => 'CNT001',
                'phone' => '081345678901',
                'email' => 'asep@tunggaljaya.com',
                'address' => 'Ciawigebang, Kuningan',
                'status' => 'active',
            ],
            [
                'name' => 'Kang Dadang',
                'employee_id' => 'CNT002',
                'phone' => '081345678902',
                'email' => 'dadang@tunggaljaya.com',
                'address' => 'Cilimus, Kuningan',
                'status' => 'active',
            ],
            [
                'name' => 'Mas Eko',
                'employee_id' => 'CNT003',
                'phone' => '081345678903',
                'email' => 'eko@tunggaljaya.com',
                'address' => 'Luragung, Kuningan',
                'status' => 'active',
            ],
        ];

        foreach ($conductors as $conductor) {
            Conductor::firstOrCreate(
                ['employee_id' => $conductor['employee_id']],
                $conductor
            );
        }
    }
}
