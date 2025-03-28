@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4 ">
        <div class="col-md-12 content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">{{ $title }}</h2>
                <a href="{{ url('booking') }}" class="btn btn-outline-warning">
                    <i class="fas fa-arrow-left"></i> กลับหน้าหลัก
                </a>
            </div>

            <div class="row g-4">
                @if($rooms->count() > 0)
                    @foreach($rooms as $room)
                    <div class="col-md-2">
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
                @else
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4>ไม่พบห้องที่ตรงกับเงื่อนไข</h4>
                        <p class="text-muted">กรุณาลองค้นหาใหม่อีกครั้ง</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection