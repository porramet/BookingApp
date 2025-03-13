<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class NewRoomSeeder extends Seeder
{
    public function run()
    {
        $rooms = [
            ['room_name' => 'ห้องประชุม F', 'capacity' => 10, 'status_id' => 1, 'building_id' => 2, 'floor' => 1, 'details' => 'ห้องประชุมขนาดเล็ก', 'service_rate' => 100.00],
            ['room_name' => 'ห้องประชุม G', 'capacity' => 8, 'status_id' => 2, 'building_id' => 2, 'floor' => 1, 'details' => 'ห้องประชุมขนาดกลาง', 'service_rate' => 100.00],
            ['room_name' => 'ห้องประชุม H', 'capacity' => 12, 'status_id' => 1, 'building_id' => 2, 'floor' => 1, 'details' => 'ห้องประชุมขนาดใหญ่', 'service_rate' => 100.00],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
