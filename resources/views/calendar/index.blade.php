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
                <a href="{{ route('booking.index') }}" class="btn btn-light btn-lg mt-3">
                    <i class="fas fa-calendar-plus me-2"></i>จองห้องเลย
                </a>
            </div>
        </div>
        
        <div class="col-md-6">
            <h2>ปฏิทินการจองห้อง</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="btn-group">
                <a href="{{ route('calendar.index', ['date' => $prevMonth, 'view' => $view]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <button class="btn btn-outline-secondary" id="current-month">{{ $currentMonth }}</button>
                <a href="{{ route('calendar.index', ['date' => $nextMonth, 'view' => $view]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            <a href="{{ route('calendar.index', ['date' => now()->format('Y-m-d'), 'view' => $view]) }}" class="btn btn-primary ms-2">วันนี้</a>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link {{ $view == 'month' ? 'active' : '' }}" href="{{ route('calendar.index', ['view' => 'month', 'date' => $currentDate]) }}">เดือน</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $view == 'week' ? 'active' : '' }}" href="{{ route('calendar.index', ['view' => 'week', 'date' => $currentDate]) }}">สัปดาห์</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $view == 'day' ? 'active' : '' }}" href="{{ route('calendar.index', ['view' => 'day', 'date' => $currentDate]) }}">วัน</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $view == 'list' ? 'active' : '' }}" href="{{ route('calendar.index', ['view' => 'list', 'date' => $currentDate]) }}">รายการ</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    @if($view == 'month')
                        @include('calendar.views.month')
                    @elseif($view == 'week')
                        @include('calendar.views.week')
                    @elseif($view == 'day')
                        @include('calendar.views.day')
                    @elseif($view == 'list')
                        @include('calendar.views.list')
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Status Legend -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>รายละเอียดสถานะ</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($statusList as $status)
                            @if(!in_array($status['status_id'], [1, 2])) {{-- ซ่อนสถานะ 1 และ 2 --}}
                                <div class="d-flex align-items-center">
                                    <div style="width: 20px; height: 20px; background-color: {{ $status['color'] }}; border-radius: 3px;"></div>
                                    <span class="ms-2">{{ $status['status_name'] }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingDetailsModalLabel">รายละเอียดการจอง</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bookingDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                html: true
            });
        });

        // Handle booking item clicks
        document.querySelectorAll('.booking-item').forEach(item => {
            item.addEventListener('click', function() {
                const bookingId = this.dataset.bookingId;
                if (bookingId) {
                    fetchBookingDetails(bookingId);
                }
            });
        });

        // Function to fetch booking details
        function fetchBookingDetails(bookingId) {
            fetch(`/calendar/bookings/${bookingId}/details`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById('bookingDetailsContent').innerHTML = `
                            <div class="alert alert-danger">${data.error}</div>
                        `;
                    } else {
                        renderBookingDetails(data);
                    }
                    
                    var modal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('bookingDetailsContent').innerHTML = `
                        <div class="alert alert-danger">เกิดข้อผิดพลาดในการโหลดข้อมูล</div>
                    `;
                });
        }

        // Function to render booking details
        function renderBookingDetails(booking) {
            const startTime = new Date(booking.booking_start).toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' });
            const endTime = new Date(booking.booking_end).toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' });
            const bookingDate = new Date(booking.booking_start).toLocaleDateString('th-TH', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });

            let historyHtml = '';
            if (booking.history && booking.history.length > 0) {
                historyHtml = `
                    <div class="mt-4">
                        <h6>ประวัติการเปลี่ยนแปลง</h6>
                        <div class="timeline">
                            ${booking.history.map(item => `
                                <div class="timeline-item">
                                    <div class="timeline-badge" style="background-color: ${item.statusColor}"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between">
                                            <strong>${item.status_name}</strong>
                                            <small class="text-muted">${new Date(item.changed_at).toLocaleString('th-TH')}</small>
                                        </div>
                                        <div class="text-muted">โดย: ${item.changed_by_name}</div>
                                        ${item.note ? `<p class="mt-1">${item.note}</p>` : ''}
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            document.getElementById('bookingDetailsContent').innerHTML = `
                <div class="row">
                    <div class="col-md-8">
                        <h4>${booking.room_name} (${booking.building_name})</h4>
                        <p class="text-muted">${bookingDate}</p>
                        
                        <div class="mb-3">
                            <span class="badge" style="background-color: ${booking.statusColor}; font-size: 1rem;">${booking.status_name}</span>
                        </div>
                        
                        <div class="mb-3">
                            <h6>รายละเอียดการจอง</h6>
                            <p><i class="far fa-clock me-2"></i> ${startTime} - ${endTime}</p>
                            <p><i class="fas fa-user me-2"></i> ผู้จอง: ${booking.user_name || booking.external_name}</p>
                            <p><i class="fas fa-phone me-2"></i> เบอร์ติดต่อ: ${booking.phone || '-'}</p>
                            <p><i class="fas fa-info-circle me-2"></i> เหตุผล: ${booking.reason || '-'}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h6>ข้อมูลเพิ่มเติม</h6>
                                <p><strong>ประเภท:</strong> ${booking.booking_type || '-'}</p>
                                <p><strong>จำนวนคน:</strong> ${booking.attendees || '-'}</p>
                                <p><strong>อุปกรณ์:</strong> ${booking.equipment_needs || '-'}</p>
                                <p><strong>สถานะการชำระเงิน:</strong> ${booking.payment_status ? formatPaymentStatus(booking.payment_status) : '-'}</p>
                                ${booking.total_price ? `<p><strong>ราคา:</strong> ${booking.total_price} บาท</p>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
                ${historyHtml}
                <style>
                    .timeline {
                        position: relative;
                        padding-left: 20px;
                    }
                    .timeline-item {
                        position: relative;
                        padding-bottom: 15px;
                    }
                    .timeline-badge {
                        position: absolute;
                        left: -20px;
                        top: 0;
                        width: 12px;
                        height: 12px;
                        border-radius: 50%;
                    }
                    .timeline-content {
                        padding-left: 10px;
                        border-left: 2px solid #dee2e6;
                    }
                </style>
            `;
        }

        function formatPaymentStatus(status) {
            const statusMap = {
                'pending': '<span class="badge bg-warning">รอชำระเงิน</span>',
                'paid': '<span class="badge bg-success">ชำระเงินแล้ว</span>',
                'cancelled': '<span class="badge bg-danger">ยกเลิก</span>',
                'refunded': '<span class="badge bg-info">คืนเงินแล้ว</span>'
            };
            return statusMap[status] || status;
        }
    });
</script>
@endsection

@section('styles')
<style>
    .calendar-month .table td {
        height: 120px;
        vertical-align: top;
    }
    .calendar-month .day-events {
        max-height: 80px;
        overflow-y: auto;
    }
    .event-item {
        cursor: pointer;
        margin-bottom: 2px;
        padding: 2px 5px;
        border-radius: 3px;
        font-size: 0.8rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: white;
    }
    .calendar-week .table th, 
    .calendar-week .table td {
        height: 60px;
        vertical-align: top;
    }
    .calendar-day .table td {
        height: 80px;
        vertical-align: top;
    }
    .nav-tabs .nav-link.active {
        font-weight: bold;
        border-bottom: 3px solid #0d6efd;
    }
</style>
@endsection