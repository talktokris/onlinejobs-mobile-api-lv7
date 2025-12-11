<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display admin profile
     */
    public function show()
    {
        $user = Auth::user();
        return view('webapp.profile.show', compact('user'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = Auth::user();
        return view('webapp.profile.edit', compact('user'));
    }

    /**
     * Update admin profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('admin.profile.show')->with('success', 'Profile updated successfully.');
    }

    /**
     * Show change password form
     */
    public function showChangePassword()
    {
        return view('webapp.profile.change-password');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.profile.show')->with('success', 'Password changed successfully.');
    }
}

