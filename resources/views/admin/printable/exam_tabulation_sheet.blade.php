@extends('admin.layouts.printable')
@section('title', 'Tabulation Sheet | ')

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
	<h4 class="text-center"><u>@{{ selected_exam.name }}</u></h4>
	<h4 class="text-center"><u>Session: @{{ selected_exam.academic_session.title }}, Class @{{ selected_class.name }}</u></h4>
	<h4 class="text-right"><u>Date: {{ Carbon\Carbon::now()->format('d-M-Y') }}</u></h4>

		<form id="updaterankfrm" action="{{ URL('exam-reports') }}" method="POST">
			{{ csrf_field() }}
			<table class="table table-bordered">
				<thead>
					<tr>
						<th rowspan="2">SI No</th>
						<th rowspan="2">GR No</th>
						<th rowspan="2">Student Name</th>

						<th v-for="subject in subject_result_attributes" :colspan="(subject.attributes.length == 1)? 2 : subject.attributes.length + 2">@{{ subject.subject.name }}</th>

						<th rowspan="2">Obtain Marks</th>
						<th rowspan="2">% age</th>
						<th rowspan="2">Grade</th>
						<th rowspan="2">Rank</th>
						<th rowspan="2">Remarks</th>
					</tr>
					<tr>
						<template v-for="attributes in subject_result_attributes">
							<template v-for="attribute in attributes.attributes">
								<th>@{{ attribute.name }}</th>
							</template>
							<th v-if="attributes.attributes.length > 1">Total</th>
							<th>Grade</th>
						</template>
					</tr>
				</thead>
				<tbody>
					<template v-for="(student, k) in computed_transcripts" :key="student.id">
						<tr>
							<td>@{{ k+1 }}</td>
							<td>@{{ student.gr_no }}</td>
							<td>@{{ student.name }}</td>
							<template v-for="result in student.result">
								<template v-for="mark in result.obtain_marks">
									<td v-if="mark.attendance">@{{ mark.marks }}</td>
									<td v-else>A</td>
								</template>
								<td v-if="result.obtain_marks.length > 1">@{{ result.total_obtain_marks }}</td>
								<td>@{{ result.grade }}</td>
							</template>
							<td>@{{ student.total_obtain_marks }}</td>
							<td>@{{ (computed_transcripts[k].grade.toLowerCase() == 'f')? '-' : computed_transcripts[k].percentage }}</td>
							<td>@{{ computed_transcripts[k].grade }}</td>
							<td>
								@{{ (computed_transcripts[k].rank)? computed_transcripts[k].rank : '-' }}
								<input type="hidden" :name="'rank['+student.id+']'" :value="(computed_transcripts[k].rank)? computed_transcripts[k].rank : '-'">
							</td>
							<td>@{{ student.remarks }}</td>
						</tr>
					</template>
				</tbody>
			</table>
		</form>

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
			selected_exam: {!! json_encode($selected_exam) !!},
			selected_class: {!! json_encode($selected_class) !!},
			subject_result_attributes: {!! json_encode($subject_result_attributes, JSON_NUMERIC_CHECK) !!},
			grades: {!! json_encode($grades, JSON_NUMERIC_CHECK) !!},
			transcripts: {!! json_encode($transcripts, JSON_NUMERIC_CHECK) !!},
			rankcounter: 0,
			computed_transcripts: []
		},
		mounted: function(){
			for(k in this.transcripts){
				this.computed_transcripts.push({
					name: this.transcripts[k].student.name,
					remarks: this.transcripts[k].remarks,
					father_name: this.transcripts[k].student.father_name,
					gr_no: this.transcripts[k].student.gr_no,
					id: this.transcripts[k].id,
					total_marks: this.transcripts[k].student_result.reduce((a, b) => a + Number(b.subject_result_attribute.total_marks), 0),
					total_obtain_marks: this.transcripts[k].student_result.reduce((a, b) => a + Number(b.total_obtain_marks), 0),
					percentage: 0,
					rank:	0,
					result: {},
					grade: '-',
				});
					this.computed_transcripts[k].result	=	this.Result(this.transcripts[k].student_result, k);

				if (this.computed_transcripts[k].grade.toLowerCase() != 'f') {	
					this.computed_transcripts[k].percentage = ((this.computed_transcripts[k].total_obtain_marks / this.computed_transcripts[k].total_marks)*100).toFixed(2);
					this.computed_transcripts[k].grade	=	this.Grade(this.computed_transcripts[k].percentage);
				}
			}

			this.computed_transcripts = this.computed_transcripts.slice().sort(function(a, b) {
				return b.percentage - a.percentage;
			});
			
			this.RankCounter();

			window.print();
		},
		updated: function () {
			this.$nextTick(function () {
			// Code that will run only after the
			// entire view has been re-rendered
			this.formSubmit();
//			$('#updaterankfrm').submit();
			});
		},
		methods: {
			Result: function(student_result, k){
				vm = this;
				student_result.forEach(function(result, i){
					student_result[i].grade = vm.SubGrade(((result.total_obtain_marks/result.subject_result_attribute.total_marks)*100).toFixed(2), k);
				});
				return student_result;
			},
			Grade: function (percentage) {
				grad = '-';
				this.grades.forEach(function(grade){
					if(Number(grade.from_percent) < percentage  && percentage <= Number(grade.to_percent)){
						grad = grade.prifix;
					}
				});
				return grad;
			},
			SubGrade: function(percentage, k){
				grade = this.Grade(percentage);
				if(this.computed_transcripts[k].grade.toLowerCase() != 'f'){
//						console.log(k);
					if(grade.toLowerCase() == 'f') {
						this.computed_transcripts[k].grade = 'F';
					}
				}
				return grade;
			},
			RankCounter: function(){
				vm = this;
				vm.computed_transcripts.forEach(function(transcript, i){
					if (vm.computed_transcripts[i].grade.toLowerCase() != 'f') {
						if (i == 0) {
							vm.computed_transcripts[i].rank	=	1;
						} else if (vm.computed_transcripts[i-1].percentage > transcript.percentage) {
							vm.computed_transcripts[i].rank	=	vm.computed_transcripts[i-1].rank+1;
						} else{
							vm.computed_transcripts[i].rank	=	vm.computed_transcripts[i-1].rank;
						}
					}
				});
			},
			formSubmit: function(){
			 	var	e = $("#updaterankfrm");
				$.ajax({
				type: e.attr("method"),
				url:  e.attr("action"),
				data: e.serialize(),
				success: function(msg){
					console.log(msg.msg);
				},
				error: function(error){
						console.log(error);
					}
				});
			},
		}
	});
</script>

@endsection