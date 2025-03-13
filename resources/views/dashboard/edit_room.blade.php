@extends('layouts.main')

@section('content')
<div class="container">
    <h2>แก้ไขห้อง: {{ $room->room_name }}</h2>
    <form action="{{ route('manage_rooms.update', $room->room_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="building_id" value="{{ $room->building_id }}">
        
        <div class="row">
            <!-- Left Column for Image -->
            <div class="col-md-4">
                <div class="form-group">
                    @if($room->image && file_exists(public_path('storage/' . basename($room->image))))
                        <img src="{{ asset('storage/' . basename($room->image)) }}" class="img-fluid mb-2" style="max-width: 400px; max-height: 300px;" alt="Current Room Image">

                    @else
                        <div class="text-center p-3 border" style="width: 400px; height: 300px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('images/no-picture.jpg') }}" class="img-fluid" alt="Image not available">
                        </div>
                    @endif





                    <div class="text-center">
                        <label for="image" class="btn btn-sm btn-outline-primary mt-2">
                            <i class="fas fa-camera"></i> Change Image
                        </label>
                        <input type="file" class="d-none" id="image" name="image">
                    </div>
                </div>
            </div>

            <!-- Right Column for Form Fields -->
            <div class="col-md-8">
                <div class="form-group">
                    <label for="room_name">ชื่อห้อง</label>
                    <input type="text" class="form-control" id="room_name" name="room_name" value="{{ $room->room_name }}" required>
                </div>
                <div class="form-group">
                    <label for="capacity">ความจุ</label>
                    <input type="number" class="form-control" id="capacity" name="capacity" value="{{ $room->capacity }}" required min="1">
                </div>
                <div class="form-group">
                    <label for="class">ประเภทห้อง</label>
                    <input type="text" class="form-control" id="class" name="class" value="{{ $room->class }}" required>
                </div>
                <div class="form-group">
                    <label for="room_details">รายละเอียดห้อง</label>
                    <textarea class="form-control" id="room_details" name="room_details">{{ $room->room_details }}</textarea>
                </div>
                <div class="form-group">
                    <label for="service_rates">อัตราค่าบริการ</label>
                    <input type="number" class="form-control" id="service_rates" name="service_rates" value="{{ $room->service_rates }}" required min="0">
                </div>
                <div class="form-group">
                    <label for="status_id">สถานะห้อง</label>
                    <select class="form-control" id="status_id" name="status_id" required>
                        @foreach($status as $stat)
                            <option value="{{ $stat->status_id }}" {{ $stat->status_id == $room->status_id ? 'selected' : '' }}>{{ $stat->status_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Form Buttons -->
        <div class="row mt-4">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                <a href="{{ route('manage_rooms.show', $room->building_id) }}" class="btn btn-secondary">ยกเลิก</a>
            </div>
        </div>
    </form>
</div>
@endsection
