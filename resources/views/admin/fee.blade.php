@extends('admin.layouts.master')

  @section('title', 'Fees |')

  @section('head')
	<link href="{{ asset('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
	<link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
	<link href="{{ asset('src/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
<!-- 	<link href="{{ asset('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"> -->
  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

		<div id="page-wrapper" class="gray-bg">

		  @include('admin.includes.top_navbar')

		  <!-- Heading -->
		  <div class="row wrapper border-bottom white-bg page-heading">
			  <div class="col-lg-8 col-md-6">
				  <h2>Fees</h2>
				  <ol class="breadcrumb">
					<li>Home</li>
					  <li Class="active">
						  <a>Fee</a>
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
							<li class="">
								<a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Invoice</a>
							</li>
							@can('fee.create.store')
								<li class="make-fee">
									<a data-toggle="tab" href="#tab-11"><span class="fa fa-edit"></span> Create Invoice</a>
								</li>
							@endcan
							@can('fee.collect.store')
								<li class="collect-invocie">
									<a data-toggle="tab" href="#tab-12"><span class="fa fa-sticky-note-o"></span> Invoice Collect</a>
								</li>
							@endcan
							@can('fee.update')
							<li class="">
								<a data-toggle="tab" href="#tab-13"><span class="fa fa-edit"></span> Update Fee</a>
							</li>
							@endcan
						</ul>
						<div class="tab-content">
							<div id="tab-10" class="tab-pane fade">
								<div class="panel-body">
								  <div class="table-responsive">
									<table class="table table-striped table-bordered table-hover dataTables-teacher" width="100%">
									  <thead>
										<tr>
										  <th>ID</th>
										  <th>GR-No</th>
										  <th>Total Amount</th>
										  <th>Discount</th>
										  <th>Paid Amount</th>
										  <th>Due Date</th>
										  <th>Issue Date</th>
										  <th>Options</th>
										</tr>
									  </thead>
									</table>
								  </div>

								</div>
							</div>
							@can('fee.create.store')
								<div id="tab-11" class="tab-pane fade make-fee">
									<div id="createfeeApp" class="panel-body">
									<h2> Create Invoice </h2>
									<div class="hr-line-dashed"></div>

										<form id="crt_invoice_frm" method="GET" action="{{ URL('fee/create') }}" class="form-horizontal jumbotron" role="form" >

										<div class="form-group{{ ($errors->has('gr_no'))? ' has-error' : '' }}">
											<label class="col-md-2 control-label"> GR-No </label>
											<div class="col-md-6">
											<select class="form-control select2_grno" name="gr_no" required="true"></select>
											@if ($errors->has('gr_no'))
												<span class="help-block">
													<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('gr_no') }} </strong>
												</span>
											@endif
											</div>
										</div>

										<div class="form-group">
											<div class="col-md-offset-2 col-md-6">
												<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-search"></span> Search </button>
											</div>
										</div>

										</form>

										@if($root == 'create')
										<div class="row">
										<h3>Student Name: <span class="bg-info"> {{ $student->name }} | {{ $student->gr_no }} </span></h3>

										<form action="{{ URL('fee/create/'.$student->id) }}" method="POST" class="form-horizontal">
											{{ csrf_field() }}
											<input type="hidden" name="student_id" value="{{ $student->id }}" required="true">
											<input type="hidden" name="arrears" :value="arrears" required="true">
											<input type="hidden" name="total_amount" :value="total_amount" required="true">
											<input type="hidden" name="net_amount" :value="net_amount" required="true">

											<div class="container-fluid form-group">
											<select class="select2 form-control" multiple="multiple" name="months[]" required="true" style="width: 100%">
												@foreach($months as $month)
												<option value="{{ $month['value'] }}">{{ $month['title'] }}</option>
												@endforeach
											</select>
											@if ($errors->has('months'))
												<span class="help-block">
													<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('months') }} </strong>
												</span>
											@endif
											</div>

											<div class="form-group">
												<label class="col-md-2 control-label">Issue Date:</label>
												<div class="col-md-6">
													<input type="text" name="issue_date" placeholder="Issue Date" required="true" value="{{ Carbon\Carbon::now()->toDateString() }}" class="form-control datepicker" readonly="true" />
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-2 control-label">Due Date:</label>
												<div class="col-md-6">
													<input type="text" name="due_date" placeholder="Due Date" required="true" value="{{ Carbon\Carbon::now()->addDays(14)->toDateString() }}" class="form-control datepicker" readonly="true" />
											@if ($errors->has('due_date'))
												<span class="help-block">
													<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('due_date') }} </strong>
												</span>
											@endif
												</div>
											</div>

											<table class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
												<th>Fees Name</th>
												<th>Amount</th>
												</tr>
											</thead>
											<tbody>
												<tr>
												<td>Tuition Fee @{{ '('+fee.tuition_fee+'*'+NoOfMonths+')' }}</td>
												<td><input type="number" class="form-control" v-model="total_tuition_fee" name="total_tuition_fee"></td>
												</tr>
												<tr v-for="additionalfe in additionalfee" v-if="additionalfe.active">
												<td>@{{ additionalfe.fee_name +' ('+ additionalfe.amount +'*'+ ((additionalfe.onetime)? 1 : NoOfMonths) +')' }}</td>
												<td>@{{ additionalfe.sumamount }}</td>
												</tr>

												<tr>
													<th>Total Amount</th>
													<th>@{{ total_amount }}</th>
												</tr>

												<tr>
													<td>Total Arears</td>
													<td>@{{ arrears }}</td>
												</tr>

												<tr>
												{{-- <th>Discount @{{ '('+ fee.discount+'*'+NoOfMonths+')' }}</th> --}}
												<th>Discount</th>
												<th><input type="number" class="form-control" name="discount" v-model="total_discount" required="true"></th>
												</tr>
											</tbody>

											<tfoot>
												<tr class="success">
												<th>Net Total</th>
												<th>@{{ net_amount }}</th>
												</tr>
												<tr>
												<td>Late Fee (Payable after due date)<input type="number" class="form-control" name="late_fee" v-model="fee.late_fee" required="true"></td>
												<td>@{{ (Number(fee.late_fee)+net_amount) }}</td>
												</tr>
											</tfoot>
											</table>

											<div class="form-group" v-if="NoOfMonths">
												<div class="col-md-offset-4 col-md-4">
													<button class="btn btn-primary btn-block" type="submit"><span class="glyphicon glyphicon-save"></span> Create Invoice </button>
												</div>
											</div>

											</form>

										</div>
										@endif

									</div>
								</div>
							@endcan
							@can('fee.collect.store')
								<div id="tab-12" class="tab-pane fade make-fee">
									<div id="collectfeeApp" class="panel-body">
										<h2> Invoice Collect </h2>
										<div class="hr-line-dashed"></div>

										<form id="invoice_collect_form" method="GET" class="form-horizontal" v-on:submit.prevent="invoiceCollectForm($event)" action="{{ URL('fee/collect') }}">

											<div class="form-group">
												<label class="col-md-2 control-label"> Invoice No </label>
												<div class="col-md-6">
													<input type="number" class="form-control" v-model="invoice_no" name="invoice_no" required="true"/>
													@if ($errors->has('invoice_no'))
														<span class="help-block">
															<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('invoice_no') }} </strong>
														</span>
													@endif
												</div>
											</div>

											<div class="form-group">
												<div class="col-md-offset-2 col-md-6">
												<button v-if="loading" class="btn btn-primary btn-block" disabled="true" type="submit"><span class="fa fa-pulse fa-spin fa-spinner"></span> Loading... </button>
												<button v-else class="btn btn-primary btn-block" type="submit"><span class="glyphicon glyphicon-search"></span> Search </button>
												</div>
											</div>

										</form>
		

										<div v-show="Invoice.id" class="row">
											<div class="hr-line-dashed"></div>
										<h3>Student Name: <span class="bg-info"> @{{ Student.name }} | @{{ Invoice.gr_no }} </span></h3>

										<form action="{{ URL('fee/collect') }}" id="invoice_post_collect_form" method="POST" class="form-horizontal" v-on:submit.prevent="invoiceCollectForm($event)">
											{{ csrf_field() }}
											<input type="hidden" name="invoice_no" :value="Invoice.id" required="true">

											<div class="container-fluid form-group">
												<label class="col-md-2 control-label">Months:</label>
												<div class="col-md-6">
													<select class="form-control" multiple="multiple" readonly>
														<option selected v-for="month in Invoice.invoice_months" :value="month">@{{ month.month }}</option>
													</select>
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-2 control-label">Issue Date:</label>
												<div class="col-md-6">
													<input type="text" placeholder="Issue Date"  :value="Invoice.created_at" class="form-control" readonly="true" />
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-2 control-label">Due Date:</label>
												<div class="col-md-6">
													<input type="text" placeholder="Due Date" :value="Invoice.due_date" class="form-control" readonly="true" />
												</div>
											</div>

											<table class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
												<th>Fees Name</th>
												<th>Amount</th>
												</tr>
											</thead>
											<tbody>

												<tr v-for="additionalfee in Invoice.invoice_detail">
												<td>@{{ additionalfee.fee_name }}</td>
												<td>@{{ additionalfee.amount }}</td>
												</tr>

												<tr>
												<th>Discount</th>
												<th>@{{ Invoice.discount }}</th>
												</tr>
											</tbody>
											<tfoot>
												<tr class="success">
												<th>Total</th>
												<th>@{{ Invoice.net_amount }}</th>
												</tr>
												<tr>
												<td>Late Fee (Payable after due date)</td>
												<td>@{{ (Invoice.late_fee + Invoice.net_amount) }}</td>
												</tr>
											</tfoot>
											</table>

											<div class="form-group">
												<label class="col-md-2 control-label">Date of payment:</label>
												<div class="col-md-6">
													<input type="text" name="date_of_payment" placeholder="date of payment" required="true" :value="date_of_payment" class="form-control datepicker" onChange="feeCollectApp.date_of_payment = this.value" readonly  />
												</div>
											</div>

											<div v-if="Invoice.due_date >= date_of_payment" class="form-group">
												<label class="col-md-2 control-label">Paid Amount:</label>
												<div class="col-md-6">
													<input type="number" name="paid_amount" placeholder="Paid Amount" required="true" :value="Invoice.net_amount" :max="Invoice.net_amount" class="form-control" />
												</div>
											</div>

											<div v-else class="form-group">
												<label class="col-md-2 control-label">Paid Amount:</label>
												<div class="col-md-6">
													<input type="number" name="paid_amount" placeholder="Paid Amount" required="true" :value="Invoice.net_amount + Invoice.late_fee" :min="Invoice.net_amount + Invoice.late_fee" :max="Invoice.net_amount + Invoice.late_fee" class="form-control" />
												</div>
											</div>

											<div class="form-group{{ ($errors->has('payment_type'))? ' has-error' : '' }}">
												<label class="col-md-2 control-label"> Payment Mode </label>
												<div class="col-md-6">
													<div class="i-checks"><label> <input type="radio" checked value="Cash" name="payment_type" required> <i></i>Cash</label></div>
													<div class="i-checks"><label> <input type="radio" value="Chalan" name="payment_type"  required> <i></i>Chalan</label></div>
												</div>
											</div>

											<div class="form-group">
												<div class="col-md-offset-4 col-md-4">
													<button v-if="loading" class="btn btn-primary btn-block" disabled="true" type="submit"><span class="fa fa-pulse fa-spin fa-spinner"></span> Loading... </button>
													<button v-else class="btn btn-primary btn-block" type="submit"><span class="glyphicon glyphicon-save"></span> Collect Invoice </button>
												</div>
											</div>

											</form>

										</div>

									</div>
								</div>
							@endcan
							@can('fee.update')
								<div id="tab-13" class="tab-pane fade">
									<div id="updatefeeApp" class="panel-body">
									<h2> Update Fee </h2>
									<div class="hr-line-dashed"></div>
										<form id="GetStdFee" method="GET" v-on:submit.prevent="formSubmit($event)" action="{{ URL('fee/update') }}" class="form-horizontal jumbotron" role="form" >

										<div class="form-group{{ ($errors->has('gr_no'))? ' has-error' : '' }}">
											<label class="col-md-2 control-label"> GR-No </label>
											<div class="col-md-6">
											<select class="form-control select2_grno" name="gr_no" required="true"></select>
											@if ($errors->has('gr_no'))
												<span class="help-block">
													<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('gr_no') }} </strong>
												</span>
											@endif
											</div>
										</div>

										<div class="form-group">
											<div class="col-md-offset-2 col-md-6">
												<button v-if="loading" class="btn btn-primary btn-block" disabled="true" type="submit"><span class="fa fa-pulse fa-spin fa-spinner"></span> Loading... </button>
												<button v-else class="btn btn-primary btn-block" type="submit"><span class="glyphicon glyphicon-search"></span> Search </button>
											</div>
										</div>

										</form>

										<form id="UpdateFee" v-if="fee.feedata" class="form-horizontal" v-on:submit.prevent="formSubmit($event)" method="POST" action="{{ URL('fee/update') }}" >
											{{ csrf_field() }}
											<input type="hidden" name="id" v-model="std.id">
											<h2>@{{ std.name }}. (GR No. @{{ std.gr_no }})</h2>
											<div class="hr-line-dashed"></div>
											<div class="col-lg-8">
												<div class="panel panel-info">
													<div class="panel-heading">
														Additional Feeses <a id="addfee" data-toggle="tooltip" title="Add Fee" @click="addAdditionalFee()" style="color: #ffffff"><span class="fa fa-plus"></span></a>
													</div>
													<div class="panel-body">
														<table id="additionalfeetbl" class="table table-bordered table-hover table-striped">
															<thead>
																<tr>
																	<th width="40%">Name</th>
																	<th width="40%">Amount</th>
																	<th width="20%">Action</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td>Tuition Fee</td>
																	<td>
																		<input type="number" name="tuition_fee" v-model.number="fee.tuition_fee" placeholder="Tuition Fee" class="form-control"/>
																	</td>
																	<td></td>
																</tr>

																<tr v-for="(fee, k) in fee.additionalfee">
																	<td>
																		<input type="hidden" :name="'fee['+k+'][id]'" v-model="fee.id" >
																		<input type="text" :name="'fee['+ k +'][fee_name]'" class="form-control" required="true" v-model="fee.fee_name">
																	</td>
																	<td>
																		<input type="number" :name="'fee['+ k +'][amount]'" class="form-control additfeeamount" required="true" min="0" v-model.number="fee.amount">
																	</td>
																	<td>
																		<div class="input-group">
																			<span class="input-group-addon" data-toggle="tooltip" title="select if onetime charge">
																				<input type="checkbox" :name="'fee['+ k +'][onetime]'" value="1" v-model="fee.onetime">
																			</span>
																			<span class="input-group-addon" data-toggle="tooltip" title="Active">
																				<input type="checkbox" :name="'fee['+ k +'][active]'" value="1" v-model="fee.active">
																			</span>
																			<a href="javascript:void(0);" class="btn btn-default text-danger removefee" data-toggle="tooltip" @click="removeAdditionalFee(k)" title="Remove">
																				<span class="fa fa-trash"></span>
																			</a>
																		</div>
																	</td>
																</tr>

															</tbody>
															<tfoot>
																<tr>
																	<th>Total</th>
																	<th>@{{ total_amount }}</th>
																	<th></th>
																</tr>
																<tr>
																	<td>Discount</td>
																	<td><input type="number" name="discount" class="form-control" placeholder="Discount" min="0" v-model.number="fee.discount"></td>
																	<td></td>
																</tr>
																<tr>
																	<th>Net Amount</th>
																	<th>@{{ net_amount }}</th>
																	<th></th>
																</tr>
																<tr>
																	<td>Late Fee</td>
																	<td>
																		<input title="leave it '0' if not apply" type="number" name="late_fee" v-model.number="fee.late_fee" placeholder="Late Fee" class="form-control"/>
																	</td>
																	<td title="leave it '0' if not apply">Apply After Due Date.</td>
																</tr>
															</tfoot>
														</table>
													</div>
												</div>
											</div>
											<input type="hidden" name="net_amount" v-model="net_amount">
											<input type="hidden" name="total_amount" v-model="total_amount">

											<div class="form-group">
												<div class="col-md-offset-2 col-md-6">
													<button v-if="loading" class="btn btn-primary btn-block" disabled="true" type="submit"><span class="fa fa-pulse fa-spin fa-spinner"></span> Loading... </button>
													<button v-else class="btn btn-primary btn-block" type="submit"><span class="glyphicon glyphicon-save"></span> Update Fee </button>
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
	<script src="{{ asset('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>

	<script src="{{ asset('src/js/plugins/dataTables/datatables.min.js') }}"></script>

	<script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

	<!-- Input Mask-->
	 <script src="{{ asset('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

	<!-- Data picker -->
	<script src="{{ asset('src/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>

	<!-- require with bootstrap-datetimepicker -->
