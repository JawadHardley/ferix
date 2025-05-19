<!-- resources/views/auth/verify.blade.php -->

@extends('layouts.fair')

@section('content')
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4 display-6">
            <i class="fa fa-earth-asia"></i> <b>Ferix</b>
        </div>
        <div class="card card-md">
            <div class="card-body">

                <div class="container">
                    <h1 class="text-center mb-3">Email Verification</h1>
                    <p class="text-center">
                        Please check your email for the verification link. If you did not receive the email, you can
                    </p>
                    <form action="{{ route('verification.send') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-8">
                                <button class="btn btn-primary w-100">
                                    Send again
                                </button>
                            </div>

                            <div class="col-4">
                                <a href="{{ route(Auth::user()->role . '' .'.dashboard') }}"
                                    class="btn btn-outline-secondary w-100">
                                    Back
                                </a>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
        <!-- <div class="text-center text-secondary mt-3">Don't have account yet? <a href="./sign-up.html" tabindex="-1">Sign
                up</a></div> -->
    </div>
</div>
@endsection