<?php
// app/Models/Booking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'booking_code',
        'num_of_seats',
        'total_price',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Perbaiki nama relationship dari 'schedule' menjadi 'schedule'
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            $booking->booking_code = 'BK' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        });
    }
}