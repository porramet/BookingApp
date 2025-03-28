<div class="calendar-day">
    <h4 class="mb-3">{{ $dayViewDate }}</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="80">เวลา</th>
                <th>รายการจอง</th>
            </tr>
        </thead>
        <tbody>
            @foreach($timeSlots as $time)
                <tr>
                    <td class="text-center">{{ $time }}</td>
                    <td>
                        @foreach($bookingsByTime[$time] ?? [] as $booking)
                            @if(!in_array($booking->status_id, [1, 2])) {{-- ซ่อนสถานะ 1 และ 2 --}}
                                <div class="event-item booking-item mb-2 p-2 rounded" 
                                     style="background-color: {{ $booking->statusColor }}"
                                     data-booking-id="{{ $booking->id }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="text-white">{{ $booking->room_name }}</strong>
                                            <span class="text-white-50 ms-2">{{ $booking->building_name }}</span>
                                        </div>
                                        <span class="badge bg-light text-dark">{{ $booking->status_name }}</span>
                                    </div>
                                    <div class="text-white-50 mt-1">
                                        <i class="far fa-clock"></i> 
                                        {{ Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} - 
                                        {{ Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}
                                    </div>
                                    <div class="text-white mt-1">
                                        <i class="fas fa-user"></i> xxx
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>