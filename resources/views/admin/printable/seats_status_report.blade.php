@extends('admin.layouts.printable')
@section('title', 'School Seats Status | ')

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
	<h4>School Capicity and Utilization Status Report</h4>
	<h4>AS ON: {{ Carbon\Carbon::now()->format('d-M-Y') }}</h3>
		<table id="rpt-att" class="table table-bordered">
			<thead>
			  <tr>
				<th rowspan="2">Class & Sections</th>
				<th rowspan="2">Capacity </th>
				<th colspan="4" class="text-center">Regular Student</th>
			  	<th colspan="4" class="text-center">Full/Half Free Ship Student</th>
			  	<th class="text-center">Net</th>
			  	<th rowspan="2"> Vacant </th>
			  </tr>
			  <tr>
				<th>Previous Strength</th>
				<th>New Admission</th>
				<th>Left</th>
				<th>Current Strength</th>

				<th>Previous Strength</th>
				<th>New Admission</th>
				<th>Left</th>
				<th>Current Strength</th>

				<th>Current Strength</th>
			  </tr>
			</thead>
			<tbody>

				@foreach($classes AS $class)
					@foreach($class->Section AS $section)
						<tr>
							<td>{{ $class->name }}-{{ $section->nick_name }}</td>

							<td class="{{ $class->id.'_'.$section->id }}_capacity capacity">{{ $section->capacity }}</td>

							<td class="{{ $class->id.'_'.$section->id }}_p_r_students p_r_students">{{ $section->Students()->WithOutDiscount()->Active()->OldAdmission($academic_session)->count() }}</td>
							<td class="{{ $class->id.'_'.$section->id }}_n_r_students n_r_students">{{ $section->Students()->WithOutDiscount()->Active()->NewAdmission($academic_session)->count() }}</td>
							<td class="{{ $class->id.'_'.$section->id }}_l_r_students l_r_students">{{ $section->Students()->WithOutDiscount()->InActiveOnSelectedSession($academic_session)->count() }}</td>
							<td class="{{ $class->id.'_'.$section->id }}_c_r_students c_r_students">{{ $section->Students()->WithOutDiscount()->Active()->count() }}</td>

							<td class="{{ $class->id.'_'.$section->id }}_p_f_students p_f_students">{{ $section->Students()->WithDiscount()->Active()->OldAdmission($academic_session)->count() }}</td>
							<td class="{{ $class->id.'_'.$section->id }}_n_f_students n_f_students">{{ $section->Students()->WithDiscount()->Active()->NewAdmission($academic_session)->count() }}</td>
							<td class="{{ $class->id.'_'.$section->id }}_l_f_students l_f_students">{{ $section->Students()->WithDiscount()->InActiveOnSelectedSession($academic_session)->count() }}</td>
							<td class="{{ $class->id.'_'.$section->id }}_c_f_students c_f_students">{{ $section->Students()->WithDiscount()->Active()->count() }}</td>

							<td class="{{ $class->id.'_'.$section->id }}_current_students current_students">{{ $section->Students()->Active()->count() }}</td>

							<td class="{{ $class->id.'_'.$section->id }}_vacant vacant" class-id="{{ $class->id }}" sec-id="{{ $section->id }}"></td>
						</tr>
					@endforeach
				@endforeach
			</tbody>
				<tfoot>
					<tr>
						<th>Total Students</th>
						<th>@{{ tot_capacity }}</th>
						
						<th>@{{ p_r_students }}</th>
						<th>@{{ n_r_students }}</th>
						<th>@{{ l_r_students }}</th>
						<th>@{{ c_r_students }}</th>
						
						<th>@{{ p_f_students }}</th>
						<th>@{{ n_f_students }}</th>
						<th>@{{ l_f_students }}</th>
						<th>@{{ c_f_students }}</th>
						<th>@{{ current_students }}</th>
						<th>@{{ total_vacant }}</th>
					</tr>
				</tfoot>
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
			tot_capacity: 0,

			p_r_students: 0,
			n_r_students: 0,
			l_r_students: 0,
			c_r_students: 0,
			
			p_f_students: 0,
			n_f_students: 0,
			l_f_students: 0,
			c_f_students: 0,

			current_students: 0,
			total_vacant: 0,
		},

		mounted: function(){
			this.TotalStudents();
			window.print();
		},

		methods: {
			TotalStudents: function(){
				vm = this;
				vacant=0;
				$('.capacity').each(function(){
					vm.tot_capacity+=Number($(this).text());
				});
				$('.p_r_students').each(function(){
					vm.p_r_students+=Number($(this).text());
				});
				$('.n_r_students').each(function(){
					vm.n_r_students+=Number($(this).text());
				});
				$('.l_r_students').each(function(){
					vm.l_r_students+=Number($(this).text());
				});
				$('.c_r_students').each(function(){
					vm.c_r_students+=Number($(this).text());
				});
				$('.p_f_students').each(function(){
					vm.p_f_students+=Number($(this).text());
				});
				$('.n_f_students').each(function(){
					vm.n_f_students+=Number($(this).text());
				});
				$('.l_f_students').each(function(){
					vm.l_f_students+=Number($(this).text());
				});
				$('.c_f_students').each(function(){
					vm.c_f_students+=Number($(this).text());
				});
				$('.current_students').each(function(){
					vm.current_students+=Number($(this).text());
				});
				$('.vacant').each(function(){

					classid =	$(this).attr('class-id');
					secid =	$(this).attr('sec-id');

					capacity = Number($('.'+classid+'_'+secid+'_capacity').text());
					current_students = Number($('.'+classid+'_'+secid+'_current_students').text());
					vacant = capacity - current_students;
					$(this).text(vacant);
					vm.total_vacant+=vacant;

				});

			},
		},
	});
</script>

@endsection