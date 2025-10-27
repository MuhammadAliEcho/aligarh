@extends('admin.layouts.master')

	@section('title', 'Students |')

	@section('head')
	<link href="{{ asset('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
	<link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
	<link href="{{ asset('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
	<script type="text/javascript">
			var sections = {!! json_encode($sections ?? '') !!};
	</script>
	@endsection

	@section('content')

	@include('admin.includes.side_navbar')

				<div id="page-wrapper" class="gray-bg">

					@include('admin.includes.top_navbar')

					<!-- Heading -->
					<div class="row wrapper border-bottom white-bg page-heading">
						<div class="col-lg-8 col-md-6">
							<h2>Students</h2>
							<ol class="breadcrumb">
								<li>Home</li>
								<li Class="active">
										<a>Students</a>
								</li>
							</ol>
						</div>
						<div class="col-lg-4 col-md-6">
							@include('admin.includes.academic_session')
						</div>
					</div>

					<!-- main Section -->

					<div class="wrapper wrapper-content animated fadeInRight">

						<div class="row">
							 <div class="col-lg-12">
								<div class="ibox float-e-margins">
										<div class="ibox-title">
												<h2>Edit Student</h2>
												<div class="hr-line-dashed"></div>
										</div>

										<div class="ibox-content">
											<form id="tchr_rgstr" method="post" action="{{ URL('students/edit/'.$student->id) }}" class="form-horizontal" enctype="multipart/form-data">
												{{ csrf_field() }}

												<div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Name</label>
													<div class="col-md-6">
														<input type="text" name="name" placeholder="Name" value="{{ old('name', $student->name) }}" class="form-control"/>
														@if ($errors->has('name'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('name') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('father_name'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Father Name</label>
													<div class="col-md-6">
														<input type="text" name="father_name" placeholder="Father Name" value="{{ old('father_name', $student->father_name) }}" class="form-control"/>
														@if ($errors->has('father_name'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('father_name') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('gender'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Gender</label>
													<div class="col-md-6">
														<select class="form-control" name="gender" placeholder="Gender">
															<option value="" disabled selected>Gender</option>
															<option>Male</option>
															<option>Female</option>
														</select>
														@if ($errors->has('gender'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('gender') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('dob'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Date Of Birth</label>
													<div class="col-md-6">
														<input type="text" id="datetimepicker4" name="dob" placeholder="DOB" value="{{ old('dob', $student->date_of_birth) }}" class="form-control"/>
														@if ($errors->has('dob'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('dob') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('place_of_birth'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Place Of Birth</label>
													<div class="col-md-6">
														<input type="text" name="place_of_birth" placeholder="Place Of Birth" value="{{ old('place_of_birth', $student->place_of_birth) }}" class="form-control"/>
														@if ($errors->has('place_of_birth'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('place_of_birth') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('religion'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Religion</label>
													<div class="col-md-6">
														<input type="text" name="religion" placeholder="Religion" value="{{ old('religion', $student->religion) }}" class="form-control"/>
														@if ($errors->has('religion'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('religion') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group {{ ($errors->has('img'))? ' has-error' : '' }}">
													<div class="col-md-2">
														<span class="btn btn-default btn-block btn-file">
															<input type="file" name="img" accept="image/*" id="imginp" />
																<span class="fa fa-image"></span>
																Upload Image
														</span>
													</div>
													<div class="col-md-6">
														<input type="hidden" name="removeImage" v-model="removeImage" />
														<template v-if="removeImage == 0">
															<button type="button" class="close" @click="removeImage = 1">
																<span aria-hidden="true">&times;</span>
															</button>
															<img id="img" src="{{ ($student->image_url == '')? '#' : URL($student->image_url) }}"  alt="Item Image... 454" class="img-responsive img-thumbnail" style="max-width:100px !important;min-width:105px !important;"/>
														</template>
														<template v-if="removeImage">
															<img id="img" src=""  alt="Item Image..." class="img-responsive img-thumbnail" :style="{ maxWidth: '100px', minWidth: '105px' }"/>
														</template>
													
														@if ($errors->has('img'))
															<span class="help-block">
																<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('img') }}</strong>
															</span>
														@endif
													</div>

												</div>

												<div class="form-group{{ ($errors->has('last_school'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Last School</label>
													<div class="col-md-6">
														<input type="text" name="last_school" placeholder="Last School Attendent" value="{{ old('last_school', $student->last_school) }}" class="form-control"/>
														@if ($errors->has('last_school'))
															<span class="help-block">
																	<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('last_school') }}</strong>
															</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('seeking_class'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Seeking Class</label>
													<div class="col-md-6">
														<input type="text" name="seeking_class" placeholder="Seeking Class" value="{{ old('seeking_class', $student->seeking_class) }}" class="form-control"/>
														@if ($errors->has('seeking_class'))
															<span class="help-block">
																	<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('seeking_class') }}</strong>
															</span>
														@endif
													</div>
												</div>
												@can('students.class_edit')
													<div class="form-group{{ ($errors->has('class'))? ' has-error' : '' }}">
														<label class="col-md-2 control-label">Class</label>
														<div class="col-md-6 select2-div">
															<select class="form-control select2" name="class">
																<option value="" disabled selected>Class</option>
																@foreach($classes AS $class)
																	<option value="{{ $class->id }}">{{ $class->name }}</option>
																@endforeach
															</select>
															@if ($errors->has('class'))
																	<span class="help-block">
																			<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('class') }}</strong>
																	</span>
															@endif
														</div>
													</div>
													<div class="form-group{{ ($errors->has('section'))? ' has-error' : '' }}">
														<label class="col-md-2 control-label">Section</label>
														<div class="col-md-6 select2-div">
															<select class="form-control select2" name="section">
															</select>
															@if ($errors->has('section'))
																	<span class="help-block">
																			<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('section') }}</strong>
																	</span>
															@endif
														</div>
													</div>
												@endcan

												<div class="form-group{{ ($errors->has('gr_no'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">GR No</label>
													<div class="col-md-6">
														<input type="number" name="gr_no" placeholder="GR NO" value="{{ old('gr_no', substr($student->gr_no, strrpos($student->gr_no, '-') + 1)) }}" class="form-control" />
														@if ($errors->has('gr_no'))
															<span class="help-block">
																<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('gr_no') }}</strong>
															</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('guardian'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Guardian</label>
													<div class="col-md-6">
														<select class="form-control" name="guardian">
															<option></option>
															@foreach($guardians as $guardian)
																<option value="{{ $guardian->id }}">{{ $guardian->name.' | '.$guardian->email }}</option>
															@endforeach
														</select>
														@if ($errors->has('guardian'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('guardian') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('guardian_relation'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Guardian Relation</label>
													<div class="col-md-6">
														<input type="text" name="guardian_relation" placeholder="guardian Relation" value="{{ old('guardian_relation', $student->guardian_relation) }}" class="form-control"/>
														@if ($errors->has('guardian_relation'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('guardian_relation') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-2 control-label">Address</label>
													<div class="col-md-6">
														<textarea type="text" name="address" placeholder="Address" class="form-control">{{ old('address', $student->address) }}</textarea>
													</div>
												</div>

												<div class="form-group{{ ($errors->has('phone'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Contact No</label>
													<div class="col-md-6">
														<div class="input-group m-b">
															<span class="input-group-addon">+92</span>
															<input type="text" name="phone" value="{{ old('phone', $student->phone) }}" placeholder="Contact No" class="form-control" data-mask="9999999999"/>
														</div>
														@if ($errors->has('phone'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('phone') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('doa'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Date Of Admission</label>
													<div class="col-md-6">
														<input type="text" id="datetimepicker5" name="doa" placeholder="Date Of Admission" value="{{ old('doa', $student->date_of_admission) }}" class="form-control"/>
														@if ($errors->has('doa'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('doa') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('doe'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Date Of Enrolled</label>
													<div class="col-md-6">
														<input type="text" id="datetimepicker6" name="doe" placeholder="Date Of Enrolled" value="{{ old('doe', $student->date_of_enrolled) }}" class="form-control"/>
														@if ($errors->has('doe'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('doe') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('receipt_no'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Receipt No</label>
													<div class="col-md-6">
														<input type="text" name="receipt_no" placeholder="Receipt NO" value="{{ old('receipt_no', $student->receipt_no) }}" class="form-control" />
														@if ($errors->has('receipt_no'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('receipt_no') }}</strong>
																</span>
														@endif
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

		@endsection

		@section('script')

		<!-- Mainly scripts 
		<script src="{{ asset('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>
		-->

		<script src="{{ asset('src/js/plugins/dataTables/datatables.min.js') }}"></script>

		<script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

		<!-- Input Mask-->
		 <script src="{{ asset('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

		<!-- Select2 -->
		<script src="{{ asset('src/js/plugins/select2/select2.full.min.js') }}"></script>

		<!-- require with bootstrap-datetimepicker -->
		<script src="{{ asset('src/js/plugins/moment/moment.min.js') }}"></script>
		<script src="{{ asset('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

		<script type="text/javascript">

		var tr;
		no = 1;

		function readURL(input) {
			if (input.files && input.files[0]) {
					var reader = new FileReader();
					reader.onload = function (e) {
							$('#img').attr('src', e.target.result);
					}
					reader.readAsDataURL(input.files[0]);
			}
		}

			$(document).ready(function(){

				$('[data-toggle="tooltip"]').tooltip();

				$('#datetimepicker4').datetimepicker({
								 format: 'DD/MM/YYYY'
					 });
				$('#datetimepicker5').datetimepicker({
								 format: 'DD/MM/YYYY'
					 });

				$('#datetimepicker6').datetimepicker({
								 format: 'YYYY-MM-DD'
					 });

				$("#tchr_rgstr").validate({
						rules: {
							name: {
								required: true,
							},
							father_name: {
								required: true,
							},
							gender: {
								required: true,
							},
							class: {
								required: true,
							},
							section: {
								required: true,
							},
							guardian: {
								required: true,
							},
							guardian_relation: {
								required: true,
							},
							tuition_fee: {
								required: true,
							},
							dob: {
								required: true,
							},
							doa: {
								required: true,
							},
							doe: {
								required: true,
							},
							gr_no: {
								required: true,
								number: true,
							},
						},
				});

			
			$('#tchr_rgstr [name="class"]').on('change', function(){
				clsid = $(this).val();
					$('#tchr_rgstr [name="section"]').html('');
					if(sections['class_'+clsid].length > 0){          
						$.each(sections['class_'+clsid], function(k, v){
							$('#tchr_rgstr [name="section"]').append('<option value="'+v['id']+'">'+v['name']+'</option>');
						});
					}
			});

			$('#tchr_rgstr [name="gender"]').val('{{ old('gender', $student->gender) }}');
			$('#tchr_rgstr [name="guardian"]').val('{{ old('guardian', $student->guardian_id) }}');
			$('#tchr_rgstr [name="class"]').val("{{ old('class', $student->class_id) }}");
			$('#tchr_rgstr [name="class"]').change();
			$('#tchr_rgstr [name="section"]').val('{{ old('section', $student->section_id) }}');

			$('#tchr_rgstr [name="guardian"]').attr('style', 'width:100%').select2({
								placeholder: "Nothing Selected",
								allowClear: true,
						});


			$("#imginp").change(function(){
					readURL(this);
			});

			});

		</script>

		@endsection

		@section('vue')
			<script type="text/javascript">
				var app = new Vue({
					el: '#app',
					data: {
						removeImage: {{ $student->image_url? 0 : 1 }},
					},
				});
			</script>
		@endsection




