@extends('admin.layouts.printable')
@section('title', 'Fee Receipts Statment | ')

@section('head')

	<style type="text/css">
		.invoice-title h2, .invoice-title h3 {
			display: inline-block;
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
      font-size: 12px;
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


@media print{
	table > thead {
		background: blue;
		color: white;
	}
}


	</style>

@endsection

@section('content')
<div class="container-fluid">

	<div class="row">
	<h3 class="text-center">{{ config('systemInfo.title') }}</h3>
	<h4>Fee Receipts Statment</h4>
	<h4>Between: ( {{ Carbon\Carbon::createFromFormat('Y-m-d', $betweendates['start'])->Format('M-Y') }} TO {{ Carbon\Carbon::createFromFormat('Y-m-d', $betweendates['end'])->Format('M-Y') }} )</h3>
		<table id="rpt-att" class="table table-bordered">
			<thead>
			  <tr>
			  	<th>Receipt #</th>
			  	<th>Mod</th>
			  	<th>Student Name</th>
			  	<th>Father's Name</th>
			  	<th>Class</th>
			  	<th>Gr No</th>
			  	<th>Month</th>
			  	<th>Amount</th>
			  </tr>
			</thead>
			<tbody>

				<tr v-for="statment in ComputedStatments">
					<td>@{{ statment.id }}</td>
					<td>@{{ statment.payment_type }}</td>
					<td>@{{ statment.student.name }}</td>
					<td>@{{ statment.student.father_name }}</td>
					<td>@{{ statment.student.std_class.name }}</td>
					<td>@{{ statment.student.gr_no }}</td>
					<td>@{{ statment.payment_month }}</td>
					<td class="paid_amount">@{{ statment.paid_amount }}</td>
				</tr>

				<tr>
					<th colspan="8" class="text-center"> Summary </th>
				</tr>
				@foreach($summary as $sum)
				<tr>
					<td colspan="7" class="text-right">{{ Carbon\Carbon::createFromFormat('Y-m-d', $sum->payment_month)->Format('M-Y') }}</td>
					<td>{{ $sum->paid_amount }}/=</td>
				</tr>
				@endforeach
				<tr>
					<th colspan="7" class="text-right">Total</th>
					<th>@{{ Total }}/=</th>
				</tr>

		</table>
	</div>

</div>

@include('admin.includes.footercopyright')

@endsection


@section('script')

@endsection

@section('vue')

<script type="text/javascript">

	var app = new Vue({
		el: '#app',
		data: { 
			total_paid_amount: 0,
			monthlysome: [],
			statments: {!! json_encode($statments, JSON_NUMERIC_CHECK) !!},
		},

		mounted: function(){
			window.print();
		},
		computed: {
			Total: function(){
				return this.ComputedStatments.reduce((a, b) => a + Number(b.paid_amount), 0);
			},
			ComputedStatments: function(){
			    return _.orderBy(this.statments, 'student.name');
			}
		},

		methods: {

		},
	});
</script>

@endsection