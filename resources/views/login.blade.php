@extends('layouts.login')

@section('container')
    <div>
        <div>
            <h6>
                <img alt="image" class="img-responsive" src="{{ asset('src/icon/favicon.png') }}" />
            </h6>
            <!--h6 class="logo-name">Aligarh</h6-->
        </div>
        <h3>Welcome to Aligarh</h3>
        <p>A Powerfull School Management System</p>
        <p>Login in. To Control IT.</p>

        <form class="m-t" role="form" method="POST" id="form" action="{{ route('login') }}">
            {{ csrf_field() }}
            @if (Session::has('redirect'))
                <input type="hidden" name="redirect" value="{{ old('redirect', Session::get('redirect')) }}" />
            @endif
            <div class="form-group{{ $errors->has('email') || $errors->has('name') ? ' has-error' : '' }}">
                <input type="text" class="form-control" placeholder="UserID" autofocus="true" name="userid"
                    value="{{ old('name') }}{{ old('email') }}">
                <span class="help-block">
                    @if (env('DB_DATABASE') == 'muhammad_aligarh')
                        Username: Demo
                    @endif
                </span>
                @if ($errors->has('name') || $errors->has('email'))
                    <span class="help-block">
                        <strong><span class="fa fa-exclamation-triangle"></span>
                            {{ $errors->first('name') }}{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" class="form-control" placeholder="Password" name="password">
                <span class="help-block">
                    @if (env('DB_DATABASE') == 'muhammad_aligarh')
                        Password: 123456
                    @endif
                </span>
                @if ($errors->has('password') || $errors->has('invalid'))
                    <span class="help-block text-danger">
                        <strong><span class="fa fa-exclamation-triangle"></span>
                            {{ $errors->first('password') }}{{ $errors->first('invalid') }}</strong>
                    </span>
                @endif
            </div>
            @if (Session::has('toastrmsg'))
                <span class="help-block text-danger">
                    <strong><span class="fa fa-exclamation-triangle"></span> {{ Session::get('toastrmsg.msg') }}</strong>
                </span>
            @endif
            <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
            <div class="col-12 float-right">
                <a href="javascript:;" onclick="toggle('none','block')" class="text-primary text-hover-primary">Forgot
                    Password?</a>
            </div>

            {{--
        <a href="#"><small>Forgot password?</small></a>
        <p class="text-muted text-center"><small>Do not have an account?</small></p>
        <a class="btn btn-sm btn-white btn-block" href="register.html">Create an account</a>
        --}}

        </form>
        <form action="{{ route('password.email') }}" method="POST" id="reset_form" style="display: none;">
            @csrf
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" class="form-control" placeholder="Email" name="email"
                        value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <span class="help-block text-danger">
                            <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                @if ($errors->has('email'))
                    <span class="help-block text-danger">
                        <strong><span class="fa fa-exclamation-triangle"></span>
                            {{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            @if (Session::has('password_reset'))
                <div class="alert alert-success">
                    {{ Session::get('password_reset') }}
                </div>
            @endif

            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
                <a href="javascript:;" onclick="toggle('block','none')" class="text-primary text-hover-primary mt-2 d-block"
                    id="cancel_btn">Cancel</a>
            </div>
        </form>
        <p class="m-t"> <small><b>Copyright</b> HASHMANAGEMENT &copy; {{ now()->year }}</small> </p>
    </div>
@endsection
