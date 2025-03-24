@extends('layouts.main')

@section('content')
<div>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>จัดการห้องในอาคาร: {{ $building->building_name }}</h2>
            <div class="d-flex align-items-center">
                <form action="{{ route('manage_rooms.show', $building->id) }}" method="GET" class="d-flex">
                    <input class="search-bar" placeholder="ค้นหาห้อง" type="text" name="search" value="{{ request('search') }}"/>
                    <button type="submit" class="icon-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
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
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">รายการห้องในอาคาร {{ $building->building_name }}</h5>
                        <div>
                            <a href="{{ route('manage_rooms.index') }}" class="btn btn-secondary btn-sm me-2">
                                <i class="fas fa-arrow-left me-1"></i>กลับไปหน้าอาคาร
                            </a>
                            <button class="btn btn-primary btn-sm" onclick="openAddRoomModal()">
                                <i class="fas fa-plus me-1"></i>เพิ่มห้อง
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4 p-4">
                            @foreach($rooms as $room)
                                <div class="col">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="position-relative">
                                            <img alt="ภาพห้อง {{ $room->room_name }}" class="card-img-top" 
                                                src="{{ $room->image ? asset('storage/' . $room->image) : asset('images/no-picture.jpg') }}" 
                                                style="height: 180px; object-fit: cover;"/>
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-{{ $room->status_id == 2 ? 'success' : 'danger' }}">
                                                    {{ $room->status->status_name }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $room->room_name }}</h5>
                                            <p class="card-text text-muted small mb-2">
                                                <i class="fas fa-building me-1"></i>อาคาร: {{ $building->building_name }}
                                            </p>
                                            <p class="card-text text-muted small mb-2">
                                                <i class="fas fa-users me-1"></i>ความจุ: {{ $room->capacity }} คน
                                            </p>
                                            <div class="d-flex gap-2">
                                                <a href="#" class="btn btn-sm btn-outline-warning flex-grow-1" 
                                                   onclick="openEditRoomModal(
                                                       '{{ $room->room_id }}', 
                                                       '{{ $room->room_name }}', 
                                                       '{{ $room->capacity }}', 
                                                       '{{ $room->class }}', 
                                                       '{{ $room->room_details }}', 
                                                       '{{ $room->service_rates }}', 
                                                       '{{ $room->image ? asset('storage/' . $room->image) : '' }}'
                                                   )">
                                                    <i class="fas fa-edit me-1"></i>แก้ไข
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger flex-grow-1" onclick="confirmDeleteRoom('{{ $room->room_id }}')">
                                                    <i class="fas fa-trash me-1"></i>ลบ
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center p-4">
                            {{ $rooms->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

<!-- Edit Room Modal -->
<div class="modal fade" id="editRoomModal" tabindex="-1" role="dialog" aria-labelledby="editRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoomModalLabel">แก้ไขห้อง</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editRoomForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="building_id" value="{{ $building->id }}">

                    <div class="form-group">
                        <label for="edit_room_name">ชื่อห้อง</label>
                        <input type="text" class="form-control" id="edit_room_name" name="room_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_capacity">ความจุ</label>
                        <input type="number" class="form-control" id="edit_capacity" name="capacity" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="edit_class">ประเภทห้อง</label>
                        <input type="text" class="form-control" id="edit_class" name="class" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_room_details">รายละเอียดห้อง</label>
                        <textarea class="form-control" id="edit_room_details" name="room_details"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_service_rates">อัตราค่าบริการ</label>
                        <input type="number" class="form-control" id="edit_service_rates" name="service_rates" required min="0">
                    </div>
                    <div class="form-group">
                        <label for="edit_image">รูปภาพห้อง</label>
                        <input type="file" class="form-control-file" id="edit_image" name="image">
                        <small class="form-text text-muted">หากไม่ต้องการเปลี่ยนรูปภาพ ให้เว้นว่างไว้</small>
                        <div id="currentImage" class="mt-2"></div>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteRoomConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteRoomConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRoomConfirmationModalLabel">ยืนยันการลบ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                คุณแน่ใจหรือไม่ที่จะลบห้องนี้?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <form id="deleteRoomForm" action="" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">ลบ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openAddRoomModal() {
    $('#addRoomModal').modal('show');
}

function openEditRoomModal(roomId, roomName, capacity, roomClass, roomDetails, serviceRates, imageUrl) {
    // Set form action
    document.getElementById('editRoomForm').action = `/manage_rooms/${roomId}`;

    // Fill in existing data
    document.getElementById('edit_room_name').value = roomName;
    document.getElementById('edit_capacity').value = capacity;
    document.getElementById('edit_class').value = roomClass;
    document.getElementById('edit_room_details').value = roomDetails;
    document.getElementById('edit_service_rates').value = serviceRates;

    // Show current image
    const currentImageDiv = document.getElementById('currentImage');
    if (imageUrl) {
        currentImageDiv.innerHTML = `<img src="${imageUrl}" alt="Current Image" style="max-width: 100%; height: auto;"/>`;
    } else {
        currentImageDiv.innerHTML = '<p>ไม่มีรูปภาพ</p>';
    }

    // Show Modal
    $('#editRoomModal').modal('show');
}

function confirmDeleteRoom(roomId) {
    // Set form action
    document.getElementById('deleteRoomForm').action = `/manage_rooms/${roomId}`;

    // Show Modal
    $('#deleteRoomConfirmationModal').modal('show');
}
</script>

@endsection