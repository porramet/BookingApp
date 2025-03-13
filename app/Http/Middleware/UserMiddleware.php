<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // ตรวจสอบว่าเป็นผู้ใช้ทั่วไปหรือไม่
        if (Auth::check() && Auth::user()->isUser()) {
            return $next($request);
        }

        return redirect('/'); // หากไม่ใช่ผู้ใช้ทั่วไป ให้ไปที่หน้าแรก
    }
}
