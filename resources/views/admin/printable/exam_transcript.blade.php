@extends('admin.layouts.printable')
@section('title', 'Exam Transcript | ')

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
	<h4 class="text-center"><u>@{{ exam_title }}</u></h4>
	<h4 class="text-center"><u>Session: @{{ selected_exams[0].academic_session.title }}</u></h4>
	<h4 class="text-center">Grade: <u> @{{ student_class.name }} </u></h4>

	<div class="">
		<table class="table">
			<tbody>
				<tr>
					<td>
						<h4>Academic Record Of: <u> @{{ student.name }}</u></h4>
					</td>
				</tr>
				<tr>
					<td>
						<h4> S/O - D/O <u> @{{ student.father_name }} </u> G.R.No <u> @{{ student.gr_no }} </u> </h4>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

		<table class="table table-bordered">
			<thead>
				<tr>
					<th rowspan="2">Subject</th>
					<th colspan="3" class="text-center">@{{ selected_exams[0].name }}</th>
					<th colspan="3" class="text-center">@{{ selected_exams[1].name }}</th>
					<th colspan="3" class="text-center">Aggregate Result</th>
				</tr>
				<tr>
					<template v-for="n in 3">
						<th>Max Marks</th>
						<th>Obtain Marks</th>
						<th>Grade</th>
					</template>
				</tr>

			</thead>
			<tbody>
				<template v-for="(result, k) in computed_result" :key="student.id">
					<tr>
						<td>@{{ result.subject_name }}</td>
						
						<td>@{{ result.total_marks[0] }}</td>
						<td>@{{ result.total_obtain_marks[0] }}</td>
						<td>@{{ Grade(result.percentage[0]) }}</td>

						<td>@{{ result.total_marks[1] }}</td>
						<td>@{{ result.total_obtain_marks[1] }}</td>
						<td>@{{ Grade(result.percentage[1]) }}</td>

						<td>@{{ result.total_marks_sum }}</td>
						<td>@{{ result.total_obtain_marks_sum }}</td>
						<td>@{{ Grade(result.percentage_sum) }}</td>

					</tr>
				</template>
			</tbody>
		</table>

		<table class="table">
			<tbody>
				<tr>
					<td colspan="2">
						Remarks:
						<u>
							@{{ exam_remarks }}</p>
						</u>
					</td>
				</tr>
				<tr>
					<td class="text-center" width="100px">
						<h3>__________________</h3><p><b>Principle Sign</b></p>						
					</td>
					<td class="text-center" width="100px">
						<h3>__________________</h3><p><b>Teacher's Sign</b></p>
					</td>
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
			student: {!! json_encode($student, JSON_NUMERIC_CHECK) !!},
			selected_exams: {!! json_encode($selected_exams, JSON_NUMERIC_CHECK) !!},
			results: {!! json_encode($results, JSON_NUMERIC_CHECK) !!},
			grades: {!! json_encode($grades, JSON_NUMERIC_CHECK) !!},
			exam_title: '{{ $exam_title }}',
			student_class: 	{!! json_encode($student_class) !!},
			exam_remarks: '',
			computed_result: [],
		},
		mounted: function(){
			if (this.results[0]) {
				if(this.results[0].remarks){
					this.exam_remarks += this.results[0].remarks+', ';
				}
			}
			if (this.results[1]) {
				if (this.results[1].remarks) {
					this.exam_remarks += this.results[1].remarks;
				}
			}
			this.computed_result = 	this.compute_result();
			window.print();
		},
		methods: {
			Grade: function (percentage) {
				grad = '-';
				this.grades.forEach(function(grade){
					if(Number(grade.from_percent) < percentage && percentage <= Number(grade.to_percent)){
						grad = grade.prifix;
						return grad;
					}
				});
				return grad;
			},
			check_result: function(results){
				if(results[0]){
					if(results[1]){
						return	results[0].student_result.length >= results[1].student_result.length;
					}
					return true;
				} else {
					return false;
				}
			},
			compute_result:		function(){

				if (this.check_result(this.results)) {
					mainloop = 0;
					subloop = 1;
				} else {
					mainloop = 1;
					subloop = 0;
				}
				computed_result = [];
				vm = this;
				this.results[mainloop].student_result.forEach(function(result, k){
					computed_result.push({
						subject_name: result.subject.name,
						total_marks: [],
						total_obtain_marks: [],
						percentage: [],
						total_marks_sum: 0,
						total_obtain_marks_sum: 0,
						percentage_sum: 0,
					});

					computed_result[k].total_marks[mainloop]	=	result.subject_result_attribute.total_marks;
					computed_result[k].total_obtain_marks[mainloop]	=	result.total_obtain_marks;

					computed_result[k].total_marks[subloop]		=	0;
					computed_result[k].total_obtain_marks[subloop]	=	0;


					if (vm.results[subloop]) {
						result2 = vm.results[subloop].student_result.find(function(rslt){
							return result.subject_id == rslt.subject_id;
						});
						if (result2) {
							computed_result[k].total_marks[subloop] = result2.subject_result_attribute.total_marks;
							computed_result[k].total_obtain_marks[subloop] = result2.total_obtain_marks;
						}
					}

					computed_result[k].percentage[mainloop] = ((computed_result[k].total_obtain_marks[mainloop] / computed_result[k].total_marks[mainloop])*100).toFixed(2);
					computed_result[k].percentage[subloop] = ((computed_result[k].total_obtain_marks[subloop] / computed_result[k].total_marks[subloop])*100).toFixed(2);

					computed_result[k].total_marks_sum = (computed_result[k].total_marks[mainloop] + computed_result[k].total_marks[subloop]);
					computed_result[k].total_obtain_marks_sum = (computed_result[k].total_obtain_marks[mainloop] + computed_result[k].total_obtain_marks[subloop]);

					computed_result[k].percentage_sum = ((computed_result[k].total_obtain_marks_sum / computed_result[k].total_marks_sum)*100).toFixed(2);
				});

				return computed_result;
			},
		}
	});
</script>

@endsection