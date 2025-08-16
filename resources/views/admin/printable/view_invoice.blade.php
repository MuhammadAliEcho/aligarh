@extends('admin.layouts.printable')
@section('title', 'Invoice #'.$invoice->id.' |')

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
	</style>

@endsection

@section('content')
<div class="container" id="invoiceVueApp">

    <div class="row">
        <div class="col-xs-12">
    		<div class="invoice-title">
    			<h3>Invoice #{{ $invoice->id }}</h3>
				<!-- <h3 class="pull-right">Order # 12345</h3> -->
    		</div>
    		<hr>
    		<div class="row">
    			<div class="col-xs-6">
    				<address>
    				<strong>Name &nbsp;&nbsp;&nbsp;: {{ $invoice->Student->name }}</strong><br>
    						GR-No &nbsp; : {{ $invoice->Student->gr_no }}<br>
    				</address>
    			</div>
    			<div class="col-xs-6 text-right">
    				<address>
            			<strong>Date : {{ $invoice->created_at }}</strong><br>
						<template v-if="invoice.paid_amount">
							<strong >Date Of Payment: @{{ invoice.date_of_payment }}</strong><br>
							<p class="pull-right bg-info">Paid</p>
						</template>
						<p v-else class="pull-right bg-danger">Unpaid</p>
    				</address>
    			</div>
    		</div>
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
    			<div class="panel-heading">
    				<h3 class="panel-title"><strong>Payment Month : <span v-for="month in months">@{{ month.month }}, </span></strong></h3>
    			</div>
    			<div class="panel-body">
    				<div class="table-responsive">
    					<table class="table table-condensed">
    						<thead>
                                <tr>
        							<th style="width: 40px">Sno</th>
        							<th class="text-center" style="width: 330px;">Particulars</th>
        							<th></th>
        							<th class="text-right" style="width: 100px">Amount</th>
                                </tr>
    						</thead>
    						<tbody>
    						@foreach($invoice->InvoiceDetail AS $k=>$detail)
								<tr>
									<td>{{ $k+1 }}</td>
									<td>{{ $detail->fee_name }}</td>
									<td></td>
									<td class="text-right">{{ $detail->amount }}</td>
								</tr>
							@endforeach
    							<tr>
    								<td colspan="3" class="thick-line"><strong class="pull-right" style="width: 100px">Total :</strong></td>
    								<td class="thick-line text-right">{{ $invoice->total_amount }}</td>
    							</tr>
    							@if($invoice->discount >= 1)
    							<tr>
    								<td class="no-line"></td>
									<td class="no-line"></td>
    								<td class="no-line"><strong class="pull-right" style="width: 100px">Discount :</strong></td>
    								<td class="no-line text-right">{{ $invoice->discount }}</td>
    							</tr>
    							<tr>
    								<td class="no-line"></td>
									<td class="no-line"></td>
    								<td class="no-line"><strong class="pull-right" style="width: 100px">Net Amount :</strong></td>
    								<td class="no-line text-right">{{ $invoice->net_amount }}</td>
    							</tr>
    							@endif
    							<tr v-if="invoice.paid_amount">
    								<td colspan="3" class="thick-line"><strong class="pull-right" style="width: 100px">Paid Amount:</strong></td>
    								<td class="thick-line text-right">@{{ invoice.paid_amount }}</td>
    							</tr>

	    							<tr>
		    							<td class="thick-line" colspan="4"><b>In Words :</b> <span id="inwords"></span></td>
	    							</tr>
                                    <tr>
                                        <th colspan="3">Payment Type: {{ $invoice->payment_type }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Chalan No: {{ $invoice->chalan_no }}</th>
                                    </tr>
    						</tbody>
    					</table>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>

    

</div>

@endsection


@section('script')
<script type="text/javascript">
	$(document).ready(function(){
		window.print();
		$('#inwords').text(toWords({{ $invoice->net_amount }}));
	});
</script>
@endsection

@section('vue')
	<script type="text/javascript">
	  var app = new Vue({
		el: '#invoiceVueApp',
		data: {
			invoice: {!! json_encode($invoice, JSON_NUMERIC_CHECK) !!},
			months: {!! json_encode($invoice->InvoiceMonths, JSON_NUMERIC_CHECK) !!}
		}
	  })
	</script>
@endsection

