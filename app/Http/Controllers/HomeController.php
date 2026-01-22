<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\MetalRate;
use Carbon\Carbon;

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


    /**
     * Show metal rate update form (ADMIN ONLY)
     */
    public function showMetalRateForm()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $today = Carbon::today();

        // Get today's rates
        $goldToday = MetalRate::today('gold')->first();
        $silverToday = MetalRate::today('silver')->first();

        // If today's rate not found, get latest old rate
        $goldRate = $goldToday ?? MetalRate::where('metal', 'gold')
            ->orderBy('rate_date', 'desc')
            ->first();

        $silverRate = $silverToday ?? MetalRate::where('metal', 'silver')
            ->orderBy('rate_date', 'desc')
            ->first();

        return view('admin.settings.metal-rate', [
            'goldRate' => $goldRate,
            'silverRate' => $silverRate,
            'today' => $today->format('d M Y'),
            'isToday' => [
                'gold' => (bool) $goldToday,
                'silver' => (bool) $silverToday,
            ]
        ]);
    }

    /**
     * Update metal rate (ADMIN ONLY)
     */
    public function updateMetalRate(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'metal' => 'required|in:gold,silver',
            'rate_per_gram' => 'required|numeric|min:0',
        ]);

        $today = Carbon::today()->toDateString();

        MetalRate::updateOrCreate(
            [
                'metal' => $request->metal,
                'rate_date' => $today,
            ],
            [
                'rate_per_gram' => $request->rate_per_gram,
            ]
        );

        return redirect()
            ->route('admin.metal-rate.form')
            ->with('success', ucfirst($request->metal) . ' rate updated successfully.');
    }

}
