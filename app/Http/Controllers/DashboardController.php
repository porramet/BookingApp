<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ดึงข้อมูลการจองล่าสุด 10 รายการ
        $recentBookings = Booking::orderBy('created_at', 'desc')->take(10)->get();

        // ดึงจำนวนห้องทั้งหมดจากฐานข้อมูล
        $totalRooms = \App\Models\Room::count();

        // ดึงจำนวนผู้ใช้ทั้งหมดจากฐานข้อมูล
        $totalUsers = \App\Models\User::count();

        // ดึงจำนวนการจองห้องทั้งหมด
        $totalBookings = \App\Models\Booking::count();

        // ดึงจำนวนการจองของเดือนปัจจุบัน
        $monthlyBookings = Booking::whereYear('created_at', date('Y'))
                                  ->whereMonth('created_at', date('m'))
                                  ->count();

        // ดึงสถิติการจองแยกตามเดือน
        $weeklyStats = Booking::selectRaw('YEARWEEK(created_at, 1) as week, COUNT(*) as total')
        ->whereBetween('created_at', [now()->subWeeks(12), now()]) // 12 สัปดาห์ย้อนหลัง
        ->groupBy('week')
        ->orderBy('week', 'asc')
        ->get();

        return view('dashboard.dashboard', compact('recentBookings', 'monthlyBookings', 'weeklyStats','totalRooms', 'totalUsers', 'totalBookings'));
    }

    public function showIndex()
    {
        // ดึงจำนวนห้องทั้งหมดจากฐานข้อมูล
        $totalRooms = \App\Models\Room::count();

        // ดึงจำนวนผู้ใช้ทั้งหมดจากฐานข้อมูล
        $totalUsers = \App\Models\User::count();

        // ดึงจำนวนการจองห้องทั้งหมด
        $totalBookings = \App\Models\Booking::count();

        // ดึงจำนวนอาคารทั้งหมดจากฐานข้อมูล
        $totalBuildings = \App\Models\Building::count();

        return view('index', compact('totalRooms', 'totalUsers', 'totalBookings', 'totalBuildings'));

    }
}
