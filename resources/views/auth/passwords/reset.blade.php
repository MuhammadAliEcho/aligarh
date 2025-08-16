@extends('layouts.login')

@section('container')
<div>
    <div>
        <h6>
            <img alt="image" class="img-responsive" src="{{ URL::to('src/icon/favicon.png') }}" />
        </h6>
    </div>
    <h3>Reset Your Password</h3>
    <p>Please enter your new password below.</p>

    <form method="POST" action="{{ route('password.update') }}" id="reset-password-form">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                name="email" value="{{ old('email', $email) }}" required autocomplete="email" autofocus
                placeholder="E-Mail Address">

            @error('email')
                <span class="help-block text-danger">
                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                name="password" required autocomplete="new-password" placeholder="New Password">

            @error('password')
                <span class="help-block text-danger">
                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <input id="password-confirm" type="password" class="form-control"
                name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
        </div>

        <button type="submit" class="btn btn-primary block full-width m-b">
            {{ __('Reset Password') }}
        </button>

        <p class="m-t">
            <a href="{{ url('login') }}" class="text-primary">Back to Login</a>
        </p>
    </form>

    <p class="m-t"> <small><b>Copyright</b> HASHMANAGEMENT &copy; {{ now()->year }}</small> </p>
</div>
@endsection
