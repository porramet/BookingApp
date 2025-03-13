<!-- resources/views/intro.blade.php -->
@extends('layouts.app')

@section('content')
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-blue-600">
                    วิธีการใช้งาน
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    เรียนรู้วิธีการใช้งานระบบจองห้องออนไลน์ของมหาวิทยาลัย
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <i class="fas fa-user-plus text-5xl text-blue-500"></i>
                    <h4 class="mt-4 text-xl font-semibold text-gray-800">ลงทะเบียน/เข้าสู่ระบบ</h4>
                    <p class="mt-2 text-gray-600">ลงทะเบียนหรือเข้าสู่ระบบเพื่อเริ่มต้นการจอง</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <i class="fas fa-door-open text-5xl text-blue-500"></i>
                    <h4 class="mt-4 text-xl font-semibold text-gray-800">เลือกห้องที่ต้องการ</h4>
                    <p class="mt-2 text-gray-600">เลือกห้องประชุมที่คุณต้องการจอง</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <i class="fas fa-check-circle text-5xl text-blue-500"></i>
                    <h4 class="mt-4 text-xl font-semibold text-gray-800">ยืนยันการจอง</h4>
                    <p class="mt-2 text-gray-600">ยืนยันการจองและรับการยืนยัน</p>
                </div>
            </div>
        </div>
    </section>
@endsection
