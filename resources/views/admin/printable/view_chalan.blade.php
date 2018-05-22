@extends('admin.layouts.printable')
@section('title', 'Student Chalan | ')

@section('head')

	<style type="text/css">
		.invoice-title h2, .invoice-title h3 {
			display: inline-block;
		}

		.table {
			width: auto;
			margin-top: 15px;
		}

		.table > tbody > tr > .no-line {
			border-top: none;
		}

		.table > thead > tr > .no-line {
			border-bottom: none;
		}

		.table > tbody > tr > .thick-line {
			border-top: 1px solid;
		}


    body {
      padding: 0px 10px;
      margin: 0px;
      font-size: 15px;
      }
    .table-bordered th,
    .table-bordered td {
      border: 1px solid black !important;
      padding: 0px;
    }   

  .table > tbody > tr > td {
      padding: 1px;
    }
    a[href]:after {
      content: none;
/*      content: " (" attr(href) ")";*/
    }



	</style>

@endsection

@section('content')
<div class="container-fluid" style="padding-left: 5px; ">

	<div class="row" style="border: 1px solid black; padding: 2px; width: 507px; position: absolute; height: 1000px">
		<div id="address" style="width: 500px;">
			<h2 class="text-center text-success">M.W.Academy</h2>
			<table style="width: 100%">
				<tbody>
					<tr>
						<td class="text-center">Bunglo No 1/18-A (Big Plot)</td>
					</tr>
					<tr>
						<td class="text-center">Shah Faisal Colony, Karachi. Tel 021-34596866</td>
					</tr>
					<tr style="border-top:1px solid black">
						<td>Bank: JS Bank</td>
					</tr>
					<tr>
						<td>Plot# CB-34, Shah Fsisal Colony, Karachi</td>
					</tr>
					<tr>
						<td>Account No. 538909</td>
					</tr>
				</tbody>
			</table>
		</div>
		<h4 class="text-center text-danger" style="width: 500px; border:1px solid black"> Student Copy </h4>
		<div id="stdcopy">
			<table style="margin-top: 15px">
				<tbody>
					<tr><td width="300px">R.No. <u>{{ config('systemInfo.next_chalan_no') }}</u></td><td width="200px">Issue Date. <u>{{ Carbon\Carbon::now()->Format('d-m-Y') }}</u></td></tr>
					<tr><td></td><td>Due Date.</td></tr>
					<tr><td>Name. <u>{{ $student->name }}</u></td><td>Father's. <u>{{ $student->father_name }}</u></td></tr>
					<tr><td>Class. <u>{{ $student->std_class->name }}</u></td><td>G.R No. <u>{{ $student->gr_no }}</u></td></tr>
					<tr><td colspan="2">Fee for the month. <u>
						@foreach($months as $month)
						{{ Carbon\Carbon::createFromFormat('Y-m-d', $month)->Format('M-Y').', ' }}
						@endforeach
					</u></td></tr>
				</tbody>
			</table>
			<div style="height: 350px">

				<table class="table table-bordered">
					<tbody>
						<tr style="background: blue; color: white;"><th width="300px">Particular</th><th width="200px">Amount</th></tr>

						<tr>
							<td>Tuition Fee</td>
							<td>@{{ total_tuition_fee }}</td>
						</tr>
						<tr v-for="additionalfe in additionalfee">
							<td>@{{ additionalfe.fee_name }}</td>
							<td>@{{ additionalfe.sumamount }}</td>
						</tr>

						@if($student->discount > 0)
						<tr>
							<th>Discount</th>
							<th>@{{ total_discount }}</th>
						</tr>
						@endif

						<tr><th class="text-right">Total</th><th>@{{ net_amount }}/=</th></tr>
					</tbody>
				</table>
				<p style="width: 500px;margin-top: 15px;">Amount In Words: <u>@{{ inwords() }}</u></p>
			</div>

			<p style="margin-top: 20px; margin-bottom: 5px; border-bottom: 1px solid">Accountant Signature</p>

			<ol style="margin-bottom: 0px">
				<li>All Types of Fees are non refundable.</li>
				<li>Late fee of 150 will be charged after 15th of every month irrespective of holidays.</li>
				<li>Receipt will only be valid when it bears the bank stamp and signature of the designated bank officer.</li>
				<li>After 25th of each moth the fee challan will no longer be valid for payment.</li>
				<li>If the voucher is lost by the parent or student, Rs 70/- will be charged for duplicate receipt.</li>
				<li>Only cash is acceptable.</li>
			</ol>
		</div>
	</div>

	<div class="row" style="border: 1px solid black; padding: 2px; width: 507px; height: 1000px; margin-left: 504px; position: absolute;" id="schoolcopy">
		<div v-html="address"></div>
		<h4 class="text-center text-danger" style="width: 500px; border:1px solid black"> School Copy </h4>
		<div v-html="schoolcopy"></div>
	</div>

	<div class="row" style="border: 1px solid black; padding: 2px; width: 507px; height: 1000px; margin-left: 1020px; position: absolute;" id=bankcopy>
		<div v-html="address"></div>
		<h4 class="text-center text-danger" style="width: 500px; border:1px solid black"> Bank Copy </h4>
		<div v-html="schoolcopy"></div>
	</div>


</div>

@endsection


@section('script')

@endsection

@section('vue')

	<script type="text/javascript">
	  var app = new Vue({
		el: '#app',
		data: {
			months: {!! json_encode($months) !!},
			fee: {
				additionalfee: {!! json_encode($student->AdditionalFee) !!},
				tuition_fee: {{ $student->tuition_fee or 0 }},
				discount:  {{ $student->discount or 0 }},
			},
			chalan_no: '',
			payment_type: '',
			total_additional_fee: 0,
			schoolcopy,
			address,
		},

		mounted: function(){
			this.schoolcopy = $("#stdcopy").html();
			this.address = $("#address").html();
			window.print();
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

			NoOfMonths: function(){
				return this.months.length;
			},
			total_tuition_fee: function(){
				return Number(this.fee.tuition_fee) * this.NoOfMonths;
			},
			total_discount: function(){
				return Number(this.fee.discount) * this.NoOfMonths;
			},
			total_amount: function(){
				tot_amount = Number(this.total_tuition_fee);
				for(k in this.additionalfee) { 

					tot_amount += Number(this.additionalfee[k].sumamount);

				}
				return  tot_amount;
			},

			net_amount: function(){
				return Number(this.total_amount) - Number(this.total_discount);
			},
		},
		methods: {
			inwords: function (){
				return toWords(this.net_amount);
			}
		}
	  });
	</script>

@endsection