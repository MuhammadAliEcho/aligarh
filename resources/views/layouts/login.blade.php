<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A Powerfull School Management System">
	<meta name="keywords" content="HTML,CSS,XML,JavaScript,management,system,school,school management system,alirarh,aligarh school management system">
	<meta name="author" content="Hash Management">

    <link rel="icon" href="{{ URL::to('src/icon/favicon.png') }}">
    <title>Login | Aligarh School Management System</title>

    <link href="{{ URL::to('src/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <link href="{{ URL::to('src/css/animate.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/style.css') }}" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
      @yield('container')
    </div>

    <!-- Mainly scripts -->
    <script src="{{ URL::to('src/js/jquery-2.1.1.js') }}"></script>
    <script src="{{ URL::to('src/js/bootstrap.min.js') }}"></script>

    <!-- Jquery Validate -->
    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <script type="text/javascript">
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
        });
    </script>

</body>

</html>
