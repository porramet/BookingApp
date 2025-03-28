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
                        <td class="{{ $day['today'] ? 'bg-light' : '' }} {{ $day['currentMonth'] ? '' : 'text-muted' }}">
                            <div class="d-flex justify-content-between">
                                <span>{{ $day['day'] }}</span>
                                @php
                                    $visibleBookings = array_filter($day['bookings'], function($booking) {
                                        return !in_array($booking->status_id, [1, 2]); // ซ่อนสถานะ 1 และ 2
                                    });
                                @endphp
                                @if(count($visibleBookings) > 0)
                                    <span class="badge bg-primary rounded-pill">{{ count($visibleBookings) }}</span>
                                @endif
                            </div>
                            <div class="day-events mt-1">
                                @foreach($visibleBookings as $index => $booking)
                                    @if($index < 3)
                                        <div class="event-item booking-item mb-1 p-1 rounded" 
                                             style="background-color: {{ $booking->statusColor }}"
                                             data-booking-id="{{ $booking->id }}"
                                             data-bs-toggle="tooltip"
                                             data-bs-html="true"
                                             title="<strong>{{ $booking->room_name }}</strong><br>
                                                    {{ Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} - {{ Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}<br>
                                                    ผู้จอง: xxx">
                                            <small class="text-white">
                                                {{ Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} 
                                                {{ Str::limit($booking->room_name, 15) }}
                                            </small>
                                        </div>
                                    @endif
                                @endforeach
                                @if(count($visibleBookings) > 3)
                                    <div class="text-center">
                                        <a href="{{ route('calendar.index', ['view' => 'day', 'date' => $day['date']]) }}" class="small">
                                            + {{ count($visibleBookings) - 3 }} รายการเพิ่มเติม
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