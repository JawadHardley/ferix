@extends('layouts.fair')
@section('content')

<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4 display-6">
            <i class="fa fa-earth-asia"></i> <b>Ferix</b>
        </div>
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Reset Password</h2>
                <x-errorshow />
                <form action="{{ route('password.update') }}" method="POST" autocomplete="off" novalidate="">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter email"
                            value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <div class="input-group input-group-flat">
                            <input type="password" id="password" class="form-control" name="password"
                                placeholder="New Password" autocomplete="off">
                            <x-password-toggle />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <div class="input-group input-group-flat">
                            <input type="password" id="password" class="form-control" name="password_confirmation"
                                placeholder="Confirm New Password" autocomplete="off">
                            <x-password-toggle />
                        </div>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center text-secondary mt-3">
            <a class="text-decoration-none" href="{{ route('login') }}" tabindex="-1">
                Home >
            </a>
        </div>
    </div>
</div>

@endsection

<!-- 


<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="New password">
    <input type="password" name="password_confirmation" required placeholder="Confirm password">
    <button type="submit">Reset Password</button>
</form> -->