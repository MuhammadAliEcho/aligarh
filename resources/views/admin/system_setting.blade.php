@extends('admin.layouts.master')

	@section('title', 'System Settings |')

	@section('head')

	<link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
	@endsection

	@section('content')

	@include('admin.includes.side_navbar')

				<div id="page-wrapper" class="gray-bg">

					@include('admin.includes.top_navbar')

					<!-- Heading -->
					<div class="row wrapper border-bottom white-bg page-heading">
							<div class="col-lg-8 col-md-6">
									<h2>Settings</h2>
									<ol class="breadcrumb">
										<li>Home</li>
											<li Class="active">
													<a>System Settings</a>
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
														<li class="active">
															<a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> General Info</a>
														</li>
												</ul>
												<div class="tab-content">
														<div id="tab-11" class="tab-pane fade fade in active add-guardian">
																<div class="panel-body">
																	<h2> General </h2>
																	<div class="hr-line-dashed"></div>

																		<form id="tchr_rgstr" method="POST" action="{{ URL('system-setting/update') }}" class="form-horizontal" >
																			{{ csrf_field() }}

																			<div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">System Name</label>
																				<div class="col-md-6">
																					<input type="text" name="name" placeholder="Name" value="{{ old('name', config('systemInfo.name')) }}" class="form-control"/>
																					@if ($errors->has('name'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('name') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('title'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">System Title</label>
																				<div class="col-md-6">
																					<input type="text" name="title" placeholder="Title" value="{{ old('name', config('systemInfo.title')) }}" class="form-control"/>
																					@if ($errors->has('title'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('title') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('email'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">E-Mail</label>
																				<div class="col-md-6">
																					<input type="text" name="email" placeholder="E-Mail" value="{{ old('email', config('systemInfo.email')) }}" class="form-control"/>
																					@if ($errors->has('email'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('email') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('address'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Address</label>
																				<div class="col-md-6">
																					<input type="text" name="address" placeholder="Address" value="{{ old('address', config('systemInfo.address')) }}" class="form-control"/>
																					@if ($errors->has('address'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('address') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('contact_no'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Contact No</label>
																				<div class="col-md-6">
																					<input type="text" name="contact_no" value="{{ old('contact_no', config('systemInfo.contact_no')) }}" placeholder="Contact No" class="form-control" data-mask="(999) 999-9999"/>
																					@if ($errors->has('contact_no'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('contact_no') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('student_capacity'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Student Capacity</label>
																				<div class="col-md-6">
																					<input type="text" name="student_capacity" value="{{ config('systemInfo.student_capacity') }}" readonly="ture" class="form-control"/>
																					@if ($errors->has('student_capacity'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('student_capacity') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group">
																				<label class="col-md-2 control-label">Next Chalan No</label>
																				<div class="col-md-6">
																					<input type="text" value="{{ config('systemInfo.next_chalan_no') }}" readonly="ture" class="form-control"/>
																				</div>
																			</div>

																			<div class="form-group">
																					<div class="col-md-offset-2 col-md-6">
																							<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Update </button>
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

		<!-- Mainly scripts -->
		<script src="{{ URL::to('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>

		<script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

		<!-- Input Mask-->
		 <script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

		<script type="text/javascript">
		var tbl;


			$(document).ready(function(){


				$("#tchr_rgstr").validate({
						rules: {
							name: {
								required: true,
							},
/*              profession: {
								required: true,
							},
							email: {
								required: true,
								email: true
							},
*/              income:{
								number:true,
							},
						},
						messages:{
							income:{
								number:'Enter valid amount'
						 },
					 }
				});

			});
		</script>

		@endsection
