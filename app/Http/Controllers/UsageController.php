<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class UsageController extends Controller
{
    public function index()
    {
        return view('usage'); // เรียก view usage.blade.php
    }
}