<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A Powerfull School Management System">
	<meta name="keywords" content="HTML,CSS,XML,JavaScript,management,system,school,school management system,alirarh,aligarh school management system">
	<meta name="author" content="Hash Management">

    <link rel="icon" href="{{ asset('src/icon/favicon.png') }}">
    <title>Login | Aligarh School Management System</title>

    <link href="{{ asset('src/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <link href="{{ asset('src/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/style.css') }}" rel="stylesheet">
     <!-- Toastr style -->
    <link href="{{ asset('src/css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
      @yield('container')
    </div>

    <!-- Mainly scripts -->
    <script src="{{ asset('src/js/jquery-2.1.1.js') }}"></script>
    <script src="{{ asset('src/js/bootstrap.min.js') }}"></script>
     <!-- Toastr js -->
    <script src="{{ asset('src/js/plugins/toastr/toastr.min.js') }}"></script>

    <!-- Jquery Validate -->
    <script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <script type="text/javascript">
        @if (session('status'))
            window.onload = function() {
                toastr.success("{{ session('status') }}");
            }
        @endif
        function toggle(form, reset_form) {
            document.getElementById("form").style.display = form;
            document.getElementById("reset_form").style.display = reset_form;
        }


         $('document').ready(function(){
            $("#form").validate({
                rules: {
                    password: {
                        required: true,
                        minlength: 6,
                        maxlength: 12
                    },
                    userid: {
                    required: true
                    }
                },
                messages: {
                password: { required: 'Password is required'},
                userid: 'UserID is required'
                }
            });

            $("#reset-password-form").validate({
                rules: {
                    password: {
                        required: true,
                        minlength: 6,
                        maxlength: 12
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    }
                },
                messages: {
                    password: {
                        required: 'Password is required',
                        minlength: 'Password must be at least 6 characters',
                        maxlength: 'Password must not exceed 12 characters'
                    },
                    password_confirmation: {
                        required: 'Please confirm your password',
                        equalTo: 'Passwords do not match'
                    }
                }
            });

        });
    </script>

</body>

</html>
