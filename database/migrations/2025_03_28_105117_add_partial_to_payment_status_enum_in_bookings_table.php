<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPartialToPaymentStatusEnumInBookingsTable extends Migration
{
    public function up()
    {
        // ใช้ raw SQL เพื่อป้องกันข้อจำกัดของ Laravel schema builder
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('pending', 'paid', 'partial', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('pending', 'paid', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
}