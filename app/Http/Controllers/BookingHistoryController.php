<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingHistory;
use Illuminate\Support\Facades\DB;

class BookingHistoryController extends Controller
{
    /**
     * เพิ่มการจองลงในประวัติ
     *
     * @param mixed $booking
     * @return void
     */
    public function addBookingToHistory($booking)
    {
        $bookingHistory = new BookingHistory();
        $bookingHistory->fill([
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id,
            'room_id' => $booking->room_id,
            'external_name' => $booking->external_name,
            'external_email' => $booking->external_email,
            'external_phone' => $booking->external_phone,
            'booking_date' => now(),
            'start_time' => $booking->booking_start,
            'end_time' => $booking->booking_end,
            'purpose' => $booking->reason,
            'status_id' => 6, // ดำเนินการเสร็จสิ้น
            'payment_status' => 'completed', // สมมติว่าชำระเงินแล้ว
            'amount' => $booking->total_price,
            'moved_to_history_at' => now(),
        ]);
        $bookingHistory->save();
    }

    /**
     * แสดงรายการประวัติการจอง
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // สร้าง query สำหรับประวัติการจอง
        $query = DB::table('booking_history')
            ->leftJoin('status', 'booking_history.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'booking_history.user_id', '=', 'users.id')
            ->select(
                'booking_history.*', 
                'status.status_name', 
                'users.name as user_name'
            );

        // ค้นหาข้อมูล
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_history.booking_id', 'like', "%{$search}%")
                  ->orWhere('booking_history.external_name', 'like', "%{$search}%")
                  ->orWhere('users.name', 'like', "%{$search}%");
            });
        }

        // กรองตามวันที่เริ่มต้น
        if ($request->has('date_from') && $request->date_from) {
            $query->where('booking_history.booking_date', '>=', $request->date_from);
        }

        // กรองตามวันที่สิ้นสุด
        if ($request->has('date_to') && $request->date_to) {
            $query->where('booking_history.booking_date', '<=', $request->date_to);
        }

        // เรียงลำดับและแบ่งหน้า
        $bookingHistory = $query->orderBy('booking_history.booking_date', 'desc')->paginate(20);


        // นับจำนวนการจอง
        $totalBookings = DB::table('booking_history')->count();
        $completedBookings = DB::table('booking_history')->where('status_id', 6)->count(); // เสร็จสิ้น
        $cancelledBookings = DB::table('booking_history')->where('status_id', 5)->count(); // ยกเลิก

        return view('dashboard.booking_history', [
            'bookings' => $bookingHistory,
            'totalBookings' => $totalBookings,
            'completedBookings' => $completedBookings,
            'cancelledBookings' => $cancelledBookings
        ]);
    }

    /**
     * แสดงประวัติการจองห้อง (Alternate Method)
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function history(Request $request)
    {
        // สร้าง query สำหรับประวัติการจอง
        $query = DB::table('booking_history')
            ->leftJoin('status', 'booking_history.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'booking_history.user_id', '=', 'users.id')
            ->select(
                'booking_history.*', 
                'status.status_name', 
                'users.name as user_name'
            );

        // ค้นหาข้อมูล
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_history.booking_id', 'like', "%{$search}%")
                  ->orWhere('booking_history.external_name', 'like', "%{$search}%")
                  ->orWhere('users.name', 'like', "%{$search}%");
            });
        }

        // กรองตามวันที่
        if ($request->has('booking_date')) {
            $bookingDate = $request->booking_date;
            $query->where(function($q) use ($bookingDate) {
                $q->whereDate('booking_history.booking_start', '<=', $bookingDate)
                  ->whereDate('booking_history.booking_end', '>=', $bookingDate);
            });
        }

        // เรียงลำดับและแบ่งหน้า
        $bookingHistories = $query->paginate(10);

        return view('dashboard.booking_history', compact('bookingHistories'));
    }
}
