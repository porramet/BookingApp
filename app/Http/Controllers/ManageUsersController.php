<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ManageUsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
        }

        $users = $query->paginate(50);
        
        // Get user statistics
        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $regularUserCount = $totalUsers - $adminCount;

        return view('dashboard.manage_users', compact('users', 'totalUsers', 'adminCount', 'regularUserCount'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'role' => $request->role
        ]);

        return redirect()->route('manage_users')
            ->with('success', 'User role updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('manage_users')
            ->with('success', 'User deleted successfully');
    }
}
