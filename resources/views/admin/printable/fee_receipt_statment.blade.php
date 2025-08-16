@extends('admin.layouts.printable')
@section('title', 'Fee Receipts Statment | ')

@section('head')
	<script src="{{ URL::to('src/moment-develop/min/moment.min.js') }}"></script>

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
	<h4>Fee Receipts Statment</h4>
	<h4>Between: ( {{ Carbon\Carbon::createFromFormat('Y-m-d', $betweendates['start'])->Format('M-Y') }} TO {{ Carbon\Carbon::createFromFormat('Y-m-d', $betweendates['end'])->Format('M-Y') }} )</h3>
		<p>Filters:- 
			OrderBy: <select v-model="orderBy">
						<option value="id">ID</option>
						<option value="student.name">StudentName</option>
						<option value="student.gr_no">StudentGrNo</option>
						<option value="student.std_class.numeric_name">Class</option>
						<option value="paid_amount">PaidAmount</option>
					</select>
			Class: <select v-model="selected_class">
						<option value="all">All</option>
						<option v-for="cls in ClassList" :value="cls.id">@{{ cls.name }}</option>
					</select>
			Payment Status: <select v-model="payment_status">
						<option value="all">All</option>
						<option value="paid">Paid</option>
						<option value="unpaid">UnPaid</option>
					</select>
			<button class="hidden-print" onClick="window.print()">Print</button>
		</p>
		<table id="rpt-att" class="table table-bordered">
			<thead>
			  <tr>
			  	<th>Receipt #</th>
			  	<th>Mod</th>
			  	<th>Student Name</th>
			  	<th>Father's Name</th>
			  	<th>Class</th>
			  	<th>Gr No</th>
			  	<th>Due Date</th>
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
					<td>@{{ statment.due_date }}</td>
					<td>
						<span v-for="month in statment.invoice_months">
							@{{ month.month }}
						</span>
					</td>
					<td v-if="statment.paid_amount">@{{ statment.paid_amount }}</td>
					<td v-else>@{{ statment.net_amount }} (not paid)</td>
				</tr>

				<tr>
					<th colspan="8" class="text-center"> Summary</th>
				</tr>
				<tr>
					<th colspan="7" class="text-right"> Months by receipt due date </th>
					<th  class="text-center"> Amount </th>
				</tr>

				<tr v-for="sum in _.orderBy(ComputedSummary, 'month')">
					<td colspan="7" class="text-right">@{{ GetDate(sum.month) }}</td>
					<td>@{{ sum.net_amount }}/-</td>
				</tr>
				<tr>
					<th colspan="7" class="text-right">Total </th>
					<th  class="text-center"> @{{ TotalComputedSummary.net_amount }}/- </th>
				</tr>
		</table>
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
			total_paid_amount: 0,
			monthlysome: [],
			statments: {!! json_encode($statments, JSON_NUMERIC_CHECK) !!},
			orderBy: 'student.name',
			type: null,
			selected_class: 'all',
			payment_status: 'all',
		},

		mounted: function(){
//			window.print();
		},
		computed: {
			ClassList: function(){
				return _.orderBy(_.uniqBy(_.map(this.statments, function (s){ 
					return { id: s.student.std_class.id, name: s.student.std_class.name };
				}), 'id'), 'id');
			},
			ComputedSummary: function() {

				var summary = [];

 				_.each(this.ComputedStatments, function(s){
					var date = s.due_date.split('-');

					var month = moment(date[2]+'-'+date[1]+'-'+01).format("Y-MM-DD");
					var Index = _.findIndex(summary, {month});
					if(Index == -1){
						summary.push({
							month,
							net_amount: s.net_amount,
							paid_amount: s.paid_amount,
						});
					} else {
						summary[Index] = {
							month,
							net_amount:	(summary[Index].net_amount + s.net_amount),
							paid_amount:	(summary[Index].paid_amount + s.paid_amount),
						};
					}
				});

				return summary;

			},
			TotalComputedSummary: function(){
				return {
					net_amount:	this.ComputedSummary.reduce((a, b) => a + Number(b.net_amount), 0),
					paid_amount:	this.ComputedSummary.reduce((a, b) => a + Number(b.paid_amount), 0),
				}
			},
			ComputedStatments: function(){

				var	statments = _.filter(this.statments, (s)=>{
						var r = true;
						if(this.selected_class != 'all'){
							r = s.student.std_class.id == this.selected_class;
						}
						if(this.payment_status != 'all'){
							r = ((this.payment_status == 'paid')? (s.paid_amount > 0) : (s.paid_amount == 0) && r );
						}
						return r;
					});
				
			    return _.orderBy(statments, this.orderBy);
			}
		},

		methods: {
			GetDate: function(date){
				return moment(date).format("MMM-Y");
			}
		},
	});
</script>

@endsection