<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingHistory extends Model
{
    use HasFactory;

    protected $table = 'booking_history';
    
    protected $fillable = [
        'user_id',
        'external_name',
        'external_email',
        'external_phone',
        'building_id',
        'room_id',
        'room_name',
        'building_name',
        'booking_start',
        'booking_end',
        'status_id',
        'reason',
        'total_price',
        'payment_status',
        'is_external',
        'fullname',
        'phone',
        'email',
        'department',
        'attendees',
        'purpose',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'status_id');
    }

    // เพิ่มความสัมพันธ์อื่นๆ ตามที่ต้องการ
}