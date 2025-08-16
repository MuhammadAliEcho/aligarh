@extends('admin.layouts.printable')
@section('title', 'Student Transfer Certificate | ')

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
      font-size: 16px;
      }

    p {
    	padding: 2px
    }

    .row {
    	border: 1px solid black !important;
    	padding: 5px
    }




	</style>

@endsection

@section('content')
<div class="container-fluid">

	<div class="row">
		<h3 class="text-center">{{ config('systemInfo.general.title') }}</h3>
		<h4>GR NO.__________________</h4>
		<h3 class="text-center"><u>TRANSFER CERTIFICATE</u></h3>

		<p>Full Name Of Student <u>@{{ student.name }}</u></p>
		<p>Father Name <u>@{{ student.father_name }}</u></p>
		<p>Religion <u>@{{ student.religion }}</u> Place Of Birth <u>@{{ student.place_of_birth }}</u></p>
		<p>Date Of Birth <u>{{ Carbon\Carbon::createFromFormat('Y-m-d', $student->getRawOriginal('date_of_birth'))->format('l jS \\of F Y') }}</u></p>
		<p>Date Of Admission <u>{{ Carbon\Carbon::createFromFormat('Y-m-d', $student->getRawOriginal('date_of_admission'))->format('d-M-Y') }}</u></p>
		<p>Previous School Attended <u>@{{ student.last_school }}</u></p>
		<p>Class Attending Now <u>@{{ student.std_class.name }}</u></p>
		<p>Progress______________________________________Conduct______________________________________</p>
		<p>Date Of Leaving the School <u>@{{ student.date_of_leaving }}</u></p>
		<p>Resion Of Leaving the School <u>@{{ student.cause_of_leaving }}</u></p>
		<p>Certified that the above information is in accordance with the school register.</p>

		<div style="padding-top: 18px">
			<div class="col-sm-4 col-md-4">
				<h4>Date <u>{{ Carbon\Carbon::now()->format('d-M-Y') }}</u></h4>
			</div>
			<div class="col-sm-4 col-md-4">
				<h4>Class Teacher ____________________</h4>
			</div>
			<div class="col-sm-4 col-md-4">
				<h4>Principle ________________________</h4>
			</div>
		</div>

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
			student: {!! json_encode($student, JSON_NUMERIC_CHECK) !!}
		},

		mounted: function(){

//			window.print();
		},

		methods: {

		},
	});
</script>

@endsection