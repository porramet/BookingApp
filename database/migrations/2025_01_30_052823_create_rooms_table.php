<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->unsignedBigInteger('building_id'); // รหัสอาคาร
            $table->unsignedBigInteger('room_id'); // รหัสห้อง (ไม่ต้องตั้ง primary key ที่นี่)
        
            // คอลัมน์อื่นๆ
            $table->string('room_name'); // ชื่อห้อง
            $table->string('class'); // ชั้น
            $table->string('room_details'); // รายละเอียดห้อง
            $table->string('image'); // รูปภาพ
            $table->integer('capacity'); // ขนาดความจุ
            $table->decimal('service_rates', 10, 2); // อัตราค่าบริการ
            $table->unsignedBigInteger('status_id'); // สถานะการใช้งาน
        
            // เชื่อมโยงกับตาราง buildings
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
        
            // เชื่อมโยงกับตาราง status
            $table->foreign('status_id')->references('status_id')->on('status')->onDelete('cascade');
        
            // กำหนด primary key เป็นคู่ (building_id, room_id)
            $table->primary(['building_id', 'room_id']);
        
            $table->timestamps(); // วันที่และเวลาที่บันทึก
        });
        
        
        
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};
