@extends('admin.layouts.printable')
@section('title', 'Student Challan | ')

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
			<table style="width: 420px">
				<tbody>
					<tr>
							<td rowspan="3" style="padding: 5px">
								<img alt="image" width="80px" src="{{ URL('/img/mwlogo.png') }}">
							</td>
							<td>
								<h2 class="text-center text-success">{{ config('systemInfo.name') }}</h2>
							</td>
					</tr>
					<tr>
						<td class="text-center">Bungalow No 1/18-A (Big Plot)</td>
					</tr>
					<tr>
						<td class="text-center">Shah Faisal Colony, Karachi. Tel 021-34596866</td>
					</tr>
				</tbody>
			</table>
			<table style="width: 500px">
				<tbody>
					<tr style="border-top:1px solid black">
						<td>Bank: Bank Islami.</td>
						<td rowspan="3" style="padding-top: 10px">
							<img alt="image" src="{{ URL('/img/bankIslamilogo.png') }}" style="width: 43px;">
						</td>
					</tr>
					<tr>
						<td>Shah Fsisal Colony No 3 Branch.</td>
					</tr>
					<tr>
						<td>Account No. 1155-0000850-0001.</td>
					</tr>
				</tbody>
			</table>
		</div>
		<h4 class="text-center text-danger" style="width: 500px; border:1px solid black"> Student's Copy </h4>
		<div id="stdcopy">
			<table style="margin-top: 15px">
				<tbody>
					<tr><td width="250px">R.No. <u>@{{ Invoice.id }}</u></td><td width="250px">Issue Date. <u>@{{ Invoice.created_at }}</u></td></tr>
					<tr><td><span v-if="Invoice.paid_amount" class="label label-success hidden-print"> PAID </span><span v-else class="label label-danger hidden-print"> UNPAID </span></td><td>Due Date. <u>@{{ Invoice.due_date }}</u></td></tr>
					<tr><td>Name. <u>{{ $student->name }}</u></td><td>Father's Name. <u>{{ $student->father_name }}</u></td></tr>
					<tr><td>Class. <u>{{ $student->std_class->name }}</u></td><td>G.R No. <u>{{ $student->gr_no }}</u></td></tr>
					<tr><td colspan="2">Fee for the month. <u>
						<span v-for="month in Invoice.invoice_months">
						@{{ month.month }},
						</span>
					</u></td></tr>
				</tbody>
			</table>
			<div style="height: 350px">

				<table class="table table-bordered">
					<tbody>
						<tr style="background: blue; color: white;">
							<th width="300px">
								<span>Particulars</span>
							</th>
							<th width="200px">Amount</th>
						</tr>

						<tr v-for="detail in Invoice.invoice_detail">
							<td>@{{ detail.fee_name }}</td>
							<td>@{{ detail.amount }}</td>
						</tr>

						<tr v-if="Invoice.discount > 0">
							<th>Discount</th>
							<th>@{{ Invoice.discount }}</th>
						</tr>

						<tr><th class="text-right">Payable within due date</th><th>@{{ Invoice.net_amount }}/-</th></tr>
						<tr><td class="text-right">Payable after due date</td><td>@{{ Invoice.net_amount + Invoice.late_fee }}/-</td></tr>
					</tbody>
				</table>
				<p style="width: 500px;margin-top: 15px;">Amount In Words: <u>@{{ inwords() }}</u></p>
			</div>

			<p style="margin-top: 20px; margin-bottom: 5px; border-bottom: 1px solid">Accountant Signature</p>

			<ol style="margin-bottom: 0px">
				<li>All Types of Fees are non refundable.</li>
				<li>Late fee of 150 will be charged after 15th of every month irrespective of holidays.</li>
				<li>Receipt will only be valid when it bears the bank stamp and signature of the designated bank officer.</li>
				<li>Fee challan will not be valid for payment after 25th of each month.</li>
				<li>If the voucher is lost by the parent or student, Rs 70/- will be charged for duplicate receipt.</li>
				<li>Only cash will be acceptable.</li>
			</ol>
		</div>
	</div>

	<div class="row" style="border: 1px solid black; padding: 2px; width: 507px; height: 1000px; margin-left: 504px; position: absolute;" id="schoolcopy">
		<div v-html="address"></div>
		<h4 class="text-center text-danger" style="width: 500px; border:1px solid black"> School's Copy </h4>
		<div v-html="schoolcopy"></div>
	</div>

	<div class="row" style="border: 1px solid black; padding: 2px; width: 507px; height: 1000px; margin-left: 1020px; position: absolute;" id=bankcopy>
		<div v-html="address"></div>
		<h4 class="text-center text-danger" style="width: 500px; border:1px solid black"> Bank's Copy </h4>
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
			Invoice: {!! json_encode($invoice, JSON_NUMERIC_CHECK) !!},
			Student: {!! json_encode($student, JSON_NUMERIC_CHECK) !!},
			schoolcopy,
			address,
		},

		mounted: function(){
			this.schoolcopy = $("#stdcopy").html();
			this.address = $("#address").html();
			window.print();
		},

		methods: {
			inwords: function (){
				var inWords = toWords(this.Invoice.net_amount);
				return inWords.charAt(0).toUpperCase() + inWords.slice(1);
			}
		}
	  });
	</script>

@endsection