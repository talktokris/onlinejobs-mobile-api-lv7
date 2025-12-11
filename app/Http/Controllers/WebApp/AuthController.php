<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function login()
    {
        if (Auth::check() && Auth::user()->role_id == 3) {
            return redirect()->route('admin.dashboard');
        }
        return view('webapp.auth.login');
    }

    /**
     * Handle login request
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user is admin (role_id = 3)
            if ($user->role_id != 3) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'You do not have permission to access the admin panel.',
                ])->withInput($request->only('email'));
            }

            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPassword()
    {
        return view('webapp.auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        
        // Check if user is admin
        if ($user->role_id != 3) {
            return back()->withErrors([
                'email' => 'This email is not associated with an admin account.',
            ]);
        }

        // Generate password reset token
        $token = Str::random(64);
        
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );

        // Send password reset email
        try {
            Mail::send('emails.reset-password', ['token' => $token, 'email' => $request->email], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password - AP Online Jobs Admin');
            });

            return back()->with('status', 'We have emailed your password reset link!');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Failed to send email. Please try again later.',
            ]);
        }
    }

    /**
     * Show reset password form
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('webapp.auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Invalid reset token.']);
        }

        // Check if token is valid (within 1 hour)
        if (Carbon::parse($passwordReset->created_at)->addHour()->isPast()) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Reset token has expired. Please request a new one.']);
        }

        // Verify token
        if (!Hash::check($request->token, $passwordReset->token)) {
            return back()->withErrors(['email' => 'Invalid reset token.']);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        
        if ($user->role_id != 3) {
            return back()->withErrors(['email' => 'This email is not associated with an admin account.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Delete password reset record
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('admin.login')->with('status', 'Your password has been reset!');
    }
}
