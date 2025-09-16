<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('title') Aligarh School Management System</title>
    <link rel="icon" href="{{ asset('src/icon/favicon.png') }}">

    <link href="{{ asset('src/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('src/entypo/entypo.css') }}" rel="stylesheet">

    <!-- Toastr style -->
    <link href="{{ asset('src/css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">

    <!-- Gritter -->
    <link href="{{ asset('src/js/plugins/gritter/jquery.gritter.css') }}" rel="stylesheet">

    <link href="{{ asset('src/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/style.css') }}" rel="stylesheet">
	
    {{-- Notification Bell --}}
    <link href="{{ asset('/src/css/notification-bell.css') }}" rel="stylesheet">
    
    @yield('head')

    <script src="{{ asset('src/js/jquery-2.1.1.js') }}"></script>

</head>

<body class="pace-done md-skin {{ Auth::user()->settings->skin_config->nav_collapse }}">
	@if( tenancy()->tenant->system_info['general']['validity'] < Carbon\Carbon::now()->toDateString())
	<div class="alert alert-danger" role="alert"> <span class="glyphicon glyphicon-warning-sign" ></span> <b>The system is expired at {{ Carbon::parse(tenancy()->tenant->system_info['general']['validity'])->toDateString() }}, account will inactive in next week. Please contact adminstrator.</b></div>
    @endif
    <div id="wrapper">
        <div id="app">
            @yield('content')
        </div>
        @include('admin.includes.footercopyright')
    </div>

    <!-- Mainly scripts -->
    <script src="{{ asset('src/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('src/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{ asset('src/js/custom.js') }}"></script>


    <!-- Toastr -->
    <script src="{{ asset('src/js/plugins/toastr/toastr.min.js') }}"></script>


    <!-- Lodash version 4.17.10 -->
    <script src="{{ asset('src/lodash.min.js') }}"></script>

	@if(env('APP_DEBUG'))
        <!-- Vue dev version -->
        <script src="{{ asset('src/vue.js') }}"></script>
    @else
        <!-- Vue -->
        <script src="{{ asset('src/vue.min-2.5.15.js') }}"></script>
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

    <script src="{{ asset('src/js/bootstrap.min.js') }}"></script>

    {{--    @include('admin.includes.skin_config')  --}}


</body>

</html>
