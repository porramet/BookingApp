<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status'; // กำหนดชื่อตารางให้ตรงกับที่สร้างไว้
    protected $primaryKey = 'status_id'; // กำหนดคีย์หลักให้ตรงกับฐานข้อมูล

    protected $fillable = ['status_name']; // ฟิลด์ที่สามารถเพิ่มข้อมูลได้

    public $timestamps = false;

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'status_id', 'status_id');
    }
}
