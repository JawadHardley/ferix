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
                <form action="{{ route('password.email') }}" method="POST" autocomplete="off" novalidate="">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class=" form-control" placeholder="Enter email">
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center text-secondary mt-3">
            <a class="text-decoration-none" href="{{ route('homesweethome') }}" tabindex="-1">
                Home >
            </a>
        </div>
    </div>
</div>

@endsection