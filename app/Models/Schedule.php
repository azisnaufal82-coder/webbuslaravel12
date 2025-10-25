<?php

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

    // Relasi ke BUS
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    // Relasi ke ROUTE
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    // ✅ Relasi ke BOOKING (WAJIB ADA)
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    protected $casts = [
    'departure_time' => 'datetime',
    'arrival_time'   => 'datetime',
];


    
}
