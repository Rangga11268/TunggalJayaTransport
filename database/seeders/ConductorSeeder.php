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
                'name' => 'Budi Santoso',
                'employee_id' => 'CNT001',
                'phone' => '081234567891',
                'email' => 'budi.santoso@example.com',
                'address' => 'Jalan Merdeka No. 123, Jakarta',
                'status' => 'active',
            ],
            [
                'name' => 'Andi Prasetyo',
                'employee_id' => 'CNT002',
                'phone' => '081234567892',
                'email' => 'andi.prasetyo@example.com',
                'address' => 'Jalan Sudirman No. 456, Bandung',
                'status' => 'active',
            ],
            [
                'name' => 'Joko Widodo',
                'employee_id' => 'CNT003',
                'phone' => '081234567893',
                'email' => 'joko.widodo@example.com',
                'address' => 'Jalan Thamrin No. 789, Surabaya',
                'status' => 'active',
            ],
            [
                'name' => 'Ahmad Rifai',
                'employee_id' => 'CNT004',
                'phone' => '081234567894',
                'email' => 'ahmad.rifai@example.com',
                'address' => 'Jalan Gatot Subroto No. 321, Medan',
                'status' => 'inactive',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'employee_id' => 'CNT005',
                'phone' => '081234567895',
                'email' => 'siti.nurhaliza@example.com',
                'address' => 'Jalan Diponegoro No. 159, Yogyakarta',
                'status' => 'active',
            ],
            [
                'name' => 'Rudi Hartono',
                'employee_id' => 'CNT006',
                'phone' => '081234567896',
                'email' => 'rudi.hartono@example.com',
                'address' => 'Jalan Asia Afrika No. 258, Bandung',
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
