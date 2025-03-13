<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingHistory;
use Illuminate\Support\Facades\DB;

class BookingHistoryController extends Controller
{
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
            'status_id' => 6,
            'payment_status' => 'completed', // Assuming payment is completed
            'amount' => $booking->total_price,
            'moved_to_history_at' => now(),
        ]);
        $bookingHistory->save();
    }

    public function index(Request $request)
    {
        $query = DB::table('booking_history')
            ->leftJoin('status', 'booking_history.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'booking_history.user_id', '=', 'users.id')
            ->select(
                'booking_history.*', 
                'status.status_name', 
                'users.name as user_name'
            );

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_history.booking_id', 'like', "%{$search}%")
                  ->orWhere('booking_history.external_name', 'like', "%{$search}%")
                  ->orWhere('users.name', 'like', "%{$search}%");
            });
        }

        // Filter by date
        if ($request->has('date_from') && $request->date_from) {
            $query->where('booking_history.booking_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('booking_history.booking_date', '<=', $request->date_to);
        }

        $bookingHistory = $query->orderBy('booking_history.booking_date', 'desc')
            ->paginate(10);
        
        return view('dashboard.booking_history', compact('bookingHistory'));
    }

    /**
 * แสดงประวัติการจองห้อง
 */
public function history(Request $request)
{
    // สร้าง query สำหรับประวัติการจอง
    $query = DB::table('booking_histories')
        ->leftJoin('status', 'booking_histories.status_id', '=', 'status.status_id')
        ->leftJoin('users', 'booking_histories.user_id', '=', 'users.id')
        ->select(
            'booking_histories.*', 
            'status.status_name', 
            'users.name as user_name'
        );

    // Search functionality
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('booking_histories.id', 'like', "%{$search}%")
              ->orWhere('booking_histories.external_name', 'like', "%{$search}%")
              ->orWhere('users.name', 'like', "%{$search}%");
        });
    }
    
    // Calendar search - if date is selected in calendar
    if ($request->has('booking_date')) {
        $bookingDate = $request->booking_date;
        $query->where(function($q) use ($bookingDate) {
            $q->whereDate('booking_histories.booking_start', '<=', $bookingDate)
              ->whereDate('booking_histories.booking_end', '>=', $bookingDate);
        });
    }

    $bookingHistories = $query->paginate(10);
    
    return view('dashboard.booking_history', compact('bookingHistories'));
}
}
