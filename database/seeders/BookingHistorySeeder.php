<?php

namespace Database\Seeders;

use App\Models\BookingHistory;
use Illuminate\Database\Seeder;

class BookingHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BookingHistory::create([
            'booking_id' => 1,
            'status_id' => 1,
            'changed_by' => 1,
            'user_id' => 1,
            'changed_at' => now()
        ]);

        BookingHistory::create([
            'booking_id' => 2,
            'status_id' => 2,
            'changed_by' => 1,
            'external_name' => 'John Doe',
            'external_email' => 'john@example.com',
            'external_phone' => '0812345678',
            'is_external' => true,
            'changed_at' => now()
        ]);
    }
}
