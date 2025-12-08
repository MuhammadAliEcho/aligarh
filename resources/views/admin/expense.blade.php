@extends('admin.layouts.master')

        @section('title', __('modules.pages_expense_title').' |')	@section('head')
	<link href="{{ asset('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
	<link href="{{ asset('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
	@endsection

	@section('content')

	@include('admin.includes.side_navbar')

				<div id="page-wrapper" class="gray-bg">

					@include('admin.includes.top_navbar')

					<!-- Heading -->
					<div class="row wrapper border-bottom white-bg page-heading">
							<div class="col-lg-8 col-md-6">
									<h2>{{ __("modules.pages_expenses_title") }}</h2>
									<ol class="breadcrumb">
										<li>{{ __("common.home") }}</li>
											<li Class="active">
													<a>Expense</a>
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
															<a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> {{ __('modules.tabs_expenses') }}</a>
														</li>
														@can('expense.add')
															<li class="add-expense">
																<a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> {{ __('modules.tabs_add_expense') }}</a>
															</li>
														@endcan
														@can('expense.summary')
															<li class="summary-expense">
																<a data-toggle="tab" href="#tab-12"><span class="fa fa-files-o"></span> Summary</a>
															</li>
														@endcan
												</ul>
												<div class="tab-content">
														<div id="tab-10" class="tab-pane fade">
																<div class="panel-body">
																	<div class="table-responsive">
																		<table class="table table-striped table-bordered table-hover dataTables-teacher" >
																			<thead>
																				<tr>
																					<th>Type</th>
																					<th>Description</th>
																					<th>Amount</th>
																					<th>Date</th>
																					<th>{{ __("labels.options") }}</th>
																				</tr>
																			</thead>
																		</table>
																	</div>

																</div>
														</div>
														@can('expense.add')
															<div id="tab-11" class="tab-pane fade add-expense">
																	<div class="panel-body">
																		<h2> {{ __('modules.forms_add_expense') }} </h2>
																		<div class="hr-line-dashed"></div>

																			<form id="tchr_rgstr" method="post" action="{{ URL('expense/add') }}" class="form-horizontal" >
																				{{ csrf_field() }}

																				<div class="form-group{{ ($errors->has('type'))? ' has-error' : '' }}">
																					<label class="col-md-2 control-label">Type</label>
																					<div class="col-md-6">
																					<select class="form-control" name="type" >
																						<option></option>
																						<option>Salary</option>
																						<option>Bills</option>
																						<option>Maintenance</option>
																						<option>Others</option>
																					</select>
																						@if ($errors->has('type'))
																								<span class="help-block">
																										<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('type') }}</strong>
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

																				<div class="form-group{{ ($errors->has('amount'))? ' has-error' : '' }}">
																					<label class="col-md-2 control-label">Amount</label>
																					<div class="col-md-6">
																						<input type="number" name="amount" value="{{ old('amount') }}" placeholder="Amount" class="form-control"/>
																						@if ($errors->has('amount'))
																								<span class="help-block">
																										<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('amount') }}</strong>
																								</span>
																						@endif
																					</div>
																				</div>

																				<div class="form-group{{ ($errors->has('date'))? ' has-error' : '' }}">
																					<label class="col-md-2 control-label">Date</label>
																					<div class="col-md-6">
																						<input type="text" name="date" value="{{ old('date') }}" placeholder="Date" class="form-control datetimepicker"/>
																						@if ($errors->has('date'))
																								<span class="help-block">
																										<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('date') }}</strong>
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
														@endcan
														@can('expense.summary')
														<div id="tab-12" class="tab-pane fade summary-expense">
															<div class="panel-body">
																<div class="jumbotron">
																	<div class="container">
																		<form id="summaryfrm" class="form-horizontal" role="form" action="{{ URL('expense/summary') }}">

																				<div class="form-group">
																					<label class="col-md-2 control-label">From Date</label>
																					<div class="col-md-6">
																						<input type="text" name="from_date" placeholder="Date" class="form-control datetimepicker" required="true" />
																					</div>
																				</div>

																				<div class="form-group">
																					<label class="col-md-2 control-label">To Date</label>
																					<div class="col-md-6">
																						<input type="text" name="to_date" placeholder="Date" class="form-control datetimepicker" required="true" />
																					</div>
																				</div>
																			
																				<div class="form-group">
																					<label class="col-md-2 control-label">Type</label>
																					<div class="col-md-6">
																						<select class="form-control" name="type" >
																							<option></option>
																							<option>Salary</option>
																							<option>Bills</option>
																							<option>Maintenance</option>
																							<option>Others</option>
																						</select>
																					</div>
																				</div>

																				<div class="form-group">
																					<label class="col-md-2 control-label">Description</label>
																					<div class="col-md-6">
																						<input type="text" name="description" placeholder="Description" class="form-control"/>
																					</div>
																				</div>

																				<div class="form-group">
																						<div class="col-md-offset-2 col-md-6">
																								<button class="btn btn-primary btn-block" type="submit" data-loading-text="<span class='fa fa-spinner fa-pulse'></span> Processing...."><span class="glyphicon glyphicon-search"></span> Search </button>
																								<a class="expense-report-print" href="#"><span class="glyphicon glyphicon-print"></span> Print</a>
																						</div>
																				</div>

																		</form>
																	</div>
																</div>
																<div class="row">
																	<div id="expense_report">

																	</div>
																</div>
															</div>
														</div>
														@endcan
												</div>
										</div>
								</div>
						</div>

					</div>


					


				</div>

		@endsection

		@section('script')

		<script src="{{ asset('src/js/jquery.print.js') }}"></script>

		<script type="text/javascript">
			$('.expense-report-print').click(function(e){
				e.preventDefault();
				$("#expense_report").print({
						globalStyles: true,
						mediaPrint: false,
						stylesheet: null,
						noPrintSelector: ".no-print",
						iframe: true,
						append: null,
						prepend: null,
						manuallyCopyFormValues: true,
						deferred: $.Deferred(),
						timeout: 250,
								title: null,
								doctype: '<!doctype html>'
				});
			});
		</script>

		<script src="{{ asset('src/js/plugins/dataTables/datatables.min.js') }}"></script>

		<script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

		<!-- require with bootstrap-datetimepicker -->
		<script src="{{ asset('src/js/plugins/moment/moment.min.js') }}"></script>
		<script src="{{ asset('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

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

				opthtm = '';
				@can('expense.edit.post')
				opthtm = '<a data-toggle="tooltip" title="Edit" class="btn btn-default btn-circle btn-xs edit-option"><span class="fa fa-edit"></span></a>';
				@endcan
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
					ajax: '{{ URL('expense') }}',
					columns: [
						{data: 'type'},
						{data: 'description'},
						{data: 'amount'},
						{data: 'date'},
//            {"defaultContent": '<div class="btn-group"><button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle option" aria-expanded="true">Action <span class="caret"></span></button><ul class="dropdown-menu"><li><a href="#"><span class="fa fa-user"></span> Profile</a></li><li class="divider"></li><li><a data-original-title="Edit" class="edit-option"><span class="fa fa-edit"></span> Edit</a></li><li><a href="#"><span class="fa fa-trash"></span> Delete</a></li></ul></div>', className: 'hidden-print'},
						{"defaultContent": opthtm, className: 'hidden-print'},
					],
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
				$(this).attr('href','{{ URL('expense/edit') }}/'+tbl.row( $(this).parents('tr') ).data().id);
				$(this).tooltip('show');
			});

				$("#tchr_rgstr").validate({
						rules: {
							type: {
								required: true,
							},
							description: {
								required: true,
							},
							amount: {
								required: true,
							},
							date:{
								required:true,
							},
						},
				});

			$('#tchr_rgstr [name="type"]').val('{{ old('type') }}');


				$('#summaryfrm').submit(function(e){
						e.preventDefault();
						var $btn = $("#summaryfrm button:submit").button('loading');
						$.ajax({
						type: $(this).attr('method'),
						url:  $(this).attr('action'),
						data: $(this).serialize(),
							success: function(data){
								$('#expense_report').html(data);
								$btn.button('reset');
							},
						error: function(){
							alert("Request failure");
							$btn.button('reset');
							}
						});

					});
			});
		</script>

		@endsection