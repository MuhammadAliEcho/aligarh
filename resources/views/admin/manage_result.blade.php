@extends('admin.layouts.master')

  @section('title', 'Student Restults Manage |')

  @section('head')
	<link href="{{ URL::to('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">

  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

		<div id="page-wrapper" class="gray-bg">

		  @include('admin.includes.top_navbar')

		  <!-- Heading -->
		  <div class="row wrapper border-bottom white-bg page-heading">
			  <div class="col-lg-8 col-md-6">
				  <h2>Students Result</h2>
				  <ol class="breadcrumb">
					<li>Home</li>
					  <li Class="active">
						  <a>Students Result</a>
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
							<li class="make-result">
							  <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Make Result </a>
							</li>
							<li class="get-result">
							  <a data-toggle="tab" href="#tab-11"><span class="fa fa-bar-chart"></span> Result Attributes</a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="tab-10" class="tab-pane fade make-result">
								<div class="panel-body" style="min-height: 400px">
								  <h2> Make Result </h2>
								  <div class="hr-line-dashed"></div>

									<form id="mk_result_frm" method="GET" action="{{ URL('manage-result/make') }}" class="form-horizontal jumbotron" role="form" >

									  <div class="form-group{{ ($errors->has('exam'))? ' has-error' : '' }}">
										<label class="col-md-2 control-label"> Exam </label>
										<div class="col-md-6">
										  <select class="form-control select2" name="exam" required="true">
											<option value="" disabled selected>Exam</option>
											@foreach($exams AS $exam)
											  <option value="{{ $exam->id }}">{{ $exam->name }}</option>
											@endforeach
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
										  <select class="form-control select2" name="class" required="true">
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

									  <div class="form-group{{ ($errors->has('subject'))? ' has-error' : '' }}">
										<label class="col-md-2 control-label"> Subject </label>
										<div class="col-md-6">
										  <select class="form-control select2" name="subject" required="true">
										  <option value="" disabled selected>Subject</option>
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
											  <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Make Result </button>
										  </div>
									  </div>

									</form>

									@if($root == 'make')
									<div class="row">
										<form v-if="SavedResultId > 0" id="SavedResultRemoveForm" method="POST" action="{{ URL('manage-result/remove') }}">
											{{ csrf_field() }}
											<input type="hidden" name="SavedResultId" :value="SavedResultId">
										</form>
									  <h3>Exam: {{ $selected_exam->name }}, Class: {{ $selected_class->name }} ({{ $selected_subject->name }})<button v-if="SavedResultId > 0" @click="removeResult()" class="pull-right btn btn-danger" title="Remove" data-toggle="tooltip"><span class="fa fa-trash"></span></button></h3>
									  <div class="hr-line-dashed"></div>

									  <form action="{{ URL('manage-result/make') }}" class="form-horizontal" method="POST">
										{{ csrf_field() }}
										<input type="hidden" name="exam" value="{{ $selected_exam->id }}">
										<input type="hidden" name="subject" value="{{ $selected_subject->id }}">
										<input type="hidden" name="class" value="{{ $selected_class->id }}">

										<div class="row">
											<div class="col-md-offset-2 col-md-6">
												<div class="panel panel-success">
													<div class="panel-heading">Result Attributes <a href="#" style="color: white" title="Add" data-toggle="tooltip" @click="addAttribute()"><span class="fa fa-plus"></span></a> | <a href="#" title="Remove" style="color: white" data-toggle="tooltip" @click="removeAttribute((ResutlAttributes.length - 1))"><span class="fa fa-remove"></span></a></div>
													<div class="panel-body">
														<table class="table">
															<tr v-for="(attribute, k) in ResutlAttributes">
																<td><input type="text" class="form-control" placeholder="Written/Oral/Practical" :name="'attributes['+k+'][name]'" v-model="attribute.name" required="true"></td>
																<td><input type="number" class="form-control" :name="'attributes['+k+'][marks]'" min="0" v-model="attribute.marks" required="true" placeholder="Marks" /></td>
															</tr>
															<tr>
																<th>Total Marks</th>
																<th><input type="number" name="total_marks" v-model="total_marks" class="form-control" readonly="true"></th>
															</tr>
														</table>
													</div>
												</div>
											</div>
										</div>

										<table class="table table-striped table-bordered table-hover">
										  <thead>
											<tr>
												<th rowspan="2">GR No</th>
												<th rowspan="2">Name</th>
												<th colspan="3" class="text-center">Obtain Marks</th>
											</tr>
											<tr>
												<th v-for="attribute in ResutlAttributes">@{{ attribute.name }}</th>
											</tr>
										  </thead>
										  <tbody>
											<tr v-for="student in computedStudents">
												<td>@{{ student.gr_no }}</td>
												<td>@{{ student.name }}</td>
												<td v-for="(result, k) in ResutlAttributes">
													<input type="hidden" :name="'students['+student.id+'][obtain_marks]['+k+'][name]'" v-model="student.student_result.obtain_marks[k].name = result.name">
													<div class="input-group m-b">
														<span class="input-group-addon" @mouseenter="tooltip" @mouseleave="tooltipdestroy" title="Attendance" @click="student.student_result.obtain_marks[k].attendance = !student.student_result.obtain_marks[k].attendance">
															<input v-model="student.student_result.obtain_marks[k].attendance" type="checkbox">
															<input type="hidden" :name="'students['+student.id+'][obtain_marks]['+k+'][attendance]'" v-model="student.student_result.obtain_marks[k].attendance">
														</span>
														<template v-if="student.student_result.obtain_marks[k].attendance">
															<input type="number" step="0.01" class="form-control" :max="result.marks" :name="'students['+student.id+'][obtain_marks]['+k+'][marks]'" v-model="student.student_result.obtain_marks[k].marks" required="true">
														</template>
														<template v-else >
															<input type="text" class="form-control" :value="'Absent'" readonly="true">
															<input type="hidden" :value="0" :name="'students['+student.id+'][obtain_marks]['+k+'][marks]'" class="form-control">
														</template>
													</div>
												</td>
											</tr>
										  </tbody>
										</table>

										<div class="form-group">
											<div class="col-md-offset-4 col-md-4">
												<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Make Result </button>
											</div>
										</div>

									  </form>
									</div>
									@endif
								</div>
							</div>

							<div id="tab-11" class="tab-pane fade get-result">
								<div class="panel-body" style="min-height: 400px">
								  <h2> Search Fields </h2>
								  <div class="hr-line-dashed"></div>

									<form id="rpt_result_frm" method="GET" action="{{ URL('manage-result/resultattributes') }}" class="form-horizontal jumbotron" role="form" >

									  <div class="form-group{{ ($errors->has('exam'))? ' has-error' : '' }}">
										<label class="col-md-2 control-label"> Exam </label>
										<div class="col-md-6">
										  <select class="form-control select2" name="exam" required="true">
											<option value="" disabled selected>Exam</option>
											@foreach($exams AS $exam)
											  <option value="{{ $exam->id }}">{{ $exam->name }}</option>
											@endforeach
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
										  <select class="form-control select2" name="class" required="true">
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

									  <div class="form-group">
										  <div class="col-md-offset-2 col-md-4">
											  <button class="btn btn-primary btn-block" type="submit"><span class="fa fa-list"></span> Show Result </button>
											  <a href="#" id="result_attributes_print" ><span class="glyphicon glyphicon-print"></span> Print</a>
										  </div>
									  </div>

									</form>

									@if($root == 'resultattributes')
									@if($subject_result->count())
									<div class="row">
										<div id="result_attributes">
										  <h3>Exam: {{ $selected_exam->name }}, Class: {{ $selected_class->name }}</h3>
										  <div class="hr-line-dashed"></div>
											<div class="table-responsive">
											  <table id="rpt-result" class="table table-striped table-bordered table-hover">
												<thead>
												  <tr>
													<th>Subject</th>
													<th>Marks</th>
													<th>Total</th>
													<th class="no-print">Options</th>
												  </tr>
												</thead>
												<tbody>
												  @foreach($subject_result AS $result)
												  <tr>
													<td>{{ $result->Subject->name }}</td>
													<td>
														@foreach($result->attributes AS $attribute)
															{{ $attribute->name }}: {{ $attribute->marks }}<br>
														@endforeach
													</td>
													<td>{{ $result->total_marks }}</td>
													<td class="no-print"><a data-toggle="tooltip" title="View & Edit" href="{{ URL::asset('manage-result/make?'.http_build_query(['exam'=>$selected_exam->id,'class'=>$selected_class->id,'subject'=>$result->subject_id])) }}" class="btn btn-primary btn-circle btn-xs"><span class="fa fa-list"></span></a></td>
												  </tr>
												  @endforeach
												</tbody>
											  </table>
											</div>
										</div>
										<form class="form-horizontal" action="{{ URL('manage-result/maketranscript') }}" method="GET">
											<input type="hidden" name="exam" value="{{ $selected_exam->id }}">
											<input type="hidden" name="class" value="{{ $selected_class->id }}">
											<div class="form-group">
												<div class="col-md-offset-2 col-md-4">
													<button type="submit" class="btn btn-primary btn-block"><span class="fa fa-file-pdf-o"></span> Make Transcript </button>
												</div>
											</div>
										</form>
									</div>
									@else
									<div class="row">
										<div class="alert alert-danger">
											<h3>
												<span class="fa fa-exclamation-triangle"></span>
												Data Not Found
											</h3>
										</div>
									</div>
									@endif
									@endif

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

	<script src="{{ URL::to('src/js/jquery.print.js') }}"></script>

	<script type="text/javascript">
	  $('#result_attributes_print').click(function(e){
		e.preventDefault();
		$("#result_attributes").print({
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

	<script type="text/javascript">

	var tbl;
	var attendancerpt;
	  $(document).ready(function(){

	  var subjects = {!! json_encode($subjects) !!};

		$('[data-toggle="tooltip"]').tooltip();

	  $('[name="class"]').on('change', function(){
		clsid = $(this).val();
		$('[name="class"]').val(clsid);
		  $('select[name="subject"]').html('<option></option>');
		  if(subjects['class_'+clsid].length > 0 && clsid > 0){          
			$.each(subjects['class_'+clsid], function(k, v){
			  $('select[name="subject"]').append('<option value="'+v['id']+'">'+v['name']+'</option>');
			});
		  }
	  });

	  @if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
		$('[name="class"]').val("{{ old('class') }}");
		$('[name="class"]').change();
		$('[name="exam"]').val("{{ old('exam') }}");
		$('select[name="subject"]').val('{{ old('subject') }}');

	  @elseif(isset($input) && $input !== null)
		$('[name="class"]').val("{{ $input['class'] }}");
		$('[name="class"]').change();
		$('[name="exam"]').val("{{ $input['exam'] }}");
		@if($root == 'make')
		$('select[name="subject"]').val("{{ $input['subject'] }}");
		@endif
	  @endif


		@if($root == 'resultattributes')
			$('.nav-tabs a[href="#tab-11"]').tab('show');
		@else
			$('.nav-tabs a[href="#tab-10"]').tab('show');
		@endif

	//Permission will be applied later
	//   "(Auth::user()->getprivileges->privileges->{$root['content']['id']}->make == 0)"
	// 		$('.make-result').hide();
	//   "endif"

	//   "(Auth::user()->getprivileges->privileges->{$root['content']['id']}->resultattributes == 0)"
	// 		$('.get-result').hide();
	//   "endif"

	  });
	</script>

	@endsection

	@if($root == 'make')
	@section('vue')
	<script type="text/javascript">
		var app = new Vue({
			el:	'#app',
			data:	{
				students: {!! json_encode($students) !!},
				ResutlAttributes: 	{!! (!empty($result_attribute?->attributes))? json_encode($result_attribute->attributes, JSON_NUMERIC_CHECK): json_encode([['name'=>'','marks'=>0]])!!},
				// (Auth::user()->getprivileges->privileges->{$root['content']['id']}->remove)
				@if(isset($result_attribute))
					SavedResultId: {{ $result_attribute->id ?? 0 }},
				@else
					SavedResultId: 0,
				@endif
				std: {},
				computedStudents: []
			},
			methods: {
				addAttribute: function (){
					if (this.ResutlAttributes.length < 2) {
						this.ResutlAttributes.push({
							name: '',
							marks: 0
						});
					}
				},
				removeAttribute: function(k){
					if (k > 0) {
						this.ResutlAttributes.splice(k, 1);
					}
				},
				getcomputedStudents: function(){
					this.computedStudents = [];
					for(k in this.students){
						this.std =	this.students[k];
						this.computedStudents.push({
							gr_no: this.students[k].gr_no,
							name: this.students[k].name,
							id: this.students[k].id,
							student_result: this.getstdresult
						});
					}
				},
				tooltip: function(){
					$(event.target).tooltip('show');
				},
				tooltipdestroy: function(){
					$(event.target).tooltip('destroy');
				},
				removeResult: function(){
					if(confirm('Are You sure to Delete Result!')){	
						$("#SavedResultRemoveForm").submit();
					}
				}
			},
			computed: 	{
				total_marks: function(){
					return	this.ResutlAttributes.reduce((a, b) => a + Number(b.marks), 0);
				},
				getstdresult: function(){
					obtain_marks = [];
					if(this.std.student_subject_result != null){
						for(k in this.ResutlAttributes){
							obtain_marks.push({
								name: (this.std.student_subject_result.obtain_marks[k] != undefined)? this.std.student_subject_result.obtain_marks[k].name : this.ResutlAttributes[k].name,
								marks: (this.std.student_subject_result.obtain_marks[k] != undefined)? this.std.student_subject_result.obtain_marks[k].marks : 0,
								attendance: (this.std.student_subject_result.obtain_marks[k] != undefined)? this.std.student_subject_result.obtain_marks[k].attendance : true
							});
						}
					} else {
						for(k in this.ResutlAttributes){
							obtain_marks.push({
								name: this.ResutlAttributes[k].name,
								marks: 0,
								attendance: true
							});
						}
					}
					return { obtain_marks };
				}

			},
			watch:{
				ResutlAttributes: function(){
					this.getcomputedStudents();
				}
			},
			mounted: function(){
				this.getcomputedStudents();
			}

		});
	</script>
	@endsection
	@endif