@extends('layouts.fair')
@section('content')

<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4 display-6">
            <i class="fa fa-earth-asia"></i> <b>Ferix</b>
        </div>
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Admin Login</h2>
                <x-errorshow />
                <form action="{{ route('admin.login') }}" method="POST" autocomplete="off" novalidate="">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter email"
                            value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group input-group-flat">
                            <input type="password" id="password" class="form-control" name="password"
                                placeholder="Password" autocomplete="off">
                            <x-password-toggle />
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input">
                            <span class="form-check-label">Remember me on this device</span>
                        </label>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- <div class="text-center text-secondary mt-3">Don't have account yet? <a href="./sign-up.html" tabindex="-1">Sign
                up</a></div> -->
    </div>
</div>

@endsection