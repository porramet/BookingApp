<!-- resources/views/contact.blade.php -->
@extends('layouts.app')

@section('content')
@include('hero')
<section class="py-16">
<div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-blue-600">ติดต่อเรา</h2>
                <p class="mt-4 text-lg text-gray-600">หากคุณมีคำถามหรือข้อสงสัย สามารถติดต่อเราได้ตามข้อมูลด้านล่าง</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <h3 class="text-xl font-semibold text-gray-800">ข้อมูลติดต่อ</h3>
                    <p class="mt-4 text-gray-600">อีเมล: support@university.com</p>
                    <p class="mt-2 text-gray-600">เบอร์โทร: 02-123-4567</p>
                    <p class="mt-2 text-gray-600">ที่อยู่: 123 ถนนมหาวิทยาลัย แขวงมหาวิทยาลัย เขตมหาวิทยาลัย กรุงเทพฯ 12345</p>
                    <div class="mt-4">
                        <h4 class="text-lg font-semibold text-gray-800">ติดตามเรา</h4>
                        <div class="mt-2 space-x-4">
                            <a class="text-blue-500 hover:text-blue-700 transition duration-300" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="text-blue-500 hover:text-blue-700 transition duration-300" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="text-blue-500 hover:text-blue-700 transition duration-300" href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <h3 class="text-xl font-semibold text-gray-800">ส่งข้อความถึงเรา</h3>
                    <form class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="name">ชื่อ</label>
                            <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="name" name="name" type="text"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="email">อีเมล</label>
                            <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" name="email" type="email"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="message">ข้อความ</label>
                            <textarea class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="message" name="message" rows="4"></textarea>
                        </div>
                        <div class="text-center">
                            <button class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition duration-300" type="submit">ส่งข้อความ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection