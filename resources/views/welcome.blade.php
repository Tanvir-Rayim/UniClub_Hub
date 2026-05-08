@extends('layouts.app')

@section('title', 'Welcome - UniClub Hub')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center py-5">
                <h1 class="display-4 fw-bold mb-4">Welcome to UniClub Hub</h1>
                <p class="lead text-muted mb-4">
                    Centralized University Club Management System
                </p>
                <p class="mb-5">
                    Manage your clubs, events, budgets, and memberships all in one place.
                </p>

                @guest
                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 gap-3">
                            Get Started - Register
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg px-4">
                            Already a member? Login
                        </a>
                    </div>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                        Go to Dashboard
                    </a>
                @endguest
            </div>

            <div class="row mt-5">
                <div class="col-md-4 text-center mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">📚 For Students</h5>
                            <p class="card-text">Join clubs, register for events, and connect with peers.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">🎯 For Executives</h5>
                            <p class="card-text">Manage members, propose events, and handle budgets.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">👨‍💼 For Admins</h5>
                            <p class="card-text">Oversee clubs, manage approvals, and allocate venues.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
