@extends('admin.layouts.master')

	@section('title', 'Fee Collection Report |')

	@section('head')
		<link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
		<link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
		<link href="{{ URL::to('src/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
	@endsection

	@section('content')

		@include('admin.includes.side_navbar')

		<div id="page-wrapper" class="gray-bg">

			@include('admin.includes.top_navbar')

			<!-- Heading -->
			<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-8 col-md-6">
							<h2>Reports</h2>
							<ol class="breadcrumb">
								<li>Home</li>
									<li Class="active">
											<a>Fee Collection Reports</a>
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
						<div class="ibox">
							<div class="ibox-title">
									<h2>Fee Receipts Statment</h2>
									<div class="hr-line-dashed"></div>
							</div>

							<div class="ibox-content">

								<form id="fee_receipts_statment" method="POST" action="{{ URL('fee-collection-reports/fee-receipts-statment') }}" class="form-horizontal" target="_blank">
									{{ csrf_field() }}

									<div class="form-group">
										<label class="col-md-2 control-label">From</label>
										<div class="col-md-6">
											<div class="input-daterange input-group" id="datepicker">
												<input type="text" class="input-sm form-control" name="start" value="" required="" readonly="" placeholder="From Month" />
												<span class="input-group-addon">to</span>
												<input type="text" class="input-sm form-control" name="end" value="" required="" readonly="" placeholder="To Month" />
											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-offset-2 col-md-6">
												<button class="btn btn-primary btn-block" type="submit"><span class="fa fa-file"></span> Show </button>
										</div>
									</div>
								</form>

							</div>
						</div>

						<div class="ibox">
							<div class="ibox-title">
									<h2>Daily Fee Collection Report</h2>
									<div class="hr-line-dashed"></div>
							</div>

							<div class="ibox-content">

								<form id="daily_fee_collection" method="POST" action="{{ URL('fee-collection-reports/daily-fee-collection') }}" class="form-horizontal" target="_blank">
									{{ csrf_field() }}

									<div class="form-group">
										<label class="col-md-2 control-label">From</label>
										<div class="col-md-6">
											<div class="input-daterange input-group" id="datepicker1">
												<input type="text" class="input-sm form-control" name="start" value="" required="" readonly="" placeholder="From Month" />
												<span class="input-group-addon">to</span>
												<input type="text" class="input-sm form-control" name="end" value="" required="" readonly="" placeholder="To Month" />
											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-offset-2 col-md-6">
												<button class="btn btn-primary btn-block" type="submit"><span class="fa fa-file"></span> Show </button>
										</div>
									</div>
								</form>

							</div>
						</div>

						<div class="ibox">
							<div class="ibox-title">
								<h2>List Of Full/Half Freeship</h2>
								<div class="hr-line-dashed"></div>
							</div>

							<div class="ibox-content">

								<form id="daily_fee_collection" method="POST" action="{{ URL('fee-collection-reports/freeship-students') }}" class="form-horizontal" target="_blank">
									{{ csrf_field() }}

									<div class="form-group">
										<div class="col-md-offset-2 col-md-6">
											<button class="btn btn-primary btn-block" type="submit"><span class="fa fa-file"></span> Show </button>
										</div>
									</div>
								</form>

							</div>
						</div>

						<div class="ibox">
							<div class="ibox-title">
								<h2>Statment Of Unpaid Fee</h2>
								<div class="hr-line-dashed"></div>
							</div>

							<div class="ibox-content">

								<form id="unpaid_fee_statment" method="POST" action="{{ URL('fee-collection-reports/unpaid-fee-statment') }}" class="form-horizontal" target="_blank">
									{{ csrf_field() }}

									<div class="form-group">
										<label class="col-md-2 control-label"> Class </label>
										<div class="col-md-6">
											<select class="form-control select2" name="class" v-model="classe">
												<option value="" selected>Class</option>
												@foreach($classes AS $class)
													<option value="{{ $class->id }}">{{ $class->name }}</option>
												@endforeach
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label"> Section </label>
										<div class="col-md-6">
										<select class="form-control select2" name="section">
											<option value="">All</option>
											<option v-for="section in sections" :value="section.id">@{{ section.name }}</option>
										</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">End Month</label>
										<div class="col-md-6">
											<div class="input-group date" id="datepicker2">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="month" readonly="tue" required="true" class="form-control">
											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-offset-2 col-md-6">
											<button class="btn btn-primary btn-block" type="submit"><span class="fa fa-file"></span> Show </button>
										</div>
									</div>
								</form>

							</div>
						</div>

						<div class="ibox">
							<div class="ibox-title">
								<h2>Yearly Collection Statment</h2>
								<div class="hr-line-dashed"></div>
							</div>

							<div class="ibox-content">

								<form id="yearly_fee_collection" method="POST" action="{{ URL('fee-collection-reports/yearly-collection-statment') }}" class="form-horizontal" target="_blank">
									{{ csrf_field() }}

									<div class="form-group">
										<label class="col-md-2 control-label"> Class </label>
										<div class="col-md-6">
											<select class="form-control select2" name="class" v-model="classe" required="true">
												<option value="" disabled selected>Class</option>
												@foreach($classes AS $class)
													<option value="{{ $class->id }}">{{ $class->name }}</option>
												@endforeach
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label"> Section </label>
										<div class="col-md-6">
										<select class="form-control select2" name="section" required="true">
											<option v-for="section in sections" :value="section.id">@{{ section.name }}</option>
										</select>
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-offset-2 col-md-6">
											<button class="btn btn-primary btn-block" type="submit"><span class="fa fa-file"></span> Show </button>
										</div>
									</div>
								</form>

							</div>
						</div>

					</div>
				</div>

			</div>


			@include('admin.includes.footercopyright')


		</div>

	@endsection

		@section('script')


		<script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

		<!-- Input Mask-->
		 <script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

		<!-- Data picker -->
		<script src="{{ URL::to('src/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>

		<script type="text/javascript">
		var tbl;


			$(document).ready(function(){


				$("#fee_receipts_statment").validate({
					rules: {
						start: {
							required: true,
						},
						end: {
							required: true,
						},
					}
				});


				$("#daily_fee_collection").validate({
					rules: {
						start: {
							required: true,
						},
						end: {
							required: true,
						},
					}
				});


				$("#unpaid_fee_statment").validate({
					rules: {
						month: {
							required: true,
						},
					}
				});

				$('#datepicker').datepicker({

					format: 'yyyy-mm-dd',
					keyboardNavigation: false,
					forceParse: false,
					autoclose: true,

					minViewMode: 1,
					todayHighlight: true
				});

				$('#datepicker1').datepicker({

					format: 'yyyy-mm-dd',
					keyboardNavigation: false,
					forceParse: false,
					autoclose: true,

					todayHighlight: true
				});

				$('#datepicker2').datepicker({

					format: 'yyyy-mm-dd',
					keyboardNavigation: false,
					forceParse: false,
					autoclose: true,

					minViewMode: 1,
					todayHighlight: true
				});

			});
		</script>

		@endsection

		@section('vue')
		<script type="text/javascript">
			var app = new Vue({
				el: "#app",
				data: {
					classe: 0,
					sections: [],
					allSections: {!! json_encode($sections) !!},
				},

				watch:{
					classe: function (newClass, oldClass) {
						this.sections = this.allSections['class_'+this.classe];
					}
				}
			})
		</script>
		@endsection
