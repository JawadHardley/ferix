@extends('layouts.fair')
@section('content')

<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4 display-6">
            <i class="fa fa-earth-asia"></i> <b>Ferix</b>
        </div>
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Vendor Login</h2>
                @if(session('message'))
                <!-- <div class="col-12"> -->
                <div class="alert alert-{{ session('status') === 'success' ? 'success' : 'danger' }} alert-dismissible"
                    role="alert">
                    <div class="alert-icon">
                        <i class="fa fa-{{ session('status') === 'success' ? 'check' : 'circle-xmark' }}"></i>
                    </div>
                    {{ session('message') }}
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
                <!-- </div> -->
                @endif

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
                <form action="{{ route('vendor.login') }}" method="POST" autocomplete="off" novalidate="">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                            placeholder="Enter email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group input-group-flat">
                            <input type="password" id="password" class="form-control" name="password"
                                placeholder="Password" autocomplete="off" required>
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
        <div class="text-center text-secondary mt-3">Don't have account yet? <a href="{{ route('vendor.register') }}"
                tabindex="-1">Sign
                up</a></div>
    </div>
</div>

@endsection