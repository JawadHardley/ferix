<!-- resources/views/auth/verify.blade.php -->

@extends('layouts.fair')

@section('content')
<div class="container">
    <h1>Email Verification</h1>
    <p>Please check your email for the verification link. If you did not receive the email, you can

    </p>
    <form action="{{ route('verification.send') }}" method="POST">
        @csrf

        <button class="btn btn-primary">
            send again
        </button>
    </form>

</div>
@endsection