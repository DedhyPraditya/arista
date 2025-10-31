@extends('layouts.app')
@section('title', 'Profile')
@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="nav-item">
        <i class="fas fa-fw fa-user"></i> / <span>Profile</span>
    </div>

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Profile Settings</h1>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user-profile-information.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Update Password -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Update Password</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user-password.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- Two Factor Authentication -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Two Factor Authentication</h6>
                </div>
                <div class="card-body">
                    @if (Auth::user()->two_factor_secret)
                        <div class="alert alert-success">
                            <i class="fas fa-shield-alt"></i> Two Factor Authentication is enabled.
                        </div>
                        <form method="POST" action="{{ route('two-factor.disable') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-times"></i> Disable Two Factor Authentication
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Two Factor Authentication is not enabled.
                        </div>
                        <a href="{{ route('profile.two-factor-setup') }}" class="btn btn-success">
                            <i class="fas fa-shield-alt"></i> Setup Two Factor Authentication
                        </a>
                    @endif
                </div>
            </div>

            <!-- Browser Sessions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Browser Sessions</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">Manage and logout your active sessions on other browsers and devices.</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-sign-out-alt"></i> Logout from All Devices
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Summary -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Summary</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if (Auth::user()->profile_photo_path)
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" 
                                 class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 120px; height: 120px;">
                                <i class="fas fa-user fa-3x text-white"></i>
                            </div>
                        @endif
                    </div>
                    <h5 class="font-weight-bold">{{ Auth::user()->name }}</h5>
                    <p class="text-muted">{{ Auth::user()->email }}</p>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar"></i> 
                            Member since {{ Auth::user()->created_at->format('M Y') }}
                        </small>
                    </div>
                    
                    @if (Auth::user()->two_factor_secret)
                    <div class="mt-2">
                        <span class="badge badge-success">
                            <i class="fas fa-shield-alt"></i> 2FA Enabled
                        </span>
                    </div>
                    @else
                    <div class="mt-2">
                        <span class="badge badge-warning">
                            <i class="fas fa-exclamation-triangle"></i> 2FA Disabled
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Security Tips -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Security Tips</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            Use a strong password
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            Enable Two Factor Authentication
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            Keep your email updated
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            Logout from other devices
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
