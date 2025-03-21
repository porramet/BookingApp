<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        // Get view type (month, week, day, list)
        $view = $request->get('view', 'month');

        // Get the current date or the date from request
        $date = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::now();
        $currentDate = $date->format('Y-m-d');

        // Get the status list with colors
        $statusList = Status::select('status_id', 'status_name')
        ->selectRaw("CASE 
            WHEN status_name = 'รออนุมัติ' THEN '#FFCC00' 
            WHEN status_name = 'อนุมัติ' THEN '#28A745' 
            WHEN status_name = 'ยกเลิก' THEN '#DC3545' 
            WHEN status_name = 'ดำเนินการเสร็จสิ้น' THEN '#6C757D' 
            ELSE '#607D8B' END as color")
        ->get();

        // Data for navigation
        $prevMonth = $date->copy()->subMonth()->format('Y-m-d');
        $nextMonth = $date->copy()->addMonth()->format('Y-m-d');
        $currentMonth = $date->format('F Y');

        switch ($view) {
            case 'month':
                return $this->monthView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view);
            case 'week':
                return $this->weekView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view);
            case 'day':
                return $this->dayView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view);
            case 'list':
                return $this->listView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view);
            default:
                return $this->monthView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view);
        }
    }

    private function monthView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view)
    {
        // Get first day of the month and last day of the month
        $firstDay = $date->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY);
        $lastDay = $date->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY);

        // Create a period for all days in the calendar view
        $period = CarbonPeriod::create($firstDay, $lastDay);

        // Get all bookings for this period
        $bookings = Booking::whereBetween('booking_start', [$firstDay, $lastDay])
            ->select('bookings.*', 'status.status_name')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->selectRaw('IFNULL(users.name, bookings.external_name) as user_name')
            ->selectRaw("CASE 
                WHEN status.status_name = 'รออนุมัติ' THEN '#FFCC00' 
                WHEN status.status_name = 'อนุมัติ' THEN '#28A745' 
                WHEN status.status_name = 'ยกเลิก' THEN '#DC3545' 
                WHEN status.status_name = 'ดำเนินการเสร็จสิ้น' THEN '#6C757D' 
                ELSE '#607D8B' END as statusColor")
            ->orderBy('booking_start')
            ->get();

        // Group bookings by date
        $bookingsByDate = [];
        foreach ($bookings as $booking) {
            $bookingDate = Carbon::parse($booking->booking_start)->format('Y-m-d');
            $bookingsByDate[$bookingDate][] = $booking;
        }

        // Prepare calendar data
        $calendarData = [];
        $currentWeek = [];
        $weekCounter = 0;

        foreach ($period as $day) {
            $dayFormat = $day->format('Y-m-d');
            $dayBookings = $bookingsByDate[$dayFormat] ?? [];

            $dayData = [
                'day' => $day->format('j'),
                'date' => $dayFormat,
                'currentMonth' => $day->format('m') === $date->format('m'),
                'today' => $day->isToday(),
                'bookings' => $dayBookings
            ];

            $currentWeek[] = $dayData;

            // Start a new week
            if ($day->dayOfWeek === Carbon::SATURDAY) {
                $calendarData[] = $currentWeek;
                $currentWeek = [];
                $weekCounter++;
            }
        }

        // Add remaining days to the last week
        if (!empty($currentWeek)) {
            $calendarData[] = $currentWeek;
        }

        return view('calendar.index', compact(
            'calendarData', 'statusList', 'prevMonth', 'nextMonth', 'currentMonth', 'currentDate', 'view'
        ));
    }

    private function weekView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view)
    {
        // Get the start and end of the week
        $startOfWeek = $date->copy()->startOfWeek(Carbon::SUNDAY);
        $endOfWeek = $date->copy()->endOfWeek(Carbon::SATURDAY);

        // Create an array of days for the current week
        $weekDays = [];
        $dayNames = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
        
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $weekDays[] = [
                'date' => $day->format('Y-m-d'),
                'dayName' => $dayNames[$i],
                'today' => $day->isToday()
            ];
        }

        // Get bookings for this week
        $bookings = Booking::whereBetween('booking_start', [$startOfWeek, $endOfWeek])
            ->select('bookings.*', 'status.status_name')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->selectRaw('IFNULL(users.name, bookings.external_name) as user_name')
            ->selectRaw("CASE 
                WHEN status.status_name = 'รออนุมัติ' THEN '#FFCC00' 
                WHEN status.status_name = 'อนุมัติ' THEN '#28A745' 
                WHEN status.status_name = 'ยกเลิก' THEN '#DC3545' 
                WHEN status.status_name = 'ดำเนินการเสร็จสิ้น' THEN '#6C757D' 
                ELSE '#607D8B' END as statusColor")
            ->orderBy('booking_start')
            ->get();

        // Generate time slots (every hour from 8:00 to 22:00)
        $timeSlots = [];
        for ($hour = 8; $hour <= 22; $hour++) {
            $timeSlots[] = sprintf('%02d:00', $hour);
        }

        // Group bookings by day and time
        $bookingsByDay = [];
        foreach ($bookings as $booking) {
            $bookingDate = Carbon::parse($booking->booking_start)->format('Y-m-d');
            $bookingTime = Carbon::parse($booking->booking_start)->format('H:00');
            $bookingsByDay[$bookingDate][$bookingTime][] = $booking;
        }

        return view('calendar.index', compact(
            'weekDays', 'timeSlots', 'bookingsByDay', 'statusList', 'prevMonth', 'nextMonth', 'currentMonth', 'currentDate', 'view'
        ));
    }

    private function dayView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view)
    {
        // Format the date for display
        $dayViewDate = $date->format('วันl ที่ d F Y');

        // Get bookings for this day
        $bookings = Booking::whereDate('booking_start', $date->format('Y-m-d'))
            ->select('bookings.*', 'status.status_name')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->selectRaw('IFNULL(users.name, bookings.external_name) as user_name')
            ->selectRaw("CASE 
                WHEN status.status_name = 'รออนุมัติ' THEN '#FFCC00' 
                WHEN status.status_name = 'อนุมัติ' THEN '#28A745' 
                WHEN status.status_name = 'ยกเลิก' THEN '#DC3545' 
                WHEN status.status_name = 'ดำเนินการเสร็จสิ้น' THEN '#6C757D' 
                ELSE '#607D8B' END as statusColor")
            ->orderBy('booking_start')
            ->get();

        // Generate time slots (every hour from 8:00 to 22:00)
        $timeSlots = [];
        for ($hour = 8; $hour <= 22; $hour++) {
            $timeSlots[] = sprintf('%02d:00', $hour);
        }

        // Group bookings by time
        $bookingsByTime = [];
        foreach ($bookings as $booking) {
            $bookingTime = Carbon::parse($booking->booking_start)->format('H:00');
            $bookingsByTime[$bookingTime][] = $booking;
        }

        return view('calendar.index', compact(
            'dayViewDate', 'timeSlots', 'bookingsByTime', 'statusList', 'prevMonth', 'nextMonth', 'currentMonth', 'currentDate', 'view'
        ));
    }

    private function listView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view)
    {
        // Get bookings for the current month
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $listBookings = Booking::whereBetween('booking_start', [$startOfMonth, $endOfMonth])
            ->select('bookings.*', 'status.status_name')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->selectRaw('IFNULL(users.name, bookings.external_name) as user_name')
            ->selectRaw("CASE 
                WHEN status.status_name = 'รออนุมัติ' THEN '#FFCC00' 
                WHEN status.status_name = 'อนุมัติ' THEN '#28A745' 
                WHEN status.status_name = 'ยกเลิก' THEN '#DC3545' 
                WHEN status.status_name = 'ดำเนินการเสร็จสิ้น' THEN '#6C757D' 
                ELSE '#607D8B' END as statusColor")
            ->orderBy('booking_start')
            ->get();

        return view('calendar.index', compact(
            'listBookings', 'statusList', 'prevMonth', 'nextMonth', 'currentMonth', 'currentDate', 'view'
        ));
    }

    /**
     * Get detailed booking information for modal display
     */
    public function getBookingDetails($id)
    {
        $booking = Booking::where('bookings.id', $id)
            ->select('bookings.*', 'status.status_name', 'rooms.room_name', 'buildings.building_name')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('buildings', 'bookings.building_id', '=', 'buildings.id')
            ->leftJoin('rooms', function ($join) {
                $join->on('bookings.building_id', '=', 'rooms.building_id')
                     ->on('bookings.room_id', '=', 'rooms.room_id');
            })
            ->selectRaw('IFNULL(users.name, bookings.external_name) as user_name')
            ->selectRaw("CASE 
                WHEN status.status_name = 'รออนุมัติ' THEN '#FFCC00' 
                WHEN status.status_name = 'อนุมัติ' THEN '#28A745' 
                WHEN status.status_name = 'ยกเลิก' THEN '#DC3545' 
                WHEN status.status_name = 'ดำเนินการเสร็จสิ้น' THEN '#6C757D' 
                ELSE '#607D8B' END as statusColor")
            ->first();

        if (!$booking) {
            return response()->json(['error' => 'ไม่พบข้อมูลการจอง'], 404);
        }

        // Get booking history
        $history = DB::table('booking_histories')
            ->where('booking_id', $id)
            ->select('booking_histories.*', 'status.status_name')
            ->leftJoin('status', 'booking_histories.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'booking_histories.changed_by', '=', 'users.id')
            ->selectRaw('IFNULL(users.name, "ระบบ") as changed_by_name')
            ->orderBy('changed_at', 'desc')
            ->get();

        $booking->history = $history;

        return response()->json($booking);
    }
}
