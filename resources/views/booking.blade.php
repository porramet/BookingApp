<!-- resources/views/booking.blade.php -->
@extends('layouts.app')

@section('content')
    <section class="py-16">
        <div class="container mx-auto">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">ระบบจองห้องมหาวิทยาลัยราชภัฏสกลนคร</h1>
        <p class="text-center text-gray-600 mb-8">เลือกอาคารที่ต้องการเพื่อดูรายละเอียดและทำการจองห้อง</p>
        <div class="text-center my-4">
            <button id="backToBuildings" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 hidden" onclick="showBuildings()">
                ← กลับไปหน้าอาคาร
            </button>
        </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <!-- Building Section -->
                @foreach($buildings as $building)
                @php
                    $roomCount = $rooms->where('building_id', $building->id)->count();
                @endphp
                <div class="bg-white rounded-lg shadow-md overflow-hidden building">
                    
                    <img alt="{{ $building->image_url }}" class="w-full h-48 object-cover" height="400" src="{{ file_exists(public_path($building->image)) ? asset($building->image) : asset('images/no-picture.jpg') }}" width="600"/>
                    <div class="p-4">
                        <h2 class="text-xl font-semibold text-gray-800">
                            {{ $building->building_name }}
                        </h2>
                        <p class="text-gray-600 text-base mt-2">
                            {{ $building->description }}
                        </p>
                        <p class="text-gray-700 text-sm mt-2">
                            จำนวนห้องทั้งหมด: <span class="font-bold">{{ $roomCount }}</span> ห้อง
                        </p>
                        <div class="flex justify-between mt-4">
                            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="fetchRooms('{{ $building->id }}')">
                                ดูห้องในอาคาร
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
                @foreach($rooms as $room)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden relative flex flex-col hidden rooms" id="{{ $room->building_id }}">
                        <img alt="{{ $room->room_name }}" class="w-full h-48 object-cover" height="400" src="{{ file_exists(public_path($room->image)) ? asset($room->image) : asset('images/no-picture.jpg') }}" width="600"/>
                        <div class="absolute top-2 right-2 text-white text-lg font-semibold px-1 py-1 rounded
                            @if($room->status->status_name === 'ไม่ว่าง') bg-red-500
                            @elseif($room->status->status_name === 'ว่าง') bg-green-500
                            @elseif($room->status->status_name === 'ไม่พร้อมใช้งาน') bg-blue-500
                            @endif">
                            {{ $room->status->status_name }}
                        </div>

                        <div class="p-4 flex-grow">
                            <div class="flex items-center mb-2">
                                <span class="bg-blue-500 text-white text-sm font-semibold px-2.5 py-0.5 rounded">
                                    ชั้น {{ $room->class }} <!-- Assuming floor is a property -->
                                </span>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">
                                {{ $room->room_name }}
                            </h2>
                            <div class="relative">
                                <p class="text-gray-600 text-base mt-2 h-12 overflow-hidden">
                                    {{ $room->room_details }}
                                </p>
                                <div class="text-gray-600 text-base mt-2">
                                    ความจุ: {{ $room->capacity }} คน
                                </div>
                                <div class="text-red-500 text-base mt-2">
                                    ราคา: {{ $room->service_rates }} บาท/คืน
                                </div>
                            </div>
                        </div>

                        <div class="p-2 ">
                            <div class="flex justify-between">
                            <a href="{{ url('/booking/' . $room->room_id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                จอง
                            </a>
                                <button class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                    ดูรายละเอียดห้อง
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="bg-white rounded-lg shadow-md overflow-hidden relative flex flex-col hidden rooms" id="{{ $room->building_id }}">
                    <img alt="{{ $room->room_name }}" class="w-full h-48 object-cover" height="400" src="{{ file_exists(public_path($room->image)) ? asset($room->image) : asset('images/no-picture.jpg') }}" width="600"/>
                    <div class="absolute top-2 right-2 text-white text-lg font-semibold px-1 py-1 rounded
                        @if($room->status->status_name === 'ไม่ว่าง') bg-red-500
                        @elseif($room->status->status_name === 'ว่าง') bg-green-500
                        @elseif($room->status->status_name === 'ไม่พร้อมใช้งาน') bg-blue-500
                        @endif">
                        {{ $room->status->status_name }}
                    </div>

                    <div class="p-4 flex-grow">
                        <div class="flex items-center mb-2">
                            <span class="bg-blue-500 text-white text-sm font-semibold px-2.5 py-0.5 rounded">
                                ชั้น {{ $room->class }}
                            </span>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">
                            {{ $room->room_name }}
                        </h2>
                        <div class="relative">
                            <p class="text-gray-600 text-base mt-2 h-12 overflow-hidden">
                                {{ $room->room_details }}
                            </p>
                            <div class="text-gray-600 text-base mt-2">
                                ความจุ: {{ $room->capacity }} คน
                            </div>
                            <div class="text-red-500 text-base mt-2">
                                ราคา: {{ $room->service_rates }} บาท/คืน
                            </div>
                        </div>
                    </div>

                    <div class="p-2">
                        <div class="flex justify-between">
                            <a href="{{ route('bookings.show', $room->room_id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                จอง
                            </a>                            
                            
                            <button class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                ดูรายละเอียดห้อง
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@push('scripts')
<script>
    function fetchRooms(buildingId) {
        fetch(`/buildings/${buildingId}/rooms`)
            .then(response => response.json())
            .then(rooms => {
                const container = document.getElementById(`rooms-container-${buildingId}`);
                const list = document.getElementById(`rooms-list-${buildingId}`);
                
                // Clear previous results
                list.innerHTML = '';
                
                if (rooms.length > 0) {
                    rooms.forEach(room => {
                        const roomCard = document.createElement('div');
                        roomCard.className = 'bg-white p-4 rounded-lg shadow-md';
                        roomCard.innerHTML = `
                            <h4 class="font-semibold">${room.room_name}</h4>
                            <p class="text-sm text-gray-600">ความจุ: ${room.capacity} คน</p>
                            <p class="text-sm text-gray-600">สถานะ: ${room.status}</p>
                        `;
                        list.appendChild(roomCard);
                    });
                    container.classList.remove('hidden');
                } else {
                    const noRooms = document.createElement('p');
                    noRooms.className = 'text-gray-600';
                    noRooms.textContent = 'ไม่มีห้องในอาคารนี้';
                    list.appendChild(noRooms);
                    container.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error fetching rooms:', error);
            });
    }
</script>
@endpush



<script>
    function fetchRooms(buildingId) {
        // ซ่อนอาคารทั้งหมด
        document.querySelectorAll('.building').forEach(building => {
            building.classList.add('hidden');
        });

        // ซ่อนห้องทั้งหมดก่อน
        document.querySelectorAll('.rooms').forEach(room => {
            room.classList.add('hidden');
        });

        // แสดงเฉพาะห้องที่ตรงกับอาคารที่เลือก
        document.querySelectorAll(`.rooms[id="${buildingId}"]`).forEach(room => {
            room.classList.remove('hidden');
        });

        // แสดงปุ่มย้อนกลับ
        document.getElementById('backToBuildings').classList.remove('hidden');
    }

    let currentRoomId = null;
    function showBookingForm(roomId) {
        currentRoomId = roomId;
        document.getElementById('bookingModal').classList.remove('hidden');
    }

    function closeBookingForm() {
        document.getElementById('bookingModal').classList.add('hidden');
    }

    function showBuildings() {

        // แสดงอาคารทั้งหมด
        document.querySelectorAll('.building').forEach(building => {
            building.classList.remove('hidden');
        });

        // ซ่อนห้องทั้งหมด
        document.querySelectorAll('.rooms').forEach(room => {
            room.classList.add('hidden');
        });

        // ซ่อนปุ่มย้อนกลับ
        document.getElementById('backToBuildings').classList.add('hidden');
    }
</script>
