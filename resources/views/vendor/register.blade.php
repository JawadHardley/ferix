@extends('layouts.fair')
@section('content')

<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4 display-6">
            <i class="fa fa-earth-asia"></i> <b>Ferix</b>
        </div>
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible" role="alert">
            <div class="alert-icon">
                <i class="fa fa-exclamation-circle"></i>
            </div>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif
        <form class="card card-md" action="{{ route('vendor.register') }}" method="POST" autocomplete="off"
            novalidate="">
            @csrf
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Vendor Register Form</h2>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                        placeholder="Enter name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                        placeholder="Enter email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Company</label>
                    <input type="text" name="company" value="Ferix io" class="form-control" placeholder="Enter Company"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group input-group-flat">
                        <input type="password" id="password" class="form-control" name="password" placeholder="Password"
                            autocomplete="off" required>
                        <x-password-toggle />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-group input-group-flat">
                        <input type="password" class="form-control" id="password2" name="password_confirmation"
                            placeholder="Password" autocomplete="off" required>
                        <x-password-toggle2 />
                    </div>
                </div>
                <!-- <div class="mb-3">
                    <label class="form-check">
                        <input type="checkbox" class="form-check-input">
                        <span class="form-check-label">Agree the <a href="./terms-of-service.html" tabindex="-1">terms
                                and policy</a>.</span>
                    </label>
                </div> -->
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">Create new account</button>
                </div>
            </div>
        </form>
        <div class="text-center text-secondary mt-3">Already have account? <a href="{{ route('vendor.login') }}"
                tabindex="-1">Sign
                in</a></div>
    </div>
</div>

@endsection