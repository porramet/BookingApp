@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">ตารางการจองห้อง</h4>
                </div>
                <div class="card-body">
                    <!-- ปุ่มกรองข้อมูล -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="building_id">เลือกอาคาร:</label>
                            <select id="building_id" class="form-control">
                                <option value="">ทั้งหมด</option>
                                @foreach($buildings as $building)
                                    <option value="{{ $building->building_id }}" {{ $building_id == $building->building_id ? 'selected' : '' }}>
                                        {{ $building->building_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="date">เลือกวันที่:</label>
                            <input type="date" id="date" class="form-control" value="{{ $dates[0]['date'] }}">
                        </div>
                    </div>

                    <!-- ตารางการจอง -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ห้อง</th>
                                    @foreach($dates as $date)
                                        <th class="{{ $date['is_holiday'] ? 'bg-danger text-white' : '' }}">
                                            {{ $date['day_th'] }}<br>{{ $date['date'] }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rooms as $room)
                                    <tr>
                                        <td>{{ $room->room_name }}</td>
                                        @foreach($dates as $date)
                                            <td class="{{ $date['is_holiday'] ? 'bg-danger text-white' : '' }}">
                                                @foreach($bookingData[$room->room_id][$date['date']] as $booking)
                                                    <div class="mb-2">
                                                        <strong>{{ $booking['time'] }}</strong><br>
                                                        {{ $booking['description'] }}<br>
                                                        <span class="badge bg-{{ $booking['status_id'] == 1 ? 'success' : ($booking['status_id'] == 2 ? 'warning' : 'primary') }}">
                                                            {{ $booking['status'] }}
                                                        </span>
                                                    </div>
                                                @endforeach
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
@endsection

@section('scripts')
<script>
    // กรองข้อมูลตารางเมื่อเลือกอาคารหรือวันที่
    $('#building_id, #date').change(function() {
        var building_id = $('#building_id').val();
        var date = $('#date').val();
        window.location.href = "{{ route('calendar.table') }}?building_id=" + building_id + "&date=" + date;
    });
</script>
@endsection