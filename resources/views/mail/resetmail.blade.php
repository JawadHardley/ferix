<p>You requested a password reset.</p>
<p>Click the link below to reset your password:</p>

<a href="{{ url('/reset-password/' . $token . '?email=' . urlencode($email)) }}">
    Reset Password
</a>

<p>If you didn’t request this, you can ignore this email.</p>