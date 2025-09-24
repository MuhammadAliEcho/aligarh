@extends('admin.layouts.master')

  @section('title', 'SMS Notifications |')

  @section('head')

	<link href="{{ asset('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
	<link href="{{ asset('src/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
	<link href="{{ asset('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
	<link href="{{ asset('src/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">

  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

		<div id="page-wrapper" class="gray-bg">

		  @include('admin.includes.top_navbar')

		  <!-- Heading -->
		  <div class="row wrapper border-bottom white-bg page-heading">
			  <div class="col-lg-8 col-md-6">
				  <h2>SMS</h2>
				  <ol class="breadcrumb">
					<li>Home</li>
					  <li Class="active">
						  <a>SMS Notifications</a>
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
							<li class="active">
							  <a data-toggle="tab" href="#tab-10"><span class="fa fa-paper-plane"></span> Send SMS</a>
							</li>
							@can('smsnotifications.history')
								<li class="sms-history">
								<a data-toggle="tab" href="#tab-11"><span class="fa fa fa-file"></span> History</a>
								</li>
							@endcan
						</ul>
						<div class="tab-content">
							<div id="tab-10" class="tab-pane fade in active">
								<div class="panel-body">
									@can('smsnotifications.sendsms')
										<h3> @{{availableSms}} SMS available validity till @{{smsValidity}} <small v-if="ValidateExpireDate == false" ><span class="label label-danger">Expired</span></small> </h3>
										<div class="hr-line-dashed"></div>
										<h2> Send Single SMS Notification </h2>
										<div class="hr-line-dashed"></div>
										<form id="single" method="POST" v-on:submit.prevent="formSubmit($event)" action="{{ URL('smsnotifications/send') }}" class="form-horizontal">
											{{ csrf_field() }}

											<div class="form-group{{ ($errors->has('exam'))? ' has-error' : '' }}">
												<label class="col-md-2 control-label"> Select One </label>
												<div class="col-md-6">
													<div class="i-checks"><label> <input type="radio" value="student" name="send_to" v-model="send_to" required=""> <i></i> Student </label></div>
													<div class="i-checks"><label> <input type="radio" value="guardian" name="send_to" v-model="send_to"> <i></i> Parent/Guardian </label></div>
													<div class="i-checks"><label> <input type="radio" value="teacher" name="send_to" v-model="send_to"> <i></i> Teacher </label></div>
													<div class="i-checks"><label> <input type="radio" value="employee" name="send_to" v-model="send_to"> <i></i> Employee </label></div>
												</div>
											</div>



											<table class="table table-hover">
												<tbody>
													<tr v-if="send_to == 'student'">
														<td>Student</td>
														<td width="40%">
															<select class="form-control select2student" v-model="selected_student_k">
																<option v-for="(student, k) in Students" :value="k+1">@{{  student.gr_no+' | '+student.name+' | '+student.phone }}</option>
															</select>
														</td>
														<td><a @click="addPhoneInfo(send_to, Students[selected_student_k-1])" class="btn btn-info"><span class="fa fa-plus"></span></a></td>
													</tr>
													<tr v-if="send_to == 'guardian'">
														<td>Guardian</td>
														<td width="40%">
															<select class="form-control select2guardian" v-model="selected_guardian_k">
																<option v-for="(student, k) in Students" v-if="check_no(student.guardian.phone)" :value="k+1">@{{  student.gr_no+' | '+student.name+' | '+student.guardian.phone }}</option>
															</select>
														</td>
														<td><a @click="addPhoneInfo(send_to, Students[selected_guardian_k-1].guardian)" class="btn btn-info"><span class="fa fa-plus"></span></a></td>
													</tr>
													<tr v-if="send_to == 'teacher'">
														<td>Teacher</td>
														<td width="40%">
														<select class="form-control select2teacher" v-model="selected_teacher_k">
															<option v-for="(teacher, k) in Teachers" v-if="check_no(teacher.phone)" :value="k+1">@{{ teacher.name+' | '+teacher.phone }}</option>
														</select>
														</td>
														<td><a @click="addPhoneInfo(send_to, Teachers[selected_teacher_k-1])" class="btn btn-info"><span class="fa fa-plus"></span></a></td>
													</tr>
													<tr v-if="send_to == 'employee'">
														<td>Employee</td>
														<td width="40%">
														<select class="form-control select2employee" v-model="selected_employee_k">
															<option v-for="(employe, k) in Employee" v-if="check_no(employe.phone)" :value="k+1">@{{ employe.name+' | '+employe.phone }}</option>
														</select>
														</td>
														<td><a @click="addPhoneInfo(send_to, Employee[selected_employee_k-1])" class="btn btn-info"><span class="fa fa-plus"></span></a></td>
													</tr>

													<tr v-for="(phone, k) in phoneinfo">
														<td colspan="2">
															Send to: @{{phone.send_to}}, Name: @{{phone.name}}, No: @{{phone.no}}
															<input type="hidden" :name="'phoneinfo['+k+'][send_to]'" :value="phone.send_to">
															<input type="hidden" :name="'phoneinfo['+k+'][id]'" :value="phone.id">
															<input type="hidden" :name="'phoneinfo['+k+'][no]'" :value="phone.no">
															<input type="hidden" :name="'phoneinfo['+k+'][name]'" :value="phone.name">
														</td>
														<td><a @click="removePhoneInfo(k)" class="btn btn-info"><span class="fa fa-remove"></span></a></td>
													</tr>

												</tbody>
											</table>

											<div class="form-group">
												<label class="col-md-2 control-label"> Message </label>
												<div class="col-md-6">
													<textarea class="form-control" name="message" rows="5" maxlength="600" v-model="message" required></textarea>
													<span class="text-info">@{{ 600-message.length }} &nbsp; &nbsp; &nbsp; &nbsp; COUNT: @{{ count_msg }}</span>
												</div>
											</div>

											<transition name="fade">
												<div v-if="error" class="alert alert-danger alert-dismissible" role="alert">
													<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													<strong>Sorry!</strong> @{{ alert_message }}.
												</div>
											</transition>

											<div class="form-group">
												<div class="col-md-offset-2 col-md-6">
													<button v-if="loading" class="btn btn-primary btn-block" disabled="true" type="submit"><span class="fa fa-pulse fa-spin fa-spinner"></span> Loading... </button>
													<button v-else class="btn btn-primary btn-block" type="submit"><span class="fa fa-paper-plane"></span> Send </button>
												</div>
											</div>
										</form>
									@endcan	
									@can('smsnotifications.sendbulksms')
										<h2> Send Bulk SMS Notification </h2>
										<div class="hr-line-dashed"></div>
										<form id="bulk" method="POST" v-on:submit.prevent="formSubmit($event)" action="{{ URL('smsnotifications/send-bulk') }}" class="form-horizontal">
											{{ csrf_field() }}

											<div class="form-group{{ ($errors->has('exam'))? ' has-error' : '' }}">
												<label class="col-md-2 control-label"> Select One </label>
												<div class="col-md-6">
													<div class="i-checks"><label> <input type="radio" value="students" name="bulk_to" v-model="bulk_to" required=""> <i></i> Students </label></div>
													<div class="i-checks"><label> <input type="radio" value="guardians" name="bulk_to" v-model="bulk_to"> <i></i> Parents/Guardians </label></div>
													<div class="i-checks"><label> <input type="radio" value="teachers" name="bulk_to" v-model="bulk_to"> <i></i> Teachers </label></div>
													<div class="i-checks"><label> <input type="radio" value="employs" name="bulk_to" v-model="bulk_to"> <i></i> Employee </label></div>
												</div>
											</div>

											<template v-if="bulk_to == 'students'">
											
											<div class="form-group">
												<label class="col-md-2 control-label"> Class </label>
												<div class="col-md-6">
												<select class="form-control" name="class" v-model="clas">
													<option :value="0">All</option>
													<option v-for="(clas, k) in Classe" :value="clas.id">@{{  clas.name }}</option>
												</select>
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-2 control-label"> Students </label>
												<div class="col-md-6">
													<template v-for="std in Students" v-if="check_no(std.phone) && (clas == 0 || clas == std.class_id)">
														<input type="hidden" :name="'students['+std.id+'][id]'" :value="std.id">
														<input type="hidden" :name="'students['+std.id+'][no]'" :value="std.phone">
														<input type="hidden" :name="'students['+std.id+'][name]'" :value="std.gr_no+' | '+std.name">
													</template>
													<select class="form-control" multiple="true" disabled="true">
														<option v-for="(student, k) in Students" v-if="check_no(student.phone) && (clas == 0 || clas == student.class_id)" :value="student.id">@{{  student.gr_no+' | '+student.name+' | '+student.phone }}</option>
													</select>
													<span class="text-info">@{{ selected_students_ks.length }} selected out of @{{ Students.length }} @{{ bulk_to }}</span>
												</div>
											</div>

											</template>

											<template v-if="bulk_to == 'guardians'">

											<div class="form-group">
												<label class="col-md-2 control-label"> Class </label>
												<div class="col-md-6">
												<select class="form-control" name="class" v-model="clas">
													<option :value="0">All</option>
													<option v-for="(clas, k) in Classe" :value="clas.id">@{{  clas.name }}</option>
												</select>
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-2 control-label"> Guardians </label>
												<div class="col-md-6">
													<template v-for="student in Students" v-if="check_no(student.guardian.phone) && (clas == 0 || clas == student.class_id)">
														<input type="hidden" :name="'guardians['+student.guardian.id+'][id]'" :value="student.guardian.id">
														<input type="hidden" :name="'guardians['+student.guardian.id+'][no]'" :value="student.guardian.phone">
														<input type="hidden" :name="'guardians['+student.guardian.id+'][name]'" :value="student.guardian.name">
													</template>
												<select class="form-control" multiple="true" disabled="true">
													<option v-for="(student, k) in Students" v-if="check_no(student.guardian.phone) && (clas == 0 || clas == student.class_id)" :value="student.guardian.id">@{{  student.gr_no+' | '+student.name+' | '+student.guardian.phone }}</option>
												</select>
												<span class="text-info">@{{ selected_guardians_ks.length }} selected out of @{{ Students.length }} @{{ bulk_to }}</span>
												</div>
											</div>
											</template>

											<div class="form-group" v-if="bulk_to == 'teachers'">
												<label class="col-md-2 control-label"> Teacher </label>
												<div class="col-md-6">
													<template v-for="teacher in Teachers" v-if="check_no(teacher.phone)">
														<input type="hidden" :name="'teachers['+teacher.id+'][id]'" :value="teacher.id">
														<input type="hidden" :name="'teachers['+teacher.id+'][no]'" :value="teacher.phone">
														<input type="hidden" :name="'teachers['+teacher.id+'][name]'" :value="teacher.name">
													</template>
												<select class="form-control" multiple="true" disabled="true">
													<option v-for="(teacher, k) in Teachers" v-if="check_no(teacher.phone)" :value="teacher.id" >@{{ teacher.name+' | '+teacher.phone }}</option>
												</select>
												<span class="text-info">@{{ selected_teachers_ks.length }} selected out of @{{ Teachers.length }} @{{ bulk_to }}</span>
												</div>
											</div>

											<div class="form-group" v-if="bulk_to == 'employs'">
												<label class="col-md-2 control-label"> Employee </label>
												<div class="col-md-6">
													<template v-for="employe in Employee" v-if="check_no(employe.phone)">
														<input type="hidden" :name="'employs['+employe.id+'][id]'" :value="employe.id">
														<input type="hidden" :name="'employs['+employe.id+'][no]'" :value="employe.phone">
														<input type="hidden" :name="'employs['+employe.id+'][name]'" :value="employe.name">
													</template>
												<select class="form-control" multiple="true" disabled="true">
													<option v-for="(employe, k) in Employee" v-if="check_no(employe.phone)" :value="employe.id" >@{{ employe.name+' | '+employe.phone }}</option>
												</select>
												<span class="text-info">@{{ selected_employs_ks.length }} selected out of @{{ Employee.length }} @{{ bulk_to }}</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-2 control-label"> Message </label>
												<div class="col-md-6">
													<textarea class="form-control" name="message" rows="5" maxlength="600" v-model="message" required></textarea>
													<span class="text-info">@{{ 600-message.length }} &nbsp; &nbsp; &nbsp; &nbsp; COUNT: @{{ count_msg }}</span>
												</div>
											</div>

											<transition name="fade">
												<div v-if="error" class="alert alert-danger alert-dismissible" role="alert">
													<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													<strong>Sorry!</strong> Invalid Number.
												</div>
											</transition>

											<div class="form-group">
												<div class="col-md-offset-2 col-md-6">
													<button v-if="loading" class="btn btn-primary btn-block" disabled="true" type="submit"><span class="fa fa-pulse fa-spin fa-spinner"></span> Loading... </button>
													<button v-else class="btn btn-primary btn-block" type="submit"><span class="fa fa-paper-plane"></span> Send </button>
												</div>
											</div>
										</form>
									@endcan
								</div>
							</div>

							@can('smsnotifications.history')
								<div id="tab-11" class="tab-pane fade sms-history">
									<div class="panel-body">
										<h2> SMS History </h2>
										<div class="hr-line-dashed"></div>
											<form id="sms_history_form" method="POST" action="{{ URL('smsnotifications/history') }}" class="form-horizontal" target="_blank">
												{{ csrf_field() }}

												<div class="form-group">
													<label class="col-md-2 control-label">From</label>
													<div class="col-md-6">
														<div class="input-daterange input-group" style="width: 100%" id="datepicker">
															<input type="text" class="input-sm form-control" name="start" required="true" readonly="" placeholder="From Date" />
															<span class="input-group-addon">to</span>
															<input type="text" class="input-sm form-control" name="end" required="true" readonly="" placeholder="To Date" />
														</div>
													</div>
												</div>

												<div class="form-group">
													<div class="col-md-offset-2 col-md-6">
														<button class="btn btn-primary btn-block" type="submit"><span class="fa fa-file"></span> Show </button>
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


		  


		</div>

	@endsection

	@section('script')


	<script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

	<!-- require with bootstrap-datetimepicker -->
	<script src="{{ asset('src/js/plugins/moment/moment.min.js') }}"></script>
	<script src="{{ asset('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

	<!-- iCheck -->
	<script src="{{ asset('src/js/plugins/iCheck/icheck.min.js') }}"></script>

	<!-- Data picker -->
	<script src="{{ asset('src/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>

	<script type="text/javascript">

	  $(document).ready(function(){

		$("#sms_history_form").validate({
			rules: {
				start: {
					required: true,
				},
				end: {
					required: true,
				},
			}
		});

		$('#datepicker').datepicker({

			format: 'yyyy-mm-dd',
			keyboardNavigation: false,
			forceParse: false,
			autoclose: true,

			minViewMode: 0,
			todayHighlight: true
		});
	  });
	</script>

	@endsection

	@section('vue')
	<!-- Select2 -->
	<script src="{{ asset('src/js/plugins/select2/select2.full.min.js') }}"></script>
	<script type="text/javascript">
	  var app = new Vue({
		el: '#app',
		data: { 

			phoneinfo: [],

			availableSms: {{$availableSms}},
			smsValidity: '{{$smsValidity}}',
			ValidateExpireDate:	{{$ValidateExpireDate}},

			send_to: '',
			bulk_to: '',

			loading: '',
			message: '',

			alert_message: '',

			clas: 0,
			
			error: false,

			selected_student_k: 0,
//			selected_students_ks: [],
			selected_guardian_k: 0,
//			selected_guardians_ks: [],
			selected_teacher_k: 0,
//			selected_teachers_ks: [],
			selected_employee_k: 0,
//			selected_employs_ks: [],
			Classe: {!! json_encode($Classe, JSON_NUMERIC_CHECK) !!},
			Students: {!! json_encode($Students, JSON_NUMERIC_CHECK) !!},
			Teachers: {!! json_encode($Teachers, JSON_NUMERIC_CHECK) !!},
			Employee: {!! json_encode($Employee, JSON_NUMERIC_CHECK) !!},
		},
		watch:	{
			send_to: function(n, o){
				this.selected_student_k = 0;
				this.selected_guardian_k = 0;
				this.selected_teacher_k = 0;
				this.selected_employee_k = 0;
						vm = this;
				setTimeout(function(){
						$('.select2'+n).select2().on('change', function(){
							vm['selected_'+n+'_k'] = $(this).val();
						});
					}, 100);
				if(o != ''){
					$('.select2'+o).select2('destroy');
				}
			},
			bulk_to: function(n, o){
				this.clas = 0;
			}
		},

		methods: {
			check_no: function(no){
				if (no == null) {
					return false;
				}
				if(isNaN(no) || no.toString().length != 10 || no.toString().indexOf('21') == 0){
					return false;
				}
				return no;
			},
			hasUnicode: function() {
				for (var i = 0; i < this.message.length; i++) {
					if (this.message.charCodeAt(i) > 127) return true;
				}
				return false;
			},
			removePhoneInfo: function(k){
				this.phoneinfo.splice(k, 1);
			},
			addPhoneInfo: function(send_to, dta){
				this.phoneinfo.push({
					send_to: send_to,
					id: dta.id,
					name: (send_to == 'student')? dta.gr_no+' | '+dta.name : dta.name,
					no: dta.phone,
				});
			},
			formSubmit: function(e){
					if (this.phoneinfo.length == 0 && e.target.id == 'single') {
						alert('Add A No');
						return false;
					}
				// hasUnicode
					if(this.hasUnicode()){
						this.error	=	true;
						this.alert_message = 'This Message Can Only Be Submitted Via Unicode';
						setTimeout(function(){
							vm.error =	false;
						}, 3000);
						return false;
					}
					this.loading = true;
					vm = this;
					$.ajax({
					type: e.target.method,
					url:  e.target.action,
					data: $(e.target).serialize(),
					success: function(msg){
//						console.log(msg);
						toastrmsg = msg.toastrmsg;

						if(msg.errors){
							vm.error	=	msg.errors;
							vm.alert_message = toastrmsg.msg;
							setTimeout(function(){
								vm.error =	false;
							}, 3000);
						} else {
							vm.message = '';
							vm.availableSms = msg.availableSms;
						}

						toastr.options = {
							closeButton: true,
							progressBar: true,
							showMethod: 'slideDown',
							timeOut: 8000
						};
						toastr[toastrmsg.type](toastrmsg.msg, toastrmsg.title);

						app.loading = false;
					},
					error: function(){
							alert("failure");
							app.loading = false;
						}
					});
				}
		},

		computed: {
			student_number: function(){
				if(this.selected_student_k > 0){
					return this.Students[this.selected_student_k-1].phone;
				}
				return 0;
			},
			employee_number: function(){
				if(this.selected_employee_k > 0){
					return this.Employee[this.selected_employee_k-1].phone;
				}
				return 0;
			},
			teacher_number: function(){
				if(this.selected_teacher_k > 0){
					return this.Teachers[this.selected_teacher_k-1].phone;
				}
				return 0;
			},
			guardian_number: function(){
				if(this.selected_guardian_k > 0){
					return this.Students[this.selected_guardian_k-1].guardian.phone;
				}
				return 0;
			},
			selected_number: function(){
				return this[this.send_to+'_number'];
			},

			student_id: function(){
				if(this.selected_student_k > 0){
					return this.Students[this.selected_student_k-1].id;
				}
				return 0;
			},
			employee_id: function(){
				if(this.selected_employee_k > 0){
					return this.Employee[this.selected_employee_k-1].id;
				}
				return 0;
			},
			teacher_id: function(){
				if(this.selected_teacher_k > 0){
					return this.Teachers[this.selected_teacher_k-1].id;
				}
				return 0;
			},
			guardian_id: function(){
				if(this.selected_guardian_k > 0){
					return this.Students[this.selected_guardian_k-1].guardian.id;
				}
				return 0;
			},

			selected_students_ks: function(){
				dta = [];
				vm = this;
				this.Students.forEach(function(student){
					if(vm.check_no(student.phone) && (vm.clas == 0 || vm.clas == student.class_id)){
						dta.push(student.id);
					}
				});
				return dta;
			},
			selected_guardians_ks: function(){
				dta = [];
				vm = this;
				this.Students.forEach(function(student){
					if(vm.check_no(student.guardian.phone) && (vm.clas == 0 || vm.clas == student.class_id)){
						dta.push(student.guardian.id);
					}
				});
				return dta;
			},
			selected_teachers_ks: function(){
				dta = [];
				vm = this;
				this.Teachers.forEach(function(teacher){
					if(vm.check_no(teacher.phone)){
						dta.push(teacher.id);
					}
				});
				return dta;
			},
			selected_employs_ks: function(){
				dta = [];
				vm = this;
				this.Employee.forEach(function(employe){
					if(vm.check_no(employe.phone)){
						dta.push(employe.id);
					}
				});
				return dta;
			},

			count_msg: function(){
				if(this.message.length){
					let counter =	this.message.length/150;
					if(counter > parseInt(counter)){
						return parseInt(counter+1);
					}
					return counter;
				}
				return 0;
			}

		}

	  });
	</script>
	@endsection

