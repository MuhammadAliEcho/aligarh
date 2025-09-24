@extends('admin.layouts.master')

	@section('title', 'Exam Reports |')

	@section('head')
		<link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
		<link href="{{ asset('src/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
		<link href="{{ asset('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
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
											<a>Exam Reports</a>
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
						@can('exam-reports.tabulation.sheet')
							<div class="ibox">
								<div class="ibox-title">
									<h2>Generate Exam Tabulation Sheet</h2>
									<div class="hr-line-dashed"></div>
								</div>
								<div class="ibox-content">
									<form id="tabulation_sheet" method="POST" action="{{ URL('exam-reports/tabulation-sheet') }}" class="form-horizontal" target="_blank">
										{{ csrf_field() }}
										<div class="form-group{{ ($errors->has('exam'))? ' has-error' : '' }}">
											<label class="col-md-2 control-label"> Exam </label>
											<div class="col-md-6">
											<select class="form-control select2" name="exam" v-model="selected_exam" required="true">
												<option value="" disabled selected>Exam</option>
												<option v-for="exam in Exams" :value="exam.id">@{{ exam.name+' "'+exam.academic_session.title+'"' }}</option>
											</select>
											@if ($errors->has('exam'))
												<span class="help-block">
													<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('exam') }}</strong>
												</span>
											@endif
											</div>
										</div>
										<div class="form-group{{ ($errors->has('class'))? ' has-error' : '' }}">
											<label class="col-md-2 control-label"> Class </label>
											<div class="col-md-6">
											<select class="form-control select2" name="class" v-model="selected_class" required="true">
												<option value="" disabled selected>Class</option>
												<option v-for="classe in Classes" :value="classe.id">@{{ classe.name }}</option>
											</select>
											@if ($errors->has('class'))
												<span class="help-block">
													<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('class') }}</strong>
												</span>
											@endif
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-offset-2 col-md-6">
												<button class="btn btn-primary btn-block" type="submit"><span class="fa fa-file"></span> Generate </button>
											</div>
										</div>
									</form>
								</div>
							</div>
						@endcan
						@can('exam-reports.award.list')
							<div class="ibox">
								<div class="ibox-title">
									<h2>Award List</h2>
									<div class="hr-line-dashed"></div>
								</div>

								<div class="ibox-content">

									<form id="tabulation_sheet" method="POST" action="{{ URL('exam-reports/award-list') }}" class="form-horizontal" target="_blank">
										{{ csrf_field() }}

										<div class="form-group{{ ($errors->has('exam'))? ' has-error' : '' }}">
											<label class="col-md-2 control-label"> Exam </label>
											<div class="col-md-6">
											<select class="form-control select2" name="exam" v-model="selected_exam" required="true">
												<option value="" disabled selected>Exam</option>
												<option v-for="exam in Exams" :value="exam.id">@{{ exam.name+' "'+exam.academic_session.title+'"' }}</option>
											</select>
											@if ($errors->has('exam'))
												<span class="help-block">
													<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('exam') }}</strong>
												</span>
											@endif
											</div>
										</div>

										<div class="form-group{{ ($errors->has('class'))? ' has-error' : '' }}">
											<label class="col-md-2 control-label"> Class </label>
											<div class="col-md-6">
											<select class="form-control select2" name="class" v-model="selected_class" required="true">
												<option value="" disabled selected>Class</option>
												<option v-for="classe in Classes" :value="classe.id">@{{ classe.name }}</option>
											</select>
											@if ($errors->has('class'))
												<span class="help-block">
													<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('class') }}</strong>
												</span>
											@endif
											</div>
										</div>

										<div class="form-group{{ ($errors->has('subject'))? ' has-error' : '' }}">
											<label class="col-md-2 control-label"> Subject </label>
											<div class="col-md-6">
											<select class="form-control" name="subject" v-model="selected_subject" required="true">
												<option value="" disabled selected>Subject</option>
												<option v-for="subject in filtered_subjects" :value="subject.id">@{{ subject.name }}</option>
											</select>
											@if ($errors->has('subject'))
												<span class="help-block">
													<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('subject') }}</strong>
												</span>
											@endif
											</div>
										</div>

										<div class="form-group">
											<div class="col-md-offset-2 col-md-6">
												<button class="btn btn-primary btn-block" type="submit"><span class="fa fa-file"></span> Generate </button>
											</div>
										</div>
									</form>

								</div>
							</div>
						@endcan
						@can('exam-reports.average.result')
							<div class="ibox">
								<div class="ibox-title">
									<h2>Average Result</h2>
									<div class="hr-line-dashed"></div>
								</div>

								<div class="ibox-content">

									<form id="tabulation_sheet" method="POST" action="{{ URL('exam-reports/average-result') }}" class="form-horizontal" target="_blank">
										{{ csrf_field() }}

										<div class="form-group{{ ($errors->has('exam'))? ' has-error' : '' }}">
											<label class="col-md-2 control-label"> Exam </label>
											<div class="col-md-6">
												<div class="i-checks"><label> <input type="radio" value="1" name="exam" required=""> <i></i> 1st Ass/Half Year </label></div>
												<div class="i-checks"><label> <input type="radio" value="2" name="exam"> <i></i> 2nd Ass/Final Year </label></div>
											</div>
										</div>

										<div class="form-group{{ ($errors->has('class'))? ' has-error' : '' }}">
											<label class="col-md-2 control-label"> Class </label>
											<div class="col-md-6">
											<select class="form-control select2" name="class" v-model="selected_class" required="true">
												<option value="" disabled selected>Class</option>
												<option v-for="classe in Classes" :value="classe.id">@{{ classe.name }}</option>
											</select>
											@if ($errors->has('class'))
												<span class="help-block">
													<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('class') }}</strong>
												</span>
											@endif
											</div>
										</div>

										<div class="form-group">
											<div class="col-md-offset-2 col-md-6">
												<button class="btn btn-primary btn-block" type="submit"><span class="fa fa-file"></span> Generate </button>
											</div>
										</div>
									</form>

								</div>
							</div>
						@endcan
						@can('exam-reports.result.transcript')
							<div class="ibox">
								<div class="ibox-title">
									<h2>Transcript Result</h2>
									<div class="hr-line-dashed"></div>
								</div>

								<div class="ibox-content">

									<form id="tabulation_sheet" method="POST" action="{{ URL('exam-reports/result-transcript') }}" class="form-horizontal" target="_blank">
										{{ csrf_field() }}

										<div class="form-group{{ ($errors->has('exam'))? ' has-error' : '' }}">
											<label class="col-md-2 control-label"> Exam </label>
											<div class="col-md-6">
												<div class="i-checks"><label> <input type="radio" value="1" name="exam" required=""> <i></i> 1st Ass/Half Year </label></div>
												<div class="i-checks"><label> <input type="radio" value="2" name="exam"> <i></i> 2nd Ass/Final Year </label></div>
											</div>
										</div>

										<div class="form-group{{ ($errors->has('gr_no'))? ' has-error' : '' }}">
											<label class="col-md-2 control-label"> GR-No </label>
											<div class="col-md-6">
											<select class="form-control" name="student_id" id="select2" required="true"></select>
											@if ($errors->has('gr_no'))
												<span class="help-block">
													<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('gr_no') }} </strong>
												</span>
											@endif
											</div>
										</div>

										<div class="form-group">
											<div class="col-md-offset-2 col-md-6">
												<button class="btn btn-primary btn-block" type="submit"><span class="fa fa-file"></span> Generate </button>
											</div>
										</div>
									</form>

								</div>
							</div>
						@endcan	
					</div>
				</div>
			</div>
			

		</div>

	@endsection

		@section('script')


		<script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

		<!-- Input Mask-->
		 <script src="{{ asset('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

		<!-- iCheck -->
		<script src="{{ asset('src/js/plugins/iCheck/icheck.min.js') }}"></script>

		<!-- Select2 -->
		<script src="{{ asset('src/js/plugins/select2/select2.full.min.js') }}"></script>

		<script type="text/javascript">
		var tbl;

			$(document).ready(function(){

				$('.i-checks').iCheck({
					checkboxClass: 'icheckbox_square-green',
					radioClass: 'iradio_square-green',
				});

				$("#tabulation_sheet").validate({
					rules: {
						class: {
							required: true,
						},
						exam: {
							required: true,
						},
					}
				});

					$('#select2').attr('style', 'width:100%').select2({
						placeholder: 'Search contacts',
						minimumInputLength: 3,
						Html: true,
						ajax: {
							url: '{{ URL('exam-reports/findstu') }}',
						  processResults: function (data) {
							return {
							  results: data
							};
						  }
						},
						tags: true,
					});


			});
		</script>

		@endsection

		@section('vue')
		<script type="text/javascript">
			var app = new Vue({
				el: "#app",
				data: {
					Exams: {!! json_encode($exams) !!},
					Classes: {!! json_encode($classes) !!},
					Subjects: {!! json_encode($subjects ?? '') !!},
					filtered_subjects: [],
					selected_class: '',
					selected_exam: '',
					selected_subject: '',
				},
				method: {

				},
				watch:{
					selected_class: function(cls){
						this.filtered_subjects	= this.Subjects.filter(function (subject) {
							return subject.class_id == cls;
						});
					}
				},
				computed: {

				}
			})
		</script>
		@endsection
