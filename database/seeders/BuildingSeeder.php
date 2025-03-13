<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create(); // Create a Faker instance

        $buildings = [
            ['id' => 1, 'building_name' => 'หอประชุม', 'citizen_save' => 'Admin'],
            ['id' => 3, 'building_name' => 'อาคาร 3', 'citizen_save' => 'Admin'],
            ['id' => 5, 'building_name' => 'อาคาร 5', 'citizen_save' => 'Admin'],
            ['id' => 8, 'building_name' => 'อาคาร 8', 'citizen_save' => 'Admin'],
            ['id' => 9, 'building_name' => 'อาคาร 9 ศูนย์วิทยาศาสตร์', 'citizen_save' => 'Admin'],
            ['id' => 10, 'building_name' => 'อาคาร 10', 'citizen_save' => 'Admin'],
            ['id' => 13, 'building_name' => 'อาคาร 13', 'citizen_save' => 'Admin'],
        ];

        foreach ($buildings as $building) {
            DB::table('buildings')->insert([
                'id' => $building['id'], // กำหนด ID เอง
                'building_name' => $building['building_name'],
                'citizen_save' => $building['citizen_save'],
                'image' => $faker->imageUrl(640, 480, 'building'), // สร้าง URL รูปภาพตัวอย่าง
                'created_at' => now(),
                'updated_at' => now(),
                'date_save' => now(),
            ]);
        }
    }

}
