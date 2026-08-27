<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bus;

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buses = [
            [
                'name' => 'Resi Bisma',
                'plate_number' => 'E 7777 TJ',
                'bus_type' => 'Executive',
                'capacity' => 30,
                'description' => 'Armada premium dengan kenyamanan maksimal dan fasilitas lengkap.',
                'status' => 'active',
                'image' => 'resiBisma.webp'
            ],
            [
                'name' => 'Primadona',
                'plate_number' => 'E 8888 TJ',
                'bus_type' => 'Executive',
                'capacity' => 30,
                'description' => 'Kebanggaan Tunggal Jaya dengan interior mewah dan pelayanan prima.',
                'status' => 'active',
                'image' => 'primadona.webp'
            ],
            [
                'name' => 'Bentas',
                'plate_number' => 'E 9999 TJ',
                'bus_type' => 'Executive',
                'capacity' => 30,
                'description' => 'Armada handal yang siap menemani perjalanan Anda dengan aman dan nyaman.',
                'status' => 'active',
                'image' => 'bentas01.webp'
            ],
        ];
        
        foreach ($buses as $data) {
            $image = $data['image'];
            unset($data['image']);
            
            $bus = Bus::create($data);

            // Attach Image
            $sourcePath = public_path('img/' . $image);
            if (\File::exists($sourcePath)) {
                try {
                    $bus->addMedia($sourcePath)
                        ->preservingOriginal()
                        ->toMediaCollection('cover');
                } catch (\Exception $e) {
                    dump("Failed to attach media for {$bus->name}: " . $e->getMessage());
                }
            }
        }
    }
}
