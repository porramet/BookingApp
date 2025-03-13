<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = Status::all()->isEmpty() ? [
            ['status_id' => 1, 'status_name' => 'ไม่พร้อมใช้งาน'], 
            ['status_id' => 2, 'status_name' => 'พร้อมใช้งาน'],     
            ['status_id' => 3, 'status_name' => 'รอดำเนินการ'], 
            ['status_id' => 4, 'status_name' => 'อนุมัติแล้ว'], 
            ['status_id' => 5, 'status_name' => 'ยกเลิกการการจอง'],     
            ['status_id' => 6, 'status_name' => 'ดำเนินการเสร็จสิ้น'] 
        ] : []; 

        foreach ($statuses as $status) {
            if (!Status::where('status_id', $status['status_id'])->exists()) { // Check if status already exists
                Status::create($status);
            }
        }
    }
}