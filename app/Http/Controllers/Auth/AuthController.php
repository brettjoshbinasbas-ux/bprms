<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResidentRegisterRequest;
use App\Models\Admin;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Login page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Login with auto-detect guard (no role selector)
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 1. Check admins table first
        $admin = Admin::where('admin_email', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->admin_password)) {
            auth('admin')->login($admin);
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // 2. Check residents table (excluding soft-deleted)
        $resident = Resident::where('resident_email', $request->email)->first();
        if ($resident && Hash::check($request->password, $resident->resident_password)) {
            auth('resident')->login($resident);
            $request->session()->regenerate();
            return redirect()->route('resident.dashboard');
        }

        // 3. Neither matched
        return back()
            ->withErrors(['email' => 'No account found with these credentials.'])
            ->withInput($request->only('email')); // keep the input field
    }

    // Resident registration
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(ResidentRegisterRequest $request)
    {
        $resident = Resident::create([
            'resident_first_name' => $request->resident_first_name,
            'resident_middle_name' => $request->resident_middle_name,
            'resident_last_name' => $request->resident_last_name,
            'resident_ic_number' => $request->resident_ic_number,
            'resident_phone' => $request->resident_phone,
            'resident_address_line1' => $request->resident_address_line1,
            'resident_address_line2' => $request->resident_address_line2,
            'resident_postcode' => $request->resident_postcode,
            'resident_city' => $request->resident_city,
            'resident_state' => $request->resident_state,
            'resident_email' => $request->resident_email,
            'resident_password' => Hash::make($request->resident_password),
            'residency_duration' => $request->residency_duration,
            'marital_status' => $request->marital_status,
            'mdch_license_holder' => $request->boolean('mdch_license_holder'),
            'business_experience' => $request->boolean('business_experience'),
            'business_type' => $request->business_type,
            'created_at' => now(),
        ]);

        auth('resident')->login($resident);
        $request->session()->regenerate();

        return redirect()->route('resident.dashboard')->with('success', 'Account created successfully. Welcome to BPRMS!');
    }

    // Logout (handles both guards)
    public function logout(Request $request)
    {
        auth('admin')->logout();
        auth('resident')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
