@extends('admin.layouts.master')

	@section('title', __('modules.pages_edit_exam_title').' |')

	@section('head')
	<link href="{{ asset('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
	@endsection

	@section('content')

	@include('admin.includes.side_navbar')

				<div id="page-wrapper" class="gray-bg">

					@include('admin.includes.top_navbar')

					<!-- Heading -->
					<div class="row wrapper border-bottom white-bg page-heading">
							<div class="col-lg-8 col-md-6">
									<h2>Exams</h2>
									<ul class="breadcrumb">
										<li>Home</li>
											<li><a>Exam</a></li>
											<li Class="active"><a>Edit</a></li>
									</ul>
							</div>
							<div class="col-lg-4 col-md-6">
								@include('admin.includes.academic_session')
							</div>
					</div>

					<!-- main Section -->

					<div class="wrapper wrapper-content animated fadeInRight">

						<div class="row ">
							 <div class="col-lg-12">
								<div class="ibox float-e-margins">
										<div class="ibox-title">
												<h2>{{ __('modules.forms_edit_exam') }}</h2>
												<div class="hr-line-dashed"></div>
										</div>

										<div class="ibox-content">

											<form id="tchr_rgstr" method="post" action="{{ URL('exam/edit/'.$exam['id']) }}" class="form-horizontal" >
												{{ csrf_field() }}
												<input type="hidden" name="exam_category" v-model="exam.category_id">
												<div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Title</label>
													<div class="col-md-6">
													<input type="text" name="name" class="form-control" placeholder="Title" value="{{ old('name', $exam->name) }}">
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
														<textarea type="text" name="description" placeholder="Description" class="form-control" required="true">{{ old('description', $exam->description) }}</textarea>
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
														<input type="text" name="start_date" value="{{ old('start_date', $exam->start_date) }}" placeholder="Start Date" class="form-control datetimepicker" />
														@if ($errors->has('start_date'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('start_date') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('end_date'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Start Date</label>
													<div class="col-md-6">
														<input type="text" name="end_date" value="{{ old('end_date', $exam->end_date) }}" placeholder="End Date" class="form-control datetimepicker" />
														@if ($errors->has('end_date'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('end_date') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('active'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Status</label>
													<div class="col-md-6">
														<select name="active" :value="(exam.active)? 1 : 0" class="form-control">
															<option value="0">Inactive</option>
															<option value="1">Active</option>
														</select>
													</div>
												</div>

												<div class="form-group">
														<div class="col-md-offset-2 col-md-6">
																<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Save Changes </button>
														</div>
												</div>
											</form>

											</div>
									</div>
							</div>
					</div>

					</div>


					


				</div>

		@endsection

		@section('script')


		<script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

		<!-- require with bootstrap-datetimepicker -->
		<script src="{{ asset('src/js/plugins/moment/moment.min.js') }}"></script>
		<script src="{{ asset('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

		<script type="text/javascript">

			$(document).ready(function(){

				$('.datetimepicker').datetimepicker({
					format: 'DD/MM/YYYY'
				});

				$("#tchr_rgstr").validate({
						rules: {
							type: {
								required: true,
							},
							description: {
								required: true,
							},
/*              start_date: {
								required: true,
							},
							end_date:{
								required:true,
							},*/
						},
				});

			});
		</script>

		@endsection

		@section('vue')
		<script type="text/javascript">
			var app = new Vue({
				el: "#app",
				data: {
					exam: {!! json_encode($exam) !!}
				},
			})
		</script>
		@endsection