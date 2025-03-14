@extends('layouts.app')

@section('content')
<!-- Main Container with Background -->
<div class="container-fluid py-5" style="background-image: url('{{ asset('images/bg-1.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="container">
        <div class="row">
            <!-- Booking Form Section -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow rounded-lg border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h4 class="mb-0 fw-bold">ข้อมูลผู้จอง <span class="text-danger">*</span></h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('booking.store') }}" method="POST" id="bookingForm" enctype="multipart/form-data" onsubmit="return handleFormSubmit(event);">

                            @csrf
                            <!-- Hidden inputs -->
                            <input type="hidden" name="room_id" value="{{ $room->room_id }}">
                            <input type="hidden" name="building_id" value="{{ $room->building_id }}">
                            <input type="hidden" name="room_name" value="{{ $room->room_name }}">
                            <input type="hidden" name="building_name" value="{{ $room->building->building_name ?? 'ไม่ระบุ' }}">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            
                            <div class="row g-3">
                                <!-- Building Name -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">อาคาร</label>
                                    <input class="form-control bg-light" type="text" value="{{ $room->building->building_name ?? 'ไม่ระบุ' }}" readonly>
                                </div>
                                
                                <!-- Room Name -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">ห้องที่จอง</label>
                                    <input class="form-control bg-light" type="text" value="{{ $room->room_name }}" readonly>
                                </div>
                                
                                <!-- User Name -->
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">ชื่อผู้จอง</label>
                                    <input class="form-control" name="external_name" type="text" required>
                                </div>
                                
                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">อีเมล</label>
                                    <input class="form-control" name="external_email" type="email" required>
                                </div>
                                
                                <!-- Phone -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">เบอร์โทร</label>
                                    <input class="form-control" name="external_phone" type="text" required>
                                </div>
                                
                                <!-- Reason -->
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">เหตุผลในการจอง</label>
                                    <textarea name="reason" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <!-- Date Selection Box -->
                            <div class="card border-0 shadow-sm mt-4 mb-3">
                                <div class="card-body p-4 text-center">
                                    <div class="d-flex justify-content-center align-items-center mb-3 flex-wrap gap-3">
                                        <div>
                                            <div class="h5 fw-bold mb-1" id="checkInDate">กรุณาเลือกวันเช็คอิน</div>
                                            <div class="small text-muted">เช็คอิน</div>
                                        </div>
                                        <div class="h4 mx-3 text-warning">→</div>
                                        <div>
                                            <div class="h5 fw-bold mb-1" id="checkOutDate">กรุณาเลือกวันเช็คเอาท์</div>
                                            <div class="small text-muted">เช็คเอาท์</div>
                                        </div>
                                    </div>
                                    
                                    <button id="toggleCalendar" type="button" class="btn btn-warning px-4 py-2 fw-semibold">
                                        <i class="bi bi-calendar-date me-2"></i>เลือกวันจอง
                                    </button>
                                    <input type="hidden" name="booking_start" id="booking_start">
                                    <input type="hidden" name="booking_end" id="booking_end">
                                    
                                    <!-- Holiday notes -->
                                    <div class="mt-4 text-start">
                                        <h6 class="fw-bold mb-2">หมายเหตุ:</h6>
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="d-inline-block rounded-circle me-2" style="width: 16px; height: 16px; background-color: #fef08a;"></span>
                                            <span class="small">วันหยุดนักขัตฤกษ์ (ไม่สามารถจองได้)</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="d-inline-block rounded-circle me-2" style="width: 16px; height: 16px; background-color: #bfdbfe;"></span>
                                            <span class="small">วันที่มีการจองแล้ว (ไม่สามารถจองได้)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Time Selection Box -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-3">เวลาจอง</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">เวลาเข้า</label>
                                            <input type="time" name="check_in_time" id="check_in_time" value="08:00" 
                                                   class="form-control bg-light" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">เวลาออก</label>
                                            <input type="time" name="check_out_time" id="check_out_time" value="23:00" 
                                                   class="form-control bg-light" readonly>
                                        </div>
                                    </div>
                                    <p class="small text-muted mt-3 mb-0">
                                        <i class="bi bi-info-circle me-1"></i>
                                        หมายเหตุ: เวลาจองเริ่มตั้งแต่ 8:00 น. ถึง 23:00 น. ของแต่ละวัน
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-danger px-4">
                                    <i class="bi bi-x-circle me-1"></i>ยกเลิก
                                </button>
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bi bi-check-circle me-1"></i>ยืนยันการจอง
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Room Info and Booking Summary -->
            <div class="col-lg-4">
                <!-- Room Info Card -->
                <div class="card shadow rounded-lg border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h4 class="mb-0 fw-bold">ข้อมูลห้องพัก</h4>
                    </div>
                    <div class="card-body p-4">
                        <!-- Room Image -->
                        <div class="mb-4">
                            @if(isset($room->image))
                                <img src="{{ asset($room->image) }}" alt="{{ $room->room_name }}" class="img-fluid rounded-lg shadow-sm">
                            @else
                                <div class="bg-light rounded-lg d-flex align-items-center justify-content-center py-5">
                                    <span class="text-muted"><i class="bi bi-image me-2"></i>ไม่มีรูปภาพ</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Room Details -->
                        <div class="mb-3">
                            <!-- Building -->
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">อาคาร:</span>
                                <span>{{ $room->building->building_name ?? 'ไม่ระบุ' }}</span>
                            </div>
                            
                            <!-- Room Name -->
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">ชื่อห้อง:</span>
                                <span class="fw-bold">{{ $room->room_name }}</span>
                            </div>
                            
                            <!-- Floor -->
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">ชั้น:</span>
                                <span>{{ $room->class }}</span>
                            </div>
                            
                            <!-- Capacity -->
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">ความจุ:</span>
                                <span>{{ $room->capacity ?? '-' }} คน</span>
                            </div>
                            
                            <!-- Details -->
                            <div class="py-2">
                                <div class="text-muted mb-1">รายละเอียด:</div>
                                <p class="mb-0">{{ $room->room_details }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Booking Summary Card -->
                <div class="card shadow rounded-lg border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h4 class="mb-0 fw-bold">สรุปการจอง</h4>
                    </div>
                    <div class="card-body p-4">
                        <!-- Price Summary -->
                        <div class="mb-4">
                            <!-- Rate -->
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">อัตราค่าบริการ:</span>
                                <span class="fw-bold">{{ number_format($room->service_rates ?? 0, 2) }} บาท/วัน</span>
                            </div>
                            
                            <!-- Days -->
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">จำนวนวัน:</span>
                                <span id="totalDays">0 วัน</span>
                            </div>
                            
                            <!-- Check-in time -->
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">เวลาเข้า:</span>
                                <span>08:00 น.</span>
                            </div>
                            
                            <!-- Check-out time -->
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">เวลาออก:</span>
                                <span>23:00 น.</span>
                            </div>
                            
                            <!-- Service Fee -->
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">ค่าบริการทั้งหมด:</span>
                                <span id="serviceFee">0 บาท</span>
                            </div>
                            
                            <!-- Total Price -->
                            <div class="d-flex justify-content-between py-3">
                                <span class="fw-bold">ราคารวมทั้งสิ้น:</span>
                                <span class="fw-bold text-warning h5 mb-0" id="totalPrice">0 บาท</span>
                            </div>
                        </div>
                        
                        <!-- Bank Payment -->
                        <div class="mb-3">
                            <div class="form-check mb-3">
                                <input type="checkbox" id="bankPaymentCheckbox" class="form-check-input">
                                <label for="bankPaymentCheckbox" class="form-check-label fw-semibold">
                                    <i class="bi bi-bank me-1"></i>ชำระผ่านธนาคาร
                                </label>
                            </div>
                            
                            <!-- Bank Payment Details -->
                            <div id="bankPaymentDetails" class="d-none p-3 bg-light rounded-3 text-center">
                                <h5 class="fw-bold mb-3">โอนเงินผ่านธนาคาร</h5>
                                
                                <!-- QR Code -->
                                <img src="{{ asset('images/apple-icon.png') }}" alt="QR Code ธนาคาร" 
                                     class="img-fluid rounded-lg shadow-sm mb-3" style="max-width: 160px;">
                                
                                <!-- Bank Details -->
                                <div class="text-start bg-white p-3 rounded-3 mb-3">
                                    <p class="mb-1"><span class="text-muted">ชื่อบัญชี:</span> <span class="fw-semibold">บริษัท ABC จำกัด</span></p>
                                    <p class="mb-1"><span class="text-muted">ธนาคาร:</span> <span class="fw-semibold">ไทยพาณิชย์</span></p>
                                    <p class="mb-0"><span class="text-muted">เลขบัญชี:</span> <span class="fw-semibold">123-456-7890</span></p>
                                </div>
                                
                                <!-- Upload Slip Button -->
                                <div class="mt-3">
                                    <label for="paymentSlip" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-upload me-2"></i>อัปโหลดสลิป
                                    </label>
                                    <input type="file" id="paymentSlip" name="payment_slip" class="d-none" accept="image/*,application/pdf" form="bookingForm">
                                    <div id="fileName" class="small text-muted mt-2">ยังไม่ได้เลือกไฟล์</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Note -->
                        <div class="bg-light p-3 rounded-3 small">
                            <p class="mb-0 text-primary">
                                <i class="bi bi-info-circle-fill me-1"></i>
                                <span class="fw-semibold">หมายเหตุ:</span> ราคาอาจมีการเปลี่ยนแปลงตามนโยบายและระยะเวลาที่จอง
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Litepicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css">
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
    /* Font family */
    body {
        font-family: 'Kanit', sans-serif;
        background-color: #f5f5f7;
        color: #333;
    }
    
    /* Card styling */
    .card {
        transition: transform 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    /* Button styling */
    .btn-success {
        background-color: #FFC107;
        border-color: #FFC107;
        color: #333;
    }
    
    .btn-success:hover {
        background-color: #e0a800;
        border-color: #e0a800;
        color: #333;
    }
    
    /* Calendar customizations */
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

    /* Holiday styling */
    .litepicker .day-item.is-holiday {
        background-color: #fef08a !important;
        color: #854d0e !important;
        font-weight: bold;
        cursor: not-allowed !important;
    }

    /* Booked days styling */
    .litepicker .day-item.is-booked {
        background-color: #bfdbfe !important;
        color: #1e40af !important;
        cursor: not-allowed !important;
    }
    
    /* Selected dates */
    .litepicker .day-item.is-start-date, 
    .litepicker .day-item.is-end-date {
        background-color: #FFC107 !important;
        color: #333 !important;
    }
    
    .litepicker .day-item.is-in-range {
        background-color: rgba(255, 193, 7, 0.2) !important;
        color: #333 !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm');
    
    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault(); // ป้องกันการส่งฟอร์มแบบปกติ
        
        // แสดง SweetAlert2 เพื่อยืนยันการจอง
        Swal.fire({
            title: 'ยืนยันการจอง',
            text: "คุณต้องการยืนยันการจองนี้หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ยืนยัน!'
        }).then((result) => {
            if (result.isConfirmed) {
                // ส่งข้อมูลฟอร์มด้วย fetch
                const formData = new FormData(bookingForm); // เก็บข้อมูลฟอร์ม

                fetch(bookingForm.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        // หากส่งข้อมูลสำเร็จ ให้รีไดเร็กต์ไปที่หน้าหลัก
                        window.location.href = '/';
                    } else {
                        // หากมีข้อผิดพลาด
                        Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถบันทึกข้อมูลได้', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์', 'error');
                });
            }
        });
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Define the variables from PHP data using JSON encoding
    const toggleButton = document.getElementById('toggleCalendar');
    const checkInDate = document.getElementById('checkInDate');
    const checkOutDate = document.getElementById('checkOutDate');
    const bookingStart = document.getElementById('booking_start');
    const bookingEnd = document.getElementById('booking_end');
    const totalDays = document.getElementById('totalDays');
    const serviceFee = document.getElementById('serviceFee');
    const totalPrice = document.getElementById('totalPrice');
    const bankPaymentCheckbox = document.getElementById('bankPaymentCheckbox');
    const bankPaymentDetails = document.getElementById('bankPaymentDetails');
    const paymentSlip = document.getElementById('paymentSlip');
    const fileName = document.getElementById('fileName');
    const bookingForm = document.getElementById('bookingForm');
    const checkInTime = document.getElementById('check_in_time');
    const checkOutTime = document.getElementById('check_out_time');
    
    // Get room service rate for calculations
    //const serviceRate = {{ $room->service_rates ?? 0 }};
    const serviceRate = parseFloat({{ $room->service_rates ?? 0 }});
    if (isNaN(serviceRate)) {
        console.error('serviceRate is not a valid number');
        return;
    }
    // Convert PHP arrays to JavaScript
    const holidaysWithNames = @json($holidaysWithNames);
    const bookedDetails = @json($bookedDetails);
    const disabledDays = @json($disabledDays);
    
    // Create arrays for holidays and booked days
    const holidays = Object.keys(holidaysWithNames);
    const bookedDays = Object.keys(bookedDetails);

    // แก้ไขการกำหนดวันที่ล็อค
    const disabledDates = disabledDays;

    const picker = new Litepicker({
        // Rest of the picker configuration remains the same
        element: toggleButton,
        singleMode: false,
        numberOfMonths: 2,
        numberOfColumns: 2,
        format: 'D MMM YYYY',
        lang: "th-TH",
        autoApply: true,
        minDate: new Date(),
        allowSingleDayRange: true,
        tooltipText: {
            one: 'วัน',
            other: 'วัน'
        },
        lockDays: disabledDates,
        // Rest of code remains the same...
    
    
    // Rest of the event listeners and code...

        setup: (picker) => {
            picker.on('render', () => {
                document.querySelectorAll('.container__days .day-item').forEach(day => {
                    const date = day.getAttribute('data-time');
                    if (date) {
                        const dateObj = new Date(parseInt(date));
                        // ใช้ toLocaleDateString เพื่อแก้ปัญหา timezone
                        const year = dateObj.getFullYear();
                        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                        const dayNumber = String(dateObj.getDate()).padStart(2, '0');
                        const formattedDate = `${year}-${month}-${dayNumber}`;

                        // กำหนด class สำหรับวันหยุด
                        if (holidays.includes(formattedDate)) {
                            day.classList.add('is-holiday');
                            day.setAttribute('data-tooltip', holidaysWithNames[formattedDate]);
                        }
                        
                        // กำหนด class สำหรับวันที่จองแล้ว
                        if (bookedDays.includes(formattedDate)) {
                            day.classList.add('is-booked');
                            day.setAttribute('data-tooltip', bookedDetails[formattedDate]);
                        }
                    }
                });
            });

            picker.on('selected', (date1, date2) => {
                console.log('date1:', date1);
                console.log('date2:', date2);

                // ดึง Date object จริงๆ ออกมาจาก date1 และ date2
                const realDate1 = date1.dateInstance;
                const realDate2 = date2 ? date2.dateInstance : null;

                // ตรวจสอบว่า realDate1 และ realDate2 เป็น Date object ที่ถูกต้อง
                if (!(realDate1 instanceof Date) || isNaN(realDate1.getTime())) {
                    console.error('date1 is not a valid Date object');
                    return;
                }
                if (realDate2 && (!(realDate2 instanceof Date) || isNaN(realDate2.getTime()))) {
                    console.error('date2 is not a valid Date object');
                    return;
                }

                // แปลงวันที่เป็น string format YYYY-MM-DD
                const formatDate = (date) => {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                };
                
                // ใช้ local date เพื่อหลีกเลี่ยงปัญหา timezone
                bookingStart.value = formatDate(realDate1);
                bookingEnd.value = realDate2 ? formatDate(realDate2) : formatDate(realDate1);
                
                // ส่วนแสดงผลเหมือนเดิม
                checkInDate.innerText = realDate1.toLocaleDateString('th-TH', { day: 'numeric', month: 'short', year: 'numeric' }) + ' (08:00 น.)';
                checkOutDate.innerText = (realDate2 ? realDate2.toLocaleDateString('th-TH', { day: 'numeric', month: 'short', year: 'numeric' }) : realDate1.toLocaleDateString('th-TH', { day: 'numeric', month: 'short', year: 'numeric' })) + ' (23:00 น.)';
                
                // คำนวณจำนวนวัน
                let days = 1;
                if (realDate2) {
                    const oneDay = 24 * 60 * 60 * 1000; // จำนวนมิลลิวินาทีใน 1 วัน
                    const firstDate = realDate1.getTime(); // แปลงเป็น timestamp
                    const secondDate = realDate2.getTime(); // แปลงเป็น timestamp
                    days = Math.round(Math.abs((secondDate - firstDate) / oneDay)) + 1;
                }

                totalDays.innerText = days + ' วัน';
                const totalServiceFee = days * serviceRate;
                serviceFee.innerText = numberWithCommas(totalServiceFee.toFixed(2)) + ' บาท';
                totalPrice.innerText = numberWithCommas(totalServiceFee.toFixed(2)) + ' บาท';
            });
        }
    });
    
    // แก้ไขส่วนการส่งฟอร์ม
    bookingForm.addEventListener('submit', function(e) {
        // ตรวจสอบว่ามีการเลือกวันที่หรือไม่
        if (!bookingStart.value || !bookingEnd.value) {
            e.preventDefault();
            alert('กรุณาเลือกวันที่จอง');
            return;
        }
        
        // เพิ่มเวลาเข้ากับวันที่
        bookingStart.value = `${bookingStart.value}T${checkInTime.value}:00`;
        bookingEnd.value = `${bookingEnd.value}T${checkOutTime.value}:00`;
        
        // เช็คว่าถ้าเลือกชำระผ่านธนาคาร แต่ไม่อัปโหลดสลิป
        if (bankPaymentCheckbox.checked && !paymentSlip.files[0]) {
            e.preventDefault();
            alert('กรุณาอัปโหลดสลิปการโอนเงิน');
            return;
        }
    });
    // ส่วนที่เหลือเหมือนเดิม

    
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
                bankPaymentDetails.classList.remove('d-none'); // แสดง QR Code + อัปโหลดสลิป
            } else {
                bankPaymentDetails.classList.add('d-none'); // ซ่อนทั้งหมด
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
