@extends('admin.layouts.master')

	@section('title', 'System Settings |')

	@section('head')
	<!-- HEAD -->
	<link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ URL::to('src/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">

	@endsection

	@section('content')

	@include('admin.includes.side_navbar')

				<div id="page-wrapper" class="gray-bg hidden-print">

					@include('admin.includes.top_navbar')

					<!-- Heading -->
					<div class="row wrapper border-bottom white-bg page-heading">
							<div class="col-lg-8 col-md-6">
									<h2>Settings</h2>
									<ol class="breadcrumb">
										<li>Home</li>
											<li Class="active">
													<a>System Settings</a>
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
													@can('system-setting.update')
														<li class="active">
															<a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> General Info</a>
														</li>
													@endcan
													@can('system-setting.print.invoice.history')
														<li>
															<a data-toggle="tab" href="#tab-11"><span class="fa fa-list"></span> Package Info</a>
														</li>
													@endcan
													@can('system-setting.history')
													<li>
														<a data-toggle="tab" href="#tab-12"><span class="fa fa-list"></span> SMS Package Info</a>
													</li>
													@endcan
												</ul>
												<div class="tab-content">
													@can('system-setting.update')
														<div id="tab-10" class="tab-pane fade fade in active add-guardian">
																<div class="panel-body">
																	<h2> General </h2>
																	<div class="hr-line-dashed"></div>

																		<form id="tchr_rgstr" method="POST" action="{{ URL('system-setting/update') }}" class="form-horizontal" >
																			{{ csrf_field() }}

																			<div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">System Name</label>
																				<div class="col-md-6">
																					<input type="text" name="name" placeholder="Name" value="{{ old('name', config('systemInfo.name')) }}" class="form-control"/>
																					@if ($errors->has('name'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('name') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('title'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">System Title</label>
																				<div class="col-md-6">
																					<input type="text" name="title" placeholder="Title" value="{{ old('name', config('systemInfo.title')) }}" class="form-control"/>
																					@if ($errors->has('title'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('title') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('email'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">E-Mail</label>
																				<div class="col-md-6">
																					<input type="text" name="email" placeholder="E-Mail" value="{{ old('email', config('systemInfo.email')) }}" class="form-control"/>
																					@if ($errors->has('email'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('email') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('address'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Address</label>
																				<div class="col-md-6">
																					<input type="text" name="address" placeholder="Address" value="{{ old('address', config('systemInfo.address')) }}" class="form-control"/>
																					@if ($errors->has('address'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('address') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('contact_no'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Contact No</label>
																				<div class="col-md-6">
																					<div class="input-group m-b">
																						<span class="input-group-addon">+92</span>
																						<input type="text" name="contact_no" value="{{ old('contact_no', config('systemInfo.contact_no')) }}" placeholder="Contact No" class="form-control" data-mask="9999999999"/>
																					</div>
																					@if ($errors->has('contact_no'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('contact_no') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('bank_name'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Bank</label>
																				<div class="col-md-6">
																					<input type="text" name="bank_name" placeholder="Name" value="{{ old('bank_name', config('systemInfo.bank_name')) }}" class="form-control"/>
																					@if ($errors->has('bank_name'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('bank_name') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('bank_address'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Bank Address</label>
																				<div class="col-md-6">
																					<input type="text" name="bank_address" placeholder="Address" value="{{ old('bank_address', config('systemInfo.bank_address')) }}" class="form-control"/>
																					@if ($errors->has('bank_address'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('bank_address') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('bank_account_no'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Bank Account No</label>
																				<div class="col-md-6">
																					<input type="text" name="bank_account_no" placeholder="Account no" value="{{ old('bank_account_no', config('systemInfo.bank_account_no')) }}" class="form-control"/>
																					@if ($errors->has('bank_account_no'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('bank_account_no') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('student_capacity'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Student Capacity</label>
																				<div class="col-md-6">
																					<input type="text" name="student_capacity" value="{{ config('systemInfo.student_capacity') }}" readonly="ture" class="form-control"/>
																					@if ($errors->has('student_capacity'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('student_capacity') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group">
																				<label class="col-md-2 control-label">Avaliable SMS</label>
																				<div class="col-md-6">
																					<input type="text" value="{{ config('systemInfo.available_sms').' till '.config('systemInfo.sms_validity') }}" readonly="ture" class="form-control"/>
																				</div>
																			</div>

																			<div class="form-group">
																				<label class="col-md-2 control-label">Next Chalan No</label>
																				<div class="col-md-6">
																					<input type="text" value="{{ config('systemInfo.next_chalan_no') }}" readonly="ture" class="form-control"/>
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
													@endcan
													@can('system-setting.print.invoice.history')
														<div id="tab-11" class="tab-pane fade fade in ">
															<div class="panel-body">
																<h2> Invoices <small> 4000/month billing backage </small> <a class="" title="Download" data-toggle="tooltip" href="{{ URL('system-setting/print-invoice-history') }}" target="_blank"> <span class="fa fa-download"> </span> </a> </h2>
																<div class="hr-line-dashed"></div>
																<table class="table table-bordered table-hover">
																	<thead>
																		<tr>
																			<th>ID</th>
																			<th>Billing Month</th>
																			<th>Amount</th>
																			<th>Status</th>
																			<th>Date Of Payment</th>
																			<th>Created At</th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr v-for="invoice in system_invoices">
																			<td>@{{invoice.id}}</td>
																			<td>@{{invoice.billing_month}}</td>
																			<td>@{{invoice.amount}}</td>
																			<td>@{{invoice.status}}</td>
																			<td>@{{invoice.date_of_payment}}</td>
																			<td>@{{invoice.created_at}}</td>
																		</tr>
																	</tbody>
																</table>
															</div>
														</div>
													@endcan
													@can('system-setting.history')
														<div id="tab-12" class="tab-pane fade in">
															<div class="panel-body">
																<h2> SMS Package <small> <span class="label label-info">PREMIUM</span> </small> </h2>
																<div class="hr-line-dashed"></div>
																<div class="container">
																	<ul class="list-group">
																		<li class="list-group-item">
																			<b>Package Name: </b>PREMIUM
																		</li>
																		<li class="list-group-item">
																			<b>Amount: </b>2700/=
																		</li>
																		<li class="list-group-item">
																			<b>No Of SMS: </b>3030
																		</li>
																		<li class="list-group-item">
																			<b>Package Activation Date: </b>2019-01-19
																		</li>
																		<li class="list-group-item">
																			<b>Validity: </b>{{ config('systemInfo.sms_validity') }} 
																			@if((config('systemInfo.sms_validity') >= Carbon\Carbon::now()->todateString()) == false)
																			<span class="label label-danger">Expired</span>
																			@endif
																		</li>
																		<li class="list-group-item">
																			<b>Remain SMS: </b>{{ config('systemInfo.available_sms') }}
																		</li>
																	</ul>
																</div>
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

		<!-- Mainly scripts -->
		<script src="{{ URL::to('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>

		<script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

		<!-- Input Mask-->
		<script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

		<!-- Data picker -->
		<script src="{{ URL::to('src/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>

		<script type="text/javascript">
		var tbl;


			$(document).ready(function(){

				$("[data-toggle='tooltip']").tooltip();

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

				$("#tchr_rgstr").validate({
						rules: {
							name: {
								required: true,
							},
/*              profession: {
								required: true,
							},
							email: {
								required: true,
								email: true
							},
*/              income:{
								number:true,
							},
						},
						messages:{
							income:{
								number:'Enter valid amount'
						 },
					 }
				});

			});
		</script>

		@endsection

		@section('vue')
		<script type="text/javascript">
			var app = new Vue({
				el: "#app",
				data: {
					system_invoices: {!! json_encode($system_invoices, JSON_NUMERIC_CHECK) !!},
				},
			})
		</script>
		@endsection
