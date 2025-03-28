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

        // Get the status list with colors from config
        $statusList = Status::all()->map(function($status) {
            return [
                'status_id' => $status->status_id,
                'status_name' => $status->status_name,
                'color' => config('status.colors.'.$status->status_id, '#607D8B')
            ];
        });

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
        $firstDay = $date->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY);
        $lastDay = $date->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY);

        $period = CarbonPeriod::create($firstDay, $lastDay);

        $bookings = Booking::whereBetween('booking_start', [$firstDay, $lastDay])
            ->select('bookings.*', 'status.status_name', 'bookings.status_id')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->get()
            ->map(function($booking) {
                $booking->statusColor = config('status.colors.'.$booking->status_id, '#607D8B');
                return $booking;
            });

        $bookingsByDate = [];
        foreach ($bookings as $booking) {
            $bookingDate = Carbon::parse($booking->booking_start)->format('Y-m-d');
            $bookingsByDate[$bookingDate][] = $booking;
        }

        $calendarData = [];
        $currentWeek = [];

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

            if ($day->dayOfWeek === Carbon::SATURDAY) {
                $calendarData[] = $currentWeek;
                $currentWeek = [];
            }
        }

        if (!empty($currentWeek)) {
            $calendarData[] = $currentWeek;
        }

        return view('calendar.index', compact(
            'calendarData', 'statusList', 'prevMonth', 'nextMonth', 'currentMonth', 'currentDate', 'view'
        ));
    }

    private function weekView($date, $statusList, $prevMonth, $nextMonth, $currentMonth, $currentDate, $view)
    {
        $startOfWeek = $date->copy()->startOfWeek(Carbon::SUNDAY);
        $endOfWeek = $date->copy()->endOfWeek(Carbon::SATURDAY);

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

        $bookings = Booking::whereBetween('booking_start', [$startOfWeek, $endOfWeek])
            ->select('bookings.*', 'status.status_name', 'bookings.status_id')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->selectRaw('IFNULL(users.name, bookings.external_name) as user_name')
            ->get()
            ->map(function($booking) {
                $booking->statusColor = config('status.colors.'.$booking->status_id, '#607D8B');
                return $booking;
            });

        $timeSlots = [];
        for ($hour = 8; $hour <= 22; $hour++) {
            $timeSlots[] = sprintf('%02d:00', $hour);
        }

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
        $dayViewDate = $date->format('วันl ที่ d F Y');

        $bookings = Booking::whereDate('booking_start', $date->format('Y-m-d'))
            ->select('bookings.*', 'status.status_name', 'bookings.status_id')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->selectRaw('IFNULL(users.name, bookings.external_name) as user_name')
            ->get()
            ->map(function($booking) {
                $booking->statusColor = config('status.colors.'.$booking->status_id, '#607D8B');
                return $booking;
            });

        $timeSlots = [];
        for ($hour = 8; $hour <= 22; $hour++) {
            $timeSlots[] = sprintf('%02d:00', $hour);
        }

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
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $listBookings = Booking::whereBetween('booking_start', [$startOfMonth, $endOfMonth])
            ->select('bookings.*', 'status.status_name', 'bookings.status_id')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->selectRaw('IFNULL(users.name, bookings.external_name) as user_name')
            ->get()
            ->map(function($booking) {
                $booking->statusColor = config('status.colors.'.$booking->status_id, '#607D8B');
                return $booking;
            });

        return view('calendar.index', compact(
            'listBookings', 'statusList', 'prevMonth', 'nextMonth', 'currentMonth', 'currentDate', 'view'
        ));
    }

    public function getBookingDetails($id)
    {
        $booking = Booking::where('bookings.id', $id)
            ->select('bookings.*', 'status.status_name', 'rooms.room_name', 'buildings.building_name', 'bookings.status_id')
            ->leftJoin('status', 'bookings.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('buildings', 'bookings.building_id', '=', 'buildings.id')
            ->leftJoin('rooms', function ($join) {
                $join->on('bookings.building_id', '=', 'rooms.building_id')
                     ->on('bookings.room_id', '=', 'rooms.room_id');
            })
            ->selectRaw('IFNULL(users.name, bookings.external_name) as user_name')
            ->first();

        if (!$booking) {
            return response()->json(['error' => 'ไม่พบข้อมูลการจอง'], 404);
        }

        // Add status color from config
        $booking->statusColor = config('status.colors.'.$booking->status_id, '#607D8B');

        // Get booking history
        $history = DB::table('booking_histories')
            ->where('booking_id', $id)
            ->select('booking_histories.*', 'status.status_name')
            ->leftJoin('status', 'booking_histories.status_id', '=', 'status.status_id')
            ->leftJoin('users', 'booking_histories.changed_by', '=', 'users.id')
            ->selectRaw('IFNULL(users.name, "ระบบ") as changed_by_name')
            ->orderBy('changed_at', 'desc')
            ->get()
            ->map(function($item) {
                $item->statusColor = config('status.colors.'.$item->status_id, '#607D8B');
                return $item;
            });

        $booking->history = $history;

        return response()->json($booking);
    }
}