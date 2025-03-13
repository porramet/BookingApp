<header class="bg-transparent absolute top-0 left-0 w-full z-10">
    <!-- Hero Section -->
<!-- resources/views/hero.blade.php -->
<section class="hero-section bg-cover bg-center text-white text-center py-20" style="background-image: url('{{ asset('images/hero-image1.jpg') }}');">
    <div class="container mx-auto px-4">
        <!-- Logo Centered -->
        <!-- Hero Text -->
        <h1 class="text-4xl font-bold">ยินดีต้อนรับสู่ระบบจองห้องออนไลน์มหาวิทยาลัยราชภัฏสกลนคร</h1>
        <p class="mt-4 text-lg">สะดวก รวดเร็ว จองห้องประชุมหรือพื้นที่ได้ทุกที่ ทุกเวลา</p>
        <div class="mt-8">
            <a class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-300 mr-4" href="{{ url('/login') }}">เข้าสู่ระบบ</a>
            @if (request()->is('/') || request()->is('booking'))
                <a class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-300" href="{{ url('/booking') }}">ดูสถานะห้องว่าง</a>
            @endif
        </div>
    </div>
</section>
</header>

<style>
    /* Logo Container */
.logo-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px; /* ปรับระยะห่างจากข้างบน */
}

/* Logo */
.logo {
    height: 190px; /* ปรับขนาดโลโก้ */
    width: auto; /* คงสัดส่วนของโลโก้ */
    margin-right: 20px; /* ระยะห่างระหว่างโลโก้และข้อความ */
}

/* ปรับระยะห่างด้านบนของข้อความ */
.hero-section h1 {
    margin-top: 20px; /* เพิ่มระยะห่างด้านบน */
}



</style>