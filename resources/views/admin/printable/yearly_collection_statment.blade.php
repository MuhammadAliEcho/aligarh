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
  .table > thead > tr > th,
  .table > tfoot > tr > th,
  .table > tfoot > tr > td {
      padding: 3px;
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
		<h4>Yearly Collection Statment</h4>
		<h4>Session: 
			{{ \Carbon\Carbon::createFromFormat('d/m/Y', $session->getOriginal('start'))->format('M-Y') }} 
			TO 
			{{ \Carbon\Carbon::createFromFormat('d/m/Y', $session->getOriginal('end'))->format('M-Y') }} 
		</h4>
		{{-- <h4>Session: {{ Carbon\Carbon::createFromFormat('Y-m-d', $session->getOriginal('start'))->Format('M-Y') }} TO {{ Carbon\Carbon::createFromFormat('Y-m-d', $session->getOriginal('end'))->Format('M-Y') }} </h4> --}}
		<h4>Class: {{ $class->name }}</h4>

		<table id="rpt-att" class="table table-bordered">
			<thead>
				<tr>
					<th>GR No</th>
					<th>Student Name</th>
					<th>Father Name</th>
					<th>Annual Charges</th>
					<th>April</th>
					<th>May</th>
					<th>Jun</th>
					<th>July</th>
					<th>Aug</th>
					<th>Sept</th>
					<th>Oct</th>
					<th>Nov</th>
					<th>Dec</th>
					<th>Jan</th>
					<th>Feb</th>
					<th>March</th>
					<th>Total Fee</th>
				</tr>
			</thead>
			<tbody>
				@foreach($statment AS $student)
				<tr>
					<td>{{ $student->gr_no }}</td>
					<td>{{ $student->name }}</td>
					<td>{{ $student->father_name }}</td>
					<td>{{ $annualfeeses->where('student_id', $student->id)->sum('amount') }}</td>

					@foreach($months AS $k => $month)
						<td>{{ ($student->{$k} - $annualfeeses->where('student_id', $student->id)->where('payment_month', '=', $month)->sum('amount')) }}</td>
					@endforeach

					<td>{{ $student->total_amount }}</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<th colspan="3" class="text-right">Total</th>
					<td>{{ $annualfeeses->sum('amount') }}</td>
					@foreach($months AS $k => $month)
						<td>{{ ($statment->sum($k)	-	$annualfeeses->where('payment_month', '=', $month)->sum('amount')) }}</td>
					@endforeach
					<td>{{ $statment->sum('total_amount') }}</td>
				</tr>
			</tfoot>
		</table>


	</div>

</div>

@include('admin.includes.footercopyright')

@endsection


@section('script')
<script type="text/javascript">
	window.print();
</script>
@endsection
