<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateBookingPaymentRequest;

use Carbon\Carbon;

class Booking_dbController extends Controller
{
    public function moveToHistory($id)
    {
        try {
            $booking = Booking::findOrFail($id);

            DB::table('booking_history')->insert([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'external_name' => $booking->external_name,
                'external_email' => $booking->external_email,
                'external_phone' => $booking->external_phone,
                'building_id' => $booking->building_id,
                'building_name' => $booking->building_name,
                'room_id' => $booking->room_id,
                'room_name' => $booking->room_name,
                'booking_start' => $booking->booking_start,
                'booking_end' => $booking->booking_end,
                'status_id' => $booking->status_id,
                'status_name' => $booking->status_name,
                'reason' => $booking->reason,
                'total_price' => $booking->total_price,
                'payment_status' => $booking->payment_status,
                'is_external' => $booking->is_external,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $booking->delete();
        } catch (\Exception $e) {
            \Log::error("Failed to move booking to history: " . $e->getMessage());
        }
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
    $status = Status::findOrFail($request->status_id);

    $booking->status_id = $status->status_id;
    // เพิ่มชื่อผู้อนุมัติ
    $booking->approver_name = Auth::user()->name;
    $booking->save();

    // ตรวจสอบว่าสถานะเป็น 6 และเรียกใช้ moveToHistory
    if ($status->status_id == 6) {
        $this->moveToHistory($id);
        \Log::info("Booking {$id} moved to history."); // ล็อกข้อความเพื่อตรวจสอบ
    }

    return redirect()->route('booking_db')->with('success', "การจองถูกเปลี่ยนสถานะเป็น {$status->status_name} เรียบร้อยแล้ว");
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

    public function confirmPayment(UpdateBookingPaymentRequest $request, $id)
{
    $booking = Booking::findOrFail($id);
    
    if ($request->hasFile('payment_slip')) {
        $booking->payment_slip = $request->file('payment_slip')->store('payment_slips', 'public');
    }

    $booking->payment_status = $request->payment_status;
    $booking->verified_at = now();
    $booking->save();

    return redirect()->route('booking_db')
        ->with('success', 'สถานะการชำระเงินถูกอัปเดตเรียบร้อยแล้ว');
}


}

