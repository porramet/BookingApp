@extends('layouts.app')

@section('content')
<!-- Main Container -->
<div class="container mt-5 mb-5">
    <div class="row">
    
    <!-- ฟอร์มจองห้องพัก -->
    <div class="col-lg-8 bg-white p-4 rounded shadow">
        <h2 class="h5 mb-4">
            ข้อมูลผู้จอง <span class="text-red-600">*</span>
        </h2>
        
        <form action="{{ route('booking.store') }}" method="POST" id="bookingForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="room_id" value="{{ $room->room_id }}">
            <input type="hidden" name="building_id" value="{{ $room->building_id }}">
            <input type="hidden" name="room_name" value="{{ $room->room_name }}">
            <input type="hidden" name="building_name" value="{{ $room->building->building_name ?? 'ไม่ระบุ' }}">
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <!-- ชื่ออาคาร -->
            <div class="mb-3">
                <label class="form-label">อาคาร</label>
                <input class="form-control" 
                    type="text" value="{{ $room->building->building_name ?? 'ไม่ระบุ' }}" readonly>
            </div>
            <!-- ห้องที่จอง -->
            <div class="mb-3">
                <label class="form-label">ห้องที่จอง</label>
                <input class="form-control" 
                    type="text" value="{{ $room->room_name }}" readonly>
            </div>
            <!-- ชื่อผู้จอง -->
            <div class="mb-3">
                <label class="form-label">ชื่อผู้จอง</label>
                <input class="form-control" 
                    name="external_name" type="text" required>
            </div>

            <!-- อีเมล -->
            <div class="mb-3">
                <label class="form-label">อีเมล</label>
                <input class="form-control" 
                    name="external_email" type="email" required>
            </div>

            <!-- เบอร์โทร -->
            <div class="mb-3">
                <label class="form-label">เบอร์โทร</label>
                <input class="form-control" 
                    name="external_phone" type="text" required>
            </div>

            <!-- เหตุผลในการจอง -->
            <div class="mb-3">
                <label class="form-label">เหตุผลในการจอง</label>
                <textarea name="reason" class="form-control" rows="3"></textarea>
            </div>

            <!-- วันที่จอง -->
            <div class="bg-white p-4 rounded shadow text-center">
                <div class="flex justify-center space-x-4 mb-4" id="selectedDates">
                    <div>
                        <div class="text-lg font-semibold" id="checkInDate">กรุณาเลือกวันเช็คอิน</div>
                        <div class="text-sm text-gray-500">เช็คอิน</div>
                    </div>
                    <div class="text-2xl font-semibold">→</div>
                    <div>
                        <div class="text-lg font-semibold" id="checkOutDate">กรุณาเลือกวันเช็คเอาท์</div>
                        <div class="text-sm text-gray-500">เช็คเอาท์</div>
                    </div>
                </div>

                <button id="toggleCalendar" type="button" class="btn btn-primary">เลือกวันจอง</button>
                <input type="hidden" name="booking_start" id="booking_start">
                <input type="hidden" name="booking_end" id="booking_end">
                
                <!-- แสดงข้อมูลวันหยุด -->
                <div class="mt-4 text-left">
                    <div class="fw-bold mb-2">หมายเหตุ:</div>
                    <div class="flex items-center mb-1">
                        <div class="w-4 h-4 bg-yellow-200 rounded-full mr-2"></div>
                        <span class="text-sm">วันหยุดนักขัตฤกษ์ (ไม่สามารถจองได้)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-200 rounded-full mr-2"></div>
                        <span class="text-sm">วันที่มีการจองแล้ว (ไม่สามารถจองได้)</span>
                    </div>
                </div>
            </div>
            
            <!-- เวลาจอง -->
                <div class="mt-4 bg-white p-4 rounded shadow">

                <h3 class="h5 mb-2">เวลาจอง</h3>
                <div class="row">
                    <div class="col">
                    <!-- เวลาเข้า (เริ่มต้น) -->
                    <div>
                    <label class="form-label">เวลาเข้า</label>
                    <input type="time" name="check_in_time" id="check_in_time" value="08:00" 
                           class="form-control" readonly>

                    </div>
                    
                    <!-- เวลาออก (สิ้นสุด) -->
                    <div>
                    <label class="form-label">เวลาออก</label>
                    <input type="time" name="check_out_time" id="check_out_time" value="23:00" 
                           class="form-control" readonly>

                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-500">หมายเหตุ: เวลาจองเริ่มตั้งแต่ 8:00 น. ถึง 23:00 น. ของแต่ละวัน</p>
            </div>
            
            <!-- ปุ่มยืนยันการจอง -->
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-danger">ยกเลิก</button>
                <button type="submit" class="btn btn-success">ยืนยันการจอง</button>
            </div>
        </form>
    </div>

    <!-- ส่วนแสดงข้อมูลห้องและสรุปราคาการจอง -->
        <div class="col-lg-4">

        <!-- ข้อมูลห้อง -->
        <div class="bg-white p-4 rounded shadow mb-4">
            <h2 class="h5 mb-4">ข้อมูลห้องพัก</h2>
            
            <!-- รูปภาพห้อง -->
            <div class="mb-3">
                @if(isset($room->image))
                    <img src="{{ asset($room->image) }}" alt="{{ $room->room_name }}" class="img-fluid rounded">
                @else
                    <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-gray-500">ไม่มีรูปภาพ</span>
                    </div>
                @endif
            </div>
            
            <!-- รายละเอียดห้อง -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>อาคาร:</span>
                        <span>{{ $room->building->building_name ?? 'ไม่ระบุ' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>ชื่อห้อง:</span>
                        <span class="fw-bold">{{ $room->room_name }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>ชั้น:</span>
                        <span>{{ $room->class }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>ความจุ:</span>
                        <span>{{ $room->capacity ?? '-' }} คน</span>
                    </div>
                    <div class="mt-2">
                        <span>รายละเอียด:</span>
                        <p class="mt-2">{{ $room->room_details }}</p>
                    </div>
                </div>

        </div>

        <!-- สรุปราคาการจอง -->
        <div class="bg-white p-4 rounded shadow">
            <h2 class="h5 mb-4">สรุปการจอง</h2>
            
            <div class="space-y-3">
                <div class="d-flex justify-content-between">
                    <span>อัตราค่าบริการ:</span>
                    <span class="fw-bold">{{ number_format($room->service_rates ?? 0, 2) }} บาท/วัน</span>
                </div>
                
                <div class="d-flex justify-content-between">
                    <span>จำนวนวัน:</span>
                    <span id="totalDays">0 วัน</span>
                </div>
                
                <div class="d-flex justify-content-between">
                    <span>เวลาเข้า:</span>
                    <span>08:00 น.</span>
                </div>
                
                <div class="d-flex justify-content-between">
                    <span>เวลาออก:</span>
                    <span>23:00 น.</span>
                </div>
                
                <div class="d-flex justify-content-between">
                    <span>ค่าบริการทั้งหมด:</span>
                    <span id="serviceFee">0 บาท</span>
                </div>
                
                <hr class="my-2">
                
                <div class="d-flex justify-content-between fw-bold">
                    <span>ราคารวมทั้งสิ้น:</span>
                    <span id="totalPrice">0 บาท</span>
                </div>
                
                <!-- ตัวเลือกชำระผ่านธนาคาร -->
                <div class="mt-4 d-flex align-items-center">
                    <input type="checkbox" id="bankPaymentCheckbox" class="form-check-input">
                    <label for="bankPaymentCheckbox" class="form-check-label">ชำระผ่านธนาคาร</label>
                </div>

                <!-- QR Code และข้อมูลบัญชีธนาคาร -->
                <div id="bankPaymentDetails" class="mt-4 text-center d-none">
                    <h3 class="text-lg font-semibold mb-2">โอนเงินผ่านธนาคาร</h3>
                    
                    <!-- รูป QR Code -->
                    <img src="{{ asset('images/apple-icon.png') }}" alt="QR Code ธนาคาร" class="w-40 h-40 mx-auto rounded-lg shadow-md">
                    
                    <!-- ข้อมูลบัญชีธนาคาร -->
                    <p class="mt-2 text-gray-700">
                        ชื่อบัญชี: <span class="font-semibold">บริษัท ABC จำกัด</span> <br>
                        ธนาคาร: <span class="font-semibold">ไทยพาณิชย์</span> <br>
                        เลขบัญชี: <span class="font-semibold">123-456-7890</span>
                    </p>

                    <!-- ปุ่มอัปโหลดสลิป -->
                    <div class="mt-4">
                        <label for="paymentSlip" class="cursor-pointer d-flex align-items-center justify-content-center bg-light border p-3 rounded">
                            <svg class="w-8 h-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="ml-2 text-gray-600">อัปโหลดสลิป</span>
                        </label>
                        <input type="file" id="paymentSlip" name="payment_slip" class="hidden" accept="image/*">
                        <div id="fileName" class="mt-2 text-sm text-gray-600">ยังไม่ได้เลือกไฟล์</div>
                    </div>
                </div>

                    <div class="mt-4 p-3 bg-light text-sm">

                    <p class="text-blue-800">
                        <span class="fw-bold">หมายเหตุ:</span> ราคาอาจมีการเปลี่ยนแปลงตามนโยบายและระยะเวลาที่จอง
                    </p>
                </div>
            </div>
        </div>
    </div>   
</div>

<!-- Litepicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css">
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

<style>
    /* ปรับ tooltip ให้แสดงบนปฏิทิน */
    .litepicker .day-item[data-tooltip] {
        position: relative;
    }
    
    .litepicker .day-item[data-tooltip]:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        top: -25px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 10;
    }

    /* สีสำหรับวันหยุด */
    .litepicker .day-item.is-holiday {
        background-color: #fef08a !important; /* yellow-200 */
        color: #854d0e !important; /* yellow-800 */
        font-weight: bold;
        cursor: not-allowed !important;
    }

    /* สีสำหรับวันจองแล้ว */
    .litepicker .day-item.is-booked {
        background-color: #bfdbfe !important; /* blue-200 */
        color: #1e40af !important; /* blue-800 */
        cursor: not-allowed !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bookingStart = document.getElementById('booking_start');
        const bookingEnd = document.getElementById('booking_end');
        const checkInDate = document.getElementById('checkInDate');
        const checkOutDate = document.getElementById('checkOutDate');
        const checkInTime = document.getElementById('check_in_time');
        const checkOutTime = document.getElementById('check_out_time');
        const toggleButton = document.getElementById('toggleCalendar');
        const totalDays = document.getElementById('totalDays');
        const serviceFee = document.getElementById('serviceFee');
        const totalPrice = document.getElementById('totalPrice');
        const bookingForm = document.getElementById('bookingForm');
        const bankPaymentCheckbox = document.getElementById('bankPaymentCheckbox');
        const bankPaymentDetails = document.getElementById('bankPaymentDetails');
        const paymentSlip = document.getElementById('paymentSlip');
        const fileName = document.getElementById('fileName');
    
        // กำหนดค่าเริ่มต้นให้กับเวลา
        checkInTime.value = "08:00";
        checkOutTime.value = "23:00";
        
        // ข้อมูลราคา
        const serviceRate = {{ $room->service_rates ?? 0 }};
    
        // ข้อมูลวันหยุดและวันที่ถูกจอง
        const holidaysWithNames = @json($holidaysWithNames);
        const holidays = Object.keys(holidaysWithNames);
        const bookedDetails = @json($bookedDetails);
        const bookedDays = Object.keys(bookedDetails);
    
        const picker = new Litepicker({
            element: toggleButton,
            singleMode: false,
            numberOfMonths: 2,
            numberOfColumns: 2,
            format: 'D MMM YYYY',
            lang: "th-TH",
            autoApply: true,
            minDate: new Date(),
            allowSingleDayRange: true, // อนุญาตให้เลือกวันเดียวได้
            tooltipText: {
                one: 'วัน',
                other: 'วัน'
            },
            lockDaysFilter: (date) => {
                const formattedDate = date.format('YYYY-MM-DD');
                return bookedDays.includes(formattedDate) || holidays.includes(formattedDate);
            },
            setup: (picker) => {
                picker.on('render', () => {
                    // ใช้การเปรียบเทียบโดยใช้วันที่แบบ string เพื่อหลีกเลี่ยงปัญหา timezone
                    const selectedStart = bookingStart.value;
                    const selectedEnd = bookingEnd.value;
    
                    document.querySelectorAll('.container__days .day-item').forEach(day => {
                        const date = day.getAttribute('data-time');
                        if (date) {
                            const dateObj = new Date(parseInt(date));
                            const formattedDate = dateObj.toISOString().split('T')[0]; // รูปแบบ YYYY-MM-DD
    
                            // รีเซ็ตคลาสก่อน
                            day.classList.remove('is-selected');
    
                            // ไฮไลต์ช่วงวันที่เลือก - เปรียบเทียบวันที่แบบ string
                            if (selectedStart && selectedEnd && 
                                formattedDate >= selectedStart && 
                                formattedDate <= selectedEnd) {
                                day.classList.add('is-selected');
                            }
    
                            // แสดง tooltip และล็อกวันที่ที่ไม่สามารถเลือกได้
                            if (holidays.includes(formattedDate)) {
                                day.classList.add('is-holiday', 'is-locked');
                                day.setAttribute('data-tooltip', holidaysWithNames[formattedDate]);
                            }
                            if (bookedDays.includes(formattedDate)) {
                                day.classList.add('is-booked', 'is-locked');
                                day.setAttribute('data-tooltip', bookedDetails[formattedDate]);
                            }
                        }
                    });
                });
    
                picker.on('selected', (date1, date2) => {
                    // รีเซ็ตไฮไลต์ก่อน
                    document.querySelectorAll('.day-item').forEach(day => {
                        day.classList.remove('is-selected');
                    });
    
                    // อัปเดตค่าฟอร์ม (เฉพาะวันที่ ไม่รวมเวลา)
                    bookingStart.value = date1.format('YYYY-MM-DD');
                    bookingEnd.value = date2 ? date2.format('YYYY-MM-DD') : date1.format('YYYY-MM-DD');
                    
                    // แสดงวันที่พร้อมเวลาเข้า-ออก
                    checkInDate.innerText = date1.format('D MMM YYYY') + ' (08:00 น.)';
                    checkOutDate.innerText = (date2 ? date2.format('D MMM YYYY') : date1.format('D MMM YYYY')) + ' (23:00 น.)';
    
                    // คำนวณจำนวนวัน
                    let days = 1;
                    if (date2) {
                        const oneDay = 24 * 60 * 60 * 1000;
                        const firstDate = new Date(date1.format('YYYY-MM-DD'));
                        const secondDate = new Date(date2.format('YYYY-MM-DD'));
                        days = Math.round(Math.abs((secondDate - firstDate) / oneDay)) + 1;
                    }
    
                    totalDays.innerText = days + ' วัน';
                    const totalServiceFee = days * serviceRate;
                    serviceFee.innerText = numberWithCommas(totalServiceFee.toFixed(2)) + ' บาท';
                    totalPrice.innerText = numberWithCommas(totalServiceFee.toFixed(2)) + ' บาท';
    
                    // บังคับให้ Litepicker อัปเดต
                    picker.render();
                });
            }
        });
    
        // เมื่อกดปุ่มยืนยันการจอง
        bookingForm.addEventListener('submit', function(e) {
            // ถ้ามีการเลือกวันที่จอง
            if (bookingStart.value && bookingEnd.value) {
                // เพิ่มเวลาเข้ากับวันที่
                const startDateTime = `${bookingStart.value}T${checkInTime.value}:00`;
                const endDateTime = `${bookingEnd.value}T${checkOutTime.value}:00`;
                
                // อัปเดตค่าในฟอร์ม
                bookingStart.value = startDateTime;
                bookingEnd.value = endDateTime;
            } else {
                // ถ้ายังไม่ได้เลือกวันที่
                e.preventDefault();
                alert('กรุณาเลือกวันที่จอง');
            }
        });
    
        // โหลดค่าจาก input ถ้ามี
        if (bookingStart.value && bookingEnd.value) {
            // แปลงวันที่ให้เป็น local time เพื่อแก้ปัญหา timezone
            let startParts = bookingStart.value.split('T')[0]; // ดึงเฉพาะส่วนวันที่ YYYY-MM-DD
            let endParts = bookingEnd.value.split('T')[0];     // ดึงเฉพาะส่วนวันที่ YYYY-MM-DD
            
            let startDate = new Date(startParts + 'T00:00:00');
            let endDate = new Date(endParts + 'T00:00:00');
    
            picker.setDateRange(startDate, endDate);
            picker.render();
        }
    
        toggleButton.addEventListener('click', function() {
            picker.show();
        });
    
        // เมื่อเลือก "ชำระผ่านธนาคาร"
        bankPaymentCheckbox.addEventListener('change', function() {
            if (this.checked) {
                bankPaymentDetails.classList.remove('hidden'); // แสดง QR Code + อัปโหลดสลิป
            } else {
                bankPaymentDetails.classList.add('hidden'); // ซ่อนทั้งหมด
            }
        });
    
        // แสดงชื่อไฟล์ที่เลือก
        paymentSlip.addEventListener('change', function() {
            fileName.innerText = this.files[0] ? this.files[0].name : "ยังไม่ได้เลือกไฟล์";
        });
    
        // ฟังก์ชันแปลงตัวเลขเป็นรูปแบบมีจุลภาค
        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    });
</script>
    
@endsection
