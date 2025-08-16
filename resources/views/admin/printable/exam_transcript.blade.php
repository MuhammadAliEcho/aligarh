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
	.table > thead > tr > th {
		padding: 2px;
    }
    .table > tbody > tr > th {
		padding: 2px;
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
	<h3 class="text-center">{{ config('systemInfo.general.title') }}</h3>	
	<h4 class="text-center"><u>@{{ exam_title }}</u></h4>
	<h4 class="text-center"><u>Session: @{{ selected_exams[0].academic_session.title }}</u></h4>
	<h4 class="text-center">Transcript for: <u> @{{ student_class.name }} </u></h4>

	<div class="">
		<hr style="margin-top: 10px; margin-bottom: 5px">
		<table style="width: 100%; margin-bottom: 10px ">
			<tbody>
				<tr>
					<td>
						<h4>Academic Record Of: <u> @{{ student.name }}</u></h4>
					</td>
					<td>
						<h4>G.R.No: <u> @{{ student.gr_no }} </u> </h4>
					</td>
				</tr>
				<tr>
					<td>
						<h4> S/O - D/O: <u> @{{ student.father_name }} </u></h4>
					</td>
					<td>
						<h4>Attendance: <u>@{{ StudentAttendance(attendance.total)+'/'+attendance.total.length }}</u></h4>
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
						<td>@{{ result.grade[0] }}</td>

						<td>@{{ result.total_marks[1] }}</td>
						<td>@{{ result.total_obtain_marks[1] }}</td>
						<td>@{{ result.grade[1] }}</td>

						<td>@{{ result.total_marks_sum }}</td>
						<td>@{{ result.total_obtain_marks_sum }}</td>
						<td>@{{ result.grade_sum }}</td>

					</tr>
				</template>
				<tr>
					<th>Grand Total: </th>

					<th>@{{ grand_total.total_marks[0] }}</th>
					<th>@{{ grand_total.total_obtain_marks[0] }}</th>
					<th>@{{ (grand_total.pass[0])? Grade(grand_total.total_percentage[0]) : 'F' }}</th>

					<th>@{{ grand_total.total_marks[1] }}</th>
					<th>@{{ grand_total.total_obtain_marks[1] }}</th>
					<th>@{{ (grand_total.pass[1])? Grade(grand_total.total_percentage[1]) : 'F' }}</th>

					<th>@{{ grand_total.total_marks[2] }}</th>
					<th>@{{ grand_total.total_obtain_marks[2] }}</th>
					<th v-if="grand_total.total_percentage[2]">
						@{{ (grand_total.pass[2])? Grade(grand_total.total_percentage[2]) : 'F' }}
					</th>
					<th v-else>-</th>

				</tr>
				<tr>
					<th>Percentage: </th>

					<th colspan="3" v-if="grand_total.pass[0]">@{{ (!isNaN(grand_total.total_percentage[0]))? grand_total.total_percentage[0]+'%' : '-' }}</th>
					<th colspan="3" v-else>-</th>

					<th colspan="3" v-if="grand_total.pass[1]">@{{ (!isNaN(grand_total.total_percentage[1]))? grand_total.total_percentage[1]+'%' : '-' }}</th>
					<th colspan="3" v-else>-</th>

					<th colspan="3">@{{ (grand_total.total_percentage[2])? grand_total.total_percentage[2]+'%' : '-' }}</th>

				</tr>

				<tr>
					<th>Attendance</th>

					<th colspan="3">@{{ StudentAttendance(attendance.first_exam)+'/'+attendance.first_exam.length }}</th>
					<th colspan="3">@{{ StudentAttendance(attendance.second_exam)+'/'+attendance.second_exam.length }}</th>
					<th colspan="3">@{{ StudentAttendance(attendance.total)+'/'+attendance.total.length }}</th>
				</tr>

				<tr>
					<th>Result</th>

					<template v-if="grand_total.pass[0]">
						<th colspan="3" v-if="Grade(grand_total.total_percentage[0]) == '-'">
							-
						</th>
						<th colspan="3" v-else>
							<u> Passed</u>, &nbsp;&nbsp; Grade: <u>@{{ Grade(grand_total.total_percentage[0]) }}</u>, &nbsp;&nbsp; Rank: <u class="text-capitalize">@{{ stringifyNumber(grand_total.ranks[0]) }} (@{{ grand_total.ranks[0] }})</u>
						</th>
					</template>
					<th colspan="3" v-else>Faild</th>

					<template v-if="grand_total.pass[1]">
						<th colspan="3" v-if="Grade(grand_total.total_percentage[1]) == '-'">
							-
						</th>
						<th colspan="3" v-else>
							<u>Passed</u>, &nbsp;&nbsp; Grade: <u>@{{ Grade(grand_total.total_percentage[1]) }}</u>, &nbsp;&nbsp; Rank: <u class="text-capitalize">@{{ stringifyNumber(grand_total.ranks[1]) }} (@{{ grand_total.ranks[1] }})</u>
						</th>
					</template>
					<th colspan="3" v-else>Faild</th>

					<template v-if="grand_total.total_percentage[2]">
						<th colspan="3" v-if="grand_total.pass[2]">
							Passed, Grade: @{{ Grade(grand_total.total_percentage[2]) }}
						</th>
						<th colspan="3" v-else>
							Faild
						</th>
					</template>
					<th colspan="3" v-else>-</th>

				</tr>
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
						<h3 style="margin-top: 50px">__________________</h3><p><b>Principle Sign</b></p>
					</td>
					<td class="text-center" width="100px">
						<h3 style="margin-top: 50px">__________________</h3><p><b>Teacher's Sign</b></p>
					</td>
				</tr>
			</tbody>
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
			special: ['zeroth','first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth', 'eleventh', 'twelvth', 'thirteenth', 'fourteenth', 'fifteenth', 'sixteenth', 'seventeenth', 'eighteenth', 'nineteenth'],
			deca: ['twent', 'thirt', 'fourt', 'fift', 'sixt', 'sevent', 'eight', 'ninet'],
			student: {!! json_encode($student, JSON_NUMERIC_CHECK) !!},
			attendance: {!! json_encode($attendance, JSON_NUMERIC_CHECK) !!},
			selected_exams: {!! json_encode($selected_exams, JSON_NUMERIC_CHECK) !!},
			results: {!! json_encode($results, JSON_NUMERIC_CHECK) !!},
			grades: {!! json_encode($grades, JSON_NUMERIC_CHECK) !!},
			exam_title: '{{ $exam_title }}',
			student_class: 	{!! json_encode($student_class) !!},
			exam_remarks: '',
			computed_result: [],
			grand_total: {
				total_marks:	[0, 0, 0],
				total_obtain_marks: [0, 0, 0],
				total_percentage: [0, 0, 0],
				ranks: ['-', '-', '-'],
				pass: [
					true, true, true
				],
			},
		},
		computed: {

		},
		mounted: function(){
			if (this.results[0]) {
				if(this.results[0].remarks){
					this.exam_remarks += this.results[0].remarks+', ';
				}
				if(this.results[0].rank){
					this.grand_total.ranks[0] = this.results[0].rank;
				}
			}
			if (this.results[1]) {
				if (this.results[1].remarks) {
					this.exam_remarks += this.results[1].remarks;
				}
				if(this.results[1].rank){
					this.grand_total.ranks[1] = this.results[1].rank;
				}
			}
			this.computed_result = 	this.compute_result();

			this.grand_total.total_percentage = this.grand_total_percentage();

			if (this.grand_total.total_marks[1]) {

				this.grand_total.total_marks[2]	=	(this.grand_total.total_marks[0] + this.grand_total.total_marks[1]);
				this.grand_total.total_obtain_marks[2]	=	(this.grand_total.total_obtain_marks[0] + this.grand_total.total_obtain_marks[1]);

				this.grand_total.total_percentage[2]	=	parseFloat(((this.grand_total.total_obtain_marks[2]/this.grand_total.total_marks[2])*100).toFixed(2));
			}

			window.print();

		},
		methods: {
			Grade: function (percentage) {
				grad = '-';
				this.grades.forEach(function(grade){
					if(Number(grade.from_percent) <= percentage && percentage <= Number(grade.to_percent)){
						grad = grade.prifix;
						return grad;
					}
				});
				return grad;
			},
			grand_total_percentage: function(){
				return 	[
							parseFloat(((this.grand_total.total_obtain_marks[0]/this.grand_total.total_marks[0])*100).toFixed(2)),
							parseFloat(((this.grand_total.total_obtain_marks[1]/this.grand_total.total_marks[1])*100).toFixed(2)),
							0
						];
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
			stringifyNumber: 	function(n) {
				if (n < 20) return this.special[n];
				if (n%10 === 0) return this.deca[Math.floor(n/10)-2] + 'ieth';
				return this.deca[Math.floor(n/10)-2] + 'y-' + this.special[n%10];
			},
			StudentAttendance: function(attendance){
				return	_.filter(attendance, function(atten) { if (atten.status) return atten }).length;
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
						grade: [],
						total_marks_sum: 0,
						total_obtain_marks_sum: 0,
						percentage_sum: 0,
						grade_sum: '-',
					});

					vm.grand_total.total_marks[mainloop]	+=	computed_result[k].total_marks[mainloop]	=	result.subject_result_attribute.total_marks;
					
					vm.grand_total.total_obtain_marks[mainloop]	+=	computed_result[k].total_obtain_marks[mainloop]	=	result.total_obtain_marks;

					computed_result[k].total_marks[subloop]		=	0;
					computed_result[k].total_obtain_marks[subloop]	=	0;


					if (vm.results[subloop]) {
						result2 = vm.results[subloop].student_result.find(function(rslt){
							return result.subject_id == rslt.subject_id;
						});
						if (result2) {
							vm.grand_total.total_marks[subloop]	+=	computed_result[k].total_marks[subloop] = result2.subject_result_attribute.total_marks;
							vm.grand_total.total_obtain_marks[subloop]	+=	computed_result[k].total_obtain_marks[subloop] = result2.total_obtain_marks;
						}
					}

					computed_result[k].percentage[mainloop] = ((computed_result[k].total_obtain_marks[mainloop] / computed_result[k].total_marks[mainloop])*100).toFixed(2);
					computed_result[k].percentage[subloop] = ((computed_result[k].total_obtain_marks[subloop] / computed_result[k].total_marks[subloop])*100).toFixed(2);

					computed_result[k].grade[mainloop]	=	vm.Grade(computed_result[k].percentage[mainloop]);
					if(computed_result[k].grade[mainloop].toLowerCase() == 'f' && vm.grand_total.pass[mainloop]){
						vm.grand_total.pass[mainloop] = false;
					}
					computed_result[k].grade[subloop]	=	vm.Grade(computed_result[k].percentage[subloop]);
					if(computed_result[k].grade[subloop].toLowerCase() == 'f' && vm.grand_total.pass[subloop]){
						vm.grand_total.pass[subloop] = false;
					}

//					if(computed_result[k].total_marks[mainloop] && computed_result[k].total_marks[subloop]){
					if(computed_result[k].total_marks[1]){

						computed_result[k].total_marks_sum = (computed_result[k].total_marks[mainloop] + computed_result[k].total_marks[subloop]);
						computed_result[k].total_obtain_marks_sum = (computed_result[k].total_obtain_marks[mainloop] + computed_result[k].total_obtain_marks[subloop]);

						computed_result[k].percentage_sum = ((computed_result[k].total_obtain_marks_sum / computed_result[k].total_marks_sum)*100).toFixed(2);
						computed_result[k].grade_sum	=	vm.Grade(computed_result[k].percentage_sum);

						if(computed_result[k].grade_sum.toLowerCase() == 'f' && vm.grand_total.pass[2]){
							vm.grand_total.pass[2] = false;
						}
					}

				});

				return computed_result;
			},
		}
	});
</script>

@endsection