<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('title') Aligarh School Management System</title>
    <link rel="icon" href="{{ URL::to('src/icon/favicon.png') }}">

    <link href="{{ URL::to('src/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/entypo/entypo.css') }}" rel="stylesheet">

    <!-- Toastr style -->
    <link href="{{ URL::to('src/css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">

    <!-- Gritter -->
    <link href="{{ URL::to('src/js/plugins/gritter/jquery.gritter.css') }}" rel="stylesheet">

    <link href="{{ URL::to('src/css/animate.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/style.css') }}" rel="stylesheet">
	
    {{-- Notification Bell --}}
    <link href="{{ URL::to('/src/css/notification-bell.css') }}" rel="stylesheet">
    
    @yield('head')

    <script src="{{ URL::to('src/js/jquery-2.1.1.js') }}"></script>

</head>

<body class="pace-done md-skin {{ Auth::user()->settings->skin_config->nav_collapse }}">
	@if( config('systemInfo.general.validity') < Carbon\Carbon::now()->toDateString())
	<div class="alert alert-danger" role="alert"> <span class="glyphicon glyphicon-warning-sign" ></span> <b> The System Is Expired!</b></div>
    @endif
    <div id="wrapper">
        <div id="app">
            @yield('content')
        </div>
        @include('admin.includes.footercopyright')
    </div>

    <!-- Mainly scripts -->
    <script src="{{ URL::to('src/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{ URL::to('src/js/custom.js') }}"></script>


    <!-- Toastr -->
    <script src="{{ URL::to('src/js/plugins/toastr/toastr.min.js') }}"></script>


    <!-- Lodash version 4.17.10 -->
    <script src="{{ URL::to('src/lodash.min.js') }}"></script>

	@if(env('APP_DEBUG'))
        <!-- Vue dev version -->
        <script src="{{ URL::to('src/vue.js') }}"></script>
    @else
        <!-- Vue -->
        <script src="{{ URL::to('src/vue.min-2.5.15.js') }}"></script>
    @endif

    @yield('vue')

	@if(Session::get('toastrmsg') !== null)

        <script type="text/javascript">
	  $(document).ready(function(){
                setTimeout(function() {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        showMethod: 'slideDown',
                        timeOut: 8000
                    };
			toastr.{{ Session::get('toastrmsg')['type'] }}('{{ Session::get('toastrmsg.msg') }}', '{{ Session::get('toastrmsg.title') }}' );
                }, 1300);
            });
        </script>

    @endif

	 @if(Session::get('script') !== null)
        <script type="text/javascript">
			$(document).ready(function(){
                window.open('{{ URL(Session::get('script')) }}', '_new');
            });
        </script>
    @endif

    @yield('script')


    <script type="text/javascript">
  $(document).ready(function(){

	$('a[href="#"]').click(function(e){
                e.preventDefault();
            });

        });

    </script>

    <script src="{{ URL::to('src/js/bootstrap.min.js') }}"></script>

    {{--    @include('admin.includes.skin_config')  --}}


</body>

</html>
