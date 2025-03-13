<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class RoomSeeder extends Seeder
{   
    public function run()
    {
        $faker = Faker::create();

        $rooms = [
            // อาคาร 10 (Building ID: 10)
            [
                'building_id' => 10,
                'room_id' => 1,
                'room_name' => 'ห้องประชุมสร้อยสุวรรณา',
                'class' => '3',
                'room_details' => 'ขนาด 200 ที่นั่ง',
                'capacity' => 200,
                'service_rates' => 5000.00,
                'status_id' => 1,
            ],

            // อาคาร 8 (Building ID: 8)
            [
                'building_id' => 8,
                'room_id' => 2,
                'room_name' => 'ห้องเรียนขนาดใหญ่',
                'class' => '1',
                'room_details' => 'ขนาด 120 ที่นั่ง',
                'capacity' => 120,
                'service_rates' => 2000.00,
                'status_id' => 1,
            ],

            // อาคาร 3 (Building ID: 3)
            [
                'building_id' => 3,
                'room_id' => 3,
                'room_name' => 'ห้องประชุม 327',
                'class' => '1',
                'room_details' => 'ขนาด 100 ที่นั่ง',
                'capacity' => 100,
                'service_rates' => 2500.00,
                'status_id' => 1,
            ],

            // อาคาร 5 (Building ID: 5)
            [
                'building_id' => 5,
                'room_id' => 4,
                'room_name' => 'ห้องประชุมราชพฤกษ์ใหญ่',
                'class' => '1',
                'room_details' => 'ขนาด 120 ที่นั่ง',
                'capacity' => 120,
                'service_rates' => 1500.00,
                'status_id' => 1,
            ],
            [
                'building_id' => 5,
                'room_id' => 5,
                'room_name' => 'ห้องเรียน 531',
                'class' => '1',
                'room_details' => 'ขนาด 35 ที่นั่ง',
                'capacity' => 35,
                'service_rates' => 600.00,
                'status_id' => 1,
            ],
            [
                'building_id' => 5,
                'room_id' => 6,
                'room_name' => 'ห้องเรียน 533',
                'class' => '1',
                'room_details' => 'ขนาด 35 ที่นั่ง',
                'capacity' => 35,
                'service_rates' => 600.00,
                'status_id' => 1,
            ],
            [
                'building_id' => 5,
                'room_id' => 7,
                'room_name' => 'ห้องเรียน 534',
                'class' => '1',
                'room_details' => 'ขนาด 35 ที่นั่ง',
                'capacity' => 35,
                'service_rates' => 600.00,
                'status_id' => 1,
            ],
            [
                'building_id' => 5,
                'room_id' => 8,
                'room_name' => 'ห้องเรียน 541',
                'class' => '1',
                'room_details' => 'ขนาด 35 ที่นั่ง',
                'capacity' => 35,
                'service_rates' => 600.00,
                'status_id' => 1,
            ],
            [
                'building_id' => 5,
                'room_id' => 9,
                'room_name' => 'ห้องปฏิบัติการคอมพิวเตอร์ 544',
                'class' => '1',
                'room_details' => 'ขนาด 35 ที่นั่ง',
                'capacity' => 35,
                'service_rates' => 4000.00,
                'status_id' => 1,
            ],
            [
                'building_id' => 5,
                'room_id' => 10,
                'room_name' => 'ห้องโถง 545',
                'class' => '1',
                'room_details' => 'ขนาด 35 ที่นั่ง',
                'capacity' => 35,
                'service_rates' => 600.00,
                'status_id' => 1,
            ],

            // ศูนย์วิทยาศาสตร์ (Building ID: 9)
            [
                'building_id' => 9,
                'room_id' => 11,
                'room_name' => 'ห้องประชุมใหญ่',
                'class' => '1',
                'room_details' => 'ขนาด 120 ที่นั่ง',
                'capacity' => 120,
                'service_rates' => 4500.00,
                'status_id' => 1,
            ],
            [
                'building_id' => 9,
                'room_id' => 12,
                'room_name' => 'ห้องประชุมเล็ก',
                'class' => '1',
                'room_details' => 'ขนาด 25 ที่นั่ง',
                'capacity' => 25,
                'service_rates' => 1000.00,
                'status_id' => 1,
            ],

            // อาคาร 13 (Building ID: 13)
            [
                'building_id' => 13,
                'room_id' => 13,
                'room_name' => 'ห้องประชุม 13209',
                'class' => '2',
                'room_details' => 'ขนาด 150 ที่นั่ง',
                'capacity' => 150,
                'service_rates' => 6000.00,
                'status_id' => 1,
            ],
            [
                'building_id' => 13,
                'room_id' => 14,
                'room_name' => 'ห้องประชุม 13503',
                'class' => '5',
                'room_details' => 'ขนาด 15 ที่นั่ง',
                'capacity' => 15,
                'service_rates' => 2000.00,
                'status_id' => 1,
            ],

            // หอประชุม (Building ID: 1)
            [
                'building_id' => 1,
                'room_id' => 15,
                'room_name' => 'หอประชุมมหาวิจิราลงกรณ',
                'class' => '1',
                'room_details' => 'พื้นที่ 1,260 ตารางเมตร (ขนาด 2,500 ที่นั่ง)',
                'capacity' => 2500,
                'service_rates' => 60000.00,
                'status_id' => 1,
            ],
            [
                'building_id' => 1,
                'room_id' => 16,
                'room_name' => 'หอประชุม 2',
                'class' => '1',
                'room_details' => 'การจัดแสดงนิทรรศการหรืองานอื่นๆ (พื้นที่ 939.10 ตารางเมตร)',
                'capacity' => 1200,
                'service_rates' => 5000.00,
                'status_id' => 1,
            ],
            [
                'building_id' => 1,
                'room_id' => 17,
                'room_name' => 'หอประชุม 1',
                'class' => '1',
                'room_details' => 'การจัดอบรม/สัมมนา (ขนาด 1,200 ที่นั่ง)',
                'capacity' => 1200,
                'service_rates' => 15000.00,
                'status_id' => 1,
            ],
            [
                'building_id' => 1,
                'room_id' => 18,
                'room_name' => 'หอประชุม 1',
                'class' => '1',
                'room_details' => 'การจัดแสดงนิทรรศการหรืองานอื่นๆ (พื้นที่ 836.50 ตารางเมตร)',
                'capacity' => 1200,
                'service_rates' => 20000.00,
                'status_id' => 1,
            ],
        ];

        foreach ($rooms as $room) {
            DB::table('rooms')->insert([
                'building_id' => $room['building_id'],
                'room_id' => $room['room_id'],
                'room_name' => $room['room_name'],
                'class' => $room['class'],
                'room_details' => $room['room_details'],
                'image' => $faker->imageUrl(640, 480, 'room'),
                'capacity' => $room['capacity'],
                'service_rates' => $room['service_rates'],
                'status_id' => $room['status_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

}

