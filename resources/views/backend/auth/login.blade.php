@extends('backend.layouts.auth')

@section('title', 'Login')
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/parsley.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/toastr.min.css') }}">
@endpush
@section('content')
    <div class="login-box">
        <div class="card card-outline">
            <div class="text-center card-header">
                <a href="{{ route('home') }}">
                    <img src="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}" alt="Logo" style="width:150px;">
                </a>
            </div>
            <div class="card-body login-card-body">

                <div class="text-center">
                    <h4>Welcome to {{ get_settings('system_name') ? get_settings('system_name') : 'Project Alpha' }}</h4>
                    <p class="login-box-msg">Sign in to start your session</p>
                </div>

                <form action="{{ route('admin.login.post') }}"  method="post" class="login_form">
                    @csrf
                    <fieldset id="loginFieldset" disabled>

                        <div class="input-group mb-1">
                            <div class="form-floating">
                                <input id="email" type="email" class="form-control" name="email" required>
                                <label for="email">Email</label>
                            </div>
                            <div class="input-group-text">
                                <span class="bi bi-envelope"></span>
                            </div>
                        </div>

                        <div class="input-group mb-1">
                            <div class="form-floating">
                                <input id="password" name="password" required type="password" class="form-control">
                                <label for="password">Password</label>
                            </div>
                            <div class="input-group-text">
                                <span class="bi bi-lock-fill"></span>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-grid gap-2">
                                    <button type="submit" id="submit" style="display: none;"  class="btn btn-primary">
                                        <i class="bi bi-send"></i>
                                        Sign In
                                    </button>
                                    <button class="btn btn-primary" id="submitting" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection
