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
        <div class="col-md-6">
            <h2>ปฏิทินการจองห้อง</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="btn-group">
                <a href="{{ route('calendar.index', ['date' => $prevMonth]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <button class="btn btn-outline-secondary" id="current-month">{{ $currentMonth }}</button>
                <a href="{{ route('calendar.index', ['date' => $nextMonth]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            <a href="{{ route('calendar.index', ['date' => now()->format('Y-m-d')]) }}" class="btn btn-primary ms-2">วันนี้</a>
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
                        <div class="calendar-month">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>อาทิตย์</th>
                                        <th>จันทร์</th>
                                        <th>อังคาร</th>
                                        <th>พุธ</th>
                                        <th>พฤหัสบดี</th>
                                        <th>ศุกร์</th>
                                        <th>เสาร์</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($calendarData as $week)
                                        <tr>
                                            @foreach($week as $day)
                                                <td class="{{ $day['today'] ? 'bg-light' : '' }} {{ $day['currentMonth'] ? '' : 'text-muted' }}" style="height: 120px; width: 14.28%; vertical-align: top;">
                                                    <div class="d-flex justify-content-between">
                                                        <span>{{ $day['day'] }}</span>
                                                        @if(count($day['bookings']) > 0)
                                                            <span class="badge bg-primary">{{ count($day['bookings']) }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="day-events">
                                                        @foreach($day['bookings'] as $index => $booking)
                                                            @if($index < 3)
                                                                <div class="event-item mt-1" 
                                                                     style="background-color: {{ $booking->statusColor }}; padding: 2px 5px; border-radius: 3px; font-size: 0.8rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                                                     data-bs-toggle="tooltip" 
                                                                     data-bs-html="true"
                                                                     title="<strong>{{ $booking->room_name }}</strong><br>
                                                                            เวลา: {{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}<br>
                                                                            ผู้จอง: {{ $booking->user_name ?? $booking->external_name }}<br>
                                                                            สถานะ: {{ $booking->status_name }}<br>
                                                                            เหตุผล: {{ $booking->reason }}">
                                                                    {{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} {{ $booking->room_name }}
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        @if(count($day['bookings']) > 3)
                                                            <div class="more-events mt-1" style="font-size: 0.8rem;">
                                                                <a href="{{ route('calendar.index', ['view' => 'day', 'date' => $day['date']]) }}">
                                                                    +{{ count($day['bookings']) - 3 }} รายการเพิ่มเติม
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif($view == 'week')
                        <div class="calendar-week">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="60">เวลา</th>
                                        @foreach($weekDays as $day)
                                            <th class="{{ $day['today'] ? 'bg-light' : '' }}">
                                                {{ $day['dayName'] }}<br>{{ $day['date'] }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($timeSlots as $time)
                                        <tr>
                                            <td>{{ $time }}</td>
                                            @foreach($weekDays as $day)
                                                <td>
                                                    @foreach($bookingsByDay[$day['date']][$time] ?? [] as $booking)
                                                        <div class="event-item" 
                                                             style="background-color: {{ $booking->statusColor }}; padding: 2px 5px; border-radius: 3px; font-size: 0.8rem; margin-bottom: 2px;"
                                                             data-bs-toggle="tooltip" 
                                                             data-bs-html="true"
                                                             title="<strong>{{ $booking->room_name }}</strong><br>
                                                                    เวลา: {{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}<br>
                                                                    ผู้จอง: {{ $booking->user_name ?? $booking->external_name }}<br>
                                                                    สถานะ: {{ $booking->status_name }}<br>
                                                                    เหตุผล: {{ $booking->reason }}">
                                                            {{ $booking->room_name }}
                                                        </div>
                                                    @endforeach
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif($view == 'day')
                        <div class="calendar-day">
                            <h4>{{ $dayViewDate }}</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="60">เวลา</th>
                                        <th>การจอง</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($timeSlots as $time)
                                        <tr>
                                            <td>{{ $time }}</td>
                                            <td>
                                                @foreach($bookingsByTime[$time] ?? [] as $booking)
                                                    <div class="event-item" 
                                                         style="background-color: {{ $booking->statusColor }}; padding: 5px; border-radius: 3px; margin-bottom: 5px;"
                                                         data-bs-toggle="tooltip" 
                                                         data-bs-html="true"
                                                         title="<strong>{{ $booking->room_name }}</strong><br>
                                                                เวลา: {{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}<br>
                                                                ผู้จอง: {{ $booking->user_name ?? $booking->external_name }}<br>
                                                                สถานะ: {{ $booking->status_name }}<br>
                                                                เหตุผล: {{ $booking->reason }}">
                                                        <div class="d-flex justify-content-between">
                                                            <span><strong>{{ $booking->room_name }}</strong> ({{ $booking->building_name }})</span>
                                                            <span class="badge bg-secondary">{{ $booking->status_name }}</span>
                                                        </div>
                                                        <div>
                                                            <small>
                                                                {{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} - 
                                                                {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}
                                                            </small>
                                                        </div>
                                                        <div>
                                                            <small>ผู้จอง: {{ $booking->user_name ?? $booking->external_name }}</small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif($view == 'list')
                        <div class="calendar-list">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>วันที่</th>
                                            <th>เวลา</th>
                                            <th>ห้อง</th>
                                            <th>อาคาร</th>
                                            <th>ผู้จอง</th>
                                            <th>สถานะ</th>
                                            <th>เหตุผล</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($listBookings as $booking)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($booking->booking_start)->format('d/m/Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}</td>
                                                <td>{{ $booking->room_name }}</td>
                                                <td>{{ $booking->building_name }}</td>
                                                <td>{{ $booking->user_name ?? $booking->external_name }}</td>
                                                <td><span class="badge" style="background-color: {{ $booking->statusColor }}">{{ $booking->status_name }}</span></td>
                                                <td>{{ $booking->reason }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>รายละเอียดสถานะ</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($statusList as $status)
                            <div class="d-flex align-items-center">
                                <div style="width: 20px; height: 20px; background-color: {{ $status->color }}; border-radius: 3px;"></div>
                                <span class="ms-2">{{ $status->status_name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingDetailsModalLabel">รายละเอียดการจอง</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bookingDetailsContent">
                <!-- Modal content will be loaded here -->
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
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Event listener for clicking on booking items
        document.querySelectorAll('.event-item').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                var bookingId = this.getAttribute('data-booking-id');
                if (bookingId) {
                    showBookingDetails(bookingId);
                }
            });
        });

        // Function to show booking details in modal
        function showBookingDetails(bookingId) {
            fetch(`/bookings/${bookingId}/details`)
                .then(response => response.json())
                .then(data => {
                    var content = `
                        <div class="booking-details">
                            <h5>${data.room_name} (${data.building_name})</h5>
                            <p><strong>วันที่:</strong> ${new Date(data.booking_start).toLocaleDateString('th-TH')}</p>
                            <p><strong>เวลา:</strong> ${new Date(data.booking_start).toLocaleTimeString('th-TH', {hour: '2-digit', minute:'2-digit'})} - 
                                                    ${new Date(data.booking_end).toLocaleTimeString('th-TH', {hour: '2-digit', minute:'2-digit'})}</p>
                            <p><strong>ผู้จอง:</strong> ${data.user_name || data.external_name}</p>
                            <p><strong>สถานะ:</strong> <span class="badge" style="background-color: ${data.statusColor}">${data.status_name}</span></p>
                            <p><strong>เหตุผล:</strong> ${data.reason || '-'}</p>
                            <p><strong>ราคา:</strong> ${data.total_price ? data.total_price + ' บาท' : '-'}</p>
                            <p><strong>สถานะการชำระเงิน:</strong> ${getPaymentStatusText(data.payment_status)}</p>
                            
                            <h6 class="mt-3">ประวัติการจอง</h6>
                            <ul class="list-group">
                                ${data.history.map(item => `
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <span>${item.status_name}</span>
                                            <small>${new Date(item.changed_at).toLocaleString('th-TH')}</small>
                                        </div>
                                        <div><small>${item.note || '-'}</small></div>
                                    </li>
                                `).join('')}
                            </ul>
                        </div>
                    `;
                    
                    document.getElementById('bookingDetailsContent').innerHTML = content;
                    var modal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching booking details:', error);
                });
        }

        function getPaymentStatusText(status) {
            switch (status) {
                case 'pending': return 'รอชำระเงิน';
                case 'paid': return 'ชำระเงินแล้ว';
                case 'cancelled': return 'ยกเลิก';
                default: return status;
            }
        }
    });
</script>
@endsection

