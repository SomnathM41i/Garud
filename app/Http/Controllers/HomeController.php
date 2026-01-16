<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Show settings page
     * - Admin: see all users & roles
     * - User: see own profile
     */
    public function index()
    {
        $user = Auth::user();

        // Admin can see all users
        if ($user->isAdmin()) {
            $users = User::where('id', '!=', $user->id)->get();
            return view('admin.settings.index', compact('users', 'user'));
        }

        // Normal user
        return view('admin.settings.profile', compact('user'));
    }

    /**
     * Update user role (ADMIN ONLY)
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,user',
        ]);

        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'User role updated successfully.');
    }

    /**
     * Update own profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
