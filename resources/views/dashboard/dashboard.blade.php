@extends('layouts.main')

@section('content')
<div>
    <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>ภาพรวม</h2>
    </div>

    <div class="row">
        <!-- ส่วนของการจองล่าสุด -->
        <div class="col-md-8">
            <div class="p-3 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5>การจองล่าสุด</h5>
                    <a href="#" class="view-all-link">ดูทั้งหมด</a>
                </div>
                <div class="booking-carousel" id="bookingCarousel">
                    @if(isset($recentBookings) && $recentBookings->count() > 0)
                        @foreach($recentBookings as $booking)
                            <div class="booking-card">
                                <img src="https://placehold.co/100x100" alt="ภาพห้องประชุม {{ $booking->room_name }}">
                                <p class="room-name">{{ $booking->room_name }}</p>
                                <p class="building-name">{{ $booking->building_name }}</p>
                                <p class="booker-name">{{ $booking->booker_name }}</p>
                                <button class="btn btn-light btn-sm" onclick="showDetails('{{ $booking->room_name }}', '{{ $booking->booker_name }}', '{{ $booking->date }}', '{{ $booking->time }}')">
                                    ดูรายละเอียด
                                </button>
                            </div>
                        @endforeach
                    @else
                        <p>ไม่มีการจองล่าสุด</p>
                    @endif
                </div>
                <div class="carousel-controls">
                    <button class="icon-btn" onclick="scrollCarousel(-200)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="icon-btn" onclick="scrollCarousel(200)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- สถิติการจอง -->
            <div class="row">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <div class="details">
                            <h3>{{ $totalRooms ?? 0 }}</h3>
                            <p>จำนวนห้อง</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <div class="details">
                            <h3>{{ $totalUsers ?? 0 }}</h3>
                            <p>จำนวนผู้ใช้</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="icon"><i class="fas fa-calendar-check"></i></div>
                        <div class="details">
                            <h3>{{ $totalBookings ?? 0 }}</h3>
                            <p>จำนวนการจองห้อง</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ส่วนของข้อมูลล่าสุด -->
        <div class="col-md-4">
            <div class="p-3 mb-4">
                <h5>ดำเนินการเสร็จสิ้นล่าสุด</h5>
                <div class="transaction-list">
                    
                </div>
            </div>

            <!-- สถิติการจองรายสัปดาห์ -->
            <div class="p-3">
                <h5>สถิติการจองรายสัปดาห์</h5>
                <div class="chart-container">
                    <canvas id="bookingChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let ctx = document.getElementById("bookingChart").getContext("2d");

        // ตรวจสอบว่าตัวแปร $weeklyStats มีค่าหรือไม่
        let bookingData = @json($weeklyStats ?? []); // ใช้ null coalescing operator เพื่อป้องกันข้อผิดพลาด
        let weeks = bookingData.map(item => "Week " + item.week);
        let totals = bookingData.map(item => item.total);

        new Chart(ctx, {
            type: "line",
            data: {
                labels: weeks,
                datasets: [{
                    label: "จำนวนการจอง (รายสัปดาห์)",
                    data: totals,
                    backgroundColor: "rgba(54, 162, 235, 0.2)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 2,
                    pointBackgroundColor: "rgba(54, 162, 235, 1)",
                    pointRadius: 5,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: { display: true }
                }
            }
        });
    });

    function scrollCarousel(amount) {
        document.getElementById('bookingCarousel').scrollBy({ left: amount, behavior: 'smooth' });
    }

    function showDetails(room, booker, date, time) {
        alert(`ห้อง: ${room}\nผู้จอง: ${booker}\nวันที่จอง: ${date}\nเวลา: ${time}`);
    }
</script>


<style>
    /* Booking Carousel Styles */
.booking-carousel {
    display: flex;
    overflow-x: auto;
    scroll-behavior: smooth;
    padding: 15px 0;
    gap: 20px;
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.booking-carousel::-webkit-scrollbar {
    display: none;
}

.carousel-controls {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
}

.booking-card {
    min-width: 180px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    padding: 15px;
    text-align: center;
}

.booking-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.booking-card img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 4px;
    margin: 0 auto 10px;
}

.booking-card .room-name {
    font-weight: 600;
    margin-bottom: 3px;
    font-size: 16px;
}

.booking-card .building-name,
.booking-card .booker-name {
    color: #666;
    margin-bottom: 5px;
    font-size: 14px;
}

/* Stats Cards */
.stat-card {
    display: flex;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    padding: 20px;
    margin-bottom: 20px;
    align-items: center;
    height: 100%;
}

.stat-card .icon {
    background-color: #FFC107;
    color: #fff;
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 20px;
}

.stat-card .details {
    flex-grow: 1;
}

.stat-card h3 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 0;
}

.stat-card p {
    color: #666;
    margin-bottom: 0;
}

/* Transaction List Styles */
.transaction-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.transaction-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.transaction-item:last-child {
    border-bottom: none;
}

.transaction-item .icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 16px;
}

.transaction-item .details p {
    font-weight: 600;
    font-size: 15px;
    margin-bottom: 2px;
}

.transaction-item .details small {
    color: #777;
    font-size: 13px;
}

.transaction-item .amount {
    font-weight: 600;
    font-size: 15px;
}

.transaction-item .amount.positive {
    color: #4CAF50;
}

.transaction-item .amount.negative {
    color: #F44336;
}

/* Chart Container */
.chart-container {
    position: relative;
    height: 250px;
    width: 100%;
    padding: 10px 0;
}

/* View All Link */
.view-all-link {
    color: #FFC107;
    font-weight: 500;
    text-decoration: none;
    font-size: 14px;
}

.view-all-link:hover {
    text-decoration: underline;
}

/* Mobile menu toggle */
.mobile-menu-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #333;
    margin: 10px;
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .mobile-menu-toggle {
        display: block;
    }
    
    .booking-card {
        min-width: 160px;
    }
    
    .chart-container {
        height: 200px;
    }
    
    .stat-card {
        margin-bottom: 15px;
    }
}

@media (max-width: 768px) {
    .booking-card {
        min-width: 150px;
    }
    
    .transaction-item .icon {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
    
    .chart-container {
        height: 180px;
    }
}

@media (max-width: 576px) {
    .booking-card {
        min-width: 140px;
        padding: 10px;
    }
    
    .booking-card img {
        width: 80px;
        height: 80px;
    }
    
    .stat-card {
        padding: 15px;
    }
    
    .stat-card .icon {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .stat-card h3 {
        font-size: 20px;
    }
    
    .chart-container {
        height: 150px;
    }
}
</style>
