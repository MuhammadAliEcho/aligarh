@extends('admin.layouts.printable')
@section('title', 'Full & Half Freeship Statment | ')

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
	<h3 class="text-center">{{ tenancy()->tenant->system_info['general']['title'] }}</h3>
	<h4>List Of Full/Half Freeship Students</h4>
	<h4>AS ON: {{ Carbon\Carbon::now()->Format('M-Y') }}</h3>

		@foreach($classes AS $class)
			@foreach($class->Section AS $section)
				@if($section->Students->count())
				<h4>{{ $class->name }} {{ $section->nick_name }}</h4>
				<table id="rpt-att" class="table table-bordered">
					<thead>
						<tr>
							<th>GR No.</th>
							<th>Student Name</th>
							<th>Father Name</th>
							<th>Actual Fee</th>
							<th>F/H Freeship Fee</th>
							<th>Net Fee</th>
							<th> % </th>
						</tr>
					</thead>
					<tbody>
						@foreach($section->Students AS $student)
						<tr>
							<td>{{ $student->gr_no }}</td>
							<td>{{ $student->name }}</td>
							<td>{{ $student->father_name }}</td>
							<td>{{ $student->total_amount }}</td>
							<td>{{ $student->discount }}</td>
							<td>{{ $student->net_amount }}</td>
							<td>{{ round(($student->discount/$student->total_amount)*100, 2) }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				@endif
			@endforeach
		@endforeach
	</div>

</div>



@endsection


@section('script')
<script type="text/javascript">
	window.print();
</script>
@endsection
