<div class="container">

	<div class="row">
		<h2>Payment history of {{ tenancy()->tenant->system_info['general']['title'] }}</h3>
			<hr>

		<h4 class="text-right"><u>Date: {{ Carbon\Carbon::now()->format('d-M-Y') }}</u></h4>
		<table class="table table-bordered invoice-table" style="margin-top: 50px">
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
				<tr v-for="invoice in system_invoices">
					<td>@{{invoice.id}}</td>
					<td>@{{invoice.billing_month}}</td>
					<td>@{{invoice.amount}}</td>
					<td>@{{invoice.status}}</td>
					<td>@{{invoice.date_of_payment}}</td>
					<td>@{{invoice.created_at}}</td>
				</tr>
			</tbody>
		</table>

	</div>

</div>
<div class="footer" style="border: none;">

	<div class="row">
	<div class="pull-right">
		<strong>All rights reserved</strong>
	</div>
	<div>
		Software Developed By <strong> HASHMANAGEMENT.COM © 2018 </strong>
	</div>
	</div>
</div>
