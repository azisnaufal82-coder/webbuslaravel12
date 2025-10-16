<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@busticket.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create regular users
        User::factory(5)->create();

        // Create buses with better facilities
        $buses = [
            [
                'name' => 'Scania Jetbus 3+',
                'bus_class' => 'Eksekutif',
                'capacity' => 40,
                'plate_number' => 'B 1234 XYZ',
                'facilities' => 'AC, Toilet, TV, Snack, WiFi, Charger, Bantal, Selimut'
            ],
            [
                'name' => 'Mercedes-Benz OH 1626',
                'bus_class' => 'Patas AC',
                'capacity' => 35,
                'plate_number' => 'B 5678 ABC',
                'facilities' => 'AC, Toilet, TV, Snack, Charger'
            ],
            [
                'name' => 'Hino RN 285',
                'bus_class' => 'Ekonomi',
                'capacity' => 45,
                'plate_number' => 'B 9012 DEF',
                'facilities' => 'AC, Toilet, Charger'
            ],
            [
                'name' => 'Volvo 9400',
                'bus_class' => 'Super Executive',
                'capacity' => 30,
                'plate_number' => 'B 3456 GHI',
                'facilities' => 'AC, Toilet, TV, Snack, WiFi, Charger, Bantal, Selimut, Meal, Entertainment'
            ]
        ];

        foreach ($buses as $bus) {
            Bus::create($bus);
        }

        // Create routes
        $routes = [
            ['origin' => 'Jakarta', 'destination' => 'Bandung', 'distance_km' => 150],
            ['origin' => 'Jakarta', 'destination' => 'Surabaya', 'distance_km' => 780],
            ['origin' => 'Bandung', 'destination' => 'Yogyakarta', 'distance_km' => 450],
            ['origin' => 'Surabaya', 'destination' => 'Malang', 'distance_km' => 90],
            ['origin' => 'Jakarta', 'destination' => 'Semarang', 'distance_km' => 430],
            ['origin' => 'Bandung', 'destination' => 'Semarang', 'distance_km' => 380],
        ];

        foreach ($routes as $route) {
            Route::create($route);
        }

        // Create schedules with more realistic times and available seats
        $baseTime = now()->addDays(1);
        
        $schedules = [];
        for ($i = 1; $i <= 20; $i++) {
            $departureTime = $baseTime->copy()->addDays(rand(1, 30))->addHours(rand(6, 22));
            $arrivalTime = $departureTime->copy()->addHours(rand(2, 8));
            
            $schedule = Schedule::create([
                'bus_id' => rand(1, 4),
                'route_id' => rand(1, 6),
                'departure_time' => $departureTime,
                'arrival_time' => $arrivalTime,
                'price' => rand(80000, 300000),
            ]);
            $schedules[] = $schedule;
        }

        // Create some bookings for testing (jangan penuhi semua kursi)
        foreach ($schedules as $schedule) {
            $randomBookings = rand(0, 3); // Buat 0-3 booking per schedule
            for ($j = 0; $j < $randomBookings; $j++) {
                Booking::create([
                    'user_id' => rand(2, 6), // Avoid admin user
                    'schedule_id' => $schedule->id,
                    'num_of_seats' => rand(1, 4),
                    'total_price' => $schedule->price * rand(1, 4),
                    'status' => ['pending', 'confirmed'][rand(0, 1)],
                    'created_at' => now()->subDays(rand(1, 10))
                ]);
            }
        }
    }
}