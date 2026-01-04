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

    /**
     * Booting model-nya.
     */
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
            // Buat jadwal harian, kalo ga ada tanggal spesifik, itung yang kedepannya aja
            // Biar logicnya tetep jalan kaya biasanya
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
        // Ambil nomor kursi yang udah laku (confirmed & paid)
        $query = $this->bookings()
            ->where('booking_status', 'confirmed')
            ->where('payment_status', 'paid')
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
                $seatNumbers = array_merge($seatNumbers, $seats);
            }
        }

        return array_map('trim', $seatNumbers);
    }

    /**
     * Ambil jam berangkat asli buat display/booking.
     * Kalo jadwal harian, itung kapan next trip-nya.
     * Kalo jadwal biasa, ya balikin aja datetime yang kesimpen.
     * Balikin format WIB biar user lokal ga bingung.
     */
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

    /**
     * Sama kayak getActualDepartureTime tapi versi Arrival.
     * Logic-nya miriplah, males jelasin ulang wkwk.
     */
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

    /**
     * Cek apa busnya udah cabut.
     * Kalo recurring, cek trip hari ini udah lewat apa belum.
     * Note: Semua pake WIB (Asia/Jakarta)
     */
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

    /**
     * Ambil jam berangkat sesuai zona waktu target
     */
    public function getDepartureTimeInTimezone($timezone = null)
    {
        if ($timezone === null) {
            // Pake timezone sistem
            $timezone = Carbon::now()->timezone;
        }

        $departureTime = $this->getActualDepartureTime();
        return $departureTime->setTimezone($timezone);
    }

    /*-------------------------------------------------------------------------
     * WIB Time Conversion Methods
     *-------------------------------------------------------------------------*/

    /**
     * Get departure time in WIB timezone for display purposes
     */
    public function getDepartureTimeWIB()
    {
        return $this->getActualDepartureTime()->setTimezone('Asia/Jakarta');
    }

    /**
     * Get arrival time in WIB timezone for display purposes
     */
    public function getArrivalTimeWIB()
    {
        return $this->getActualArrivalTime()->setTimezone('Asia/Jakarta');
    }

    /**
     * Cek jadwalnya masih bisa dibooking ga
     */
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

    

    /**
     * Ambil tanggal-tanggal berangkat kedepannya (range date)
     */
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

    /**
     * Ambil bookingan yang harus dicancel kalo busnya berangkat
     */
    public function getBookingsToCancel()
    {
        // Kalo daily, balikin SEMUA yang belom cancel
        // soalnya kursinya harus kosong lagi besoknya
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

    /**
     * Scope buat jadwal yang 'available' aja
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
            ->whereHas('bus')
            ->whereHas('route');
    }

    

    /**
     * Scope for daily schedules
     */
    public function scopeDaily($query)
    {
        return $query->where('is_daily', false);
    }

    /**
     * Scope for daily recurring schedules
     */
    public function scopeDailyRecurring($query)
    {
        return $query->where('is_daily', true);
    }

    /**
     * Get formatted schedule information for display
     */
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
