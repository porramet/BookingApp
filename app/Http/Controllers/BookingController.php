<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Building;

class BookingController extends Controller
{
    // ข้อมูลวันหยุดประจำปี 2025
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

    public function index()
    {
        $buildings = Building::with('rooms')->get();
        $rooms = Room::with('status')->get();
        return view('index', compact('buildings', 'rooms'));
    }

    public function showBookingForm($id)
    {
        if (!is_numeric($id)) {
            return back()->with('error', 'Invalid room ID provided.');
        }
        
        try {
            // Get room with building relationship
            $room = Room::with('building')->findOrFail($id);

            // Get booked dates
            $bookedDates = Booking::where('room_id', $id)
                ->whereIn('status_id', [1, 2, 3])
                ->get(['booking_start', 'booking_end', 'external_name']);

            // Process booked dates
            $bookedDetails = [];
            foreach ($bookedDates as $booking) {
                $start = new \DateTime($booking->booking_start);
                $end = new \DateTime($booking->booking_end);
                
                $period = new \DatePeriod(
                    $start,
                    new \DateInterval('P1D'),
                    $end
                );
                
                $bookingInfo = "จองโดย: " . mb_substr($booking->external_name, 0, 1) . "xxx";
                
                foreach ($period as $date) {
                    $formattedDate = $date->format('Y-m-d');
                    $bookedDetails[$formattedDate] = $bookingInfo;
                }
            }

            // Get holidays
            $holidaysWithNames = $this->holidays;
            
            // Get all disabled days
            $disabledDays = array_merge(array_keys($bookedDetails), array_keys($holidaysWithNames));

            return view('partials.booking-form', compact(
                'room',
                'disabledDays',
                'holidaysWithNames',
                'bookedDetails'
            ));

        } catch (\Exception $e) {
            \Log::error('Booking form error: ' . $e->getMessage());
            return back()->with('error', 'ไม่พบห้องที่ต้องการ หรือเกิดข้อผิดพลาดในการแสดงแบบฟอร์ม');
        }
    }

