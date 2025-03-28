@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        
            <!-- Hero Banner -->
            <div class="card bg-warning text-white mb-4">
                <div class="card-body text-center py-5">
                    <h1 class="display-4 fw-bold">ระบบจองห้องออนไลน์</h1>
                    <h2>มหาวิทยาลัยราชภัฏสกลนคร</h2>
                    <p class="lead mt-3">บริการจองห้องเรียน ห้องประชุม และสถานที่จัดกิจกรรมต่างๆ แบบออนไลน์</p>
                    <a href="{{ route('booking.index') }}" class="btn btn-warning btn-lg mt-3">
                        <i class="fas fa-calendar-plus me-2"></i>จองห้องเลย
                    </a>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card bg-light shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-building text-primary display-4 mb-3"></i>
                            <h3 class="fw-bold">{{ $totalBuildings }} อาคาร</h3>

                            <p>อาคารที่ให้บริการจองห้อง</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-door-open text-success display-4 mb-3"></i>
                            <h3 class="fw-bold">{{ $totalRooms }} ห้อง</h3>

                            <p>ห้องที่เปิดให้จอง</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-check text-warning display-4 mb-3"></i>
                            <h3 class="fw-bold">{{ $totalBookings }} การจองทั้งหมด</h3>

                            <p>การจองทั้งหมด</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Rooms -->
            <h3 class="fw-bold mb-3">ห้องแนะนำ</h3>
            <div class="row g-4 mb-4">
                @if(isset($featuredRooms) && count($featuredRooms) > 0)
                    @foreach($featuredRooms as $room)
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <img src="{{ $room->image ? asset('storage/'.$room->image) : '/api/placeholder/400/200' }}" class="card-img-top" alt="Room Image">
                                <div class="card-body">
                                    <h5 class="fw-bold">{{ $room->room_name }}</h5>
                                    <p class="text-muted mb-1">อาคาร {{ $room->building->building_name }} ชั้น {{ $room->floor }}</p>
                                    <p class="text-muted mb-1">รองรับได้ {{ $room->capacity }} คน</p>
                                    <p class="fw-bold text-warning">฿{{ number_format($room->service_rates, 2) }} /วัน</p>
                                    <a href="{{ route('partials.booking.form', ['id' => $room->room_id]) }}" class="btn btn-warning w-100">จองเลย</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center">
                        <p>ไม่มีห้องแนะนำในขณะนี้</p>
                    </div>
                @endif
            </div>

            <!-- How to Book Section -->
            <div class="card bg-light mb-4">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4 text-center">วิธีการจองห้อง</h3>
                    <div class="row text-center g-4">
                        <div class="col-md-3">
                            <div class="p-3">
                                <div class="rounded-circle bg-primary text-white d-inline-flex justify-content-center align-items-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-search fa-2x"></i>
                                </div>
                                <h4>1. เลือกห้อง</h4>
                                <p>ค้นหาและเลือกห้องที่ต้องการจองตามวัตถุประสงค์การใช้งาน</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <div class="rounded-circle bg-success text-white d-inline-flex justify-content-center align-items-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                </div>
                                <h4>2. เลือกวันเวลา</h4>
                                <p>เลือกวันและเวลาที่ต้องการใช้งานห้อง</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <div class="rounded-circle bg-warning text-white d-inline-flex justify-content-center align-items-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-clipboard-list fa-2x"></i>
                                </div>
                                <h4>3. กรอกข้อมูล</h4>
                                <p>กรอกข้อมูลการจองและรายละเอียดการใช้งาน</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <div class="rounded-circle bg-danger text-white d-inline-flex justify-content-center align-items-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <h4>4. รอการยืนยัน</h4>
                                <p>รอการยืนยันการจองผ่านอีเมลหรือตรวจสอบในระบบ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Holidays -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">วันหยุดประจำปี 2025</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>วันขึ้นปีใหม่</span>
                                    <span class="badge bg-primary rounded-pill">1 มกราคม 2025</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>วันมาฆบูชา</span>
                                    <span class="badge bg-primary rounded-pill">10 กุมภาพันธ์ 2025</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>วันจักรี</span>
                                    <span class="badge bg-primary rounded-pill">6 เมษายน 2025</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>วันสงกรานต์</span>
                                    <span class="badge bg-primary rounded-pill">13-15 เมษายน 2025</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>วันแรงงานแห่งชาติ</span>
                                    <span class="badge bg-primary rounded-pill">1 พฤษภาคม 2025</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>วันเฉลิมพระชนมพรรษา ร.10</span>
                                    <span class="badge bg-primary rounded-pill">28 กรกฎาคม 2025</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>วันแม่แห่งชาติ</span>
                                    <span class="badge bg-primary rounded-pill">12 สิงหาคม 2025</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>วันปิยมหาราช</span>
                                    <span class="badge bg-primary rounded-pill">23 ตุลาคม 2025</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>วันพ่อแห่งชาติ/วันชาติ</span>
                                    <span class="badge bg-primary rounded-pill">5 ธันวาคม 2025</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>วันสิ้นปี</span>
                                    <span class="badge bg-primary rounded-pill">31 ธันวาคม 2025</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact & FAQ -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">ติดต่อเรา</h4>
                        </div>
                        <div class="card-body">
                            <p><i class="fas fa-university me-2"></i> มหาวิทยาลัยราชภัฏสกลนคร</p>
                            <p><i class="fas fa-map-marker-alt me-2"></i> 680 ถนนนิตโย ตำบลธาตุเชิงชุม อำเภอเมือง จังหวัดสกลนคร 47000</p>
                            <p><i class="fas fa-phone-alt me-2"></i> 042-970021</p>
                            <p><i class="fas fa-envelope me-2"></i> booking@snru.ac.th</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0">คำถามที่พบบ่อย</h4>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                            ทำการจองห้องได้ล่วงหน้ากี่วัน?
                                        </button>
                                    </h2>
                                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            สามารถทำการจองล่วงหน้าได้ไม่เกิน 30 วัน
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                            มีค่าใช้จ่ายในการจองห้องหรือไม่?
                                        </button>
                                    </h2>
                                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            ค่าใช้จ่ายขึ้นอยู่กับประเภทห้องและระยะเวลาการใช้งาน โปรดตรวจสอบราคาในหน้ารายละเอียดห้อง
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                            สามารถยกเลิกการจองได้หรือไม่?
                                        </button>
                                    </h2>
                                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            สามารถยกเลิกการจองได้ผ่านระบบออนไลน์ โดยต้องยกเลิกก่อนวันใช้งานอย่างน้อย 24 ชั่วโมง
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                            ต้องชำระเงินอย่างไร?
                                        </button>
                                    </h2>
                                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            สามารถชำระเงินผ่านบัตรเครดิต/เดบิต หรือชำระเงินสดที่สำนักงานการเงินของมหาวิทยาลัย
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                            มีห้องให้เลือกกี่ประเภท?
                                        </button>
                                    </h2>
                                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            มีห้องให้เลือกหลากหลายประเภท เช่น ห้องเรียนขนาดเล็ก ห้องประชุม ห้องสัมมนา และห้องอเนกประสงค์
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection
