<?php
// app/Models/Bus.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bus_class',
        'capacity',
        'plate_number',
        'facilities'
    ];

    // Add this method to handle facilities
    public function getFacilitiesArrayAttribute()
    {
        if (is_array($this->facilities)) {
            return $this->facilities;
        }
        
        if (is_string($this->facilities)) {
            return array_map('trim', explode(',', $this->facilities));
        }
        
        return [];
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}