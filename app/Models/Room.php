<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    //protected $primaryKey = ['building_id', 'room_id'];
    protected $primaryKey = 'room_id';
    protected $table = 'rooms'; // กำหนดชื่อตารางให้แน่นอน
    //protected $primaryKey = 'room_id'; // แก้จาก 'id' เป็น 'room_id'
    protected $keyType = 'int'; // กำหนด type เป็น integer
    public $incrementing = false;


    protected $fillable = [
        'room_id',
        'room_name',
        'class',
        'room_details',
        'capacity',
        'service_rates',
        'status_id',
        'building_id',
        'image',
    ];


    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id'); // ระบุ foreign key ให้ชัดเจน
    }
    

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
