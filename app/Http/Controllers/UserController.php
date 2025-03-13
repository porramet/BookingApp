<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function changePassword(Request $request)
    {
        // Password change logic here
        return redirect()->back()->with('success', 'Password changed successfully');
    }
}
