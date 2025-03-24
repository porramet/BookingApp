<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('booking_history', function (Blueprint $table) {
            $table->id(); // รหัสประวัติการจอง
            $table->unsignedBigInteger('booking_id'); // รหัสการจอง (เชื่อมโยงกับตาราง bookings)
            $table->unsignedBigInteger('user_id')->nullable(); // รหัสผู้ใช้ที่ทำการเปลี่ยนแปลง (nullable เพราะอาจเป็นระบบหรือบุคคลภายนอก)
            $table->string('external_name')->nullable(); // ชื่อผู้จอง (ถ้าเป็นบุคคลภายนอก)
            $table->string('external_email')->nullable(); // อีเมลของบุคคลภายนอก
            $table->string('external_phone')->nullable(); // เบอร์โทรของบุคคลภายนอก
            $table->unsignedBigInteger('building_id'); // รหัสอาคาร
            $table->string('building_name')->nullable(); // ชื่ออาคาร
            $table->unsignedBigInteger('room_id'); // รหัสห้อง
            $table->string('room_name')->nullable(); // ชื่อห้อง
            $table->dateTime('booking_start'); // เวลาเริ่มต้นการจอง
            $table->dateTime('booking_end'); // เวลาสิ้นสุดการจอง
            $table->unsignedBigInteger('status_id'); // รหัสสถานะการจอง
            $table->string('status_name')->nullable(); // ชื่อสถานะการจอง
            $table->text('reason')->nullable(); // เหตุผลในการเปลี่ยนแปลงสถานะ
            $table->decimal('total_price', 10, 2)->nullable(); // ค่าบริการรวม (ถ้ามี)
            $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('pending'); // สถานะการชำระเงิน
            $table->boolean('is_external')->default(false); // ระบุว่าผู้จองเป็นบุคคลภายนอกหรือไม่
            $table->timestamps(); // วันที่สร้างและอัปเดต
        
            // Foreign keys
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
            $table->foreign(['building_id', 'room_id'])->references(['building_id', 'room_id'])->on('rooms')->onDelete('cascade');
            $table->foreign('status_id')->references('status_id')->on('status')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_history');
    }
}