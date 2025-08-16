@extends('admin.layouts.printable')
@section('title', 'Average Result | ')

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
	<h4 class="text-center">AVERAGE RESULT</h4>
	<h4 class="text-center"><u>@{{ exam_title }}</u></h4>
	<h4 class="text-center"><u>Session: @{{ selected_exams[0].academic_session.title }}</u></h4>

	<div class="">
		<table class="table">
			<tbody>
				<tr>
					<td>
						<h4>Class: @{{ selected_class.name }}</h4>
					</td>
					<td>
						<h4 class="text-right"><u>Date: {{ Carbon\Carbon::now()->format('d-M-Y') }}</u></h4>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

		<table class="table table-bordered">
			<thead>
				<tr>
					<th>SI No</th>
					<th>GR No</th>
					<th>Student Name</th>
					<th v-for="exam in selected_exams">@{{ exam.name }}</th>
					<th>G.Total</th>
					<th>Avg %</th>
					<th>Grade</th>
					<th>Rank</th>
					<th>Remarks</th>
				</tr>

			</thead>
			<tbody>
				<template v-for="(student, k) in computed_result" :key="student.id">
					<tr :class="[(student.rank < 4 && student.rank > 0)? 'success' : '']">
						<td>@{{ k+1 }}</td>
						<td>@{{ student.gr_no }}</td>
						<td>@{{ student.name }}</td>

						<td>@{{ student.total_obtain_marks[0] }}</td>
						<td>@{{ student.total_obtain_marks[1] }}</td>

						<td>@{{ student.total_obtain_marks_sum }}</td>
						<td>@{{ student.percentage }}</td>
						<td v-if="student.pass">@{{ Grade(student.percentage) }}</td>
						<td v-else>F</td>
						<td>@{{ student.rank }}</td>
						<td>@{{ student.remarks }}</td>
					</tr>
				</template>
			</tbody>
		</table>

		<table class="table">
			<tbody>
				<tr>
					<td>
						<u>
							<p>Total No Of Student: @{{ computed_result.length }}</p>
							<p>Total No Of Student: @{{ computed_result[0].rank }}</p>
						</u></br>
							1st Position Name <u> @{{ computed_result[0].name }}  % @{{ computed_result[0].percentage }}</u> </br>
							2nd Position Name <u> @{{ computed_result[1].name }}  % @{{ computed_result[0].percentage }} </u> </br>
							3rd Position Name <u> @{{ computed_result[2].name }}  % @{{ computed_result[0].percentage }} </u> </br>
					</td>
					<td class="text-center" width="100px">
						<h3 style="margin-top: 50px">__________________</h3><p><b>Teacher's Sign</b></p>
						<h3 style="margin-top: 50px">__________________</h3><p><b>Rechecker's Sign</b></p>						
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
			selected_exams: {!! json_encode($selected_exams) !!},
			selected_class: {!! json_encode($selected_class) !!},
			results: {!! json_encode($results, JSON_NUMERIC_CHECK) !!},
			grades: {!! json_encode($grades, JSON_NUMERIC_CHECK) !!},
			exam_title: '{{ $exam_title }}',
			rankcounter: 0,
			computed_result: [],
		},
		mounted: function(){
			this.computed_result = 	this.compute_result();
			this.RankCounter();
			window.print();
		},
		computed: {

		},
		methods: {
			Grade: function (percentage) {
				grad = '-';
				this.grades.forEach(function(grade){
					if(Number(grade.from_percent) <= percentage  && percentage <= Number(grade.to_percent)){
						grad = grade.prifix;
						return grad;
					}
				});
				return grad;
			},
			RankCounter: function(){
				vm = this;
				var rankno = 0;
				this.computed_result.forEach(function(result, i){
					if(result.total_obtain_marks[0] && result.total_obtain_marks[1] && result.pass){
						if (i == 0) {
							vm.computed_result[i].rank	=	rankno	= 1;
						} else if (vm.computed_result[i-1].percentage > result.percentage) {
							vm.computed_result[i].rank	=	++rankno;
						} else {
							vm.computed_result[i].rank	=	rankno;
						}
					}
				});
			},
/*			RankCounter: function(i){
				if (i == 0) {
					this.rankcounter = 1;
				} else if (this.computed_result[i-1].percentage > this.computed_result[i].percentage) {
					this.rankcounter++;
				}
				return this.rankcounter;
			},
*/
			compute_result:		function(){
				if (this.results[0].length >= this.results[1].length) {
					mainloop = 0;
					subloop = 1;
				} else {
					mainloop = 1;
					subloop = 0;
				}
				computed_result = [];
				vm = this;
				this.results[mainloop].forEach(function(result, k){
					computed_result.push({
						name: result.student.name,
						remarks: (result.remarks == null)? '' : result.remarks,
						father_name: result.student.father_name,
						gr_no: result.student.gr_no,
						id: result.id,
						total_marks: [],
						total_obtain_marks: [],
						total_marks_sum: 0,
						total_obtain_marks_sum: 0,
						percentage: 0,
						rank:	0,
						pass: true,
					});
//					console.log(vm.results);
					computed_result[k].total_marks[mainloop]	=	result.student_result.reduce((a, b) => a + Number(b.subject_result_attribute.total_marks), 0);
					computed_result[k].total_marks[subloop]		=	0;

					computed_result[k].total_obtain_marks[mainloop]	=	result.student_result.reduce((a, b) => a + Number(b.total_obtain_marks), 0);
					computed_result[k].total_obtain_marks[subloop]	=	0;

					if (vm.results[subloop]) {
						result2 = vm.results[subloop].find(function(rslt){
							return result.student_id == rslt.student_id;
						});
						if (result2) {
							computed_result[k].total_marks[subloop] = result2.student_result.reduce((a, b) => a + Number(b.subject_result_attribute.total_marks), 0);
							computed_result[k].total_obtain_marks[subloop] = result2.student_result.reduce((a, b) => a + Number(b.total_obtain_marks), 0);
							if(result2.remarks != null){
								computed_result[k].remarks += ' ,' + result2.remarks;
							}
						
							computed_result[k].pass = vm.IsPassed(result.student_result, result2.student_result);
						}
					}

					computed_result[k].total_marks_sum = (computed_result[k].total_marks[mainloop] + computed_result[k].total_marks[subloop]);
					computed_result[k].total_obtain_marks_sum = (computed_result[k].total_obtain_marks[mainloop] + computed_result[k].total_obtain_marks[subloop]);

					computed_result[k].percentage = ((computed_result[k].total_obtain_marks_sum / computed_result[k].total_marks_sum)*100).toFixed(2);
				});
				computed_result = computed_result.slice().sort(function(a, b) { return b.percentage - a.percentage; });

				return computed_result;
			},

			IsPassed: function(r1, r2){
				if(r1.length >= r2.length){
					result1 = r1;
					result2 = r2;
				} else {
					result1 = r2;
					result2 = r1;
				}
				total_obtain_marks = total_marks = 0;
				var rtrn = true;
				_.forEach(result1, function(value, key){
					r2k =	_.findIndex(result2, {subject_id: value.subject_id});
					if(r2k >= 0){
						total_marks = value.subject_result_attribute.total_marks + result2[r2k].subject_result_attribute.total_marks;
						total_obtain_marks = value.total_obtain_marks + result2[r2k].total_obtain_marks;
					} else {
						total_marks = value.subject_result_attribute.total_marks;
						total_obtain_marks = value.total_obtain_marks;
					}
					rtrn = ((vm.Grade(((total_obtain_marks/total_marks)*100).toFixed(2))).toLowerCase() == 'f')? false : true;

					if(rtrn == false){
						return false;
					}
//						total_marks = value.subject_result_attribute.total_marks 
				});

				return rtrn;
			},
		}
	});
</script>

@endsection