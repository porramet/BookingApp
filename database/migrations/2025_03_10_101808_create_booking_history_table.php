<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('booking_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id')->unique(); // รหัสการจองจากตาราง bookings
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();
            $table->string('external_name')->nullable();
            $table->string('external_email')->nullable();
            $table->string('external_phone')->nullable();
            $table->date('booking_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('purpose')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->string('payment_status')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->dateTime('moved_to_history_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_history');
    }
}