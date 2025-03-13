<!-- resources/views/usage.blade.php -->
@extends('layouts.app')

@section('content')
    <!-- How It Works Section -->
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
      <i class="fas fa-user-plus text-5xl text-blue-500">
      </i>
      <h4 class="mt-4 text-xl font-semibold text-gray-800">
       ลงทะเบียน/เข้าสู่ระบบ
      </h4>
      <p class="mt-2 text-gray-600">
       ลงทะเบียนหรือเข้าสู่ระบบเพื่อเริ่มต้นการจอง
      </p>
     </div>
     <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
      <i class="fas fa-door-open text-5xl text-blue-500">
      </i>
      <h4 class="mt-4 text-xl font-semibold text-gray-800">
       เลือกห้องที่ต้องการ
      </h4>
      <p class="mt-2 text-gray-600">
       เลือกห้องประชุมที่คุณต้องการจอง
      </p>
     </div>
     <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
      <i class="fas fa-check-circle text-5xl text-blue-500">
      </i>
      <h4 class="mt-4 text-xl font-semibold text-gray-800">
       ยืนยันการจอง
      </h4>
      <p class="mt-2 text-gray-600">
       ยืนยันการจองและรับการยืนยัน
      </p>
     </div>
    </div>
    <div class="mt-12">
     <h3 class="text-2xl font-bold text-blue-600 text-center">
      ขั้นตอนการใช้งาน
     </h3>
     <div class="mt-8 space-y-8">
      <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
       <h4 class="text-xl font-semibold text-gray-800">
        1. ลงทะเบียน/เข้าสู่ระบบ
       </h4>
       <p class="mt-2 text-gray-600">
        เริ่มต้นด้วยการลงทะเบียนหรือเข้าสู่ระบบด้วยบัญชีผู้ใช้ของคุณ
       </p>
       <img alt="ภาพหน้าจอการลงทะเบียนหรือเข้าสู่ระบบ" class="w-full h-48 object-cover rounded-lg mt-4" height="400" src="https://storage.googleapis.com/a1aa/image/NguYrcaxceWQK6YYfUFA8kf1ysbvGZPiaKAydgrYyUj1ZDQoA.jpg" width="800"/>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
       <h4 class="text-xl font-semibold text-gray-800">
        2. เลือกห้องที่ต้องการ
       </h4>
       <p class="mt-2 text-gray-600">
        เลือกห้องประชุมที่คุณต้องการจองจากรายการห้องที่มีให้บริการ
       </p>
       <img alt="ภาพหน้าจอการเลือกห้องประชุม" class="w-full h-48 object-cover rounded-lg mt-4" height="400" src="https://storage.googleapis.com/a1aa/image/JnZfjobEzEw3Gqu5qgbOenf9JefhkAE7vGFTfKrbMkOCObACF.jpg" width="800"/>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
       <h4 class="text-xl font-semibold text-gray-800">
        3. ยืนยันการจอง
       </h4>
       <p class="mt-2 text-gray-600">
        ตรวจสอบรายละเอียดการจองและยืนยันการจองของคุณ
       </p>
       <img alt="ภาพหน้าจอการยืนยันการจอง" class="w-full h-48 object-cover rounded-lg mt-4" height="400" src="https://storage.googleapis.com/a1aa/image/HB25QSXCHsJDL5bgSOjjVAweCnw4QFOOtwByqW1WLipb2AEKA.jpg" width="800"/>
      </div>
     </div>
    </div>
   </div>
  </section>
@endsection