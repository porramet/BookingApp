<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;

class NewBuildingSeeder extends Seeder
{
    public function run()
    {
        $buildings = [
            ['building_name' => 'อาคาร D', 'citizen_save' => 'สมชาย ใจดี'],
            ['building_name' => 'อาคาร E', 'citizen_save' => 'สมหญิง ใจดี'],
            ['building_name' => 'อาคาร F', 'citizen_save' => 'สมปอง ใจดี'],
        ];

        foreach ($buildings as $building) {
            Building::create($building);
        }
    }
}
