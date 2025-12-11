<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password - AP Online Jobs</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2563eb;">AP Online Jobs - Password Reset</h2>
        <p>Hello,</p>
        <p>You are receiving this email because we received a password reset request for your admin account.</p>
        <p>Click the button below to reset your password:</p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/admin/password/reset/' . $token . '?email=' . urlencode($email)) }}" 
               style="display: inline-block; padding: 12px 24px; background-color: #2563eb; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Reset Password
            </a>
        </div>
        <p>Or copy and paste this URL into your browser:</p>
        <p style="word-break: break-all; color: #2563eb;">
            {{ url('/admin/password/reset/' . $token . '?email=' . urlencode($email)) }}
        </p>
        <p>This password reset link will expire in 60 minutes.</p>
        <p>If you did not request a password reset, no further action is required.</p>
        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 12px; color: #666;">
            &copy; {{ date('Y') }} AP Online Jobs. All rights reserved.
        </p>
    </div>
</body>
</html>

