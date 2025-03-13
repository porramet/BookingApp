<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\BookingHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MoveCompletedBookingsToHistory extends Command
{
    protected $signature = 'bookings:move-to-history';
    protected $description = 'Move completed bookings to history table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::today();
        
        // ค้นหาการจองที่สิ้นสุดวันจองแล้ว (วันที่จองน้อยกว่าวันนี้)
        $completedBookings = Booking::where('booking_start', '<', $today)->get();
        
        $count = 0;
        
        foreach ($completedBookings as $booking) {
            // สร้างบันทึกใหม่ในตารางประวัติ
            BookingHistory::create([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'room_id' => $booking->room_id,
                'external_name' => $booking->external_name,
                'external_email' => $booking->external_email,
                'external_phone' => $booking->external_phone,
                'booking_date' => $booking->booking_date,
                'start_time' => $booking->start_time,
                'end_time' => $booking->end_time,
                'purpose' => $booking->purpose,
                'status_id' => $booking->status_id,
                'payment_status' => $booking->payment_status,
                'amount' => $booking->amount,
                'moved_to_history_at' => now(),
            ]);
            
            // เปลี่ยนสถานะเป็น "จบแล้ว" (สถานะ ID 4)
            $booking->status_id = 6;
            $booking->save();
            
            $count++;
        }
        
        $this->info("Moved {$count} bookings to history.");
    }
}
