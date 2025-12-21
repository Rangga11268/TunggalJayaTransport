<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    // Role Constants
    const ROLE_ADMIN = 'admin';
    const ROLE_SCHEDULE_MANAGER = 'schedule_manager';
    const ROLE_CUSTOMER = 'customer';
    const ROLE_DRIVER = 'driver';
    const ROLE_CONDUCTOR = 'conductor';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'phone_verified_at',
        'is_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
        ];
    }

    public function articles()
    {
        return $this->hasMany(NewsArticle::class, 'author_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function hasPhoneVerified(): bool
    {
        return !is_null($this->phone_verified_at);
    }

    public function isFullyVerified(): bool
    {
        return $this->hasVerifiedEmail() && $this->hasPhoneVerified() && $this->is_verified;
    }

    /**
     * Get the user's role name.
     */
    public function getRoleAttribute()
    {
        return $this->getRoleNames()->first();
    }
}
