@extends('layouts.main')

@section('content')
<div>
    <div class="col-md-10 content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>จัดการห้องและอาคาร</h2>
            <div class="d-flex align-items-center">
                <form action="{{ route('manage_rooms.index') }}" method="GET" class="d-flex">
                    <input class="search-bar" name="search" placeholder="ค้นหาอาคาร" type="text" value="{{ request('search') }}"/>
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
            <div class="col-md-3">
                <div class="stat-card" onclick="showBuildings()">
                    <i class="fas fa-building icon"></i>
                    <div class="details">
                        <h3>{{ $totalBuildings }}</h3>
                        <p>จำนวนอาคารทั้งหมด</p>
                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-door-closed icon"></i>
                    <div class="details">
                        <h3>{{ $totalRooms }}</h3>
                        <p>ห้องทั้งหมดในระบบ</p>
                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" onclick="showAvailableRooms()">
                    <i class="fas fa-door-open icon"></i>
                    <div class="details">
                        <h3>{{ $rooms->where('status_id', '2')->count() }}</h3>
                        <p>ห้องที่ใช้งานได้</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" onclick="showUnavailableRooms()">
                    <i class="fas fa-door-closed icon"></i>
                    <div class="details">
                        <h3>{{ $rooms->where('status_id', '1')->count() }}</h3>
                        <p>ห้องที่ใช้งานไม่ได้</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="buildings-container">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>รายการอาคาร</h5>
                        <button class="btn btn-primary" onclick="openAddBuildingModal()">เพิ่มอาคาร</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($buildings as $building)
                                <div class="col-md-3">
                                    <div class="card building-card mb-4" data-name="{{ $building->building_name }}">
                                        <img alt="ภาพ{{ $building->building_name }}" class="card-img-top" src="{{ $building->image ? asset('storage/' . $building->image) : asset('images/no-picture.jpg') }}" style="height: 200px; object-fit: cover"/>

                                        <div class="card-body">
                                            <h5>{{ $building->building_name }}</h5>
                                            <p>บันทึกโดย: {{ $building->citizen_save }}</p>
                                            <p>จำนวนห้อง: {{ $building->rooms->count() }}</p>
                                            <button class="btn btn-sm btn-warning" onclick="openEditBuildingModal('{{ $building->id }}', '{{ $building->building_name }}', '{{ $building->citizen_save }}')">แก้ไข</button>
                                            <button class="btn btn-sm btn-danger" onclick="confirmDeleteBuilding('{{ $building->id }}')">ลบ</button>
                                            <button class="btn btn-sm btn-info" onclick="window.location.href='{{ route('manage_rooms.show', $building->id) }}'">ดูห้อง</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-md-12">
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $buildings->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Building Modal -->
<div class="modal fade" id="addBuildingModal" tabindex="-1" role="dialog" aria-labelledby="addBuildingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBuildingModalLabel">เพิ่มอาคาร</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
<form id="addBuildingForm" action="{{ route('manage.buildings.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf
                    <div class="form-group">
                        <label for="building_name">ชื่ออาคาร</label>
                        <input type="text" class="form-control" id="building_name" name="building_name" required>
                    </div>
                    <div class="form-group">
                        <label for="citizen_save">ชื่อผู้บันทึก</label>
                        <input type="text" class="form-control" id="citizen_save" name="citizen_save" required>
                    </div>
                    <div class="form-group">
                        <label for="building_image">รูปภาพอาคาร</label>
                        <input type="file" class="form-control" id="building_image" name="image" accept="image/*">
                        <small class="form-text text-muted">รองรับไฟล์รูปภาพ (jpeg, png, jpg, gif) ขนาดไม่เกิน 2MB</small>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('addBuildingForm').submit();">เพิ่มอาคาร</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Building Modal -->
<div class="modal fade" id="editBuildingModal" tabindex="-1" role="dialog" aria-labelledby="editBuildingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBuildingModalLabel">แก้ไขอาคาร</h5>
                <button type="button" class="close" data-dismiss="modal="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
<form id="editBuildingForm" action="" method="POST" enctype="multipart/form-data">

                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="edit_building_name">ชื่ออาคาร</label>
                        <input type="text" class="form-control" id="edit_building_name" name="building_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_citizen_save">บันทึกโดย</label>
                        <input type="text" class="form-control" id="edit_citizen_save" name="citizen_save" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_building_image">รูปภาพอาคาร</label>
                        <input type="file" class="form-control" id="edit_building_image" name="image" accept="image/*">
                        <small class="form-text text-muted">รองรับไฟล์รูปภาพ (jpeg, png, jpg, gif) ขนาดไม่เกิน 2MB</small>
                        <div id="currentImage" class="mt-2"></div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('editBuildingForm').submit()">บันทึก</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">ยืนยันการลบ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                คุณแน่ใจหรือไม่ที่จะลบอาคารนี้?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <form id="deleteForm" action="" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">ลบ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openAddBuildingModal() {
    $('#addBuildingModal').modal('show');
}

function openEditBuildingModal(id, name, citizen_save) {
    // Set form action
    document.getElementById('editBuildingForm').action = `/manage/buildings/${id}`;
    // Fill in existing data
    document.getElementById('edit_building_name').value = name;
    document.getElementById('edit_citizen_save').value = citizen_save;
    // Show Modal
    $('#editBuildingModal').modal('show');
}

function confirmDeleteBuilding(id) {
    // Set form action
    document.getElementById('deleteForm').action = `/manage/buildings/${id}`;
    // Show Modal
    $('#deleteConfirmationModal').modal('show');
}
</script>

@endsection

