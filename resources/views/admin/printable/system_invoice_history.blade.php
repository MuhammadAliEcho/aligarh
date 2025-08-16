@extends('admin.layouts.printable')
@section('title', 'System Invoice History | ')

@section('head')
	<style type="text/css">
	
		.invoice-title h2, .invoice-title h3 {
			display: inline-block;
		}

		.table > tbody > tr > .no-line {
			border-top: none;
			background: none;
		}

		.table > thead > tr > .no-line {
			border-bottom: none;
		}

		.table > tbody > tr > .thick-line {
			border-top: 1px solid;
		}

    body {
		padding: 0px 0px;
		margin: 0px;
		font-size: 13px;
/*		background-image: url('{{URL('img/hashmangementletterhead-Alain.jpg')}}');
			background-size: cover;
			height: 100%;
			overflow: hidden;*/
      }
    .table-bordered th,
    .table-bordered td {
    	border: 1px solid black !important;
    	padding: 0px;
		background: none;
    }   

  .table > tbody > tr > td {
      padding: 5px;
		background: none;
    }
    a[href]:after {
      content: none;
/*      content: " (" attr(href) ")";*/
    }
/*	@media print {
		.invoice-table {
			margin-top: 50px;
			background: transparent;
		}
	}*/

	.invoice-table {
		margin-top: 0px;
		background: none;
	}
	.table tr td, .table tr th{
		background-color: rgba(255,255,255, 0.3) !important;
	}


     @page {
        margin: 0px;
     }


	</style>

@endsection

@section('content')
<div class="" style="position: relative;">

	<img src="{{URL('img/hashmangementletterhead-Alain.jpg')}}" style="width:21.0cm; height:29.2cm; margin: none; padding: none">
	
	<div class="row" style="position: absolute; top: 150px; left: 2.5cm; width: 16cm">
		<h3 class="center"> Payment history of {{ config('systemInfo.general.title') }} </h3>
		<hr>
		<h4 class="text-right"><u>Date: {{ Carbon\Carbon::now()->format('d-M-Y') }}</u></h4>
		<table class="table table-bordered invoice-table">
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
				@foreach($system_invoices AS $invoice)
				<tr>
					<td>{{$invoice->id}}</td>
					<td>{{$invoice->billing_month}}</td>
					<td>{{$invoice->amount}}</td>
					<td>{{$invoice->status}}</td>
					<td>{{$invoice->date_of_payment}}</td>
					<td>{{$invoice->created_at}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>

</div>

@endsection


@section('script')

@endsection

@section('vue')

@endsection