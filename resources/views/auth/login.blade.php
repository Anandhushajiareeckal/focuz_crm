@extends('layouts.auth_layout')
@section('content')
    <p class="login-box-msg">Sign in to start your session</p>
    <div class="row">
        <div class="col">
            @foreach (['email', 'password'] as $field)
                @foreach ($errors->get($field) as $message)
                    <div class="alert alert-sm alert-info">
                        {{ $message }}
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
    <form action="{{ route('login') }}" method="post">
        @csrf
        <div class="input-group mb-3">
            <input type="email" class="form-control" name="email" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>
        @error('email')
            <div class="fv-plugins-message-container invalid-feedback">
                {{ $message }}
            </div>
        @enderror
        <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-right">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>

        </div>
    </form>

    <p class="mb-1">
        <a href="{{ route('password.request') }}">I forgot my password</a>
    </p>
@endsection
