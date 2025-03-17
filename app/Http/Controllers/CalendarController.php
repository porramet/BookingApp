<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Building;

class CalendarController extends Controller
{
    private $holidays = [
        '2025-01-01' => 'วันขึ้นปีใหม่',
        '2025-02-10' => 'วันมาฆบูชา',
        '2025-04-06' => 'วันจักรี',
        '2025-04-13' => 'วันสงกรานต์',
        '2025-04-14' => 'วันสงกรานต์',
        '2025-04-15' => 'วันสงกรานต์',
        '2025-05-01' => 'วันแรงงานแห่งชาติ',
        '2025-05-05' => 'วันฉัตรมงคล',
        '2025-05-13' => 'วันพืชมงคล',
        '2025-06-03' => 'วันเฉลิมพระชนมพรรษาสมเด็จพระราชินี',
        '2025-07-11' => 'วันอาสาฬหบูชา',
        '2025-07-12' => 'วันเข้าพรรษา',
        '2025-07-28' => 'วันเฉลิมพระชนมพรรษา ร.10',
        '2025-08-12' => 'วันแม่แห่งชาติ',
        '2025-10-13' => 'วันคล้ายวันสวรรคต ร.9',
        '2025-10-23' => 'วันปิยมหาราช',
        '2025-12-05' => 'วันพ่อแห่งชาติ/วันชาติ',
        '2025-12-10' => 'วันรัฐธรรมนูญ',
        '2025-12-31' => 'วันสิ้นปี'
    ];

    public function index(Request $request)
    {
        // ดึงข้อมูลอาคารทั้งหมด
        $buildings = Building::all();
        
        // ดึงข้อมูลห้องทั้งหมด หรือกรองตามอาคาร
        $building_id = $request->input('building_id');
        
        $rooms = Room::when($building_id, function($query) use ($building_id) {
            return $query->where('building_id', $building_id);
        })->with('building')->get();
        
        // ดึงข้อมูลการจองทั้งหมด
        $bookings = Booking::with(['room', 'status'])->whereIn('status_id', [1, 2, 3])->get();
        
        // สร้างข้อมูลสำหรับปฏิทิน
        $calendarData = [];
        
        foreach ($bookings as $booking) {
            $start = new \DateTime($booking->booking_start);
            $end = new \DateTime($booking->booking_end);
            $end->modify('+1 day'); // เพิ่ม 1 วันให้กับวันสิ้นสุดเพื่อให้แสดงถึงวันสุดท้าย
            
            // สีของการจองตามสถานะ
            $statusColors = [
                1 => '#28a745', // สถานะอนุมัติ - สีเขียว
                2 => '#ffc107', // สถานะรออนุมัติ - สีเหลือง
                3 => '#007bff', // สถานะรอการยืนยัน - สีน้ำเงิน
                4 => '#dc3545', // สถานะยกเลิก - สีแดง
            ];
            
            $color = isset($statusColors[$booking->status_id]) ? $statusColors[$booking->status_id] : '#6c757d';
            
            $roomName = $booking->room ? $booking->room->room_name : 'Unknown Room'; // Check for null
            
            $calendarData[] = [
                'id' => $booking->id,
                'title' => $roomName . ' - ' . mb_substr($booking->external_name, 0, 1) . 'xxx',
                'start' => $booking->booking_start,
                'end' => $booking->booking_end,
                'color' => $color,
                'room_id' => $booking->room_id,
                'building_id' => $booking->building_id,
                'url' => route('bookings.show', $booking->id)
            ];
        }
        
        // เพิ่มวันหยุดเข้าไปในปฏิทิน
        foreach ($this->holidays as $date => $name) {
            $calendarData[] = [
                'title' => $name,
                'start' => $date,
                'allDay' => true,
                'color' => '#dc3545', // สีแดงสำหรับวันหยุด
                'rendering' => 'background',
                'className' => 'holiday'
            ];
        }
        
        return view('calendar.index', compact('buildings', 'rooms', 'calendarData'));
    }
    
