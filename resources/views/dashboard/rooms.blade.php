@extends('layouts.main')

@section('content')
<div>
    <div class="col-md-10 content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>จัดการห้องในอาคาร: {{ $building->building_name }}</h2>
            <div class="d-flex align-items-center">
                <form action="{{ route('manage_rooms.show', $building->id) }}" method="GET" class="d-flex">
                    <input class="search-bar" placeholder="ค้นหาห้อง" type="text" name="search" value="{{ request('search') }}"/>
                    <button type="submit" class="icon-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <button class="icon-btn">
                    <i class="fas fa-cog"></i>
                </button>
                <button class="icon-btn">
                    <i class="fas fa-bell"></i>
                </button>
                <img alt="Profile image" class="profile-img" src="https://placehold.co/40x40"/>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="fas fa-building icon"></i>
                    <div class="details">
                        <h3>{{ $totalCount }}</h3>
                        <p>ห้องทั้งหมด</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="fas fa-door-open icon"></i>
                    <div class="details">
                        <h3>{{ $availableCount }}</h3>
                        <p>ห้องที่ใช้งานได้</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="fas fa-door-closed icon"></i>
                    <div class="details">
                        <h3>{{ $unavailableCount }}</h3>
                        <p>ห้องที่ใช้งานไม่ได้</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="rooms-container">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>รายการห้องในอาคาร {{ $building->building_name }}</h5>
                        <div>
                            <a href="{{ route('manage_rooms.index') }}" class="btn btn-secondary">กลับไปหน้าอาคาร</a>
                            <button class="btn btn-primary mr-2" onclick="openAddRoomModal()">เพิ่มห้อง</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($rooms as $room)
                                <div class="col-md-3">
                                    <div class="card room-card mb-4">
                                        <img alt="ภาพห้อง {{ $room->room_name }}" class="card-img-top" 
                                            src="{{ $room->image ? asset('storage/' . $room->image) : asset('images/no-picture.jpg') }}"/>


                                        <div class="card-body">
                                            <h5>{{ $room->room_name }}</h5>
                                            <p>อาคาร: {{ $building->building_name }}</p>
                                            <p>ความจุ: {{ $room->capacity }}</p>
                                            <p>สถานะ: {{ $room->status->status_name }}</p>
                                            <a href="{{ route('manage_rooms.edit', $room->room_id) }}" class="btn btn-sm btn-warning">แก้ไข</a>

                                            <button class="btn btn-sm btn-danger">ลบ</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-md-12">
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $rooms->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openAddRoomModal() {
    $('#addRoomModal').modal('show');
}

</script>




<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog" aria-labelledby="addRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoomModalLabel">เพิ่มห้องใหม่</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('manage_rooms.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="building_id" value="{{ $building->id }}">

                    <div class="form-group">
                        <label for="room_name">ชื่อห้อง</label>
                        <input type="text" class="form-control" id="room_name" name="room_name" required>
                    </div>
                    <div class="form-group">
                        <label for="capacity">ความจุ</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="class">ประเภทห้อง</label>
                        <input type="text" class="form-control" id="class" name="class" required>
                    </div>
                    <div class="form-group">
                        <label for="room_details">รายละเอียดห้อง</label>
                        <textarea class="form-control" id="room_details" name="room_details"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="service_rates">อัตราค่าบริการ</label>
                        <input type="number" class="form-control" id="service_rates" name="service_rates" required min="0">
                    </div>
                    <div class="form-group">
                        <label for="image">รูปภาพห้อง</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
