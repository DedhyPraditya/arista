@extends('layouts.app')
@section('title', 'Setup Two Factor Authentication')
@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="nav-item">
        <i class="fas fa-fw fa-user"></i> / <a href="{{ route('profile.custom') }}">Profile</a> / <span>Setup 2FA</span>
    </div>

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Setup Two Factor Authentication</h1>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Enable Two Factor Authentication</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Two Factor Authentication (2FA)</strong> adds an extra layer of security to your account. 
                        You'll need to use an authenticator app to generate codes when logging in.
                    </div>

                    <h5>Step 1: Install an Authenticator App</h5>
                    <p>Download and install one of these authenticator apps on your mobile device:</p>
                    <ul>
                        <li><strong>Google Authenticator</strong> (Android/iOS)</li>
                        <li><strong>Authy</strong> (Android/iOS/Desktop) - Recommended</li>
                        <li><strong>Microsoft Authenticator</strong> (Android/iOS)</li>
                    </ul>

                    <h5 class="mt-4">Step 2: Setup 2FA</h5>
                    <p>Click the button below to start the setup process:</p>
                    
                    <form method="POST" action="{{ route('two-factor.enable') }}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-shield-alt"></i> Enable Two Factor Authentication
                        </button>
                    </form>

                    <div class="mt-4">
                        <a href="{{ route('profile.custom') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Security Tips -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Security Tips</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            Keep your authenticator app secure and don't share it with others
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            Save your recovery codes in a safe place
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            Use a strong, unique password for your account
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            Keep your device's time synchronized
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
