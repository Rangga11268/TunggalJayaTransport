<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Booking extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'booking_code',
        'passenger_name',
        'passenger_phone',
        'passenger_email',
        'seat_numbers',
        'number_of_seats',
        'total_price',
        'payment_status',
        'booking_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    
    public function getBookedSeatNumbersAttribute()
    {
        if ($this->seat_numbers) {
            return explode(',', $this->seat_numbers);
        }
        return [];
    }
    
    public function setSeatNumbersAttribute($value)
    {
        // Validate that seat numbers don't exceed the number of seats booked
        if ($value) {
            $seatNumbers = explode(',', $value);
            if (count($seatNumbers) > $this->number_of_seats) {
                throw new \InvalidArgumentException('Number of seat numbers cannot exceed the number of seats booked');
            }
        }
        
        $this->attributes['seat_numbers'] = $value;
    }
    
    public function setNumberOfSeatsAttribute($value)
    {
        // Validate that the number of seats doesn't exceed bus capacity
        if ($this->schedule && $value > $this->schedule->bus->capacity) {
            throw new \InvalidArgumentException('Number of seats cannot exceed bus capacity');
        }
        
        $this->attributes['number_of_seats'] = $value;
    }
}
