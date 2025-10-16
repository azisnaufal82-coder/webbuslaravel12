<?php
// app/Models/Schedule.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'route_id',
        'departure_time',
        'arrival_time',
        'price'
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime'
    ];

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

    public function getAvailableSeatsAttribute()
    {
        $bookedSeats = $this->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('num_of_seats');
            
        return max(0, $this->bus->capacity - $bookedSeats);
    }

    public function getHasAvailableSeatsAttribute()
    {
        return $this->available_seats > 0;
    }
}