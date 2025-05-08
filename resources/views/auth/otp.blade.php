@extends('layouts.auth')

@section('title', 'Email Verification')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-4">
            <div class="card shadow-lg p-4 rounded-4" style="background: rgba(255, 255, 255, 0.8); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
                <div class="card-body">
                    <h2 class="text-center text-primary mb-3">Email Verification</h2>
                    <p class="text-muted text-center">We've sent a verification link to your email</p>
                    <hr />
                    @if(session()->has('error'))
                        <div class="alert alert-danger text-center" role="alert">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                    @if(session()->has('success'))
                        <div class="alert alert-success text-center" role="alert">
                            {{ session()->get('success') }}
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Your account has been verified successfully!',
                                    icon: 'success',
                                    confirmButtonText: 'Continue',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "{{ route('dashboard') }}";
                                    }
                                });
                            });
                        </script>
                    @endif
                    
                    <div class="text-center">
                        <p>Check your email for a verification link.</p>
                        <p>Didn't receive the email?</p>
                        <form action="{{ route('verification.resend') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link">Resend Verification Email</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Include SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection