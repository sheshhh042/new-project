<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileSettingsController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile-settings', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('profile.settings')->with('success', 'Profile updated successfully.');
    }

    // ✅ Add the missing profile() method
    public function profile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user')); // Use 'auth.profile' instead of 'profile'
    }
}
