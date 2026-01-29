<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Carbon\Carbon;

class Booking extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'booking_date',
        'booking_code',
        'passenger_name',
        'passenger_phone',
        'passenger_email',
        'seat_numbers',
        'number_of_seats',
        'total_price',
        'payment_status',
        'booking_status',
        'payment_started_at',
        'snap_token',
        'midtrans_transaction_id',
        'check_in_time',
        'promo_code_id',
        'discount_amount',
        'original_total_price',
    ];

    protected $casts = [
        'booking_date' => 'date', // Renamed to departure_date in snippet, but original is booking_date. Keeping booking_date as per original.
        'departure_date' => 'date', // Added from snippet
        'payment_started_at' => 'datetime',
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'original_total_price' => 'decimal:2',
        // 'seat_numbers' => 'array', // Removed as it conflicts with CSV storage
        'check_in_time' => 'datetime',
    ];

    protected $appends = ['departure_time'];

    public function getDepartureTimeAttribute()
    {
        if (!$this->schedule) {
            return null;
        }

        $timeString = $this->schedule->departure_time->format('H:i:s');
        
        // Kalo ada booking_date, gabungin sama jam jadwal
        if ($this->booking_date) {
            return $this->booking_date->setTimeFromTimeString($timeString);
        }

        // Fallback ke jam jadwal (walaupun taun jebot 2000)
        return $this->schedule->departure_time;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    
    public function paymentHistories()
    {
        return $this->hasMany(PaymentHistory::class);
    }
    
    public function latestPaymentHistory()
    {
        return $this->hasOne(PaymentHistory::class)->latestOfMany();
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
        // Validasi jumlah kursi, jangan sampe lebih dari yg di-booking
        if ($value) {
            $seatNumbers = explode(',', $value);
            if (count($seatNumbers) > $this->number_of_seats) {
                throw new \InvalidArgumentException('Kebanyakan milih kursi woy, jatahnya cuma ' . $this->number_of_seats);
            }
        }
        
        $this->attributes['seat_numbers'] = $value;
    }
    
    public function setNumberOfSeatsAttribute($value)
    {
        // Validasi lagi, jangan maruk melebihi kapasitas bus
        if ($this->schedule && $value > $this->schedule->bus->capacity) {
            throw new \InvalidArgumentException('Busnya ga muat bos');
        }
        
        $this->attributes['number_of_seats'] = $value;
    }
    
    
    public function isPaymentExpired()
    {
        // Expired 30 menit kalo masih pending, kelamaan nunggu keburu diambil orang
        if ($this->payment_status === 'pending' && $this->payment_started_at) {
            return $this->payment_started_at->addMinutes(30)->isPast();
        }
        
        return false;
    }
    
    
    public function startPayment()
    {
        $this->payment_started_at = Carbon::now();
        $this->save();
    }
    
    
    public static function getOccupiedSeatsForSchedule($scheduleId, $date)
    {
        return self::where('schedule_id', $scheduleId)
            ->whereDate('booking_date', $date)
            ->whereIn('payment_status', ['paid', 'pending']) // Include pending to prevent double booking
            ->pluck('seat_numbers')
            ->flatMap(function ($seatNumbers) {
                // seat_numbers is stored as comma-separated string like "1,2,3"
                return explode(',', $seatNumbers);
            })
            ->map(fn($seat) => (int) trim($seat))
            ->unique()
            ->values()
            ->toArray();
    }
}
