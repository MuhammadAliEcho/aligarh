@extends('admin.layouts.master')

	@section('title', 'Exam Grades |')

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
										@can('exam-grades.update')
											<li Class="active">
												<a>Exam Grades</a>
											</li>
										@endcan
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
													@can('exam-grades.update')
														<li class="active">
															<a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Exam Grades</a>
														</li>
													@endcan
												</ul>
												<div class="tab-content">
													@can('exam-grades.update')
														<div id="tab-11" class="tab-pane fade fade in active add-guardian">
																<div class="panel-body">
																	
																	<div class="alert alert-info ">
																		<h4>Note! </h4>Can set grades prifix between percentages.
																	</div>

																	<form id="tchr_rgstr" method="POST" action="{{ URL('exam-grades/update') }}" class="form-horizontal" >
																		{{ csrf_field() }}

																		<div class="col-lg-8">
																			<div class="panel panel-info">
																				<div class="panel-heading">
																					Exam Grades <a href="#" id="addfee" data-toggle="tooltip" title="Add Grade" @click="addgrade()" style="color: #ffffff"><span class="fa fa-plus"></span></a>
																				</div>
																				<div class="panel-body">
																					<table id="additionalfeetbl" class="table table-bordered table-hover table-striped">
																						<thead>
																							<tr>
																								<th>#</th>
																								<th>Percent From</th>
																								<th>Percent To</th>
																								<th>Prifix</th>
																								<th>Name</th>
																								<th>Action</th>
																							</tr>
																						</thead>
																						<tbody>
																							<tr v-for="(grade, k) in exam_grades">
																								<td>@{{ k+1 }}</td>
																								<td><input type="number" step="0.1" :name="'grades['+ grade.id +'][from_percent]'" class="form-control" required="true" v-model="grade.from_percent"></td>
																								<td><input type="number" step="0.1" :name="'grades['+ grade.id +'][to_percent]'" class="form-control additfeeamount" min="1" v-model="grade.to_percent" required="true"></td>
																								<td><input type="text" :name="'grades['+ grade.id +'][prifix]'" class="form-control additfeeamount" v-model="grade.prifix" required="true"></td>
																								<td><input type="text" :name="'grades['+ grade.id +'][name]'" class="form-control additfeeamount" v-model="grade.name" required="true"></td>
																								<td>
																									<a href="javascript:void(0);" class="btn btn-default text-danger" data-toggle="tooltip" @click="removegrade(k)" title="Remove" ><span class="fa fa-trash"></span></a>
																								</td>
																							</tr>
																						</tbody>
																					</table>
																				</div>
																			</div>
																		</div>

																		<div v-if="edited" class="form-group">
																				<div class="col-md-offset-2 col-md-6">
																					<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Update </button>
																					<a class="btn btn-default" href="{{ URL('exam-grades') }}"><span class="fa fa-close"></span> Cancel </a>
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

				$('[data-toggle="tooltip"]').tooltip();
				$('table tbody').on('mouseenter', '[data-toggle="tooltip"]', function(){
					$(this).tooltip('show');
				}).mouseleave(function(){
					$(this).tooltip('destroy');
				});

				$("#tchr_rgstr").validate();

			});
		</script>

		@endsection

@section('vue')
	<script type="text/javascript">
		var app = new Vue({
			el: '#app',
			data: { 
				exam_grades: {!! $grades !!},
				edited: false
			},
			computed: {
				lastid: function(){
					return this.exam_grades[this.exam_grades.length-1].id;
				}
			},
			methods: {
				addgrade: function (){
					this.exam_grades.push({
						id: this.lastid+1,
						from_percent: 0,
						to_percent: 0,
						prifix: '',
						name: ''
					});
					this.edited = true;
				},
				removegrade: function(k){
					this.exam_grades.splice(k, 1);
					this.edited = true;
				}
			},

		});
	</script>
@endsection
