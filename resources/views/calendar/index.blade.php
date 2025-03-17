@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3>{{ $currentMonth ?? 'กุมภาพันธ์ 2025' }}</h3>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="btn-group">
                        <a href="{{ route('calendar.index', ['month' => 'prev']) }}" class="btn btn-primary"><< ก่อนหน้า</a>
                        <a href="{{ route('calendar.index') }}" class="btn btn-warning">วันนี้</a>
                        <a href="{{ route('calendar.index', ['month' => 'next']) }}" class="btn btn-primary">ถัดไป >></a>
                        <a href="{{ route('calendar.index', ['view' => 'month']) }}" class="btn btn-warning">เดือน</a>
                        <a href="{{ route('calendar.index', ['view' => 'week']) }}" class="btn btn-warning">สัปดาห์</a>
                        <a href="{{ route('calendar.index', ['view' => 'day']) }}" class="btn btn-warning">วัน</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="calendarTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendar" type="button" role="tab" aria-controls="calendar" aria-selected="true">
                        <i class="fas fa-calendar"></i> ปฏิทิน
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="table-tab" data-bs-toggle="tab" data-bs-target="#table" type="button" role="tab" aria-controls="table" aria-selected="false">
                        <i class="fas fa-table"></i> ตารางห้อง
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="calendarTabsContent">
                <!-- Calendar Tab -->
                <div class="tab-pane fade show active" id="calendar" role="tabpanel" aria-labelledby="calendar-tab">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="building_filter">เลือกอาคาร:</label>
                                <select id="building_filter" class="form-select" onchange="filterByBuilding(this.value)">
                                    <option value="">ทั้งหมด</option>
                                    @foreach($buildings as $building)
                                        <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                            {{ $building->building_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="room_filter">เลือกห้อง:</label>
                                <select id="room_filter" class="form-select" onchange="filterByRoom(this.value)">
                                    <option value="">ทั้งหมด</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->room_id }}" {{ request('room_id') == $room->room_id ? 'selected' : '' }}>
                                            {{ $room->room_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="calendar-container">
                        <table class="table table-bordered calendar-table">
                            <thead>
                                <tr class="bg-light">
                                    <th width="14%" class="text-center">วันอาทิตย์</th>
                                    <th width="14%" class="text-center">วันจันทร์</th>
                                    <th width="14%" class="text-center">วันอังคาร</th>
                                    <th width="14%" class="text-center">วันพุธ</th>
                                    <th width="14%" class="text-center">วันพฤหัสบดี</th>
                                    <th width="14%" class="text-center">วันศุกร์</th>
                                    <th width="14%" class="text-center">วันเสาร์</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Calendar rows will be generated dynamically -->
                                @php
                                    $currentDate = new DateTime('first day of this month');
                                    $lastDay = new DateTime('last day of this month');
                                    $startWeekday = $currentDate->format('w');
                                    $totalDays = $lastDay->format('d');
                                    $currentDay = 1;
                                    $currentWeek = 0;
                                @endphp
                                
                                @while ($currentDay <= $totalDays)
                                    @if ($currentWeek === 0)
                                        <tr class="calendar-row">
                                        @for ($i = 0; $i < $startWeekday; $i++)
                                            <td class="calendar-day empty"></td>
                                        @endfor
                                        
                                        @for ($i = $startWeekday; $i < 7; $i++)
                                            <td class="calendar-day">
                                                <div class="day-number">{{ $currentDay }}</div>
                                                <div class="day-content">
                                                    @foreach($calendarData as $event)
                                                        @if(date('Y-m-d', strtotime($event['start'])) === date('Y-m-', strtotime($currentDate->format('Y-m-d'))) . str_pad($currentDay, 2, '0', STR_PAD_LEFT))
                                                            <div class="booking-event" style="background-color: {{ $event['color'] }}">
                                                                <small>{{ $event['title'] }}</small>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </td>
                                            @php $currentDay++; @endphp
                                        @endfor
                                        </tr>
                                        @php $currentWeek++; @endphp
                                    @else
                                        <tr class="calendar-row">
                                        @for ($i = 0; $i < 7 && $currentDay <= $totalDays; $i++)
                                            <td class="calendar-day">
                                                <div class="day-number">{{ $currentDay }}</div>
                                                <div class="day-content">
                                                    @foreach($calendarData as $event)
                                                        @if(date('Y-m-d', strtotime($event['start'])) === date('Y-m-', strtotime($currentDate->format('Y-m-d'))) . str_pad($currentDay, 2, '0', STR_PAD_LEFT))
                                                            <div class="booking-event" style="background-color: {{ $event['color'] }}">
                                                                <small>{{ $event['title'] }}</small>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </td>
                                            @php $currentDay++; @endphp
                                        @endfor
                                        
                                        @for ($i; $i < 7; $i++)
                                            <td class="calendar-day empty"></td>
                                        @endfor
                                        </tr>
                                        @php $currentWeek++; @endphp
                                    @endif
                                @endwhile
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Table Tab -->
                <div class="tab-pane fade" id="table" role="tabpanel" aria-labelledby="table-tab">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="building_table_filter">เลือกสถานะ:</label>
                                <select id="building_table_filter" class="form-select" onchange="filterTableByBuilding(this.value)">
                                    <option value="">อาคาร 11</option>
                                    @foreach($buildings as $building)
                                        <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                            {{ $building->building_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8 text-end">
                            <div class="btn-group">
                                <a href="{{ route('calendar.table', ['date' => 'prev']) }}" class="btn btn-primary"><< ก่อนหน้า</a>
                                <a href="{{ route('calendar.table') }}" class="btn btn-warning">วันนี้</a>
                                <a href="{{ route('calendar.table', ['date' => 'next']) }}" class="btn btn-primary">ถัดไป >></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-light">
                                    <th>ห้อง</th>
                                    @foreach($dates ?? [] as $date)
                                        <th class="text-center {{ isset($date['is_holiday']) && $date['is_holiday'] ? 'bg-danger text-white' : '' }}">
                                            {{ $date['day_th'] }} {{ \Carbon\Carbon::parse($date['date'])->format('d') }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rooms as $room)
                                    <tr>
                                        <td>{{ $room->room_name }}</td>
                                        @foreach($dates ?? [] as $date)
                                            <td class="position-relative">
                                                @if(isset($bookingData[$room->room_id][$date['date']]))
                                                    @foreach($bookingData[$room->room_id][$date['date']] as $booking)
                                                        <div class="booking-slot" style="background-color: 
                                                            @switch($booking['status_id'])
                                                                @case(1) #28a745 @break
                                                                @case(2) #ffc107 @break
                                                                @case(3) #007bff @break
                                                                @default #6c757d
                                                            @endswitch
                                                        ">
                                                            <strong>{{ $booking['time'] }}</strong><br>
                                                            {{ $booking['description'] }}
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize calendar with the data
    const calendarData = @json($calendarData);
    
    // Filter functions
    window.filterByBuilding = function(buildingId) {
        window.location.href = "{{ route('calendar.index') }}?building_id=" + buildingId;
    };
    
    window.filterByRoom = function(roomId) {
        const buildingId = document.getElementById('building_filter').value;
        window.location.href = "{{ route('calendar.index') }}?building_id=" + buildingId + "&room_id=" + roomId;
    };
    
    window.filterTableByBuilding = function(buildingId) {
        window.location.href = "{{ route('calendar.table') }}?building_id=" + buildingId;
    };
});
</script>

<style>
.calendar-day {
    height: 120px;
    vertical-align: top;
    padding: 5px;
}

.calendar-day .day-number {
    font-weight: bold;
    color: #666;
    text-align: right;
    margin-bottom: 5px;
}

.calendar-day.empty {
    background-color: #f8f9fa;
}

.booking-event {
    padding: 2px 4px;
    border-radius: 3px;
    margin-bottom: 2px;
    color: white;
    font-size: 0.85em;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.booking-slot {
    padding: 4px;
    border-radius: 3px;
    margin-bottom: 4px;
    color: white;
    font-size: 0.85em;
}

.holiday {
    background-color: #ffeded !important;
}
</style>
@endsection