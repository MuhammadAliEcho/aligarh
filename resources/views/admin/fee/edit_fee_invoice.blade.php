@extends('admin.layouts.master')

  @section('title', 'Edit Fees Invoice |')

  @section('head')
	<link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
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
				  <h2>Fees</h2>
				  <ol class="breadcrumb">
					<li>Home</li>
					  <li Class="active">
					  <a>Edit Fee Invoie# {{ $invoice_master->id }}</a>
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

					<div class="ibox">
						<div class="ibox-title">
							<h2>Student Name: <span class="bg-info"> {{ $student->name }} | {{ $student->gr_no }} </span></h2>
							<div class="hr-line-dashed"></div>
						</div>
							@if ($errors->any())
								<div class="container">
								@foreach ($errors->all() as $error)
									<span class="help-block">
										<strong><span class="fa fa-exclamation-triangle"></span> {{ $error }} </strong>
									</span>
								@endforeach
								</div>
							@endif
						<div id="createfeeApp" class="ibox-content">

							<form action="{{ URL('fee/edit-invoice') }}" method="POST" class="form-horizontal">
								{{ csrf_field() }}

								<input type="hidden" name="invoice_id" :value="invoice_master.id" required="true">
								<input type="hidden" name="total_amount" :value="total_amount" required="true">
								<input type="hidden" name="net_amount" :value="net_amount" required="true">

								<div class="container-fluid form-group">
								<select class="select2 form-control" multiple="multiple" name="months[]" required="true" style="width: 100%">
									@foreach ($months as $month)
										<option value="{{ $month['value'] }}" {{ $month['selected']? 'selected' : '' }} > {{ $month['title'] }}</option>
									@endforeach


								</select>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label">Issue Date:</label>
									<div class="col-md-6">
									<input type="text" name="issue_date" placeholder="Issue Date" required="true" value="{{ Carbon\Carbon::createFromFormat('d-m-Y', $invoice_master->created_at)->Format('Y-m-d') }}" class="form-control datepicker" readonly="true" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label">Due Date:</label>
									<div class="col-md-6">
										<input type="text" name="due_date" placeholder="Due Date" required="true" v-model="invoice_master.due_date" class="form-control datepicker" readonly="true" />
									</div>
								</div>

								<table class="table table-striped table-bordered table-hover">
								  <thead>
									<tr>
									  <th>Fees Name <a @click="addAdditionalFee()" class="btn btn-default btn-xs pull-right"><span class="fa fa-plus"></span></a></th>
									  <th>Amount</th>
									  <th>Action</th>
									</tr>
								  </thead>
								  <tbody>

									<tr v-for="(additionalfe, k) in additionalfee">
									<td>
										<input type="text" class="form-control" :name="'additionalfee['+k+'][fee_name]'" v-model="additionalfe.fee_name" required="true">
									</td>
									<td>
										<input type="number" class="form-control" :name="'additionalfee['+k+'][amount]'" v-model="additionalfe.amount" required="true">
									</td>

									<td>
										<a @click="removeAdditionalFee(k)" class="btn btn-default btn-xs"><i class="fa fa-remove"></i></a>
									</td>

									</tr>

									<tr>
										<th>Total Amount</th>
										<th>@{{ total_amount }}</th>
									</tr>

									<tr>
									  <th>Discount</th>
									  <th><input type="number" class="form-control" name="discount" v-model="invoice_master.discount" required="true"></th>
									</tr>

								</tbody>

								  <tfoot>
									<tr class="success">
									  <th>Net Total</th>
									  <th>@{{ net_amount }}</th>
									</tr>
									<tr>
									  <td>Late Fee (Payable after due date)<input type="number" class="form-control" name="late_fee" v-model="invoice_master.late_fee" required="true"></td>
									  <td>@{{ (Number(invoice_master.late_fee)+net_amount) }}</td>
									</tr>
								  </tfoot>
								</table>


								<div class="form-group">
									<div class="col-md-offset-2 col-md-6">
										<label class=""> <input type="checkbox" value="1" name="paid" v-model="paid_show" id="paid_show"> Payment Received </label>
									</div>
								</div>

								<div v-if="paid_show">

									<div class="form-group">
										<label class="col-md-2 control-label">Date of payment:</label>
										<div class="col-md-6">
											@if($invoice_master->date_of_payment > 0)
												<input type="text" name="date_of_payment" placeholder="date of payment" required="true" :value="invoice_master.date_of_payment" class="form-control datepicker" onChange="app.invoice_master.date_of_payment = this.value" readonly  />
											@else
												<input type="text" name="date_of_payment" placeholder="date of payment" required="true" value="{{  Carbon\Carbon::now()->toDateString() }}" class="form-control datepicker" onChange="app.invoice_master.date_of_payment = this.value" readonly  />
											@endif
										</div>
									</div>

									<div v-if="invoice_master.due_date >= invoice_master.date_of_payment" class="form-group">
										<label class="col-md-2 control-label">Paid Amount:</label>
										<div class="col-md-6">
											<input type="number" name="paid_amount" placeholder="Paid Amount" required="true" v-model="invoice_master.paid_amount" :max="net_amount" class="form-control" />
										</div>
									</div>

									<div v-else class="form-group">
										<label class="col-md-2 control-label">Paid Amount:</label>
										<div class="col-md-6">
											<input type="number" name="paid_amount" placeholder="Paid Amount" required="true" v-model="invoice_master.paid_amount" :min="net_amount + Number(invoice_master.late_fee)" :max="net_amount + Number(invoice_master.late_fee)" class="form-control" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label">Payment Mode:</label>
										<div class="col-md-6">
											<div class="i-checks"><label> <input v-model="invoice_master.payment_type" type="radio" checked value="Cash" name="payment_type" required > <i></i>Cash</label></div>
											<div class="i-checks"><label> <input v-model="invoice_master.payment_type" type="radio" value="Chalan" name="payment_type" required> <i></i>Chalan</label></div>
										</div>
									</div>

								</div>

								<div class="form-group" v-if="NoOfMonths">
									<div class="col-md-offset-4 col-md-4">
										<button class="btn btn-primary btn-block" type="submit"><span class="glyphicon glyphicon-save"></span> Update Invoice </button>
									</div>
								</div>

							</form>

						</div>
					</div>

				</div>
			</div>

		  </div>

		  

		</div>

	@endsection

	@section('script')


	<script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

	<!-- Input Mask-->
	 <script src="{{ asset('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

	<!-- Data picker -->
	<script src="{{ asset('src/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>

	<script type="text/javascript">
	var tbl;

	  $(document).ready(function(){

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

		$('#paid_show').on('change', function(){

			setTimeout(function(){

			$('.datepicker').datepicker({
					format: 'yyyy-mm-dd',
					keyboardNavigation: false,
					forceParse: false,
					autoclose: true,
				});
			}, 15);
		});

	  });
	</script>

	@endsection

	@section('vue')

	<!-- Select2 -->
	<script src="{{ asset('src/js/plugins/select2/select2.full.min.js') }}"></script>

	<script type="text/javascript">
	  var app = new Vue({
		el: '#createfeeApp',
		data: {
			invoice_master: {!! json_encode($invoice_master->getRawOriginal(), JSON_NUMERIC_CHECK) !!},
			invoice_months: {!! json_encode($months, JSON_NUMERIC_CHECK) !!},
			additionalfee: {!! json_encode($invoice_detail, JSON_NUMERIC_CHECK) !!},
			months: {},
			NoOfMonths:0,
			chalan_no: '',
			payment_type: 'Cash',
			paid_show: {{ $invoice_master->paid_amount }},
		},

		watch:{
			months: function(months){
				if(this.months){
					this.NoOfMonths	=	this.months.length;
				} else {
					this.NoOfMonths = 0;
				}
			}
		},

		mounted: function(){
			var vm = this;
			$(".select2").select2({
				placeholder: "select months"
			}).on('change', function(){
				vm.months = $(this).val();
			}).trigger('change');
		},

		computed: {

			total_amount: function(){
				tot_amount = 0;
				for(k in this.additionalfee) { 
					tot_amount += Number(this.additionalfee[k].amount);
				}
				return  tot_amount;
			},

			net_amount: function(){
				return Number(Number(this.total_amount) - Number(this.invoice_master.discount));
			},
		},
		methods: {
			addAdditionalFee: function (){
				this.additionalfee.push({
					fee_name: '',
					amount: 0,
				});
			},
			removeAdditionalFee: function(k){
				this.additionalfee.splice(k, 1);
			}
		}

	  });
	</script>

	@endsection
