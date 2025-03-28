<!-- resources/views/booking.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row mb-4">
        
        <div class="card bg-warning text-white mb-4">
            <div class="card-body text-center py-5">
                <h1 class="display-4 fw-bold">ระบบจองห้องออนไลน์</h1>
                <h2>มหาวิทยาลัยราชภัฏสกลนคร</h2>
                <p class="lead mt-3">บริการจองห้องเรียน ห้องประชุม และสถานที่จัดกิจกรรมต่างๆ แบบออนไลน์</p>
            </div>
        </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold">ประเภทห้อง</h3>
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
                <h3 class="fw-bold">อาคารทั้งหมด</h3>
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
                @foreach($rooms->take(8) as $room)
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <!-- แก้ไขส่วนแสดงรูปภาพ -->
                        <img src="{{ $room->image ? asset('storage/'.$room->image) : asset('images/no-picture.jpg') }}" 
                             class="card-img-top" 
                             alt="รูปภาพห้อง {{ $room->room_name }}"
                             style="height: 200px; object-fit: cover;">
                        
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $room->room_name }}</h5>
                            <p class="text-muted mb-1">อาคาร {{ $room->building->building_name }} ชั้น {{ $room->class }}</p>
                            <p class="text-muted mb-1">รองรับได้ {{ $room->capacity }} คน</p>
                            <p class="fw-bold text-warning">฿{{ number_format($room->service_rates, 2) }} /วัน</p>
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
                    // $popularRooms = $rooms->sortByDesc('booking_count')->take(4);
                    $popularRooms = $rooms->take(8); // Changed to take 8 for 2 rows of 4
                @endphp
                @foreach($popularRooms as $room)
                <div class="col-md-3"> {{-- Changed from col-md-4 to col-md-3 --}}
                    <div class="card shadow-sm">
                        <img src="{{ $room->image ? asset('storage/'.$room->image) : '/api/placeholder/400/200' }}" class="card-img-top" alt="Room Image">
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $room->room_name }}</h5>
                            <p class="text-muted mb-1">อาคาร {{ $room->building->building_name }} ชั้น {{ $room->class }}</p>
                            <p class="text-muted mb-1">รองรับได้ {{ $room->capacity }} คน</p>
                            <p class="fw-bold text-warning">฿{{ number_format($room->service_rates, 2) }} /วัน</p>
                            <a href="{{ url('booking/'.$room->room_id) }}" class="btn btn-warning w-100">จองเลย</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
    </div>
</div>
@endsection
