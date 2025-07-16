@extends('admin.layouts.printable')
@section('title', 'Award List | ')

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
	<h3 class="text-center">{{ config('systemInfo.title') }}</h3>
	<h4 class="text-center">AWARD LIST</h4>
	<h4 class="text-center"><u>@{{ selected_exam.name }}, &nbsp;@{{ selected_exam.academic_session.title }}</u></h4>

	<div class="">
		<table class="table" style="margin-bottom: 5px;">
			<tbody>
				<tr>
					<td>
						<h4>Subject: @{{ selected_subject.name }}</h4><h4>Maximum Marks: @{{result_attribute.total_marks}}</h4>
					</td>
					<td>
						<h4>Class: @{{ selected_class.name }}</h4>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

		<table class="table table-bordered">
			<thead>
				<tr>
					<th rowspan="2">SI No</th>
					<th rowspan="2">GR No</th>
					<th rowspan="2">Student Name</th>
					<th :colspan="result_attribute.attributes.length">Obtain Marks</th>

					<th rowspan="2">Total Marks</th>
					<th rowspan="2">Grade</th>
				</tr>
				<tr>
					<template v-for="attribute in result_attribute.attributes">
						<th>@{{ attribute.name }}</th>
					</template>
				</tr>

			</thead>
			<tbody>
				<template v-for="(student, k) in result_attribute.student_result" :key="student.id">
					<tr>
						<td>@{{ k+1 }}</td>
						<td>@{{ student.student.gr_no }}</td>
						<td>@{{ student.student.name }}</td>
						<template v-for="mark in student.obtain_marks">
							<td v-if="mark.attendance">@{{ mark.marks }}</td>
							<td v-else>A</td>
						</template>
						<td>@{{ student.total_obtain_marks }}</td>
						<td>@{{ GradePercentage(k) }}</td>
					</tr>
				</template>
			</tbody>
		</table>

		<table class="table">
			<tbody>
				<tr>
					<td>
						<u>
							<p>Total No Of Student: @{{ result_attribute.student_result.length }}</p>
							<p>Total No Of Student Passed: @{{ NoOfPassStd().length }}</p>
							<p>Net Result: @{{ netResult() }} %</p>
						</u>
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
			selected_exam: {!! json_encode($selected_exam) !!},
			selected_class: {!! json_encode($selected_class) !!},
			selected_subject: {!! json_encode($selected_subject, JSON_NUMERIC_CHECK) !!},
			result_attribute: {!! json_encode($result_attribute, JSON_NUMERIC_CHECK) !!},
			grades: {!! json_encode($grades, JSON_NUMERIC_CHECK) !!},
			computed_transcripts: []
		},

		mounted: function(){
			this.OrderByStudent();
			window.print();
		},
		computed: {
			ComputedStudentResult: function(){
				return _.orderBy(this.result_attribute.student_result, 'student.name');
			},
			ordered_computed_transcripts: function(){
				return this.computed_transcripts.slice().sort(function(a, b) {
					return b.percentage - a.percentage;
				});
			},
			total_obtain_marks: function(){
				return this.result_attribute.student_result.reduce((a, b) => a + Number(b.total_obtain_marks), 0);
			},
		},
		methods: {
			OrderByStudent:	function(){
				this.result_attribute.student_result =	_.orderBy(this.result_attribute.student_result, 'student.name');
			},
			GradePercentage: function(k){
				this.result_attribute.student_result[k].percentage	= this.Percentage(this.result_attribute.student_result[k].total_obtain_marks);
				return	this.result_attribute.student_result[k].grade	=	this.Grade(this.result_attribute.student_result[k].percentage);
			},
			Percentage: function(obtain_marks){
				return ((obtain_marks/this.result_attribute.total_marks)*100).toFixed(2);
			},
			Grade: function (percentage) {
				grad = '-';
				this.grades.forEach(function(grade){
					if(Number(grade.from_percent) <= percentage  && percentage <= Number(grade.to_percent)){
						grad = grade.prifix;
					}
				});
				return grad;
			},
			NoOfPassStd: function(){
				return this.result_attribute.student_result.filter(std => std.grade.toLowerCase() != 'f');
			},
			netResult: function(){
				return (((this.NoOfPassStd()).length/this.result_attribute.student_result.length)*100).toFixed(2);
			}
		}
	});
</script>

@endsection