<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Bus extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'plate_number',
        'bus_type',
        'bus_category',
        'capacity',
        'description',
        'status',
        'year',
    ];

    public static function getStandardFacilities()
    {
        return collect([
            ['name' => 'Full AC Dual Blower', 'icon' => 'fas fa-snowflake', 'desc' => 'Suhu kabin teratur dan sejuk sepanjang perjalanan'],
            ['name' => 'Audio & Karaoke Smart TV', 'icon' => 'fas fa-tv', 'desc' => 'Layar multimedia hiburan dan sistem karaoke'],
            ['name' => 'Wi-Fi Gratis 4G', 'icon' => 'fas fa-wifi', 'desc' => 'Koneksi internet stabil untuk seluruh penumpang'],
            ['name' => 'Toilet Bersih High-Standard', 'icon' => 'fas fa-toilet', 'desc' => 'Fasilitas toilet yang terawat di dalam bus'],
            ['name' => 'Reclining Seat & Leg Rest', 'icon' => 'fas fa-chair', 'desc' => 'Kursi ergonomis bersandar empuk dengan sandaran kaki'],
            ['name' => 'USB Fast Charger', 'icon' => 'fas fa-plug', 'desc' => 'Colokan pengisi daya gadget di setiap baris kursi'],
            ['name' => 'Bantal & Selimut Steril', 'icon' => 'fas fa-[#10207a]', 'desc' => 'Perlengkapan istirahat bersih dan wangi'],
            ['name' => 'Bagasi Ekstra Luas', 'icon' => 'fas fa-suitcase-rolling', 'desc' => 'Kapasitas bagasi barang yang aman dan besar'],
        ]);
    }

    protected $appends = ['image_url'];

    public function activeBookings()
    {
        return $this->hasMany(Booking::class, 'assigned_bus_id')->where('status', 'active');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function charterBookings()
    {
        return $this->belongsToMany(CharterBooking::class, 'charter_booking_bus', 'bus_id', 'charter_booking_id')->withTimestamps();
    }

    public function drivers()
    {
        return $this->belongsToMany(Driver::class)->withTimestamps();
    }

    public function conductors()
    {
        return $this->belongsToMany(Conductor::class)->withTimestamps();
    }

    public function getImageUrlAttribute()
    {
        $url = $this->getFirstMediaUrl('cover') ?: $this->getFirstMediaUrl('buses');
        if (!empty($url)) {
            return $url;
        }

        $nameLower = strtolower($this->name ?? '');
        if (str_contains($nameLower, 'bisma')) return '/img/resiBisma.webp';
        if (str_contains($nameLower, 'primadona')) return '/img/primadona.webp';
        if (str_contains($nameLower, 'bentas')) return '/img/bentas01.webp';
        if (str_contains($nameLower, 'kyloren') || str_contains($nameLower, 'parwis')) return '/img/kylorenParwis.webp';
        if (str_contains($nameLower, 'bungsu')) return '/img/bungsu.webp';
        if (str_contains($nameLower, 'jedha')) return '/img/jedha.webp';
        if (str_contains($nameLower, 'fortuna')) return '/img/fortuna.webp';
        if (str_contains($nameLower, 'semar')) return '/img/semar.webp';
        
        return '/img/heroImg.jpg';
    }
}
