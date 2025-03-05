@extends('layouts.app')

@section('title', 'Profile & Settings')

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Profile Information Section -->
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-header bg-primary text-white">
                    <h4>Profile Information</h4>
                </div>
                <div class="card-body">
                    <!-- Profile Icon Based on Role -->
                    <div class="mb-3">
                        @if(auth()->user()->role == 'admin')
                            <i class="fas fa-user-shield fa-5x text-primary"></i> <!-- Admin Icon -->
                        @else
                            <i class="fas fa-user fa-5x text-secondary"></i> <!-- Regular User Icon -->
                        @endif
                    </div>
                    <h5 class="font-weight-bold">{{ auth()->user()->name }}</h5>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                    <span class="badge 
                        {{ auth()->user()->role == 'admin' ? 'badge-danger' : 'badge-secondary' }}">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Profile & Settings Section -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Profile & Settings</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="profileTabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#profile">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#settings">Settings</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Profile Form -->
                        <div class="tab-pane fade show active" id="profile">
                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" name="name" 
                                                placeholder="Full Name" value="{{ auth()->user()->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" name="email" 
                                                placeholder="Email" value="{{ auth()->user()->email }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="text" class="form-control" name="phone" placeholder="Phone Number">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" class="form-control" name="address" 
                                                placeholder="Address" value="{{ auth()->user()->address }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                                </div>
                            </form>
                        </div>

                        <!-- Settings Form -->
                        <div class="tab-pane fade" id="settings">
                            <form action="{{ route('settings.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label>Change Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="New Password">
                                </div>

                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm New Password">
                                </div>

                                <div class="form-group">
                                    <label>Notification Preferences</label>
                                    <select class="form-control" name="notifications">
                                        <option value="enabled">Enable Notifications</option>
                                        <option value="disabled">Disable Notifications</option>
                                    </select>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-success w-100">Save Settings</button>
                                </div>
                            </form>
                        </div>
                    </div> <!-- End Tab Content -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#profileTabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
@endsection
