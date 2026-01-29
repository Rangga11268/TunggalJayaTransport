<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

class Schedule extends Model
{
    protected $fillable = [
        'bus_id',
        'route_id',
        'departure_time',
        'arrival_time',
        'price',
        'status',
        'is_daily', // New field for daily recurring schedules
        'days_of_week', // ["Monday", "Friday"]
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'is_daily' => 'boolean',
        'days_of_week' => 'array',
    ];

    
    protected static function boot()
    {
        parent::boot();

        // Pas load data, ubah jam ke WIB biar ga pusing
        static::retrieved(function ($schedule) {
            if ($schedule->departure_time instanceof Carbon) {
                $schedule->departure_time = $schedule->departure_time->setTimezone('Asia/Jakarta');
            }
            if ($schedule->arrival_time instanceof Carbon) {
                $schedule->arrival_time = $schedule->arrival_time->setTimezone('Asia/Jakarta');
            }
        });
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getBookedSeatsCount($forDate = null)
    {
        $query = $this->bookings()
            ->where('booking_status', 'confirmed')
            ->where('payment_status', 'paid')
            ->where('booking_status', '!=', 'cancelled');

        // Kalo ngecek tanggal tertentu, filter pake booking_date
        if ($forDate) {
            $query->whereDate('booking_date', $forDate);
        } else {
            if ($this->is_daily) {
                // Untuk jadwal recurring, itung bookingan masa depan aja
                $query->whereDate('booking_date', '>=', Carbon::today());
            }
        }

        return $query->sum('number_of_seats');
    }

    public function getAvailableSeatsCount($forDate = null)
    {
        // Cuma itung yang udah confirm & lunas, cancel ga dianggep
        $bookedSeats = $this->getBookedSeatsCount($forDate);

        $available = $this->bus->capacity - $bookedSeats;
        return max(0, $available);
    }

    public function getBookedSeatNumbers($forDate = null)
    {
        $query = $this->bookings()
            ->whereIn('payment_status', ['paid', 'pending']) // Include pending!
            ->where('booking_status', '!=', 'cancelled') // Yang cancel gausah diajak
            ->whereNotNull('seat_numbers');

        // Filter tanggal kalo ada request
        if ($forDate) {
            $query->whereDate('booking_date', $forDate);
        } elseif ($this->is_daily) {
            // Kalo recurring dan ga ada tanggal, ambil yang depan-depan aja
            $query->whereDate('booking_date', '>=', Carbon::today());
        }

        $bookings = $query->pluck('seat_numbers')->toArray();

        $seatNumbers = [];
        foreach ($bookings as $seatString) {
            if ($seatString) {
                $seats = explode(',', $seatString);
                foreach ($seats as $seat) {
                    $seatNumbers[] = (int) trim($seat); // Convert to integer
                }
            }
        }

        return array_values(array_unique($seatNumbers)); // Remove duplicates and reindex
    }

    
    public function getActualDepartureTime($forDate = null)
    {
        $departureTime = null;

        if ($this->is_daily) {
            // Buat recurring, tampilin jam buat tanggal yg diminta atau jadwal terdekat selanjutnya
            if ($forDate) {
                $departureTime = $forDate->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
            } else {
                $now = Carbon::now('Asia/Jakarta');
                $today = Carbon::today('Asia/Jakarta');
                // Ambil jam berangkat hari ini
                $todayDeparture = $today->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));

                // Kalo belum lewat jamnya hari ini, pake yg hari ini
                if ($todayDeparture->isFuture()) {
                    $departureTime = $todayDeparture;
                } else {
                    // Kalo udah lewat, ya berarti besok
                    $departureTime = $today->copy()->addDay()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
                }
            }
        } else {
            // Jadwal biasa, easy peasy
            $departureTime = $this->departure_time;
        }

