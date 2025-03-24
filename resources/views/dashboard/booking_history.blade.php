@extends('layouts.main')

@section('content')
<div>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>ประวัติการจองห้อง</h2>
            <div class="d-flex align-items-center">
                <form action="{{ route('booking_history') }}" method="GET" class="d-flex">
                    <input class="search-bar me-2" placeholder="ค้นหาประวัติการจอง" type="text" name="search" value="{{ request('search') }}"/>
                    <button type="submit" class="icon-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="fas fa-book icon"></i>
                    <div class="details">
                        <h3>{{ $totalBookings }}</h3>
                        <p>จำนวนการจองทั้งหมด</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="fas fa-check-circle icon"></i>
                    <div class="details">
                        <h3>{{ $completedBookings }}</h3>
                        <p>การจองที่เสร็จสิ้น</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="fas fa-times-circle icon"></i>
                    <div class="details">
                        <h3>{{ $cancelledBookings }}</h3>
                        <p>การจองที่ยกเลิก</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-history me-2"></i> รายการประวัติการจอง
                        </h5>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-outline-primary btn-sm me-2" id="openCalendar">
                                <i class="fas fa-calendar-alt"></i> เลือกวันที่
                            </button>
                            <input type="text" id="datepicker" class="form-control" style="max-width: 150px; opacity: 0; position: absolute;">
                            <a href="{{ route('booking_db') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-calendar-day"></i> การจองปัจจุบัน
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>รหัสการจอง</th>
                                        <th>ผู้จองห้อง</th>
                                        <th>เบอร์โทรศัพท์</th>
                                        <th>วันเวลาที่จอง</th>
                                        <th>วันเวลาที่สิ้นสุดจอง</th>
                                        <th>สถานะการชำระเงิน</th>
                                        <th class="text-center">สถานะการจอง</th>
                                        <th class="text-center">การดำเนินการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($bookings as $booking)
                                    <tr>
                                        <td class="text-center">{{ (($bookings->currentPage() - 1) * $bookings->perPage()) + $loop->iteration }}</td>
                                        <td><span class="badge bg-light text-dark">{{ $booking->id }}</span></td>
                                        <td>
                                            <div class="fw-bold">{{ $booking->external_name }}</div>
                                            <small class="text-muted">{{ $booking->external_email }}</small>
                                        </td>
                                        <td>{{ $booking->external_phone }}</td>
                                        <td>
                                            <div><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($booking->booking_start)->format('d/m/Y') }}</div>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i> 
                                                {{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($booking->booking_end)->format('d/m/Y') }}</div>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i> 
                                                {{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $booking->payment_status == 'ชำระแล้ว' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $booking->payment_status }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge 
                                                @if($booking->status_id == 4) bg-success 
                                                @elseif($booking->status_id == 5) bg-danger 
                                                @elseif($booking->status_id == 6) bg-secondary 
                                                @else bg-info @endif">
                                                {{ $booking->status_name }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-outline-info btn-sm view-details" 
                                                   data-bs-toggle="modal" data-bs-target="#detailsModal{{ $booking->id }}">
                                                    <i class="fas fa-eye"></i> รายละเอียด
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal for Booking Details -->
                                    <div class="modal fade" id="detailsModal{{ $booking->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $booking->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title" id="detailsModalLabel{{ $booking->id }}">
                                                        <i class="fas fa-info-circle me-2"></i> รายละเอียดการจองห้อง
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6 class="fw-bold text-primary mb-3">ข้อมูลการจอง</h6>
                                                            <div class="mb-2">
                                                                <strong>รหัสการจอง:</strong> {{ $booking->id }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>วันที่จอง:</strong> {{ \Carbon\Carbon::parse($booking->booking_start)->format('d/m/Y') }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>เวลา:</strong> 
                                                                {{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} - 
                                                                {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>วัตถุประสงค์:</strong> {{ $booking->reason ?? 'ไม่ระบุ' }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>สถานะการชำระเงิน:</strong> 
                                                                <span class="badge {{ $booking->payment_status == 'ชำระแล้ว' ? 'bg-success' : 'bg-warning' }}">
                                                                    {{ $booking->payment_status }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6 class="fw-bold text-primary mb-3">ข้อมูลผู้จอง</h6>
                                                            <div class="mb-2">
                                                                <strong>ชื่อผู้จอง:</strong> {{ $booking->external_name }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>อีเมล:</strong> {{ $booking->external_email }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>โทรศัพท์:</strong> {{ $booking->external_phone }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h6 class="fw-bold text-primary mb-3">ข้อมูลห้อง</h6>
                                                            <div class="mb-2">
                                                                <strong>อาคาร:</strong> {{ $booking->building_name }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>ห้อง:</strong> {{ $booking->room_name }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            <i class="fas fa-calendar-times fa-2x mb-3"></i>
                                            <p>ไม่พบข้อมูลประวัติการจอง</p>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $bookings->appends(['search' => request('search'), 'booking_date' => request('booking_date')])->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
@endsection

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- สคริปต์หลัก -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var datepicker = document.getElementById("datepicker");

        var calendar = flatpickr(datepicker, {
            dateFormat: "Y-m-d",
            defaultDate: "{{ request('booking_date') }}",
            onChange: function (selectedDates, dateStr, instance) {
                if (dateStr) {
                    window.location.href = `{{ route('booking_history') }}?booking_date=${dateStr}`;
                }
            }
        });

        document.getElementById("openCalendar").addEventListener("click", function () {
            calendar.open(); // เปิด Flatpickr ทันทีเมื่อกดปุ่ม
        });
    });
</script>