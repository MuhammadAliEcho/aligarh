@extends('admin.layouts.master')

	@section('title', 'Exams |')

	@section('head')
	<link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
	<link href="{{ URL::to('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
	@endsection

	@section('content')

	@include('admin.includes.side_navbar')

		<div id="page-wrapper" class="gray-bg">

			@include('admin.includes.top_navbar')

			<!-- Heading -->
			<div class="row wrapper border-bottom white-bg page-heading">
				<div class="col-lg-8 col-md-6">
					<h2>Exams</h2>
					<ol class="breadcrumb">
					<li>Home</li>
						<li Class="active">
						<a>Exams</a>
						</li>
					</ol>
				</div>
				<div class="col-lg-4 col-md-6">
				@include('admin.includes.academic_session')
				</div>
			</div>

			<!-- main Section -->

			<div class="wrapper wrapper-content animated fadeInRight">

			<div class="row ">
				<div class="col-lg-12">
					<div class="tabs-container">
						<ul class="nav nav-tabs">
							<li class="">
								<a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Exam List</a>
							</li>
							<li class="add-exam">
								<a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Create Exam</a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="tab-10" class="tab-pane fade">
								<div class="panel-body">
									<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover dataTables-teacher" >
										<thead>
										<tr>
											<th>Title</th>
											<th>Description</th>
											<th>Duration</th>
											<th>Options</th>
										</tr>
										</thead>
									</table>
									</div>

								</div>
							</div>
							<div id="tab-11" class="tab-pane fade add-exam">
								<div class="panel-body">
									<h2> Create Exam </h2>
									<div class="hr-line-dashed"></div>

									<form id="tchr_rgstr" method="post" action="{{ URL('exam/add') }}" class="form-horizontal" >
										{{ csrf_field() }}

										<div class="form-group{{ ($errors->has('exam_category'))? ' has-error' : '' }}">
										<label class="col-md-2 control-label">Select Category</label>
										<div class="col-md-6">
											<select name="exam_category" class="form-control">
												@foreach(config('examcategories') AS $k=>$exam)
												<option value="{{ $k }}">{{ $exam }}</option>
												@endforeach
											</select>
											@if ($errors->has('name'))
												<span class="help-block">
													<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('name') }}</strong>
												</span>
											@endif
										</div>
										</div>
										

										<div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
										<label class="col-md-2 control-label">Title</label>
										<div class="col-md-6">
										<input type="text" name="name" class="form-control" placeholder="Title" value="{{ old('name') }}">
											@if ($errors->has('name'))
												<span class="help-block">
													<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('name') }}</strong>
												</span>
											@endif
										</div>
										</div>

										<div class="form-group{{ ($errors->has('description'))? ' has-error' : '' }}">
										<label class="col-md-2 control-label">Description</label>
										<div class="col-md-6">
											<textarea type="text" name="description" placeholder="Description" class="form-control" required="true">{{ old('description') }}</textarea>
											@if ($errors->has('description'))
												<span class="help-block">
													<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('description') }}</strong>
												</span>
											@endif
										</div>
										</div>

										<div class="form-group{{ ($errors->has('start_date'))? ' has-error' : '' }}">
										<label class="col-md-2 control-label">Start Date</label>
										<div class="col-md-6">
											<input type="text" name="start_date" value="{{ old('start_date') }}" placeholder="Start Date" class="form-control datetimepicker" required="true" />
											@if ($errors->has('start_date'))
												<span class="help-block">
													<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('start_date') }}</strong>
												</span>
											@endif
										</div>
										</div>

										<div class="form-group{{ ($errors->has('end_date'))? ' has-error' : '' }}">
										<label class="col-md-2 control-label">End Date</label>
										<div class="col-md-6">
											<input type="text" name="end_date" value="{{ old('end_date') }}" placeholder="End Date" class="form-control datetimepicker" required="true" />
											@if ($errors->has('end_date'))
												<span class="help-block">
													<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('end_date') }}</strong>
												</span>
											@endif
										</div>
										</div>

										<div class="form-group">
											<div class="col-md-offset-2 col-md-6">
												<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Save </button>
											</div>
										</div>
									</form>

								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

			</div>


			@include('admin.includes.footercopyright')


		</div>

	@endsection

	@section('script')

	<script src="{{ URL::to('src/js/plugins/dataTables/datatables.min.js') }}"></script>

	<script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

	<!-- require with bootstrap-datetimepicker -->
	<script src="{{ URL::to('src/js/plugins/moment/moment.min.js') }}"></script>
	<script src="{{ URL::to('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

	<script type="text/javascript">

	var tbl;
		$(document).ready(function(){

		@if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
		$('a[href="#tab-11"]').tab('show');
		@else
		$('a[href="#tab-10"]').tab('show');
		@endif

		$('.datetimepicker').datetimepicker({
			format: 'DD/MM/YYYY'
		});

/*    For Column Search
		$('.dataTables-teacher thead th').each( function () {
			var title = $('.dataTables-teacher thead th').eq( $(this).index() ).text();
			if (title !== 'Options') {
			$(this).html( '<input class="" type="text" placeholder="'+title+'" />' );
			}
		});
*/

		opthtm = '<a data-toggle="tooltip" title="Edit" class="btn btn-default btn-circle btn-xs edit-option"><span class="fa fa-edit"></span></a>';
	tbl =   $('.dataTables-teacher').DataTable({
			dom: '<"html5buttons"B>lTfgitp',
			buttons: [
			{extend: 'copy'},
			{extend: 'csv'},
			{extend: 'excel', title: 'Expense List'},
			{extend: 'pdf', title: 'Expense List'},

			{extend: 'print',
				customize: function (win){
				$(win.document.body).addClass('white-bg');
				$(win.document.body).css('font-size', '10px');

				$(win.document.body).find('table')
				.addClass('compact')
				.css('font-size', 'inherit');
				},
				exportOptions: {
				columns: [ 0, 1, 2, 3]
				}
			}
			],
			Processing: true,
			serverSide: true,
			ajax: '{{ URL('exam') }}',
			columns: [
			{data: 'name'},
			{data: 'description'},
			{data: 'start_date'},
//            {data: 'end_date'},
//            {"defaultContent": '<div class="btn-group"><button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle option" aria-expanded="true">Action <span class="caret"></span></button><ul class="dropdown-menu"><li><a href="#"><span class="fa fa-user"></span> Profile</a></li><li class="divider"></li><li><a data-original-title="Edit" class="edit-option"><span class="fa fa-edit"></span> Edit</a></li><li><a href="#"><span class="fa fa-trash"></span> Delete</a></li></ul></div>', className: 'hidden-print'},
			{"defaultContent": opthtm, className: 'hidden-print'},
			],
			"columnDefs": [
			{
				// The `data` parameter refers to the data for the cell (defined by the
				// `data` option, which defaults to the column being worked with, in
				// this case `data: 0`.
				data: 2,
				"render": function ( data, type, row ) {
					return data +' - '+ row.end_date;
				},
				"targets": 2
			},
//            { "visible": false,  "targets": [ 1 ] }
			]
		});

/*    for Column search
		tbl.columns().eq( 0 ).each( function ( colIdx ) {
			$( 'input', tbl.column( colIdx ).header() ).on( 'keyup change', function () {
				tbl
					.column( colIdx )
					.search( this.value )
					.draw();
			});
		});*/

		$('.dataTables-teacher tbody').on( 'mouseenter', '.edit-option', function () {
		$(this).attr('href','{{ URL('exam/edit') }}/'+tbl.row( $(this).parents('tr') ).data().id);
		$(this).tooltip('show');
		});

		$("#tchr_rgstr").validate({
			rules: {
				name: {
				required: true,
				},
				description: {
				required: true,
				},
				start_date: {
				required: true,
				},
				end_date:{
				required:true,
				},
				exam_category: {
					required: true,
				}
			},
		});

		@if(Auth::user()->getprivileges->privileges->{$root['content']['id']}->add == 0)
		$('.add-exam').hide();
		@endif

		@if(Auth::user()->getprivileges->privileges->{$root['content']['id']}->edit == 0)
		$('.edit-exam').hide();
		@endif

		});
	</script>

	@endsection