    public function store(Request $request)
    {
        try {
            \Log::debug('Incoming booking request:', $request->all());
            
            // Validate request data
            $validated = $request->validate([
                'room_id' => 'required|exists:rooms,room_id',
                'building_id' => 'required|exists:buildings,id',
                'room_name' => 'required|string|max:255',
                'building_name' => 'required|string|max:255',
                'external_name' => 'required|string|max:255',
                'external_email' => 'required|email|max:255',
                'external_phone' => 'required|string|max:20',
                'booking_start' => 'required|date|after:today',
                'booking_end' => 'required|date|after:booking_start',
                'reason' => 'nullable|string',
                'payment_slip' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            // ตรวจสอบว่าวันที่จองอยู่ในวันหยุดหรือไม่
            $startDate = new \DateTime($validated['booking_start']);
            $endDate = new \DateTime($validated['booking_end']);
            $holidays = array_keys($this->holidays);
            
            // ตรวจสอบช่วงวันที่จองว่ามีวันหยุดหรือไม่
            $holidayInRange = false;
            $holidayName = '';
            
            $period = new \DatePeriod(
                $startDate,
                new \DateInterval('P1D'),
                $endDate
            );
            
            foreach ($period as $date) {
                $formattedDate = $date->format('Y-m-d');
                if (in_array($formattedDate, $holidays)) {
                    $holidayInRange = true;
                    $holidayName = $this->holidays[$formattedDate];
                    break;
                }
            }
            
            if ($holidayInRange) {
                return back()->with('error', "ไม่สามารถจองห้องในวันหยุดนักขัตฤกษ์ได้ ({$holidayName})")
                             ->withInput();
            }

            // ตรวจสอบการซ้อนทับกับการจองอื่น
            $conflictingBooking = Booking::where('room_id', $validated['room_id'])
                ->whereIn('status_id', [1, 2, 3])
                ->where(function($query) use ($validated) {
                    $query->where(function($q) use ($validated) {
                        // กรณีวันเริ่มต้นหรือวันสิ้นสุดของการจองใหม่อยู่ในช่วงการจองที่มีอยู่
                        $q->whereBetween('booking_start', [$validated['booking_start'], $validated['booking_end']])
                          ->orWhereBetween('booking_end', [$validated['booking_start'], $validated['booking_end']]);
                    })
                    ->orWhere(function($q) use ($validated) {
                        // กรณีการจองที่มีอยู่ครอบคลุมการจองใหม่ทั้งหมด
                        $q->where('booking_start', '<=', $validated['booking_start'])
                          ->where('booking_end', '>=', $validated['booking_end']);
                    });
                })
                ->exists();

            if ($conflictingBooking) {
                return back()->with('error', 'ห้องไม่ว่างในช่วงเวลาที่เลือก กรุณาเลือกเวลาอื่น')
                             ->withInput();
            }

            // คำนวณราคารวม
            $room = Room::find($validated['room_id']);
            $start = new \DateTime($validated['booking_start']);
            $end = new \DateTime($validated['booking_end']);
            $days = $end->diff($start)->days;
            $totalPrice = $room->service_rates * $days;

            // สร้างข้อมูลการจอง
            $booking = new Booking();
            $booking->fill($validated);
            $booking->status_id = 3; // สถานะรอการยืนยัน
            $booking->is_external = true;
            $booking->total_price = $totalPrice;
            $booking->payment_status = 'pending';
            
            // กรณีผู้ใช้ที่ login แล้ว
            if (auth()->check()) {
                $booking->user_id = auth()->id();
            }
            
            // จัดการกับไฟล์อัปโหลด
            if ($request->hasFile('payment_slip')) {
                $file = $request->file('payment_slip');
                $filePath = $file->store('payment_slips', 'public'); // Store file in public/payment_slips
                $booking->payment_slip = $filePath;
            }
            
            $booking->save();

            // ส่งอีเมลแจ้งยืนยันการจอง (ควรทำในส่วนนี้)
            // Mail::to($booking->external_email)->send(new BookingConfirmation($booking));

            return redirect()->route('booking.index')->with('success', 'การจองห้องสำเร็จ! กรุณาตรวจสอบอีเมลของคุณเพื่อยืนยันการจอง');
        } catch (\Exception $e) {
            \Log::error('Booking failed: ' . $e->getMessage(), ['request' => $request->all()]);
            return back()->with('error', 'เกิดข้อผิดพลาดในการจอง: ' . $e->getMessage())
                         ->withInput();
        }
    }

    public function show($id)
    {
        $booking = Booking::with(['room', 'building', 'status'])->findOrFail($id);
        return view('dashboard.booking_show', compact('booking'));
    }
    
    // แสดงรายการจองของผู้ใช้ปัจจุบัน
    public function myBookings()
    {
        if (auth()->check()) {
            $bookings = Booking::where('user_id', auth()->id())
                ->with(['room', 'building', 'status'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            return redirect()->route('login')
                ->with('error', 'กรุณาเข้าสู่ระบบเพื่อดูรายการจองของคุณ');
        }
        
        return view('dashboard.my_bookings', compact('bookings'));
    }
    
    // ยกเลิกการจอง
    public function cancel($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            // ตรวจสอบว่าผู้ใช้มีสิทธิ์ยกเลิกการจองนี้หรือไม่
            if (auth()->check() && $booking->user_id == auth()->id()) {
                $booking->status_id = 4; // สถานะยกเลิก
                $booking->payment_status = 'cancelled';
                $booking->save();
                
                return back()->with('success', 'ยกเลิกการจองเรียบร้อยแล้ว');
            } else {
                return back()->with('error', 'คุณไม่มีสิทธิ์ยกเลิกการจองนี้');
            }
        } catch (\Exception $e) {
            \Log::error('Cancel booking failed: ' . $e->getMessage());
            return back()->with('error', 'เกิดข้อผิดพลาดในการยกเลิกการจอง');
        }
    }
}
