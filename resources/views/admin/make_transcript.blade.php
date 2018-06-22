@extends('admin.layouts.master')

	@section('title', 'Make Transcript |')

	@section('head')

	@endsection

	@section('content')

	@include('admin.includes.side_navbar')

				<div id="page-wrapper" class="gray-bg">

					@include('admin.includes.top_navbar')

					<!-- Heading -->
					<div class="row wrapper border-bottom white-bg page-heading">
							<div class="col-lg-8 col-md-6">
									<h2>Make Transcript</h2>
									<ol class="breadcrumb">
										<li>Home</li>
										<li Class="active">
											<a>Make Transcript</a>
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
											<h2>Exam: <u>{{ $selected_exam->name }}</u>, Class: <u>{{ $selected_class->name }}</u></h2>
											<div class="hr-line-dashed"></div>
										</div>

										<div class="ibox-content">

											<table class="table table-striped table-hover table-bordered">
												<thead>
													<tr>
														<th>GR No</th>
														<th>Name</th>
														<th>Father Name</th>
														<th>Total Marks</th>
														<th>Obtain Marks</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<template v-for="student in computedStudents" :key="student.id">
														
													<tr>
														<td>@{{ student.gr_no }}</td>
														<td>@{{ student.name }}</td>
														<td>@{{ student.father_name }}</td>
														<td>@{{ student.total_marks }}</td>
														<td>@{{ student.total_obtain_marks }}</td>
														<td><button @click="{student.transcriptshow = !student.transcriptshow}" @mouseenter="tooltip" @mouseleave="tooltipdestroy" class="btn" :class="[student.transcriptshow? 'btn-primary' : 'btn-default']"  title="Marks List"><span class="fa fa-list"></span></button></td>
													</tr>
													<tr v-if="student.transcriptshow">
														<td colspan="6">
															<table class="table table-bordered table-hover table-striped">
																<thead>
																	<tr>
																		<th rowspan="2">Subject</th>
																		<th colspan="2">Total Marks</th>
																		<th colspan="2">Obtain Marks</th>
																	</tr>
																	<tr>
																		<th>Attributes</th>
																		<th>Total</th>
																		<th>Attributes</th>
																		<th>Total</th>
																	</tr>
																</thead>
																<tbody>
																	<tr v-for="result in student.transcript">
																		<td>@{{ result.subject.name }}</td>
																		<td>
																			<template v-for="attribute in result.subject_result_attribute.attributes">
																				@{{ attribute.name }}: @{{ attribute.marks }}<br>
																			</template>
																		</td>
																		<td>@{{ result.subject_result_attribute.total_marks }}</td>
																		<td>
																			<template v-for="mark in result.obtain_marks">
																				@{{ mark.name }}: @{{ mark.marks }}<br>
																			</template>
																		</td>
																		<td>@{{ result.total_obtain_marks }}</td>
																	</tr>
																</tbody>
																<tfoot>
																	<tr>
																		<td colspan="5">
																			<form class="form-horizontal" v-on:submit.prevent="formSubmit($event)" method="POST">
																				{{csrf_field()}}
																				<input type="hidden" name="id" v-model="student.id">
																				<textarea class="form-control" name="remarks" placeholder="Enter Remarks" v-model="student.remarks"></textarea>
																				<div class="col-md-4 pull-right">
																					<button v-if="loading" class="btn btn-primary btn-block" disabled="true" type="submit"><span class="fa fa-pulse fa-spin fa-spinner"></span> Loading... </button>
																					<button v-else class="btn btn-default btn-block" type="submit"><span class="fa fa-save"></span> Save </button>
																				</div>
																			</form>
																		</td>
																	</tr>
																</tfoot>
															</table>
														</td>
													</tr>
													</template>
												</tbody>
											</table>
										</div>
									</div>
							</div>
						</div>

					</div>


					@include('admin.includes.footercopyright')


				</div>

		@endsection

		@section('script')



		@endsection

		@section('vue')
		<script type="text/javascript">
		var app = new Vue({
			el:	'#app',
			data:	{
				transcripts: {!! json_encode($transcripts) !!},
				loading: false,
				computedStudents: []
			},
			methods: {
				formSubmit: function(e){
					this.loading = true;
//					alert(e.target.method);
					$.ajax({
					type: e.target.method,
					url:  e.target.action,
					data: $(e.target).serialize(),
					success: function(msg){

						toastr.options = {
							closeButton: true,
							progressBar: true,
							showMethod: 'slideDown',
							timeOut: 8000
						};
						toastr[msg.type](msg.msg, msg.title);

						app.loading = false;
					},
					error: function(){
							alert("failure");
							app.loading = false;
						}
					});
				},
				tooltip: function(){
					$(event.target).tooltip('show');
				},
				tooltipdestroy: function(){
					$(event.target).tooltip('destroy');
				}
			},
			mounted: function(){ 
				for(k in this.transcripts){
					this.computedStudents.push({
						name: this.transcripts[k].student.name,
						remarks: this.transcripts[k].remarks,
						father_name: this.transcripts[k].student.father_name,
						gr_no: this.transcripts[k].student.gr_no,
						id: this.transcripts[k].id,
						total_marks: this.transcripts[k].student_result.reduce((a, b) => a + Number(b.subject_result_attribute.total_marks), 0),
						total_obtain_marks: this.transcripts[k].student_result.reduce((a, b) => a + Number(b.total_obtain_marks), 0),
						transcript: this.transcripts[k].student_result,
						transcriptshow: false
					});
				}
			}
		});
		</script>
		@endsection