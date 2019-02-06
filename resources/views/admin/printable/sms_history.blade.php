@extends('admin.layouts.printable')
@section('title', 'SMS History | ')

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



	</style>

@endsection

@section('content')
<div class="container-fluid">

	<div class="row">
	<h3 class="text-center">{{ config('systemInfo.title') }}</h3>
	<h4>SMS Reports</h4>
	<h4>Between: ( {{ Carbon\Carbon::createFromFormat('Y-m-d', Request::input('start'))->Format('d-M-Y') }} TO {{ Carbon\Carbon::createFromFormat('Y-m-d', Request::input('end'))->Format('d-M-Y') }} )</h4>
		<table id="rpt-att" class="table table-bordered">
			<thead>
			  <tr>
				<th>ID</th>
				<th>No Count</th>
				<th>Message</th>
				<th>Cost</th>
				<th>Status</th>
				<th>User</th>
				<th>TimeStamp</th>
				<th class="hidden-print">Action</th>
			  </tr>
			</thead>
			<tbody>
				<template v-for="(log, k) in history">
				<tr>
					<td>@{{ k+1 }}</td>
					<td>@{{ log.phone_info.length }}</td>
					<td>@{{ log.message }}</td>
					<td>@{{ log.total_price }}</td>
					<td>@{{ log.total_price? 'Success' : 'Faild' }}</td>
					<td>@{{ log.user.name }}</td>
					<td>@{{ log.created_at }}</td>
					<td class="hidden-print"><button @click="log.show = !log.show"><span class="fa fa-bars"></span></button></td>
				</tr>
				<tr v-if="log.show">
					<td colspan="6">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Name</th>
									<th>Send To</th>
									<th>Number</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="phone_info in log.phone_info">
									<td>@{{ phone_info.name }}</td>
									<td>@{{ phone_info.send_to }}</td>
									<td>
										@{{ phone_info.no }}
										<span v-if="(checkDuplicate(k, phone_info.no)).length > 1">| Duplicate </span>
										<span v-if="findInResponse(k, phone_info.no) != false">| Success </span>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				</template>
				<tr>
					<th>Total</th>
					<th>@{{ total_no_count }}</th>
					<th></th>
					<th>@{{ total_cost }}</th>
					<th colspan="2">@{{ success_count }} Success & @{{ faild_count }} Faild </th>
				</tr>
			</tbody>
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
			history: [],
		},
		created: function(){
			this.addShowAttribute();
		},

		mounted: function(){
			window.print();
		},
		computed: {
			total_cost: function(){
				return this.history.reduce((a, b) => a + Number(b.total_price), 0);
			},
			total_no_count: function(){
				return this.history.reduce((a, b) => a + Number(b.phone_info.length), 0);
			},
			success_count: function(){
				return (_.filter(this.history, function(log) { return log.total_price != 0 })).length;
			},
			faild_count: function(){
				return (_.filter(this.history, function(log) { return log.total_price == 0 })).length;
			}
		},

		methods: {
			addShowAttribute: function(){
				const history = [];
				_.forEach({!! json_encode($history, JSON_NUMERIC_CHECK) !!}, function(value, key) {
					history[key] = value;
					history[key]['show'] = false;
				});
				this.history = history;
			},
			findInResponse: function(k, no){
				if (this.history[k].api_response.totalprice) {
					return _.find(this.history[k].api_response.messages, function(message) { return message.gsm == no });
				}
				return false;
			},
			checkDuplicate: function(k, no){
				return _.filter(this.history[k].phone_info, function(info){
					return info.no == no;
				});
				return [];
			}
		},
	});
</script>

@endsection