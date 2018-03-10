<!DOCTYPE html>
<html>

  <head>

      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <title>@yield('title')</title>
      <link rel="icon" href="{{ URL::to('src/icon/favicon.png') }}">

      <link href="{{ URL::to('src/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ URL::to('src/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

      <link href="{{ URL::to('src/css/animate.css') }}" rel="stylesheet">
      <link href="{{ URL::to('src/css/style.css') }}" rel="stylesheet">

  </head>

  <body class="gray-bg">

    @yield('content')
      <!-- Mainly scripts -->
      <script src="{{ URL::to('src/js/jquery-2.1.1.js') }}"></script>
      <script src="{{ URL::to('src/js/bootstrap.min.js') }}"></script>

  </body>

</html>
