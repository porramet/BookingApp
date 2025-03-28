<div class="calendar-week">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="80">เวลา</th>
                @foreach($weekDays as $day)
                    <th class="{{ $day['today'] ? 'bg-light' : '' }}">
                        <div>{{ $day['dayName'] }}</div>
                        <div class="small">{{ Carbon\Carbon::parse($day['date'])->format('d/m') }}</div>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($timeSlots as $time)
                <tr>
                    <td class="text-center">{{ $time }}</td>
                    @foreach($weekDays as $day)
                        <td>
                            @foreach($bookingsByDay[$day['date']][$time] ?? [] as $booking)
                                @if(!in_array($booking->status_id, [1, 2])) {{-- ซ่อนสถานะ 1 และ 2 --}}
                                    <div class="event-item booking-item mb-1 p-1 rounded" 
                                         style="background-color: {{ $booking->statusColor }}"
                                         data-booking-id="{{ $booking->id }}"
                                         data-bs-toggle="tooltip"
                                         data-bs-html="true"
                                         title="<strong>{{ $booking->room_name }}</strong><br>
                                                {{ Carbon\Carbon::parse($booking->booking_start)->format('H:i') }} - {{ Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}<br>
                                                ผู้จอง: xxx">
                                        <small class="text-white">{{ Str::limit($booking->room_name, 10) }}</small>
                                    </div>
                                @endif
                            @endforeach
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>