    public function getCalendarData(Request $request)
    {
        // สำหรับการเรียกข้อมูลผ่าน AJAX
        $room_id = $request->input('room_id');
        $building_id = $request->input('building_id');
        $start_date = $request->input('start');
        $end_date = $request->input('end');
        
        $query = Booking::with(['room', 'status'])->whereIn('status_id', [1, 2, 3]);
        
        if ($room_id) {
            $query->where('room_id', $room_id);
        }
        
        if ($building_id) {
            $query->where('building_id', $building_id);
        }
        
        if ($start_date && $end_date) {
            $query->where(function($q) use ($start_date, $end_date) {
                $q->whereBetween('booking_start', [$start_date, $end_date])
                  ->orWhereBetween('booking_end', [$start_date, $end_date])
                  ->orWhere(function($q2) use ($start_date, $end_date) {
                      $q2->where('booking_start', '<=', $start_date)
                         ->where('booking_end', '>=', $end_date);
                  });
            });
        }
        
        $bookings = $query->get();
        
        $calendarData = [];
        
        foreach ($bookings as $booking) {
            $statusColors = [
                1 => '#28a745', // อนุมัติ - เขียว
                2 => '#ffc107', // รออนุมัติ - เหลือง
                3 => '#007bff', // รอการยืนยัน - น้ำเงิน
            ];
            
            $color = isset($statusColors[$booking->status_id]) ? $statusColors[$booking->status_id] : '#6c757d';
            
            $roomName = $booking->room ? $booking->room->room_name : 'Unknown Room'; // Check for null
            
            $calendarData[] = [
                'id' => $booking->id,
                'title' => $roomName . ' - ' . mb_substr($booking->external_name, 0, 1) . 'xxx',
                'start' => $booking->booking_start,
                'end' => $booking->booking_end,
                'color' => $color,
                'room_id' => $booking->room_id,
                'building_id' => $booking->building_id,
                'url' => route('bookings.show', $booking->id)
            ];
        }
        
        // เพิ่มวันหยุด
        foreach ($this->holidays as $date => $name) {
            if ((!$start_date || $date >= $start_date) && (!$end_date || $date <= $end_date)) {
                $calendarData[] = [
                    'title' => $name,
                    'start' => $date,
                    'allDay' => true,
                    'color' => '#dc3545',
                    'rendering' => 'background',
                    'className' => 'holiday'
                ];
            }
        }
        
        return response()->json($calendarData);
    }
    
    public function getTableView(Request $request)
    {
        $building_id = $request->input('building_id');
        $date = $request->input('date', date('Y-m-d'));
        $week_start = date('Y-m-d', strtotime('monday this week', strtotime($date)));
        
        // ดึงข้อมูลห้องตามอาคาร
        $rooms = Room::when($building_id, function($query) use ($building_id) {
            return $query->where('building_id', $building_id);
        })->with('building')->get();
        
        // สร้างวันที่สำหรับแสดงในตาราง (จันทร์-อาทิตย์)
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $current_date = date('Y-m-d', strtotime("+$i days", strtotime($week_start)));
            $dates[] = [
                'date' => $current_date,
                'day' => date('D', strtotime($current_date)),
                'day_th' => $this->getDayThai(date('w', strtotime($current_date))),
                'is_holiday' => isset($this->holidays[$current_date])
            ];
        }
        
        // ดึงข้อมูลการจองในช่วงสัปดาห์นี้
        $bookings = Booking::whereBetween('booking_start', [$week_start, date('Y-m-d', strtotime('+6 days', strtotime($week_start)))])
            ->orWhereBetween('booking_end', [$week_start, date('Y-m-d', strtotime('+6 days', strtotime($week_start)))])
            ->orWhere(function($query) use ($week_start) {
                $query->where('booking_start', '<=', $week_start)
                      ->where('booking_end', '>=', date('Y-m-d', strtotime('+6 days', strtotime($week_start))));
            })
            ->whereIn('status_id', [1, 2, 3])
            ->with(['room', 'status'])
            ->get();
        
        // จัดรูปแบบข้อมูลการจองตามห้องและวัน
        $bookingData = [];
        foreach ($rooms as $room) {
            $bookingData[$room->room_id] = [];
            
            foreach ($dates as $dateInfo) {
                $bookingData[$room->room_id][$dateInfo['date']] = [];
                
                // กรองการจองสำหรับห้องและวันนี้
                $roomBookings = $bookings->filter(function($booking) use ($room, $dateInfo) {
                    $bookingStart = strtotime($booking->booking_start);
                    $bookingEnd = strtotime($booking->booking_end);
                    $currentDate = strtotime($dateInfo['date']);
                    
                    return $booking->room_id == $room->room_id &&
                           $bookingStart <= strtotime($dateInfo['date'] . ' 23:59:59') &&
                           $bookingEnd >= strtotime($dateInfo['date'] . ' 00:00:00');
                });
                
                foreach ($roomBookings as $booking) {
                    $startTime = date('H:i', strtotime($booking->booking_start));
                    $endTime = date('H:i', strtotime($booking->booking_end));
                    
                    $bookingData[$room->room_id][$dateInfo['date']][] = [
                        'id' => $booking->id,
                        'time' => $startTime . ' - ' . $endTime,
                        'description' => $booking->reason ? $booking->reason : 'จองโดย: ' . mb_substr($booking->external_name, 0, 1) . 'xxx',
                        'status' => $booking->status->name,
                        'status_id' => $booking->status_id
                    ];
                }
            }
        }
        
        $buildings = Building::all();
        
        return view('calendar.table', compact('rooms', 'dates', 'bookingData', 'buildings', 'building_id'));
    }
    
    private function getDayThai($day)
    {
        $days = [
            0 => 'อา.',
            1 => 'จ.',
            2 => 'อ.',
            3 => 'พ.',
            4 => 'พฤ.',
            5 => 'ศ.',
            6 => 'ส.'
        ];
        
        return isset($days[$day]) ? $days[$day] : '';
    }
}
