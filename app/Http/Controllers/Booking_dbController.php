<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Booking_dbController extends Controller
{
    public function moveToHistory($id)
    {
        $booking = Booking::findOrFail($id);
        
        // Create a new entry in the booking history
        DB::table('booking_histories')->insert([
            'user_id' => $booking->user_id,
            'external_name' => $booking->external_name,
            'external_email' => $booking->external_email,
            'external_phone' => $booking->external_phone,
            'building_id' => $booking->building_id,
            'room_id' => $booking->room_id,
            'room_name' => $booking->room_name,
            'building_name' => $booking->building_name,
            'booking_start' => $booking->booking_start,
            'booking_end' => $booking->booking_end,
            'status_id' => $booking->status_id,
            'reason' => $booking->reason,
            'total_price' => $booking->total_price,
            'payment_status' => $booking->payment_status,
            'is_external' => $booking->is_external,
            'fullname' => $booking->fullname,
            'phone' => $booking->phone,
            'email' => $booking->email,
            'department' => $booking->department,
            'attendees' => $booking->attendees,
            'purpose' => $booking->purpose,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Delete the original booking
        $booking->delete();
    }
    
    public function index(Request $request)
    {
        // สร้าง query ที่ไม่เชื่อมโยงกับตาราง rooms และ buildings
        $query = DB::table('bookings')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->select(
                'bookings.*', 
                'status.status_name', 
                'users.name as user_name'
            );

        // ไม่แสดงรายการที่ดำเนินการเสร็จสิ้นแล้ว (status_id = 6)
        $query->where('bookings.status_id', '!=', 6);
        
        // ตรวจสอบการจองที่สิ้นสุดวันแล้ว แต่ยังไม่ได้ทำเครื่องหมายว่าเสร็จสิ้น
        $this->autoCompletePastBookings();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bookings.id', 'like', "%{$search}%")
                  ->orWhere('bookings.external_name', 'like', "%{$search}%")
                  ->orWhere('users.name', 'like', "%{$search}%");
            });
        }
        
        // Calendar search - if date is selected in calendar
        if ($request->has('booking_date')) {
            $bookingDate = $request->booking_date;
            $query->where(function($q) use ($bookingDate) {
                $q->whereDate('bookings.booking_start', '<=', $bookingDate)
                  ->whereDate('bookings.booking_end', '>=', $bookingDate);
            });
        }

        $bookings = $query->paginate(10);
        
        // Count booking statistics
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status_id', 3)->count(); // รอดำเนินการ
        $confirmedBookings = Booking::where('status_id', 4)->count(); // ได้รับอนุมัติ
        
        // ดึงข้อมูลสถานะทั้งหมดเพื่อใช้ในการแสดงตัวเลือก
        $statuses = DB::table('status')->get();
        
        return view('dashboard.booking_db', compact('bookings', 'totalBookings', 'pendingBookings', 'confirmedBookings', 'statuses'));
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status_id = $request->status_id;
        $booking->save();

        // ถ้าสถานะเป็น "ดำเนินการเสร็จสิ้น" (status_id = 6) ให้ย้ายไปยังประวัติ
        if ($request->status_id == 6) {
            $this->moveToHistory($id);
        }

        $statusName = DB::table('status')
            ->where('status_id', $request->status_id)
            ->value('status_name');
            
        return redirect()->route('booking_db')->with('success', "การจองถูกเปลี่ยนสถานะเป็น{$statusName}เรียบร้อยแล้ว");
    }
    
    /**
     * ตรวจสอบและอัปเดตสถานะการจองที่สิ้นสุดไปแล้วโดยอัตโนมัติ
     */
    private function autoCompletePastBookings()
    {
        $now = Carbon::now();
        
        // ค้นหาการจองที่สิ้นสุดแล้วแต่ยังไม่ได้ทำเครื่องหมายว่าเสร็จสิ้น
        $pastBookings = Booking::where('booking_end', '<', $now)
            ->whereNotIn('status_id', [5, 6]) // ไม่รวมที่ยกเลิกหรือเสร็จสิ้นแล้ว
            ->get();
            
        foreach ($pastBookings as $booking) {
            // อัปเดตสถานะเป็น "ดำเนินการเสร็จสิ้น" (status_id = 6)
            $booking->status_id = 6;
            $booking->save();
            
            // ย้ายไปยังประวัติการจอง
            $this->moveToHistory($booking->id);
        }
    }
}