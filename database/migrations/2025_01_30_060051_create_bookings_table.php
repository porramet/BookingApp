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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id(); // รหัสการจอง
            $table->unsignedBigInteger('user_id')->nullable(); // รหัสผู้ใช้ภายใน (nullable เพราะบุคคลภายนอกอาจไม่มี)
            $table->string('external_name')->nullable(); // ชื่อผู้จอง (ถ้าเป็นบุคคลภายนอก)
            $table->string('external_email')->nullable(); // อีเมลของบุคคลภายนอก
            $table->string('external_phone')->nullable(); // เบอร์โทรของบุคคลภายนอก
            $table->unsignedBigInteger('building_id'); // รหัสอาคาร
            $table->string('building_name')->nullable(); // ชื่ออาคาร (ถ้ามี)
            $table->string('room_name')->nullable(); // ชื่อห้อง (ถ้ามี)
            $table->unsignedBigInteger('room_id'); // รหัสห้อง
            $table->unsignedBigInteger('status_id'); // รหัสสถานะการจอง
            $table->string('status_name')->nullable();
            $table->dateTime('booking_start'); // เวลาเริ่มต้นการจอง
            $table->dateTime('booking_end'); // เวลาสิ้นสุดการจอง
            $table->text('reason')->nullable(); // เหตุผลในการจอง
            $table->decimal('total_price', 10, 2)->nullable(); // ค่าบริการรวม (ถ้ามี)
            $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('pending'); // สถานะการชำระเงิน
            $table->boolean('is_external')->default(false); // ระบุว่าผู้จองเป็นบุคคลภายนอกหรือไม่
            $table->timestamps(); // วันที่สร้างและอัปเดต
            $table->string('payment_slip')->nullable(); // เส้นทางไฟล์สลิปการโอนเงิน
            $table->timestamp('verified_at')->nullable();
            $table->string('approver_name')->nullable();


            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
            $table->foreign(['building_id', 'room_id'])->references(['building_id', 'room_id'])->on('rooms')->onDelete('cascade');

            $table->foreign('status_id')->references('status_id')->on('status')->onDelete('cascade');

        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
