@extends('admin.layouts.master')

	@section('title', __('modules.pages_classes_title').' |')

	@section('head')
	<link href="{{ asset('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
	@endsection

	@section('content')

	@include('admin.includes.side_navbar')

				<div id="page-wrapper" class="gray-bg">

					@include('admin.includes.top_navbar')

					<!-- Heading -->
					<div class="row wrapper border-bottom white-bg page-heading">
							<div class="col-lg-8 col-md-6">
									<h2>Teachers</h2>
									<ol class="breadcrumb">
										<li>Home</li>
											<li>
													<a>Classes</a>
											</li>
											<li Class="active">
													<a>Edit Class</a>
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
								<div class="ibox float-e-margins">
										<div class="ibox-title">
												<h2>{{ __('modules.forms_edit_class') }}</h2>
												<div class="hr-line-dashed"></div>
										</div>

										<div class="ibox-content">

													<form id="tchr_rgstr" method="post" action="{{ URL('manage-classes/edit/'.$class->id) }}" class="form-horizontal" >
														{{ csrf_field() }}

														<div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
															<label class="col-md-2 control-label">Name</label>
															<div class="col-md-6">
																<input type="text" name="name" placeholder="Name" value="{{ old('name', $class->name) }}" class="form-control"/>
																@if ($errors->has('name'))
																		<span class="help-block">
																				<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('name') }}</strong>
																		</span>
																@endif
															</div>
														</div>

														<div class="form-group{{ ($errors->has('numeric_name'))? ' has-error' : '' }}">
															<label class="col-md-2 control-label">Name Numeric</label>
															<div class="col-md-6">
																<input type="number" name="numeric_name" placeholder="Numeric Name" value="{{ old('numeric_name', $class->numeric_name) }}" class="form-control"/>
																@if ($errors->has('numeric_name'))
																		<span class="help-block">
																				<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('numeric_name') }}</strong>
																		</span>
																@endif
															</div>
														</div>

														<div class="form-group{{ ($errors->has('prifix'))? ' has-error' : '' }}">
															<label class="col-md-2 control-label">Prifix</label>
															<div class="col-md-6">
																<input type="text" name="prifix" placeholder="Prifix" value="{{ old('prifix', $class->prifix) }}" class="form-control" required="true" />
																@if ($errors->has('prifix'))
																		<span class="help-block">
																				<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('prifix') }}</strong>
																		</span>
																@endif
															</div>
														</div>

														<div class="form-group{{ ($errors->has('teacher'))? ' has-error' : '' }}">
															<label class="col-md-2 control-label">Teacher</label>
															<div class="col-md-6 select2-div">
																<select class="form-control select2" name="teacher">
																	<option></option>
																	@foreach($teachers as $teacher)
																		<option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
																	@endforeach
																</select>
																@if ($errors->has('teacher'))
																		<span class="help-block">
																				<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('teacher') }}</strong>
																		</span>
																@endif
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

		<!-- Select2 -->
		<script src="{{ asset('src/js/plugins/select2/select2.full.min.js') }}"></script>

		<script type="text/javascript">

			$(document).ready(function(){
				$('[name="teacher"]').val({{ $class->teacher_id }});
				$('.select2').select2({
								placeholder: "Select a Teacher",
								allowClear: true,
						});
				$('.select2-div>span').attr('style', 'width:100%');

				$("#tchr_rgstr").validate({
						rules: {
							name: {
								required: true,
							},
							prifix: {
								required: true,
							},
							numeric_name: {
								required: true,
							},
						},
				});

				$('#tchr_rgstr [name="gender"]').val('{{ old('gender') }}');
			@if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
				$('a[href="#tab-11"]').click();
			@endif

			});
		</script>

		@endsection