        // Convert ke WIB
        return $departureTime->setTimezone('Asia/Jakarta');
    }

    
    public function getActualArrivalTime($forDate = null)
    {
        $arrivalTime = null;

        if ($this->is_daily) {
            if ($forDate) {
                $arrivalTime = $forDate->copy()->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
            } else {
                $now = Carbon::now('Asia/Jakarta');
                $today = Carbon::today('Asia/Jakarta');

                $todayDeparture = $today->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));

                if ($todayDeparture->isFuture()) {
                    $arrivalTime = $today->copy()->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
                } else {
                    $arrivalTime = $today->copy()->addDay()->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
                }
            }
        } else {
            $arrivalTime = $this->arrival_time;
        }

        return $arrivalTime->setTimezone('Asia/Jakarta');
    }

    
    public function hasDeparted($forDate = null)
    {
        // Pake WIB dong pastinya
        $now = Carbon::now('Asia/Jakarta');

        if ($this->is_daily) {
            // Cek departure time hari yg diminta
            $checkDate = $forDate ?: Carbon::today('Asia/Jakarta');
            $checkDeparture = $checkDate->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));

            // Lewat ga?
            return $checkDeparture->isPast();
        }

        // Jadwal biasa
        if ($this->departure_time instanceof Carbon) {
            return $this->departure_time->isPast();
        }

        try {
            $departureTime = Carbon::parse($this->departure_time);
            return $departureTime->isPast();
        } catch (\Exception $e) {
            return false;
        }
    }

    
    public function getDepartureTimeInTimezone($timezone = null)
    {
        if ($timezone === null) {
            // Pake timezone sistem
            $timezone = Carbon::now()->timezone;
        }

        $departureTime = $this->getActualDepartureTime();
        return $departureTime->setTimezone($timezone);
    }

    

    
    public function getDepartureTimeWIB()
    {
        return $this->getActualDepartureTime()->setTimezone('Asia/Jakarta');
    }

    
    public function getArrivalTimeWIB()
    {
        return $this->getActualArrivalTime()->setTimezone('Asia/Jakarta');
    }

    
    public function isAvailableForBooking($forDate = null)
    {
        // Aktif ga nih?
        if ($this->status !== 'active') {
            return false;
        }

        // Udah cabut belom?
        if ($this->hasDeparted($forDate)) {
            return false;
        }

        // Masih ada kursi kosong ga?
        return $this->getAvailableSeatsCount($forDate) > 0;
    }

    

    
    public function getUpcomingDates($startDate = null, $endDate = null, $limit = 10)
    {
        // Buat jadwal harian, balikin range tanggal
        if ($this->is_daily) {
            $startDate = $startDate ? \Carbon\Carbon::parse($startDate) : \Carbon\Carbon::today('Asia/Jakarta');
            $endDate = $endDate ? \Carbon\Carbon::parse($endDate) : $startDate->copy()->addMonths(3); // Default 3 bulan

            $dates = collect();
            $currentDate = $startDate->copy();
            $count = 0;

            // Loop tiap hari
            while ($currentDate->lte($endDate) && $count < $limit) {
                $dates->push($currentDate->copy());
                $currentDate->addDay();
                $count++;
            }

            return $dates;
        }

        // Kalo bukan recurring, ya kosong
        return collect();
    }

    
    public function getBookingsToCancel()
    {
        if ($this->is_daily) {
            return $this->bookings()
                ->where('booking_status', '!=', 'cancelled') // Yang belom dicancel
                ->get();
        } else {
            // Kalo reguler, cuma yg payment pending aja yg dicancel
            return $this->bookings()
                ->where('booking_status', '!=', 'cancelled') // Yang belom dicancel
                ->where('payment_status', 'pending') // Yang belom bayar
                ->get();
        }
    }

    
    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
            ->whereHas('bus')
            ->whereHas('route');
    }

    

    
    public function scopeDaily($query)
    {
        return $query->where('is_daily', false);
    }

    
    public function scopeDailyRecurring($query)
    {
        return $query->where('is_daily', true);
    }

    
    public function getDisplayInfo()
    {
        $departure = $this->getActualDepartureTime();
        $arrival = $this->getActualArrivalTime();

        return [
            'departure' => $departure->format('H:i'),
            'arrival' => $arrival->format('H:i'),
            'date' => $departure->format('M j'),
            'full_departure' => $departure->format('Y-m-d H:i:s'),
            'full_arrival' => $arrival->format('Y-m-d H:i:s'),
            'is_daily' => $this->is_daily,
        ];
    }
}
