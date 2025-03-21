<!-- resources/views/contact.blade.php -->
@extends('layouts.app')

@section('content')
<section class="py-16">
<div class="container">
    <div class="text-center mb-4">
        <h2 class="display-4 text-primary">ติดต่อเรา</h2>
        <p class="lead">หากคุณมีคำถามหรือข้อสงสัย สามารถติดต่อเราได้ตามข้อมูลด้านล่าง</p>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title">ข้อมูลติดต่อ</h3>
                    <p class="card-text">อีเมล: support@university.com</p>
                    <p class="card-text">เบอร์โทร: 02-123-4567</p>
                    <p class="card-text">ที่อยู่: 123 ถนนมหาวิทยาลัย แขวงมหาวิทยาลัย เขตมหาวิทยาลัย กรุงเทพฯ 12345</p>
                    <h4 class="mt-4">ติดตามเรา</h4>
                    <div class="mt-2">
                        <a class="text-primary" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="text-primary" href="#"><i class="fab fa-twitter"></i></a>
                        <a class="text-primary" href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title">ส่งข้อความถึงเรา</h3>
                    <form>
                        <div class="form-group">
                            <label for="name">ชื่อ</label>
                            <input class="form-control" id="name" name="name" type="text"/>
                        </div>
                        <div class="form-group">
                            <label for="email">อีเมล</label>
                            <input class="form-control" id="email" name="email" type="email"/>
                        </div>
                        <div class="form-group">
                            <label for="message">ข้อความ</label>
                            <textarea class="form-control" id="message" name="message" rows="4"></textarea>
                        </div>
                        <button class="btn btn-primary" type="submit">ส่งข้อความ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

@endsection
