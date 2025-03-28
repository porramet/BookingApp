<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
        'booking_end' => 'datetime',
        'booking_start' => 'datetime',
        'verified_at' => 'datetime',
    ];
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    protected $fillable = [
        'user_id',
        'external_name',
        'external_email',
        'external_phone',
        'building_id',
        'room_id',
        'room_name', // ✅ เพิ่ม
        'building_name', // ✅ เพิ่ม
        'booking_start',
        'booking_end',
        'status_id',
        'reason',
        'approver_name',
        'total_price',
        'payment_status',
        'is_external',
        'fullname',
        'phone',
        'email',
        'status',
        'department',
        'payment_slip',
        'attendees',
        'purpose'
        

    ];

}



