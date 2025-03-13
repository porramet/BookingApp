<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingHistory extends Model
{
    use HasFactory;

    protected $table = 'booking_history';
    
    protected $fillable = [
        'booking_id', 'user_id', 'room_id', 'external_name', 'external_email', 
        'external_phone', 'booking_date', 'start_time', 'end_time', 
        'purpose', 'status_id', 'payment_status', 'amount', 'moved_to_history_at'
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