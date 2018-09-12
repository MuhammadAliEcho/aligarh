@extends('admin.layouts.master')

  @section('title', 'Students |')

  @section('head')
  <!-- HEAD -->
		<link href="{{ URL::to('src/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
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
								<template v-if="student.active && allow_user">
									<hr>
									<a href="#" v-on:dblclick="leavingfrm = !leavingfrm" data-toggle="tooltip" title="DoubleClick to Inactive"><b>Actvie</b></a>
									<form v-show="leavingfrm" method="post" v-on:submit.prevent="formSubmit($event)" :action="URL+'/students/leave/'+student.id" class="form-horizontal">
										{{ csrf_field() }}
										<input type="hidden" name="id" v-model="student.id">
										<div class="alert alert-warning ">
											<h4><span class="fa fa-exclamation-triangle"></span> Carefully! </h4>
											<p>
												Once Set Date of Leaving its means the student is leave or Inactive,
												<br>
												<b>Remember</b> IT will not rechange to active again.
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
				</div>
				<div class="col-md-8">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>Details</h5>
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
										<td>@{{ student.contact_no }}</td>
									</tr>
									<tr>
										<th>Fee :</th>
										<td>@{{ student.net_amount }} /=</td>
									</tr>
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

	<!-- Data picker -->
	<script src="{{ URL::to('src/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>


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
				allow_user: {{ Auth::user()->getprivileges->privileges->{$root['content']['id']}->leave }}
			},
			mounted: function(){
				$("[data-toggle='tooltip']").on('mouseenter', function(){
						$(this).tooltip('show');
					}).mouseleave(function(){
						$(this).tooltip('destroy');
					});
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
			}

		});
	</script>
	@endsection

