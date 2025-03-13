@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9 content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">ระบบจองห้องมหาวิทยาลัยราชภัฏสกลนคร</h2>
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative">
                        <input type="text" placeholder="ค้นหาห้อง..." class="form-control ps-4">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-2 text-gray-400"></i>
                    </div>
                    <button class="btn btn-light position-relative">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                    </button>
                    <button class="btn btn-light">
                        <i class="fas fa-cog"></i>
                    </button>
                    <img src="/api/placeholder/40/40" class="rounded-circle" alt="Profile">
                </div>
            </div>

            <div class="card bg-warning text-white mb-4">
                <div class="card-body text-center">
                    <h3 class="card-title">ส่วนลดพิเศษ</h3>
                    <h2 class="fw-bold">รับส่วนลด 20% สำหรับการจองครั้งแรก</h2>
                    <p>ใช้โค้ด <strong>NEWUSER20</strong> เมื่อทำการจอง</p>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold">ประเภทห้อง</h3>
                <a href="{{ route('rooms.index') }}" class="text-warning fw-bold">ดูทั้งหมด</a>
            </div>
            <div class="row g-3">
                @php
                    // Get unique room types from the rooms collection
                    $roomTypes = $rooms->pluck('class')->unique();
                @endphp
                
                @foreach($roomTypes as $roomType)
                @php
                    $icon = match($roomType) {
                        'ห้องประชุม' => 'users',
                        'หอประชุม' => 'landmark',
                        'ห้องสัมมนา' => 'book',
                        'ห้องคอม' => 'laptop',
                        'ห้องเรียน' => 'chalkboard-teacher',
                        default => 'building',
                    };
                @endphp
                <div class="col-md-2">
                    <a href="{{ route('rooms.byType', ['type' => $roomType]) }}" class="text-decoration-none">
                        <div class="card text-center py-3 shadow-sm">
                            <div class="card-body">
                                <i class="fas fa-{{ $icon }} text-warning display-6 mb-2"></i>
                                <p class="mb-0 fw-semibold">{{ $roomType }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h3 class="fw-bold">อาคาร</h3>
                <a href="{{ route('buildings.index') }}" class="text-warning fw-bold">ดูทั้งหมด</a>
            </div>
            <div class="row g-3">
                @foreach($buildings as $building)
                <div class="col-md-2">
                    <a href="{{ route('rooms.byBuilding', ['building_id' => $building->id]) }}" class="text-decoration-none">
                        <div class="card text-center py-3 shadow-sm">
                            <div class="card-body">
                                <i class="fas fa-building text-warning display-6 mb-2"></i>
                                <p class="mb-0 fw-semibold">{{ $building->building_name }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h3 class="fw-bold">ห้องทั้งหมด</h3>
                <a href="{{ route('rooms.index') }}" class="text-warning fw-bold">ดูทั้งหมด</a>
            </div>
            <div class="row g-4">
                @foreach($rooms->take(6) as $room)
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <img src="{{ $room->image ? asset('storage/'.$room->image) : '/api/placeholder/400/200' }}" class="card-img-top" alt="Room Image">
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $room->room_name }}</h5>
                            <p class="text-muted mb-1">อาคาร {{ $room->building->building_name }} ชั้น {{ $room->class }}</p>
                            <p class="text-muted mb-1">รองรับได้ {{ $room->capacity }} คน</p>
                            <p class="fw-bold text-warning">฿{{ number_format($room->service_rates, 2) }} /ชั่วโมง</p>
                            // Update the booking link
<a href="{{ route('partials.booking.form', ['id' => $room->room_id]) }}" class="btn btn-warning w-100">จองเลย</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h3 class="fw-bold">ห้องยอดนิยม</h3>
                <a href="{{ route('rooms.popular') }}" class="text-warning fw-bold">ดูทั้งหมด</a>
            </div>
            <div class="row g-4">
                @php
                    // You could modify this to get popular rooms from database, for example:
                    // $popularRooms = $rooms->sortByDesc('booking_count')->take(3);
                    $popularRooms = $rooms->take(3);
                @endphp
                @foreach($popularRooms as $room)
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <img src="{{ $room->image ? asset('storage/'.$room->image) : '/api/placeholder/400/200' }}" class="card-img-top" alt="Room Image">
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $room->room_name }}</h5>
                            <p class="text-muted mb-1">อาคาร {{ $room->building->building_name }} ชั้น {{ $room->class }}</p>
                            <p class="text-muted mb-1">รองรับได้ {{ $room->capacity }} คน</p>
                            <p class="fw-bold text-warning">฿{{ number_format($room->service_rates, 2) }} /ชั่วโมง</p>
                            <a href="{{ url('booking/'.$room->room_id) }}" class="btn btn-warning w-100">จองเลย</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection