@extends('admin.layouts.master')

	@section('title', 'Students |')

	@section('head')
	<link href="{{ asset('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">

	<style type="text/css">
		@media print{
			body {
				padding: 0px 10px;
				margin: 0px;
				font-size: 12px;
			}
			.invoice-title h2, .invoice-title h3 {
				display: inline-block;
			}

			.table > tbody > tr > td, 
			.table > tbody > tr > th {
				border-top: none;
				padding: 3px;
			}

/*			.table > thead > tr > .no-line {
				border-bottom: none;
			}
			.table > tbody > tr > .thick-line {
				border-top: 1px solid;
			}
*/
			.bottom-border {
				border-bottom: 1px solid;
			}


			.table-bordered th,
			.table-bordered td {
				border: 1px solid black !important;
				padding: 0px;
			}   

			.sibling-table > tbody > tr > td {
				padding: 1px;
			}
			.sibling-table > thead > tr > th {
				padding: 2px;
			}
			.sibling-table {
				margin-bottom: 10px;
			}
			a[href]:after {
				content: none;
				/*      content: " (" attr(href) ")";*/
			}
		}
	</style>

	@endsection

	@section('content')

	@include('admin.includes.side_navbar')

				<div id="page-wrapper" class="gray-bg hidden-print">

					@include('admin.includes.top_navbar')

					<!-- Heading -->
					<div class="row wrapper border-bottom white-bg page-heading">
						<div class="col-lg-8 col-md-6">
							<h2>Students</h2>
							<ol class="breadcrumb">
								<li>Home</li>
								<li>Student</li>
								<li Class="active">
									<a>Parent Interview</a>
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
												<h2>Parent Interview of Student <b>@{{student.name}}</b> <a v-on:click.stop.prevent="print()" title="Profile Print" data-toggle="tooltip"><span class="fa fa-print"></span></a></h2>
												<div class="hr-line-dashed"></div>
										</div>

										<div class="ibox-content">
											<form  method="post" v-on:submit.prevent="formSubmit($event)" :action="URL+'/students/interview/'+student.id" class="form-horizontal">
												{{ csrf_field() }}
												<input type="hidden" name="student_id" v-model="student.id">
												<div class="form-group">
													<label class="col-md-2 control-label">Father Qualification</label>
													<div class="col-md-6">
														<select class="form-control" name="father_qualification" v-model="interview.father_qualification">
															<option value="-">-</option>
															<option>Matric</option>
															<option>Inter</option>
															<option>Graduate</option>
															<option>Master</option>
															<option>Others</option>
														</select>
													</div>
												</div>

												<div class="form-group{{ ($errors->has('mother_qualification'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Mother Qualification</label>
													<div class="col-md-6">
														<select class="form-control" name="mother_qualification" v-model="interview.mother_qualification">
															<option value="-">-</option>
															<option>Matric</option>
															<option>Inter</option>
															<option>Graduate</option>
															<option>Master</option>
															<option>Others</option>
														</select>
														@if ($errors->has('mother_qualification'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('mother_qualification') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('father_occupation'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Father Occupation</label>
													<div class="col-md-6">
														<input type="text" name="father_occupation" placeholder="Father Occupation" v-model="interview.father_occupation" class="form-control" />
														@if ($errors->has('father_occupation'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('father_occupation') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('mother_occupation'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Mother Occupation</label>
													<div class="col-md-6">
														<input type="text" name="mother_occupation" placeholder="Father Qualification" v-model="interview.mother_occupation" class="form-control" />
														@if ($errors->has('mother_occupation'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('mother_occupation') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('monthly_income'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Monthly Income</label>
													<div class="col-md-6">
														<input type="number" name="monthly_income" placeholder="Father Qualification" v-model="interview.monthly_income" class="form-control" />
														@if ($errors->has('monthly_income'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('monthly_income') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('other_job_father'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Father Other Job</label>
													<div class="col-md-6">
														<input type="text" name="other_job_father" v-model="interview.other_job_father" class="form-control" />
														@if ($errors->has('other_job_father'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('other_job_father') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('other_job_mother'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Mother Other Job</label>
													<div class="col-md-6">
														<input type="text" name="other_job_mother" v-model="interview.other_job_mother" class="form-control" />
														@if ($errors->has('other_job_mother'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('other_job_mother') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group{{ ($errors->has('family_structure'))? ' has-error' : '' }}">
													<label class="col-md-2 control-label">Family Structure</label>
													<div class="col-md-6">
														<select class="form-control" name="family_structure" v-model="interview.family_structure" >
															<option value="-">-</option>
															<option>Single</option>
															<option>Join</option>
														</select>
														@if ($errors->has('family_structure'))
																<span class="help-block">
																		<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('family_structure') }}</strong>
																</span>
														@endif
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-2 control-label">Parents Living</label>
													<div class="col-md-6">
														<select class="form-control" name="parents_living" v-model="interview.parents_living" >
															<option value="-">-</option>
															<option>Living Together</option>
															<option>Separate</option>
															<option>Divorced</option>
															<option>Widow</option>
														</select>
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-2 control-label">No of Boys</label>
													<div class="col-md-6">
														<input type="number" name="no_of_children['boys']" v-model="interview.no_of_children.boys" class="form-control"/>
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-2 control-label">No of Girls</label>
													<div class="col-md-6">
														<input type="number" name="no_of_children['girls']" v-model="interview.no_of_children.girls" class="form-control"/>
													</div>
												</div>

												<div v-for="(question, k) in interview.questions" class="form-group">
													<div class="col-md-12">
														<label class="control-label">@{{question.q}}</label>
														<input type="hidden" :name="'questions['+k+'][q]'" v-model="question.q">
														<input type="text" :name="'questions['+k+'][a]'" v-model="question.a" class="form-control"/>
													</div>
												</div>

												<hr>
												<h3>For Montessori Only</h3>

												<template v-for="(question, k) in interview.questions_montessori">
													<div v-if="k > 0" class="form-group">
														<div class="col-md-12">
															<label class="control-label">@{{question.q}}</label>
															<input type="hidden" :name="'questions_montessori['+k+'][q]'" v-model="question.q">
															<input type="text" :name="'questions_montessori['+k+'][a]'" v-model="question.a" class="form-control"/>
														</div>
													</div>
													<div v-else class="form-group">
														<label class="col-md-4 control-label">@{{question.q}}</label>
														<div class="col-md-4">
															<input type="hidden" :name="'questions_montessori['+k+'][q]'" v-model="question.q">
															<select class="form-control" :name="'questions_montessori['+k+'][a]'" v-model="question.a" >
																<option value="-">-</option>
																<option>Yes</option>
																<option>No</option>
															</select>
														</div>
													</div>
												</template>

												<div class="form-group">
													<label class="col-md-2 control-label">Remarks</label>
													<div class="col-md-6">
														<textarea class="form-control" rows="4" v-model="interview.remarks" name="remarks"></textarea>
													</div>
												</div>

												<div class="form-group">
													<div class="col-md-offset-2 col-md-6">
														<button v-if="loading" class="btn btn-primary btn-block" disabled="true" type="submit"><span class="fa fa-pulse fa-spin fa-spinner"></span> Loading... </button>
														<button v-else class="btn btn-primary btn-block" type="submit"><span class="glyphicon glyphicon-save"></span> Update </button>
													</div>
												</div>
											</form>

										</div>
									</div>
								</div>
						</div>

					</div>


					


				</div>

		<div id="parent_interview_printable" class="visible-print">
			@include('admin.printable.include.parent_interview')
		</div>

		@endsection

		@section('script')

		<!-- Select2 -->
		<script src="{{ asset('src/js/plugins/select2/select2.full.min.js') }}"></script>

		<!-- require with bootstrap-datetimepicker -->
		<script src="{{ asset('src/js/plugins/moment/moment.min.js') }}"></script>
		<script src="{{ asset('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

		<script type="text/javascript">

			$(document).ready(function(){

				$('[data-toggle="tooltip"]').tooltip();

				$('#datetimepicker4').datetimepicker({
						 format: 'DD/MM/YYYY'
					 });
			});

		</script>

		@endsection

@section('vue')

	<script type="text/javascript">
		var app = new Vue({
			el: '#app',
			data: {
				loading: false,
				URL: '{{ URL('/') }}',
				student: {!! json_encode($student, JSON_NUMERIC_CHECK) !!},
				default_interview: {
					father_qualification: 	'-',
					mother_qualification: 	'-',
					father_occupation: 	'',
					mother_occupation: 	'',
					monthly_income: 	0,
					other_job_father: 	'',
					other_job_mother: 	'',
					family_structure: 	'-',
					parents_living: 	'-',
					remarks: 			'',
					no_of_children: 	{boys: 0, girls: 0},
					questions: 	{!! json_encode(config('parentInterviewQuestions.questions'), JSON_NUMERIC_CHECK) !!},
					questions_montessori: 	{!! json_encode(config('parentInterviewQuestions.questions_montessori_'.strtolower($student->gender)), JSON_NUMERIC_CHECK) !!},
				}
			},
			computed: {
				interview: function(){
					return this.student.parent_interview? this.student.parent_interview : this.default_interview;
				}
			},
			methods: {
				print: function(){
					window.print();
				},
				formSubmit: function(e){
					this.loading = true;
					$.ajax({
					type: e.target.method,
					url:  e.target.action,
					data: $(e.target).serialize(),
					success: function(msg){
						app.AjaxMsg(msg);
						app.loading = false;
					},
					error: function(){
							alert("failure");
							app.loading = false;
						}
					});
				},
				AjaxMsg: function(msg){
						toastr.options = {
							closeButton: true,
							progressBar: true,
							showMethod: 'slideDown',
							timeOut: 8000
						};
						toastr[msg.type](msg.msg, msg.title);
				},
				no_of_children: function(){
					no = '';
					no += this.interview.no_of_children.boys? this.interview.no_of_children.boys+' Boys' : '0 Boys';
					no += this.interview.no_of_children.girls? this.interview.no_of_children.girls+' Girls' : ' 0 Girls';
					return no;
				}
			}

		});
	</script>

@endsection