<!-- 	<script src="{{ asset('src/js/plugins/moment/moment.min.js') }}"></script>
	<script src="{{ asset('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script> -->

	<script type="text/javascript">
	var tbl;

	  function select2template(data) {
		if (!data.id) { return data.text; }
		var $data = $(
		  // '<span class="text-danger">'+data.text+'</span>'
		  data.htm1+data.text+data.htm2
		);
		return $data;
	  };


	  $(document).ready(function(){

	  @if((COUNT($errors) >= 1 && !$errors->has('toastrmsg')) || $root == 'create')
		$('a[href="#tab-11"]').tab('show');
		@if(isset($Input))
		  $('[name="gr_no"').val('{{ $Input['gr_no'] }}');
		@endif
	  @else
		$('a[href="#tab-10"]').tab('show');
	  @endif

		opthtm = '';
		@can('fee.chalan.print')
		opthtm += '<a data-toggle="tooltip" target="_new" title="View" class="btn btn-default btn-circle btn-xs edit-option"><span class="fa fa-file-pdf-o"></span></a>';
		@endcan
		@can('fee.invoice.print')
		opthtm += '<a data-toggle="tooltip" target="_new" title="Print Invoice" class="btn btn-default btn-circle btn-xs print-invoice"><span class="fa fa-print"></span></a>';
		@endcan
		@can('fee.edit.invoice.post')
		opthtm += '<a data-toggle="tooltip" title="Edit" class="btn btn-default btn-circle btn-xs edit-invoice"><span class="fa fa-edit"></span></a>';
		@endcan
		@can('fee.group.chalan.print')
		opthtm += '<a data-toggle="tooltip" target="_blank" title="Print Group Invoice" class="btn btn-default btn-circle btn-xs group-invoice"><span class="fa fa-print"></span></a>';
		@endcan
		tbl = $('.dataTables-teacher').DataTable({
		  dom: '<"html5buttons"B>lTfgitp',
		  buttons: [
			{ extend: 'copy'},
			{extend: 'csv'},
			{extend: 'excel', title: 'ExampleFile'},
			{extend: 'pdf', title: 'ExampleFile'},

			{extend: 'print',
			  customize: function (win){
				$(win.document.body).addClass('white-bg');
				$(win.document.body).css('font-size', '10px');

				$(win.document.body).find('table')
				.addClass('compact')
				.css('font-size', 'inherit');
			  }
			}
		  ],
		  Processing: true,
		  serverSide: true,
		  order: [[0, "desc"]],
		  ajax: '{{ URL('fee') }}',
		  columns: [
			{data: 'id', name: 'invoice_master.id'},
			{data: 'gr_no', name: 'invoice_master.gr_no'},
			{data: 'total_amount', name: 'invoice_master.total_amount'},
			{data: 'discount', name: 'invoice_master.discount'},
			{data: 'paid_amount', name: 'invoice_master.paid_amount'},
			{data: 'due_date', name: 'invoice_master.due_date'},
			{data: 'created_at', name: 'invoice_master.created_at'},
			{"defaultContent": opthtm, className: 'hidden-print'},
		  ],
		});

	  $('.dataTables-teacher tbody').on( 'mouseenter', '.edit-option', function () {
		$(this).attr('href','{{ URL('fee/chalan/') }}/'+tbl.row( $(this).parents('tr') ).data().id);
		$(this).tooltip('show');
	  });
	  $('.dataTables-teacher tbody').on( 'mouseenter', '.print-invoice', function () {
		$(this).attr('href','{{ URL('fee/invoice/') }}/'+tbl.row( $(this).parents('tr') ).data().id);
		$(this).tooltip('show');
	  });
	  $('.dataTables-teacher tbody').on( 'mouseenter', '.edit-invoice', function () {
		$(this).attr('href','{{ URL('fee/edit-invoice/?id=') }}'+tbl.row( $(this).parents('tr') ).data().id);
		$(this).tooltip('show');
	  });
	  $('.dataTables-teacher tbody').on( 'mouseenter', '.group-invoice', function () {
		$(this).attr('href','{{ URL('fee/group-chalan/') }}/'+tbl.row( $(this).parents('tr') ).data().guardian_id);
		$(this).tooltip('show');
	  });

		$("#tchr_rgstr").validate({
			rules: {
			  gr_no: {
				required: true,
			  },
			  year: {
				required: true,
			  },
			  month: {
				required: true,
			  },
			},
		});

		$('.datepicker').datepicker({
		  format: 'yyyy-mm-dd',
		  keyboardNavigation: false,
		  forceParse: false,
		  autoclose: true,
		});

		$('.select2_grno').attr('style', 'width:100%').select2({
			placeholder: 'Search contacts',
			minimumInputLength: 3,
			Html: true,
			ajax: {
				url: '{{ URL('fee/findstu') }}',
/*                dataType: 'json',
				data: function (term, page) {
					return {
						contact_names_value: term
					};
				},
				results: function (data, page) {
					return {results: data.data};
				}*/
			  processResults: function (data) {
				return {
				  results: data
				};
			  }
			},
			tags: true,
			// templateResult: select2template,
		});

	  @if(Session::get('invoice_created') !== null)
		window.open('{{ URL('fee/chalan/'.Session::get('invoice_created')) }}', '_new');
	  @endif

	  });
	</script>

	@endsection

	@section('vue')

	<!-- Select2 -->
	<script src="{{ asset('src/js/plugins/select2/select2.full.min.js') }}"></script>

	@if($root == 'create')
	<script type="text/javascript">
	  var app = new Vue({
		el: '#createfeeApp',
		data: {
			months: {},
			NoOfMonths:0,
			fee: {
				additionalfee: {!! json_encode($student->AdditionalFee, JSON_NUMERIC_CHECK) !!},
				tuition_fee: {{ $student->tuition_fee ?? 0 }},
				late_fee: {{ $student->late_fee ?? 0 }},
				discount:  {{ $student->discount ?? 0 }},
			},
			arrears: {{ $arrears ?? 0 }},
			chalan_no: '',
			payment_type: '',
			total_tuition_fee: 0,
			total_discount: 0,
			total_additional_fee: 0,
		},

		watch:{
			months: function(months){
				if(this.months){
					this.NoOfMonths	=	this.months.length;
				} else {
					this.NoOfMonths = 0;
				}
				this.total_tuition_fee	= Number(this.fee.tuition_fee) * this.NoOfMonths;
				this.total_discount = (Number(this.fee?.discount) || 0) * (Number(this.NoOfMonths) || 0);
			}
		},

		mounted: function(){
			var vm = this;
			$(".select2").select2({
				placeholder: "select months"
			}).on('change', function(){
				vm.months = $(this).val();
			});
		},

		computed: {
			additionalfee: function(){
				additionalfee = [];
				for(k in this.fee.additionalfee) {
					if(this.fee.additionalfee[k].active){
						additionalfee.push({
							"fee_name": this.fee.additionalfee[k].fee_name,
							"sumamount": (Number(this.fee.additionalfee[k].amount) * ((this.fee.additionalfee[k].onetime)? 1 : this.NoOfMonths)),
							"amount": Number(this.fee.additionalfee[k].amount),
							"active": Number(this.fee.additionalfee[k].active),
							"onetime": Number(this.fee.additionalfee[k].onetime)
						});
					}
				}
				return additionalfee;
			},

			total_amount: function(){
				tot_amount = Number(this.total_tuition_fee);
				for(k in this.additionalfee) { 
					tot_amount += Number(this.additionalfee[k].sumamount);
				}
				return  tot_amount;
			},

			net_amount: function(){
				return Number(Number(this.total_amount) - Number(this.total_discount)) + Number(this.arrears);
			},
		}
	  });
	</script>
	@endif
	@can('fee.update')
	<script type="text/javascript">
		var feeApp = new Vue({
			el: '#updatefeeApp',
			data: {	
				loading: false,
				fee: {
					additionalfee: {},
					tuition_fee: 0,
					late_fee: 0,
					discount:  0,
					feedata: false,
				},
				std: {
					id: 0,
					name: '',
					gr_no: '',
				}
			},
			methods: {
				formSubmit: function(e){
					this.loading = true;
					$.ajax({
					type: e.target.method,
					url:  e.target.action,
					data: $(e.target).serialize(),
					success: function(msg){

						if (e.target.id == 'GetStdFee') {
//							console.log(msg);
							feeApp.std.id = msg.id;
							feeApp.std.name = msg.name;
							feeApp.std.gr_no = msg.gr_no;
							feeApp.fee.additionalfee = msg.additional_fee;
							feeApp.fee.tuition_fee = msg.tuition_fee;
							feeApp.fee.late_fee = msg.late_fee;
							feeApp.fee.discount = msg.discount;
							feeApp.fee.feedata = true;
						} else {
//							console.log(msg);
							feeApp.AjaxMsg(msg);
							feeApp.fee.feedata = false;
						}

						feeApp.loading = false;
					},
					error: function(){
							alert("failure");
							feeApp.loading = false;
							feeApp.fee.feedata = false;
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
				addAdditionalFee: function (){
					this.fee.additionalfee.push({
						id: 0,
						fee_name: '',
						amount: 0,
						active: 1,
						onetime: 1
					});
				},
				removeAdditionalFee: function(k){
					this.fee.additionalfee.splice(k, 1);
				}
			},

			computed: {
				total_amount: function(){
					tot_amount = Number(this.fee.tuition_fee);
					for(k in this.fee.additionalfee) { 
						if(this.fee.additionalfee[k].active){
							tot_amount += Number(this.fee.additionalfee[k].amount);
						}
					}
					return  tot_amount;
				},
				net_amount: function(){
					return Number(this.total_amount) - Number(this.fee.discount);
				}
			}

		});
	</script>
	@endcan
	@can('fee.collect.store')
		<script type="text/javascript">
		var feeCollectApp = new Vue({
			el: '#collectfeeApp',
			data: {	
				loading: false,
				invoice_no: null,
				date_of_payment: '{{ Carbon\Carbon::now()->toDateString() }}',
				Invoice: [],
				Student: [],
			},
			methods: {
				invoiceCollectForm: function(e){
					this.loading = true;
					$.ajax({
					type: e.target.method,
					url:  e.target.action,
					data: $(e.target).serialize(),
					success: function(msg){
						if (e.target.id == 'invoice_collect_form' && e.target.method == "get") {
							feeCollectApp.Invoice = msg.invoice;
							feeCollectApp.Invoice['invoice_detail'] = msg.invoice_detail;
							feeCollectApp.Invoice['invoice_months'] = msg.invoice_months;
							feeCollectApp.Student = msg.student;
						} else {
							feeCollectApp.AjaxMsg(msg);
							feeCollectApp.Invoice = [];
							feeCollectApp.Student = [];
						}
						feeCollectApp.loading = false;
					},
					error: function(error){
							console.log(error);
							if(error.status == 422){
								feeCollectApp.AjaxMsg(error.responseJSON);
							} else {
								alert('Request failure');
							}
							feeCollectApp.loading = false;
							feeCollectApp.Invoice = [];
							feeCollectApp.Student = [];
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
			}
		});
		</script>
		@endcan
	@endsection
