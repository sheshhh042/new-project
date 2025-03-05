@extends('layouts.auth')

@section('title', 'OTP Verification')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-4">
            <div class="card shadow-lg p-4 rounded-4">
                <div class="card-body">
                    <h2 class="text-center text-primary mb-3">OTP Verification</h2>
                    <p class="text-muted text-center">Enter the OTP sent to your email</p>
                    <hr />
                    @if(session()->has('error'))
                        <div class="alert alert-danger text-center" role="alert">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                    <form action="{{ route('otp.verify') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="otp" class="form-label">Enter OTP</label>
                            <input type="text" name="otp" class="form-control p-2 text-center" id="otp" placeholder="******" required>
                            @error('otp')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold">Verify OTP</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
