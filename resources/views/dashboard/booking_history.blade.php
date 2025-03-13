@extends('layouts.main')

@section('content')
<div>
    <div class="col-md-10 content">
        <div class="d-flex justify-content-between align-items-center mb-4">
         <h2>
          ประวัติการจองห้อง
         </h2>
         <div class="d-flex align-items-center">
            <form action="{{ route('booking.history') }}" method="GET" class="d-flex">
                <div class="me-2">
                    <label for="date_from" class="form-label">จากวันที่</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="me-2">
                    <label for="date_to" class="form-label">ถึงวันที่</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="ms-2">
                    <label for="search" class="form-label">ค้นหา</label>
                    <div class="d-flex">
                        <input class="form-control search-bar" placeholder="ค้นหาประวัติการจอง" type="text" name="search" id="search" value="{{ request('search') }}"/>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
         </div>
        </div>
        
        <div class="row">
         <div class="col-md-12">
          <div class="card mb-4">
           <div class="card-header d-flex justify-content-between align-items-center">
            <h5>
             รายการประวัติการจอง
            </h5>
           </div>
           <div class="card-body">
            <table class="table table-striped">
             <thead>
              <tr>
               <th>#</th>
               <th>รหัสการจอง</th>
                <th>ผู้ใช้</th>
                <th>ผู้จองห้อง</th>
                <th>อีเมลผู้ใช้</th>
                <th>เบอร์โทรศัพท์</th>
                <th>วันที่จอง</th>
                <th>เวลาเริ่ม-สิ้นสุด</th>
                <th>สถานะการชำระเงิน</th>
                <th>สถานะการจอง</th>
              </tr>
             </thead>
             <tbody>
             @foreach($bookingHistory as $history)
              <tr>
               <td>{{ (($bookingHistory->currentPage() - 1) * $bookingHistory->perPage()) + $loop->iteration }}</td>
               <td>{{ $history->booking_id }}</td>
                <td>{{ $history->user_name ?? 'N/A' }}</td>
                <td>{{ $history->external_name }}</td>
                <td>{{ $history->external_email }}</td>
                <td>{{ $history->external_phone }}</td>
                <td>{{ date('d/m/Y', strtotime($history->booking_date)) }}</td>
                <td>{{ date('H:i', strtotime($history->start_time)) }} - {{ date('H:i', strtotime($history->end_time)) }}</td>
                <td>{{ $history->payment_status }}</td>
                <td>
                    <span class="badge 
                        @if($history->status_id == 1) bg-success 
                        @elseif($history->status_id == 4) bg-warning 
                        @elseif($history->status_id == 5) bg-danger 
                        @elseif($history->status_id == 6) bg-secondary 
                        @else bg-info @endif">
                        {{ $history->status_name }}
                    </span>
                </td>
              </tr>
             @endforeach
             </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{ $bookingHistory->appends(['search' => request('search'), 'date_from' => request('date_from'), 'date_to' => request('date_to')])->links('pagination::bootstrap-5') }}
            </div>
           </div>
          </div>
        </div>
    </div>
</div>
@endsection

<style>
    /* Main Layout Styles */
 body {
     font-family: 'Poppins', sans-serif;
     background-color: #f5f5f7;
     color: #333;
 }
 
 .container {
     max-width: 1200px;
     margin: 0 auto;
     padding: 20px;
 }
 
 .content {
     margin: 0 auto;
 }
 
 /* Header Styles */
 h2 {
     font-weight: 700;
     color: #333;
     margin-bottom: 20px;
 }
 
 /* Search Bar */
 .search-bar {
     border: none;
     background-color: #fff;
     border-radius: 30px;
     padding: 10px 15px;
     width: 200px;
     box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
     margin-right: 10px;
 }
 
 .icon-btn {
     background-color: #fff;
     border: none;
     border-radius: 50%;
     width: 40px;
     height: 40px;
     display: flex;
     align-items: center;
     justify-content: center;
     margin-left: 10px;
     box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
     cursor: pointer;
     transition: all 0.3s ease;
 }
 
 .icon-btn:hover {
     background-color: #f8f8f8;
 }
 
 .profile-img {
     width: 40px;
     height: 40px;
     border-radius: 50%;
     margin-left: 15px;
     object-fit: cover;
 }
 
 /* Stat Cards */
 .stat-card {
     background-color: #fff;
     border-radius: 15px;
     padding: 20px;
     display: flex;
     align-items: center;
     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
     transition: transform 0.3s ease;
     height: 100px;
 }
 
 .stat-card:hover {
     transform: translateY(-5px);
 }
 
 .stat-card .icon {
     font-size: 24px;
     background-color: #FFC107;
     color: #fff;
     padding: 15px;
     border-radius: 12px;
     margin-right: 15px;
 }
 
 .stat-card .details h3 {
     font-size: 24px;
     font-weight: 700;
     margin: 0;
     color: #333;
 }
 
 .stat-card .details p {
     margin: 5px 0 0;
     color: #777;
     font-size: 14px;
 }
 
 /* Card Styles */
 .card {
     border: none;
     border-radius: 15px;
     box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
     overflow: hidden;
 }
 
 .card-header {
     background-color: #fff;
     border-bottom: 1px solid #eee;
     padding: 15px 20px;
 }
 
 .card-header h5 {
     font-weight: 600;
     margin: 0;
     color: #333;
 }
 
 .card-body {
     padding: 20px;
 }
 
 /* Table Styles */
 .table {
     width: 100%;
     margin-bottom: 0;
 }
 
 .table th {
     font-weight: 600;
     color: #555;
     border-top: none;
     border-bottom: 2px solid #eee;
     padding: 12px 8px;
     background-color: #f9f9f9;
 }
 
 .table td {
     padding: 12px 8px;
     vertical-align: middle;
     border-top: 1px solid #eee;
 }
 
 .table-striped tbody tr:nth-of-type(odd) {
     background-color: #f9f9f9;
 }
 
 /* Button Styles */
 .btn {
     border-radius: 8px;
     padding: 6px 12px;
     font-weight: 500;
     font-size: 13px;
     transition: all 0.3s ease;
 }
 
 .btn-success {
     background-color: #FFC107;
     border-color: #FFC107;
     color: #fff;
 }
 
 .btn-success:hover {
     background-color: #e0a800;
     border-color: #e0a800;
 }
 
 .btn-danger {
     background-color: #F44336;
     border-color: #F44336;
 }
 
 .btn-danger:hover {
     background-color: #d32f2f;
     border-color: #d32f2f;
 }
 
 /* Custom Style for Status */
 td:nth-child(8) {
     font-weight: 600;
 }
 
 /* Custom Style for Different Status */
 td:nth-child(8):contains('approved') {
     color: #4CAF50;
 }
 
 td:nth-child(8):contains('pending') {
     color: #FFC107;
 }
 
 td:nth-child(8):contains('rejected') {
     color: #F44336;
 }
 
 /* Custom Style for Payment Status */
 td:nth-child(9) {
     font-weight: 600;
 }
 
 td:nth-child(9):contains('paid') {
     color: #4CAF50;
 }
 
 td:nth-child(9):contains('unpaid') {
     color: #F44336;
 }
 
 /* Responsive Adjustments */
 @media (max-width: 768px) {
     .stat-card {
         margin-bottom: 20px;
     }
     
     .table-responsive {
         overflow-x: auto;
     }
 }
 </style>
 