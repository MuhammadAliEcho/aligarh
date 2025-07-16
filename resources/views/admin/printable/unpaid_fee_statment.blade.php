@extends('admin.layouts.printable')
@section('title', 'Bill Remain Statment | ')

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
		<h4>Bill Remain Statment</h4>
		<h4>AS ON: {{ \Carbon\Carbon::parse($betweendates['start'])->format('M-Y') }} - {{ \Carbon\Carbon::parse($betweendates['end'])->format('M-Y') }}</h4>
		{{-- <h4>AS ON: {{ Carbon\Carbon::createFromFormat('Y-m-d', $betweendates['start'])->Format('M-Y') }}-{{ Carbon\Carbon::createFromFormat('Y-m-d', $betweendates['end'])->Format('M-Y') }}</h3> --}}

			<template v-for="(students, classname) in unpaid_fee_statment">
				<h4>@{{ classname }}</h4>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Gr No.</th>
							<th>Student Name</th>
							<th>Father Name</th>
							<th>Month</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="student in _.orderBy(students, 'name')">
							<td>@{{ student.gr_no }}</td>
							<td>@{{ student.name }}</td>
							<td>@{{ student.father_name }}</td>
							<td>@{{ student.month }}</td>
							<td>@{{ student.amount }}</td>
						</tr>
						<tr>
							<th colspan="4" class="text-right">Total</th>
							<th>@{{ TotalAmount(students) }}</th>
						</tr>
					</tbody>
				</table>
			</template>

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
			unpaid_fee_statment: {!! json_encode($unpaid_fee_statment, JSON_NUMERIC_CHECK) !!},
		},

		mounted: function(){
			window.print();
		},
		computed: {


		},

		methods: {
			TotalAmount: function(students){
				return students.reduce((a, b) => a + Number(b.amount), 0);
			},

		},
	});
</script>

@endsection
