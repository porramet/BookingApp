<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>สมัครสมาชิก - ระบบจองห้องออนไลน์ของมหาวิทยาลัย</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-image: url('{{ asset('images/bg-1.jpg') }}');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">สมัครสมาชิก</h2>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form class="space-y-6" action="{{ route('register') }}" method="POST">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700" for="name">ชื่อ</label>
                <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="name" name="name" type="text" required/>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700" for="email">อีเมล</label>
                <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" name="email" type="email" required/>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700" for="password">รหัสผ่าน</label>
                <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="password" name="password" type="password" required/>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700" for="password_confirmation">ยืนยันรหัสผ่าน</label>
                <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="password_confirmation" name="password_confirmation" type="password" required/>
            </div>
            <div>
                <button class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300" type="submit">สมัครสมาชิก</button>
            </div>
        </form>
        <p class="mt-6 text-center text-gray-600">มีบัญชีอยู่แล้ว? <a class="text-blue-500 hover:underline" href="{{ url('/login') }}">เข้าสู่ระบบ</a></p>
        <p class="mt-6 text-center text-gray-600"><a class="text-blue-500 hover:underline" href="{{ url('/') }}">กลับสู่หน้าหลัก</a></p>
    </div>
</body>
</html>
