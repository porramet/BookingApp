@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9 content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">อาคารทั้งหมด</h2>
                <a href="{{ url('/') }}" class="btn btn-outline-warning">
                    <i class="fas fa-arrow-left"></i> กลับหน้าหลัก
                </a>
            </div>

            <div class="row g-4">
                @foreach($buildings as $building)
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-4">
                            <i class="fas fa-building text-warning display-4 mb-3"></i>
                            <h5 class="fw-bold">{{ $building->building_name }}</h5>
                            <p class="text-muted mb-3">จำนวนห้องทั้งหมด: {{ $building->rooms_count }} ห้อง</p>
                            <a href="{{ route('rooms.byBuilding', ['building_id' => $building->id]) }}" class="btn btn-warning w-100">ดูห้องในอาคารนี้</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection