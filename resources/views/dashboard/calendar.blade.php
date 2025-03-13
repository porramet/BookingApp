@extends('layouts.main')

@section('content')
<div>
    <div class="col-md-10 content">
        <div class="d-flex justify-content-between align-items-center mb-4">
         <h2>
          ปฏิทินการจองห้อง
         </h2>
         <div class="d-flex align-items-center">
          <input class="search-bar" placeholder="ค้นหาบางอย่าง" type="text"/>
          <button class="icon-btn">
           <i class="fas fa-cog">
           </i>
          </button>
          <button class="icon-btn">
           <i class="fas fa-bell">
           </i>
          </button>
          <img alt="Profile image" class="profile-img" src="https://placehold.co/40x40"/>
         </div>
        </div>
        <div class="row">
         <div class="col-md-12">
          <div class="card mb-4">
           <div class="card-header d-flex justify-content-between align-items-center">
            <h5>
             ปฏิทินการจองห้อง
            </h5>
           </div>
           <div class="card-body">
            <div id="calendar">
            </div>
           </div>
          </div>
         </div>
        </div>
        <div class="row">
         <div class="col-md-12">
          <div class="card mb-4">
           <div class="card-header d-flex justify-content-between align-items-center">
            <h5>
             ตารางการจองห้อง
            </h5>
           </div>
           <div class="card-body">
            <table class="table table-striped">
             <thead>
              <tr>
               <th>
                #
               </th>
               <th>
                ห้อง
               </th>
               <th>
                ผู้จอง
               </th>
               <th>
                วันที่จอง
               </th>
               <th>
                เวลา
               </th>
               <th>
                สถานะ
               </th>
              </tr>
             </thead>
             <tbody>
              <tr>
               <td>
                1
               </td>
               <td>
                ห้องประชุม A
               </td>
               <td>
                สมชาย ใจดี
               </td>
               <td>
                12/22
               </td>
               <td>
                10:00 - 12:00
               </td>
               <td>
                <span class="badge bg-success">
                 ยืนยันแล้ว
                </span>
               </td>
              </tr>
              <tr>
               <td>
                2
               </td>
               <td>
                ห้องประชุม B
               </td>
               <td>
                สมหญิง ใจดี
               </td>
               <td>
                01/24
               </td>
               <td>
                14:00 - 16:00
               </td>
               <td>
                <span class="badge bg-warning">
                 รอการยืนยัน
                </span>
               </td>
              </tr>
              <tr>
               <td>
                3
               </td>
               <td>
                ห้องประชุม C
               </td>
               <td>
                สมปอง ใจดี
               </td>
               <td>
                02/15
               </td>
               <td>
                09:00 - 11:00
               </td>
               <td>
                <span class="badge bg-danger">
                 ยกเลิก
                </span>
               </td>
              </tr>
              <tr>
               <td>
                4
               </td>
               <td>
                ห้องประชุม D
               </td>
               <td>
                สมศรี ใจดี
               </td>
               <td>
                03/10
               </td>
               <td>
                13:00 - 15:00
               </td>
               <td>
                <span class="badge bg-success">
                 ยืนยันแล้ว
                </span>
               </td>
              </tr>
              <tr>
               <td>
                5
               </td>
               <td>
                ห้องประชุม E
               </td>
               <td>
                สมหมาย ใจดี
               </td>
               <td>
                04/05
               </td>
               <td>
                11:00 - 13:00
               </td>
               <td>
                <span class="badge bg-warning">
                 รอการยืนยัน
                </span>
               </td>
              </tr>
             </tbody>
            </table>
            <nav aria-label="Page navigation example">
             <ul class="pagination justify-content-center">
              <li class="page-item">
               <a class="page-link" href="#" tabindex="-1">
                ก่อนหน้า
               </a>
              </li>
              <li class="page-item">
               <a class="page-link" href="#">
                1
               </a>
              </li>
              <li class="page-item">
               <a class="page-link" href="#">
                2
               </a>
              </li>
              <li class="page-item">
               <a class="page-link" href="#">
                3
               </a>
              </li>
              <li class="page-item">
               <a class="page-link" href="#">
                ถัดไป
               </a>
              </li>
             </ul>
            </nav>
           </div>
