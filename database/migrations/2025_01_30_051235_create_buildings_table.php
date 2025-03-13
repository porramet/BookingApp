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
      // ตาราง buildings
        Schema::create('buildings', function (Blueprint $table) {
            $table->id('id'); // ใช้ id() เพื่อสร้าง primary key แบบ unsignedBigInteger
            $table->string('building_name'); // ชื่ออาคาร
            $table->string('citizen_save'); // ชื่อผู้บันทึก
            $table->timestamps();
            $table->timestamp('date_save')->useCurrent(); // ใช้เวลาในปัจจุบันโดยอัตโนมัติ
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buildings');
    }
};
