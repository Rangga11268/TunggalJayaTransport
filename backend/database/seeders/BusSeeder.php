<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bus;

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds based on real Tunggal Jaya fleet data.
     */
    public function run(): void
    {
        // Truncate or clean existing if foreign keys permit, or updateOrCreate
        $buses = [
            [
                'name' => 'Resi Bisma (Bentas-02)',
                'plate_number' => 'E 7799 YC',
                'bus_type' => 'Jetbus 5 SHD (Adiputro)',
                'capacity' => 50,
                'description' => 'Rute resmi Luragung - Kalideres / Kalijodo PP via Tol Cipali.',
                'status' => 'active',
            ],
            [
                'name' => 'Primadona (Bentas-05)',
                'plate_number' => 'E 7873 YC',
                'bus_type' => 'Jetbus 3+ SHD',
                'capacity' => 50,
                'description' => 'Rute resmi Ciawi - Roxy / Jembatan 5 / Season City PP.',
                'status' => 'active',
            ],
            [
                'name' => 'Bentas-01 (Salamina)',
                'plate_number' => 'E 7781 YC',
                'bus_type' => 'Jetbus 3+ SHD',
                'capacity' => 59,
                'description' => 'Rute pagi Kuningan - Kalideres PP via Cirendang & Jatibening.',
                'status' => 'active',
            ],
            [
                'name' => 'Dewi Fortuna',
                'plate_number' => 'E 7443 TJ',
                'bus_type' => 'Jetbus 3+ SHD',
                'capacity' => 59,
                'description' => 'Rute resmi Kuningan - Cirebon - Rangkasbitung Banten PP.',
                'status' => 'active',
            ],
            [
                'name' => 'Semar Mesem (Bentas-03)',
                'plate_number' => 'E 7823 YC',
                'bus_type' => 'Jetbus 3+ SHD',
                'capacity' => 59,
                'description' => 'Rute siang Kadurama - Kalideres PP via Cirendang & Grogol.',
                'status' => 'active',
            ],
            [
                'name' => 'Kylo Ren',
                'plate_number' => 'E 7890 TJ',
                'bus_type' => 'Jetbus 5 SHD Single Glass',
                'capacity' => 50,
                'description' => 'Unit pariwisata Hino RM 280 dengan suspensi udara, audio karaoke, dan TV.',
                'status' => 'active',
            ],
            [
                'name' => 'Jupiter (R25)',
                'plate_number' => 'E 7555 TJ',
                'bus_type' => 'New Armada R25',
                'capacity' => 50,
                'description' => 'Unit pariwisata Karoseri New Armada dengan suspensi udara dan interior modern.',
                'status' => 'active',
            ],
            [
                'name' => 'Takumi',
                'plate_number' => 'E 7332 TJ',
                'bus_type' => 'Jetbus 5 SHD Double Glass',
                'capacity' => 50,
                'description' => 'Unit pariwisata dengan suspensi udara dan sistem audio karaoke.',
                'status' => 'active',
            ],
            [
                'name' => 'Darth Vader',
                'plate_number' => 'E 7221 TJ',
                'bus_type' => 'Jetbus 5 SHD Single Glass',
                'capacity' => 50,
                'description' => 'Unit pariwisata dengan fasilitas audio dan TV.',
                'status' => 'active',
            ],
            [
                'name' => 'Winata',
                'plate_number' => 'E 7888 TJ',
                'bus_type' => 'Jetbus 3+ SHD',
                'capacity' => 50,
                'description' => 'Armada pariwisata dan AKAP lintas Sumatera.',
                'status' => 'active',
            ],
        ];

        foreach ($buses as $data) {
            Bus::updateOrCreate(
                ['plate_number' => $data['plate_number']],
                $data
            );
        }
    }
}