</div>
@endsection

<script>
    $(document).ready(function() {
         $('#calendar').fullCalendar({
             header: {
                 left: 'prev,next today',
                 center: 'title',
                 right: 'month,agendaWeek,agendaDay'
             },
             events: [
                 {
                     title: 'ห้องประชุม A - สมชาย ใจดี',
                     start: '2023-12-22T10:00:00',
                     end: '2023-12-22T12:00:00',
                     color: '#28a745'
                 },
                 {
                     title: 'ห้องประชุม B - สมหญิง ใจดี',
                     start: '2023-01-24T14:00:00',
                     end: '2023-01-24T16:00:00',
                     color: '#ffc107'
                 },
                 {
                     title: 'ห้องประชุม C - สมปอง ใจดี',
                     start: '2023-02-15T09:00:00',
                     end: '2023-02-15T11:00:00',
                     color: '#dc3545'
                 },
                 {
                     title: 'ห้องประชุม D - สมศรี ใจดี',
                     start: '2023-03-10T13:00:00',
                     end: '2023-03-10T15:00:00',
                     color: '#28a745'
                 },
                 {
                     title: 'ห้องประชุม E - สมหมาย ใจดี',
                     start: '2023-04-05T11:00:00',
                     end: '2023-04-05T13:00:00',
                     color: '#ffc107'
                 }
             ]
         });
     });
   </script>

<style>
    body {
             font-family: 'Arial', sans-serif;
             background-color: #f8f9fa;
         }
         .sidebar {
             background-color: #fff;
             height: 100vh;
             padding: 20px;
             border-right: 1px solid #e0e0e0;
         }
         .sidebar .nav-link {
             color: #6c757d;
             font-weight: 500;
             margin-bottom: 10px;
         }
         .sidebar .nav-link.active {
             color: #007bff;
             background-color: #e9ecef;
             border-radius: 5px;
         }
         .content {
             padding: 20px;
         }
         .card {
             border-radius: 10px;
             box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
         }
         .card-header {
             background-color: #fff;
             border-bottom: none;
         }
         .card-body {
             padding: 20px;
         }
         .card-footer {
             background-color: #fff;
             border-top: none;
         }
         .card-blue {
             background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
             color: #fff;
         }
         .card-blue .card-body {
             padding: 20px;
         }
         .card-blue .card-footer {
             background-color: transparent;
         }
         .chart-container {
             position: relative;
             height: 200px;
         }
         .profile-img {
             width: 40px;
             height: 40px;
             border-radius: 50%;
         }
         .search-bar {
             background-color: #f1f3f4;
             border: none;
             border-radius: 20px;
             padding: 10px 20px;
             width: 100%;
         }
         .search-bar:focus {
             outline: none;
             box-shadow: none;
         }
         .icon-btn {
             background-color: #f1f3f4;
             border: none;
             border-radius: 50%;
             padding: 10px;
             margin-left: 10px;
         }
         .icon-btn i {
             color: #6c757d;
         }
         .transaction-item {
             display: flex;
             align-items: center;
             justify-content: space-between;
             padding: 10px 0;
             border-bottom: 1px solid #e0e0e0;
         }
         .transaction-item:last-child {
             border-bottom: none;
         }
         .transaction-item .icon {
             width: 40px;
             height: 40px;
             border-radius: 50%;
             display: flex;
             align-items: center;
             justify-content: center;
             margin-right: 10px;
         }
         .transaction-item .icon i {
             font-size: 20px;
         }
         .transaction-item .details {
             flex-grow: 1;
         }
         .transaction-item .amount {
             font-weight: 500;
         }
         .transaction-item .amount.negative {
             color: #dc3545;
         }
         .transaction-item .amount.positive {
             color: #28a745;
         }
   </style>