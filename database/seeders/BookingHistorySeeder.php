<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookingHistory;
use Illuminate\Support\Facades\DB;

class BookingHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed 100 booking history records
        for ($i = 1; $i <= 100; $i++) {
            BookingHistory::create([
                'booking_id' => rand(1, 50), // Assuming there are valid booking IDs from 1 to 50
                'user_id' => rand(1, 10), // Assuming there are 10 users
                'external_name' => 'User ' . $i,
                'external_email' => 'user' . $i . '@example.com',
                'external_phone' => '0123456789',
                'building_id' => rand(1, 5), // Assuming there are 5 buildings
                'building_name' => 'Building ' . rand(1, 5),
                'room_id' => rand(1, 20), // Assuming there are 20 rooms
                'room_name' => 'Room ' . rand(1, 20),
                'booking_start' => now(),
                'booking_end' => now()->addDays(rand(1, 30)),
                'status_id' => 6, // Assuming status_id 6 is for completed
                'status_name' => 'Completed',
                'reason' => 'Business Meeting',
                'total_price' => rand(1000, 5000),
                'payment_status' => 'paid', // Assuming 'paid' is the correct value
                'is_external' => false,
            ]);

        }
    }
}
