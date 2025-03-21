@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
            <!-- Header Section -->
            <!-- How-to-use Content -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="fw-bold text-warning mb-4">ขั้นตอนการใช้งานระบบจองห้อง</h3>
                    <div class="row g-4">
                        <!-- Step 1 -->
                        <div class="col-md-6">
                            <div class="card bg-light p-3 h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-warning rounded-circle p-3 me-3">
                                        <i class="fas fa-search text-white fs-4"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">ขั้นตอนที่ 1: ค้นหาห้อง</h5>
                                </div>
                                <p class="text-muted">
                                    ใช้ช่องค้นหาหรือเลือกประเภทห้องและอาคารเพื่อค้นหาห้องที่คุณต้องการจอง
                                </p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="col-md-6">
                            <div class="card bg-light p-3 h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-warning rounded-circle p-3 me-3">
                                        <i class="fas fa-calendar-alt text-white fs-4"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">ขั้นตอนที่ 2: ตรวจสอบวันว่าง</h5>
                                </div>
                                <p class="text-muted">
                                    ตรวจสอบวันที่และเวลาที่ห้องว่างเพื่อเลือกเวลาที่เหมาะสมสำหรับการจอง
                                </p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="col-md-6">
                            <div class="card bg-light p-3 h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-warning rounded-circle p-3 me-3">
                                        <i class="fas fa-check-circle text-white fs-4"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">ขั้นตอนที่ 3: ยืนยันการจอง</h5>
                                </div>
                                <p class="text-muted">
                                    กรอกข้อมูลการจองและยืนยันการจองห้อง พร้อมรับส่วนลดพิเศษสำหรับการจองครั้งแรก
                                </p>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="col-md-6">
                            <div class="card bg-light p-3 h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-warning rounded-circle p-3 me-3">
                                        <i class="fas fa-file-alt text-white fs-4"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">ขั้นตอนที่ 4: รับเอกสารยืนยัน</h5>
                                </div>
                                <p class="text-muted">
                                    หลังจากจองสำเร็จ ระบบจะส่งเอกสารยืนยันการจองไปยังอีเมลของคุณ
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="fw-bold text-warning mb-4">คำถามที่พบบ่อย (FAQ)</h3>
                    <div class="accordion" id="faqAccordion">
                        <!-- FAQ 1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                                    ฉันสามารถจองห้องล่วงหน้าได้กี่วัน?
                                </button>
                            </h2>
                            <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    คุณสามารถจองห้องล่วงหน้าได้สูงสุด 30 วัน
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                                    ฉันสามารถยกเลิกการจองได้หรือไม่?
                                </button>
                            </h2>
                            <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    คุณสามารถยกเลิกการจองได้สูงสุด 24 ชั่วโมงก่อนเวลาจอง
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                                    ฉันจะชำระเงินได้อย่างไร?
                                </button>
                            </h2>
                            <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    คุณสามารถชำระเงินผ่านบัตรเครดิตหรือเงินสดที่สำนักงาน
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection