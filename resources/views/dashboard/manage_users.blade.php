@extends('layouts.main')

@section('content')
<div>
    <div class="col-md-10 content">
        <div class="d-flex justify-content-between align-items-center mb-4">
         <h2>
          จัดการผู้ใช้
         </h2>
         <div class="d-flex align-items-center">
            <form action="{{ route('manage_users.index') }}" method="GET" class="d-flex">
                <input class="search-bar" placeholder="ค้นหาผู้ใช้" type="text" name="search" value="{{ request('search') }}"/>
                <button type="submit" class="icon-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
         </div>
        </div>
        <div class="row mb-4">
         <div class="col-md-4">
          <div class="stat-card">
           <i class="fas fa-users icon">
           </i>
           <div class="details">
            <h3>
             {{ $totalUsers }}
            </h3>
            <p>
             จำนวนผู้ใช้ทั้งหมด
            </p>
           </div>
          </div>
         </div>
         <div class="col-md-4">
          <div class="stat-card">
           <i class="fas fa-user-shield icon">
           </i>
           <div class="details">
            <h3>
             {{ $adminCount }}
            </h3>
            <p>
             จำนวนผู้ดูแลระบบ
            </p>
           </div>
          </div>
         </div>
         <div class="col-md-4">
          <div class="stat-card">
           <i class="fas fa-user icon">
           </i>
           <div class="details">
            <h3>
             {{ $regularUserCount }}
            </h3>
            <p>
             จำนวนผู้ใช้ทั่วไป
            </p>
           </div>
          </div>
         </div>
        </div>
        <div class="row">
         <div class="col-md-12">
          <div class="card mb-4">
           <div class="card-header d-flex justify-content-between align-items-center">
            <h5>
             รายการผู้ใช้
            </h5>
           </div>
           <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <table class="table table-striped">
             <thead>
              <tr>
               <th>#</th>
               <th>ชื่อผู้ใช้</th>
               <th>อีเมล</th>
               <th>บทบาท</th>
               <th>การกระทำ</th>
              </tr>
             </thead>
             <tbody>
             @foreach($users as $user)
              <tr>
               <td>{{ (($users->currentPage() - 1) * $users->perPage()) + $loop->iteration }}</td>

               <td>{{ $user->name }}</td>
               <td>{{ $user->email }}</td>
               <td>
                <form action="{{ route('manage_users.update', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <select name="role" class="form-select" onchange="this.form.submit()">
                     <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>ผู้ดูแลระบบ</option>
                     <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>ผู้ใช้ทั่วไป</option>
                    </select>
                </form>
               </td>
               <td>
                <form action="{{ route('manage_users.destroy', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('คุณแน่ใจหรือไม่ที่ต้องการลบผู้ใช้นี้?')">ลบ</button>
                </form>
               </td>
              </tr>
             @endforeach
             </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{ $users->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
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
