@extends('admin.layouts.master')

  @section('title', 'Student Certificates |')

  @section('head')
  <!-- HEAD -->
	<script type="text/javascript" src="{{ URL::to('src/js/plugins/ckeditor_4.10.1/ckeditor.js') }}"></script>
  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

		<div id="page-wrapper" class="gray-bg">

		  @include('admin.includes.top_navbar')

		  <!-- Heading -->
		  <div class="row wrapper border-bottom white-bg page-heading">
			  <div class="col-lg-8 col-md-6">
				  <h2>Student Certificate</h2>
				  <ol class="breadcrumb">
					<li>Home</li>
					<li><a href="{{ URL('students') }}"> Students </a></li>
					<li Class="active">
						<a>Profile</a>
					</li>
					<li Class="active">
						Certificate
					</li>
				  </ol>
			  </div>
			  <div class="col-lg-4 col-md-6">
				@include('admin.includes.academic_session')
			  </div>
		  </div>

		  <!-- main Section -->
		<div class="wrapper wrapper-content">
			<div class="row animated fadeInRight">
				<div class="col-lg-12">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h2>Certificate For @{{ student.name }}</h2>
							<div class="hr-line-dashed"></div>
						</div>

						<div class="ibox-content">
							<form id="certificateform" method="post" :action="URL+'/students/certificate'" class="form-horizontal" enctype="multipart/form-data">
								{{ csrf_field() }}
								<input type="hidden" name="student_id" v-model="student_id">
								<input v-if="update" type="hidden" name="id" v-model="certificate.id">

								<div v-if="update == false" class="form-group">
									<label class="col-md-2 control-label">Certificate Template</label>
									<div class="col-md-6">
										<select class="form-control" v-model="selected_certificate">
											<option value="transfer_certificate">Transfer Certificate</option>
											<option value="character_certificate">Character Certificate</option>
										</select>
									</div>
								</div>

								<div class="form-group{{ ($errors->has('title'))? ' has-error' : '' }}">
									<label class="col-md-2 control-label">Title</label>
									<div class="col-md-6">
										<input type="text" name="title" placeholder="Title" v-model="title" class="form-control" />
										@if ($errors->has('title'))
												<span class="help-block">
														<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('title') }}</strong>
												</span>
										@endif
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-12">
										<textarea name="certificate"></textarea>
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-offset-2 col-md-6">
										<button v-if="update" class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Update </button>
										<button v-else class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Create </button>
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

	<script type="text/javascript">
		$("#certificateform").validate({
			rules: {
				title: {
					required: true,
				},
				certificate: {
					required: true,
				}
			}
		});
		window.onload = function() {
		    CKEDITOR.replace( 'certificate' );
			_.forEach(app.student, function(value, key) {
				app.default_certificate.transfer_certificate =	_.replace(app.default_certificate.transfer_certificate, new RegExp('@{{'+key+'}}', "g"), value);
				app.default_certificate.character_certificate =	_.replace(app.default_certificate.character_certificate, new RegExp('@{{'+key+'}}', "g"), value);
			});
			_.forEach(app.gender_render, function(v, k){
				app.default_certificate.character_certificate =	_.replace(app.default_certificate.character_certificate, new RegExp('@{{'+k+'}}', "g"), v[app.student.gender]);
			});
			CKEDITOR.instances.certificate.setData(app.computed_certificate);
		};
	</script>


	@endsection

	@section('vue')
	<script type="text/javascript">
		var app = new Vue({
			el: "#app",
			data: {
				URL: "{{ URL('/') }}",
				default_certificate: {!! json_encode(config('certificates')) !!},
				selected_certificate: 'transfer_certificate',
				gender_render: {
					gender_name: {
						'Male': 'Mr.',
						'Female': 'Miss.'
					},
					gender_father: {
						'Male': 'S/O',
						'Female': 'D/O'
					},
					gender_his_her: {
						'Male': 'His',
						'Female': 'Her'
					}

				},
				@if($root['option'] == 'update')
					update: true,
					certificate: {!! json_encode($certificate, JSON_NUMERIC_CHECK) !!},
					student: {!! json_encode($student, JSON_NUMERIC_CHECK) !!}
				@else
					update: false,
					student: {!! json_encode($student, JSON_NUMERIC_CHECK) !!}
				@endif
			},
			computed: {
				student_id: function(){
					return this.update? this.certificate.student_id : this.student.id;
				},
				computed_certificate: function(){
					return this.update? this.certificate.certificate : this.computed_default_certificate;
				},
				title: function(){
					return this.update? this.certificate.title : '';
				},
				computed_default_certificate: function(){
					return this.default_certificate[this.selected_certificate];
				}
			},
			watch: {
				selected_certificate: function(newVal){
					CKEDITOR.instances.certificate.setData(this.computed_default_certificate);
				}
			},
			mounted: function(){
				$("[data-toggle='tooltip']").on('mouseenter', function(){
						$(this).tooltip('show');
					}).mouseleave(function(){
						$(this).tooltip('destroy');
					});
			},
			methods: {

			}

		});
	</script>
	@endsection

