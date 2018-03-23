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

	@yield('head')

	<script src="{{ URL::to('src/js/jquery-2.1.1.js') }}"></script>

  </head>

<body class="pace-done md-skin {{ Auth::user()->settings->skin_config->nav_collapse }}">
  <div id="wrapper">
	<div id="app">
		@yield('content')
	</div>
  </div>

	<!-- Mainly scripts -->
	<script src="{{ URL::to('src/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
	<script src="{{ URL::to('src/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

	<!-- Flot -->
	<script src="{{ URL::to('src/js/plugins/flot/jquery.flot.js') }}"></script>
	<script src="{{ URL::to('src/js/plugins/flot/jquery.flot.tooltip.min.js') }}"></script>
	<script src="{{ URL::to('src/js/plugins/flot/jquery.flot.spline.js') }}"></script>
	<script src="{{ URL::to('src/js/plugins/flot/jquery.flot.resize.js') }}"></script>
	<script src="{{ URL::to('src/js/plugins/flot/jquery.flot.pie.js') }}"></script>

	<!-- Peity -->
	<script src="{{ URL::to('src/js/plugins/peity/jquery.peity.min.js') }}"></script>
	<script src="{{ URL::to('src/js/demo/peity-demo.js') }}"></script>

	<!-- Custom and plugin javascript -->
	<script src="{{ URL::to('src/js/custom.js') }}"></script>

  {{--
	<script type="text/javascript">

	  // Append config box / Only for demo purpose
	  // Uncomment on server mode to enable XHR calls
	  $.get("{{ URL('/skin-config') }}", function (data) {
		if (!$('body').hasClass('no-skin-config'))
			$('body').append(data);
	  });
	</script>
	--}}

	<script src="{{ URL::to('src/js/plugins/pace/pace.min.js') }}"></script>

	<!-- jQuery UI -->
	<script src="{{ URL::to('src/js/plugins/jquery-ui/jquery-ui.min.js') }}"></script>

	<!-- GITTER -->
	<script src="{{ URL::to('src/js/plugins/gritter/jquery.gritter.min.js') }}"></script>

	<!-- Sparkline -->
	<script src="{{ URL::to('src/js/plugins/sparkline/jquery.sparkline.min.js') }}"></script>

	<!-- Sparkline demo data  -->
	<script src="{{ URL::to('src/js/demo/sparkline-demo.js') }}"></script>

	<!-- ChartJS-->
	<script src="{{ URL::to('src/js/plugins/chartJs/Chart.min.js') }}"></script>

	<!-- Toastr -->
	<script src="{{ URL::to('src/js/plugins/toastr/toastr.min.js') }}"></script>

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
