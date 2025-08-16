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
	<h3 class="text-center">{{ config('systemInfo.general.title') }}</h3>
	<h4>Daily Fee Collection Report</h4>
	<h4>Between: ( {{ Carbon\Carbon::createFromFormat('Y-m-d', $betweendates['start'])->Format('M-Y') }} TO {{ Carbon\Carbon::createFromFormat('Y-m-d', $betweendates['end'])->Format('M-Y') }} )</h4>
		
		@foreach($invoice_dates AS $invoice)
		<hr>
		<h4>{{ Carbon\Carbon::createFromFormat('Y-m-d', $invoice->date)->Format('D, M d, Y') }}</h4>
		<table id="rpt-att" class="table table-bordered">
			<thead>
			  <tr>
			  	<th>Fee Description / Mode of Payment</th>
			  	<th>Cash</th>
			  	<th>Bank</th>
			  	<th>Total</th>
			  </tr>
			</thead>
			<tbody>
				@foreach($daily_fee_collection[$invoice->date] AS $collection)
				<tr>
					<td>{{ $collection->fee_name }}</td>
					<td>{{ $collection->cash }}</td>
					<td>{{ $collection->chalan }}</td>
					<td>{{ $collection->amount }}</td>
				</tr>
				@endforeach
				<tr>
					<td>Discount</td>
					<td colspan="3">{{ $invoice->discount }}</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<th class="text-right">Total</th>
					<th>{{ $daily_fee_collection[$invoice->date]->sum('cash') }}/=</th>
					<th>{{ $daily_fee_collection[$invoice->date]->sum('chalan') }}/=</th>
					<th>{{ ($daily_fee_collection[$invoice->date]->sum('amount') - $invoice->discount) }}/=</th>
				</tr>
			</tfoot>
		</table>
		@endforeach
		<table class="table table-bordered">
			<thead>
				<tr>
					<th colspan="2" class="text-center">Total</th>
				</tr><tr>
					<th>Cash Amount</th><th>{{ $total_cash_amount }}/=</th>
				</tr><tr>
					<th>Chalan Amount</th><th>{{ $total_chalan_amount }}/=</th>
				</tr><tr>
					<th>Discount Amount</th><th>{{ $total_discount_amount }}/=</th>
				</tr><tr>
					<th>Net Amount</th><th>{{ $net_total_amount }}/=</th>
				</tr>
			</thead>
		</table>
	</div>

</div>



@endsection


@section('script')
<script type="text/javascript">

	window.print();

</script>

@endsection

@section('vue')


@endsection