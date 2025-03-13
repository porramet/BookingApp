<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Booking::create([
            'user_id' => 1,
            'building_id' => 1,
            'room_id' => 1,
            'booking_start' => now(),
            'booking_end' => now()->addHours(2),
            'status_id' => 1,
            'total_price' => 500.00,
            'payment_status' => 'paid',
            'is_external' => false
        ]);

        Booking::create([
            'external_name' => 'John Doe',
            'external_email' => 'john@example.com',
            'external_phone' => '0812345678',
            'building_id' => 2,
            'room_id' => 3,
            'booking_start' => now()->addDay(),
            'booking_end' => now()->addDay()->addHours(3),
            'status_id' => 2,
            'total_price' => 750.00,
            'payment_status' => 'pending',
            'is_external' => true
        ]);
    }
}
