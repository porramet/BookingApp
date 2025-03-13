@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h2>รายละเอียดการจอง</h2>

    <div class="card p-4">
        <p><strong>รหัสการจอง:</strong> {{ $booking->id }}</p>
        <p><strong>ชื่อผู้จอง:</strong> {{ $booking->external_name }}</p>
        <p><strong>อีเมล:</strong> {{ $booking->external_email }}</p>
        <p><strong>เบอร์โทร:</strong> {{ $booking->external_phone }}</p>
        <p><strong>ชื่อห้อง:</strong> {{ $booking->room_id }}</p>
        <p><strong>ชื่ออาคาร:</strong> {{ $booking->building_id }}</p>
        <p><strong>เวลาเริ่มต้น:</strong> {{ $booking->booking_start }}</p>
        <p><strong>เวลาสิ้นสุด:</strong> {{ $booking->booking_end }}</p>
        <p><strong>เหตุผล:</strong> {{ $booking->reason }}</p>
        <p><strong>ค่าบริการรวม:</strong> {{ number_format($booking->total_price, 2) }} บาท</p>
        <p><strong>สถานะการชำระเงิน:</strong> {{ $booking->payment_status }}</p>
        <p><strong>บุคคลภายนอก:</strong> {{ $booking->is_external ? 'ใช่' : 'ไม่ใช่' }}</p>

        <a href="{{ route('dashboard.booking_db') }}" class="btn btn-secondary mt-3">ย้อนกลับ</a>
    </div>
</div>
@endsection
