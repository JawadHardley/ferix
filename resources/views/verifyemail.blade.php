<!-- resources/views/auth/verify.blade.php -->

@extends('layouts.fair')

@section('content')
<div class="container">
    <h1>Email Verification</h1>
    <p>Please check your email for the verification link. If you did not receive the email, you can <a
            href="{{ route('verification.resend') }}">click here to request another one</a>.</p>
</div>
@endsection