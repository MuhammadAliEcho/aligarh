@extends('admin.layouts.master')

  @section('title', 'Students |')

  @section('head')
  <!-- HEAD -->
		<link href="{{ asset('src/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">

	<style type="text/css">
		@media print{
			body {
				padding: 0px 10px;
				margin: 0px;
				font-size: 13px;
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
					<li><a href="{{ URL('students') }}"> Students </a></li>
					<li Class="active">
						<a>Profile</a>
					</li>
					<li Class="active">
						@{{ student.name }}
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
				<div class="col-md-4">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>Profile Detail</h5>
						</div>
						<div>
							<div class="ibox-content no-padding border-left-right">
							  <center>
								<img alt="image" class="img-responsive" src="{{ URL(($student->image_url == '')? 'img/avatar.jpg' : $student->image_url) }}">
							  </center>
							</div>
							<div class="ibox-content profile-content">
								<h4><strong>@{{ student.name }}</strong></h4>
								<p><i class="fa fa-map-marker"></i> @{{ student.address }}</p>
								<p v-if="student.active == false"><b>Date Of Leaving:</b> @{{  student.date_of_leaving }}</p>
								<template v-if="student.active && allow_user_leave">
									<hr>
									<a href="#" v-on:dblclick="leavingfrm = !leavingfrm" data-toggle="tooltip" title="DoubleClick to Inactive"><b>Actvie</b></a>
									<form v-show="leavingfrm" method="post" v-on:submit.prevent="formSubmit($event)" :action="URL+'/students/leave/'+student.id" class="form-horizontal">
										{{ csrf_field() }}
										<input type="hidden" name="id" v-model="student.id">
										<div class="alert alert-warning ">
											<h4><span class="fa fa-exclamation-triangle"></span> Important </h4>
											<p>
												Once the Date of Leaving is set, the student becomes inactive and cannot be reactivated.
												<br>
												<b>To rejoin,</b> a new registration form is required.
											</p>
										</div>
										<div class="form-group">
											<div class="col-md-offset-1 col-md-10">
												<span>Cause Of Leaving</span>
												<textarea class="form-control" name="cause_of_leaving" rows="3" style="resize: none"></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-offset-1 col-md-10">
												<input type="text" name="date_of_leaving" v-model="student.date_of_leaving" autocomplete="off" placeholder="date of leaving" class="form-control" readonly="true">
											</div>
										</div>
										<div v-if="student.date_of_leaving" class="form-group">
											<div class="col-md-offset-2 col-md-10">
												<button v-if="loading" class="btn btn-primary" disabled="true" type="submit"><span class="fa fa-pulse fa-spin fa-spinner"></span> Loading... </button>
												<button v-else class="btn btn-primary" type="submit">Save</button>
											</div>
										</div>
									</form>
								</template>
							</div>
						</div>
					</div>

					<div v-if="siblings.length" class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>Siblings</h5>
						</div>
						<div class="ibox-content">
							<table class="table">
								<thead>
									<th>S.No</th>
									<th>Gr No</th>
									<th>Name</th>
								</thead>
								<tbody>
									<tr v-for="(std, k) in siblings" :key="std.id">
										<td><a :href="'/students/profile/' + std.id">@{{ k + 1 }}</a></td>
										<td><a :href="'/students/profile/' + std.id">@{{ std.gr_no }}</a></td>
										<td><a :href="'/students/profile/' + std.id">@{{ std.name }}</a></td>
									</tr>
								</tbody>
								
							</table>
						</div>
					</div>

					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>Certificates</h5>
						</div>
						<div class="ibox-content">
							<table class="table" v-if="student.certificates.length">
								<thead>
									<tr>
										<th>Title</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="certificate in student.certificates">
										<td>@{{ certificate.title }}</td>
										<td>
											@can('students.certificate.create')
											<a :href="URL+'/students/certificate/update?certificate_id='+certificate.id" title="view" data-toggle="tooltip"><span class="fa fa-file-pdf-o"></span></a>
											@endcan
										</td>
									</tr>
								</tbody>
							</table>
							@can('students.certificate.create')
							<form :action="URL+'/students/certificate/new'" method="get">
								<input type="hidden" name="student_id" v-model="student.id">
								<button class="btn btn-primary btn-block">Create Certificate</button>
							</form>
							@endcan
						</div>
					</div>

					<div v-if="allow_user_certificate" class="ibox float-e-margins hidden">
						<div class="ibox-title">
							<h5>Certificates</h5>
						</div>
						<div class="ibox-content profile-content">
							<form :action="URL+'/students/certificate/transfercertificate'" method="post" target="_blank">
								{{ csrf_field() }}
								<input type="hidden" name="id" :value="student.id">
								<button type="submit" class="btn btn-primary btn-block"><span class="fa fa-file-pdf-o"></span> Transfer Certificate</button>
							</form>
						</div>
					</div>

				</div>
				<div class="col-md-8">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>Details <a v-on:click.stop.prevent="print()" title="Profile Print" data-toggle="tooltip"><span class="fa fa-print"></span></a> </h5>
						</div>
						<div class="ibox-content">

							<table class="table table-hover">
								<tbody>
									<tr>
										<th>Name :</th>
										<td>@{{ student.name }}</td>
									</tr>
									<tr>
										<th>Father Name :</th>
										<td>@{{ student.father_name }}</td>
									</tr>
									<tr>
										<th>Religion :</th>
										<td>@{{ student.religion }}</td>
									</tr>
									<tr>
										<th>GR NO :</th>
										<td>@{{ student.gr_no }}</td>
									</tr>
									<tr>
										<th>Gender :</th>
										<td>@{{ student.gender }}</td>
									</tr>
									<tr>
										<th>Date Of Birth :</th>
										<td>@{{ student.date_of_birth }}</td>
									</tr>
									<tr>
										<th>Date Of Admission :</th>
										<td>@{{ student.date_of_admission }}</td>
									</tr>
									<tr>
										<th>Date Of Enrolled :</th>
										<td>@{{ student.date_of_enrolled }}</td>
									</tr>
									<tr>
										<th>Place Of Birth :</th>
										<td>@{{ student.place_of_birth }}</td>
									</tr>
									<tr>
										<th>Last Attend School :</th>
										<td>@{{ student.last_school }}</td>
									</tr>
									<tr>
										<th>Seeking Class :</th>
										<td>@{{ student.seeking_class }}</td>
									</tr>
									<tr>
										<th>Receipt No :</th>
										<td>@{{ student.receipt_no }}</td>
									</tr>
									<tr>
										<th>Class :</th>
										<td>@{{ student.std_class.name }} @{{ student.section.nick_name }}</td>
									</tr>
									<tr>
										<th>Parent :</th>
										<td>
										  <a :href="URL+'/guardians/profile/'+student.guardian.id">
											@{{ student.guardian.name }}. ( @{{ student.guardian_relation }})
										  </a>
										</td>
									</tr>
									<tr>
										<th>Email :</th>
										<td>@{{ student.email }}</td>
									</tr>
									<tr>
										<th>Contact :</th>
										<td>@{{ student.phone }}</td>
									</tr>
									<tr>
										<th>Fee :</th>
										<td>@{{ student.net_amount }} /=</td>
									</tr>
								</tbody>
							</table>
							@can('students.interview.update.create')
							<a :href="URL+'/students/interview/'+student.id" class=" btn btn-primary btn-block"><span class="fa fa-podcast"></span> Parent Interview</a>
							@endcan

						</div>
					</div>

				</div>
			</div>
		</div>

		  

		</div>

		<div id="student_profile_printable" class="visible-print">
			@include('admin.printable.include.student_profile')
		</div>

	@endsection

	@section('script')

	<!-- Data picker -->
	<script src="{{ asset('src/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>

	@endsection

	@section('vue')
	<script type="text/javascript">
		var app = new Vue({
			el: "#app",
			data: {
				URL: "{{ URL('/') }}",
				student: {!! json_encode($student, JSON_NUMERIC_CHECK) !!},
				leavingfrm: false,
				loading: false,
				allow_user_leave: {{ Auth::user()->hasPermissionTo('students.leave') ? 'true' : 'false' }},
				allow_user_certificate: {{ Auth::user()->hasPermissionTo('students.certificate.get') ? 'true' : 'false' }},
			},
			mounted: function(){
				$("[data-toggle='tooltip']").on('mouseenter', function(){
						$(this).tooltip('show');
					}).mouseleave(function(){
						$(this).tooltip('destroy');
					});
//				window.print();
			},
			updated: function(){
				$('input[name="date_of_leaving"]').datepicker({
						format: 'yyyy-mm-dd',
						keyboardNavigation: false,
						forceParse: false,
						autoclose: true,

						todayHighlight: true
					}).change(function(){
						app.student.date_of_leaving = $(this).val();
					});
			},
			computed: {
				siblings: function(){
					return this.student.guardian.student;
/*					vm = this;
					return	_.filter(this.student.guardian.student, function(std){
								return std.id !== vm.student.id;
							});*/
				}
			},
			methods: {
				formSubmit: function(e){
					this.loading = true;
					$.ajax({
					type: e.target.method,
					url:  e.target.action,
					data: $(e.target).serialize(),
					success: function(dta){
						msg = dta.toastrmsg;
						toastr.options = {
							closeButton: true,
							progressBar: true,
							showMethod: 'slideDown',
							timeOut: 8000
						};
						toastr[msg.type](msg.msg, msg.title);

						if(dta.updated){
							app.student.active = 0;
						}

						app.loading = false;
					},
					error: function(){
							alert("failure");
							app.loading = false;
						}
					});
				},
				print: 	function(){
					window.print();
				}
			}

		});
	</script>
	@endsection

