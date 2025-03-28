@extends('layouts.main')

@section('content')
<div>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
         <h2>
          จัดการการจองห้อง
         </h2>
         <div class="d-flex align-items-center">
            <form action="{{ route('booking_db') }}" method="GET" class="d-flex">
                <input class="search-bar me-2" placeholder="ค้นหาการจอง" type="text" name="search" value="{{ request('search') }}"/>
                <button type="submit" class="icon-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
         </div>
        </div>
        <div class="row mb-4">
         <div class="col-md-4">
          <div class="stat-card">
           <i class="fas fa-book icon">
           </i>
           <div class="details">
            <h3>
             {{ $totalBookings }}
            </h3>
            <p>
             จำนวนการจองทั้งหมด
            </p>
           </div>
          </div>
         </div>
         <div class="col-md-4">
            <div class="stat-card">
             <i class="fas fa-clock icon">
             </i>
             <div class="details">
              <h3>
               {{ $pendingBookings }}
              </h3>
              <p>
               จำนวนการจองที่รอดำเนินการ
              </p>
             </div>
            </div>
           </div>
         <div class="col-md-4">
          <div class="stat-card">
           <i class="fas fa-check-circle icon">
           </i>
           <div class="details">
            <h3>
             {{ $confirmedBookings }}
            </h3>
            <p>
             จำนวนการจองที่อุนมัติแล้ว
            </p>
           </div>
          </div>
         </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-list me-2"></i> รายการการจอง
                        </h5>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-outline-primary btn-sm" id="openCalendar">
                            <i class="fas fa-calendar-alt"></i> เลือกวันที่
                        </button>
                        <input type="text" id="datepicker" class="form-control" style="max-width: 150px; opacity: 0; position: absolute;">
                        <a href="{{ route('booking_history') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-history"></i> ประวัติการจอง
                        </a>
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
                                        <th class="text-center">สถานะการอนุมัติ</th>
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
                                            <div class="payment-status-container">
                                                @if($booking->payment_slip)
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge 
                                                            @if($booking->payment_status == 'paid') bg-success
                                                            @elseif($booking->payment_status == 'partial') bg-warning
                                                            @elseif($booking->payment_status == 'cancelled') bg-danger
                                                            @else bg-secondary @endif 
                                                            me-2">
                                                            {{ match($booking->payment_status) {
                                                                'paid' => 'ชำระครบถ้วน',
                                                                'partial' => 'ชำระบางส่วน',
                                                                'cancelled' => 'ยกเลิกการชำระ',
                                                                default => 'รอการชำระ'
                                                            } }}
                                                        </span>
                                                        
                                                        <div class="btn-group" role="group">
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-outline-primary" 
                                                                    data-bs-toggle="dropdown" 
                                                                    aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <button class="dropdown-item" 
                                                                            data-bs-toggle="modal" 
                                                                            data-bs-target="#paymentSlipModal{{ $booking->id }}">
                                                                        <i class="fas fa-file-invoice me-2"></i>ดูสลิปการชำระ
                                                                    </button>
                                                                </li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <form action="{{ route('booking.confirm-payment', $booking->id) }}" method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="payment_status" value="paid">
                                                                        <button type="submit" class="dropdown-item text-success">
                                                                            <i class="fas fa-check-circle me-2"></i>ชำระครบถ้วน
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                                <li>
                                                                    <form action="{{ route('booking.confirm-payment', $booking->id) }}" method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="payment_status" value="partial">
                                                                        <button type="submit" class="dropdown-item text-warning">
                                                                            <i class="fas fa-exclamation-circle me-2"></i>ชำระบางส่วน
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                                <li>
                                                                    <form action="{{ route('booking.confirm-payment', $booking->id) }}" method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="payment_status" value="cancelled">
                                                                        <button type="submit" class="dropdown-item text-danger">
                                                                            <i class="fas fa-times-circle me-2"></i>ยกเลิกการชำระ
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        ยังไม่ได้ชำระเงิน
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge 
                                                @if($booking->status_id == 1) bg-info 
                                                @elseif($booking->status_id == 2) bg-warning 
                                                @elseif($booking->status_id == 3) bg-danger 
                                                @elseif($booking->status_id == 4) bg-success 
                                                @else bg-secondary @endif"
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                title="อนุมัติโดย: {{ $booking->approver_name ?? 'ยังไม่มีผู้อนุมัติ' }}">
                                                {{ $booking->status_name }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-outline-info btn-sm view-details" 
                                                   data-bs-toggle="modal" data-bs-target="#detailsModal{{ $booking->id }}">
                                                    <i class="fas fa-eye"></i> รายละเอียด
                                                </a>
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-edit"></i> เปลี่ยนสถานะ
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <form action="{{ route('booking.update-status', $booking->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status_id" value="4">
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="fas fa-check-circle"></i> อนุมัติการจอง
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('booking.update-status', $booking->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status_id" value="5">
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-times-circle"></i> ยกเลิกการจอง
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('booking.update-status', $booking->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status_id" value="3">
                                                            <button type="submit" class="dropdown-item text-warning">
                                                                <i class="fas fa-clock"></i> รอดำเนินการ
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('booking.update-status', $booking->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status_id" value="6">
                                                            <button type="submit" class="dropdown-item text-secondary">
                                                                <i class="fas fa-check-double"></i> ดำเนินการเสร็จสิ้น
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal สำหรับแสดงสลิปการชำระเงิน -->
                                    <div class="modal fade" id="paymentSlipModal{{ $booking->id }}" tabindex="-1"
                                        aria-labelledby="paymentSlipModalLabel{{ $booking->id }}" 
                                        aria-modal="true" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-light">
                                                <h2 class="modal-title h5" id="paymentSlipModalLabel{{ $booking->id }}">
                                                    <i class="fas fa-file-invoice me-2"></i>สลิปการชำระเงิน
                                                </h2>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                                                        aria-label="ปิดหน้าต่างสลิปการชำระเงิน"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <div class="payment-slip-container">
                                                    @if($booking->payment_slip && Storage::disk('public')->exists('payment_slips/'.basename($booking->payment_slip)))
                                                        <div style="max-width: 100%; overflow: auto; margin: 0 auto;">
                                                            <img src="{{ Storage::url($booking->payment_slip) }}" 
                                                                    alt="สลิปการชำระเงินสำหรับการจองหมายเลข {{ $booking->id }}"
                                                                    class="img-fluid rounded shadow-sm mb-3"
                                                                    style="max-height: 60vh; width: auto; display: block; margin: 0 auto;">
                                                        </div>
                                                    @else
                                                        <div class="alert alert-warning" role="alert">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>ไม่พบสลิปการชำระเงิน
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="payment-details bg-light p-3 rounded">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong>เลขที่การจอง:</strong> 
                                                                <span class="badge bg-secondary">{{ $booking->id }}</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>วันที่ชำระ:</strong> 
                                                                {{ \Carbon\Carbon::parse($booking->updated_at)->format('d/m/Y H:i') }}
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-6">
                                                                <strong>ยอดชำระ:</strong> 
                                                                {{ number_format($booking->total_price, 2) }} บาท
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>สถานะ:</strong> 
                                                                <span class="badge 
                                                                    @if($booking->payment_status == 'paid') bg-success
                                                                    @elseif($booking->payment_status == 'partial') bg-warning
                                                                    @elseif($booking->payment_status == 'cancelled') bg-danger
                                                                    @else bg-secondary @endif">
                                                                    {{ match($booking->payment_status) {
                                                                        'paid' => 'ชำระครบถ้วน',
                                                                        'partial' => 'ชำระบางส่วน',
                                                                        'cancelled' => 'ยกเลิกการชำระ',
                                                                        default => 'รอการชำระ'
                                                                    } }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-2"></i>ปิด
                                                </button>
                                                @if($booking->payment_slip && Storage::disk('public')->exists('payment_slips/'.basename($booking->payment_slip)))
                                                    <a href="{{ Storage::url($booking->payment_slip) }}" 
                                                        target="_blank" 
                                                        class="btn btn-primary" 
                                                        download="สลิปการชำระเงิน_{{ $booking->id }}.jpg">
                                                        <i class="fas fa-download me-2"></i>ดาวน์โหลด
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    </div>

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
                                                                <strong>วันที่สิ้นสุดการจอง:</strong> {{ \Carbon\Carbon::parse($booking->booking_end)->format('d/m/Y') }}
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
                                                                <strong>จำนวนผู้เข้าร่วม:</strong> {{ $booking->attendees ?? 'ไม่ระบุ' }} คน
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
                                                            <div class="mb-2">
                                                                <strong>หน่วยงาน/แผนก:</strong> {{ $booking->department ?? 'ไม่ระบุ' }}
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
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            <i class="fas fa-calendar-times fa-2x mb-3"></i>
                                            <p>ไม่พบข้อมูลการจอง</p>
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
        var modal = document.getElementById('paymentSlipModal{{ $booking->id }}');
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        var calendar = flatpickr(datepicker, {
            dateFormat: "Y-m-d",
            defaultDate: "{{ request('booking_date') }}",
            onChange: function (selectedDates, dateStr, instance) {
                if (dateStr) {
                    window.location.href = `{{ route('booking_db') }}?booking_date=${dateStr}`;
                }
            }
        });

        document.getElementById("openCalendar").addEventListener("click", function () {
            calendar.open(); // เปิด Flatpickr ทันทีเมื่อกดปุ่ม
        });
    });
    // เมื่อ Modal เปิด
    modal.addEventListener('shown.bs.modal', function () {
        // ซ่อนเนื้อหาหลักจากโปรแกรมอ่านหน้าจอ
        document.querySelectorAll('body > *:not([aria-hidden="true"]):not(.modal-backdrop)').forEach(function(el) {
            if (el !== modal) {
                el.setAttribute('aria-hidden', 'true');
            }
        });
        
        // โฟกัสไปที่ปุ่มปิด
        this.querySelector('[data-bs-dismiss="modal"]').focus();
    });
    
    // เมื่อ Modal ปิด
    modal.addEventListener('hidden.bs.modal', function () {
        // คืนสถานะ aria-hidden ของเนื้อหาหลัก
        document.querySelectorAll('body > *[aria-hidden="true"]').forEach(function(el) {
            el.removeAttribute('aria-hidden');
        });
    });
</script>
