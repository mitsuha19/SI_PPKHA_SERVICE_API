<!-- login.blade.php -->
@extends('layouts.auth')

@section('content')
<div class="form-container">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <h2>Login</h2>

        <div class="form-group">
            <label for="nim"><i class="icon fa fa-user"></i> NIM</label>
            <input type="text" name="nim" id="nim" placeholder="Enter your NIM" value="{{ old('nim') }}" required>
            @error('nim') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="password"><i class="icon fa fa-lock"></i> Password</label>
            <input type="password" name="password" id="password" placeholder="Enter your Password" required>
            @error('password') <span class="error">{{ $message }}</span> @enderror
        </div>

        <!-- reCAPTCHA -->
        {!! NoCaptcha::renderJs() !!}
        @if(config('app.login_require_captcha'))
        {!! NoCaptcha::display() !!}
        <br>
        <span class="help-block">
            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
        </span>
        @endif

        <button type="submit" class="btn btn-primary">Login</button>

    </form>
</div>
@endsection