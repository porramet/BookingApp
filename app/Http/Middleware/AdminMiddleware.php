<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // ตรวจสอบว่าเป็นผู้ดูแลระบบหรือไม่
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        return redirect('/'); // หากไม่ใช่ผู้ดูแลระบบ ให้ไปที่หน้าแรก
    }
}
