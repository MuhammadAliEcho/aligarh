@extends('admin.layouts.printable')
@section('title', 'Average Result | ')

@section('head')
<style type="text/css">
	body {
		padding: 0 10px;
		margin: 0;
		font-size: 12px;
	}
	.invoice-title h2, .invoice-title h3 {
		display: inline-block;
	}
	.table > tbody > tr > .no-line,
	.table > thead > tr > .no-line {
		border: none;
	}
	.table > tbody > tr > .thick-line {
		border-top: 1px solid;
	}
	.table-bordered th,
	.table-bordered td {
		border: 1px solid black !important;
		padding: 2px;
	}
	.table > tbody > tr > td,
	.table > thead > tr > th {
		padding: 2px;
	}
	a[href]:after {
		content: none;
	}
</style>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<h3 class="text-center">{{ tenancy()->tenant->system_info['general']['title'] }}</h3>
		<h4 class="text-center">AVERAGE RESULT</h4>
		<h4 class="text-center"><u>@{{ exam_title }}</u></h4>
		<h4 class="text-center"><u>Session: @{{ selected_exams[0].academic_session.title }}</u></h4>

		<table class="table">
			<tbody>
				<tr>
					<td><h4>Class: @{{ selected_class.name }}</h4></td>
					<td class="text-right">
						<h4><u>Date: {{ \Carbon\Carbon::now()->format('d-M-Y') }}</u></h4>
					</td>
				</tr>
			</tbody>
		</table>

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
				<template v-for="(student, index) in computed_result" :key="student.id">
					<tr :class="{ success: student.rank > 0 && student.rank < 4 }">
						<td>@{{ index + 1 }}</td>
						<td>@{{ student.gr_no }}</td>
						<td>@{{ student.name }}</td>

						<td v-for="mark in student.total_obtain_marks">@{{ mark }}</td>

						<td>@{{ student.total_obtain_marks_sum }}</td>
						<td>@{{ student.percentage }}</td>
						<td>@{{ student.pass ? Grade(student.percentage) : 'F' }}</td>
						<td>@{{ student.rank }}</td>
						<td>@{{ student.remarks }}</td>
					</tr>
				</template>
			</tbody>
		</table>

		<table class="table" v-if="computed_result.length">
			<tbody>
				<tr>
					<td>
						<u>
							<p>Total No Of Students: @{{ computed_result.length }}</p>
						</u><br>
						<p>1st Position: <u>@{{ computed_result[0]?.name }} (% @{{ computed_result[0]?.percentage }})</u></p>
						<p v-if="computed_result.length > 1">2nd Position: <u>@{{ computed_result[1]?.name }} (% @{{ computed_result[1]?.percentage }})</u></p>
						<p v-if="computed_result.length > 2">3rd Position: <u>@{{ computed_result[2]?.name }} (% @{{ computed_result[2]?.percentage }})</u></p>
					</td>
					<td class="text-center" width="100px">
						<h3 style="margin-top: 50px">__________________</h3>
						<p><b>Teacher's Sign</b></p>
						<h3 style="margin-top: 50px">__________________</h3>
						<p><b>Rechecker's Sign</b></p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
@endsection

@section('vue')
<script type="text/javascript">
	new Vue({
		el: '#app',
		data: {
			selected_exams: @json($selected_exams),
			selected_class: @json($selected_class),
			results: @json($results, JSON_NUMERIC_CHECK),
			grades: @json($grades, JSON_NUMERIC_CHECK),
			exam_title: '{{ $exam_title }}',
			computed_result: [],
		},
		mounted() {
			this.computed_result = this.compute_result();
			this.calculateRank();

			// conssole.log(this.computed_result);
			window.print();
		},
		methods: {
			Grade(percentage) {
				for (const grade of this.grades) {
					if (Number(grade.from_percent) <= percentage && percentage <= Number(grade.to_percent)) {
						return grade.prifix;
					}
				}
				return '-';
			},
			calculateRank() {
				let rank = 0;
				this.computed_result?.forEach((result, index) => {
					if (result.pass && result.total_obtain_marks.every(mark => mark !== 0)) {
						if (index === 0) {
							rank = 1;
						} else if (this.computed_result[index - 1].percentage > result.percentage) {
							rank++;
						}
						result.rank = rank;
					}
				});
			},
			compute_result() {

				// let mainIdx = this.results[0].length >= this.results[1]?.length ? 0 : 1;
				let mainIdx = 0;
				let subIdx = 1 - mainIdx;

				const computed = [];

				this.results[mainIdx]?.forEach(result => {
					const student = {
						id: result.id,
						name: result.student.name,
						gr_no: result.student.gr_no,
						father_name: result.student.father_name,
						remarks: result.remarks || '',
						// total_marks: [0, 0],
						total_marks: [0],
						// total_obtain_marks: [0, 0],
						total_obtain_marks: [0],
						total_marks_sum: 0,
						total_obtain_marks_sum: 0,
						percentage: 0,
						rank: 0,
						pass: true
					};

					student.total_marks[mainIdx] = result.student_result.reduce((sum, r) => sum + Number(r.subject_result_attribute.total_marks), 0);
					student.total_obtain_marks[mainIdx] = result.student_result.reduce((sum, r) => sum + Number(r.total_obtain_marks), 0);

					const match = this.results[subIdx]?.find(r => r.student_id === result.student_id);
					if (match) {
						student.total_marks[subIdx] = match.student_result.reduce((sum, r) => sum + Number(r.subject_result_attribute.total_marks), 0);
						student.total_obtain_marks[subIdx] = match.student_result.reduce((sum, r) => sum + Number(r.total_obtain_marks), 0);
						if (match.remarks) {
							student.remarks += ', ' + match.remarks;
						}
						student.pass = this.isPassed(result.student_result, match.student_result);
					}

					student.total_marks_sum = student.total_marks.reduce((sum, mark) => sum + mark, 0);
					student.total_obtain_marks_sum = student.total_obtain_marks.reduce((sum, mark) => sum + mark, 0);
					student.percentage = ((student.total_obtain_marks_sum / student.total_marks_sum) * 100).toFixed(2);
					computed.push(student);
				});

				return computed.sort((a, b) => b.percentage - a.percentage);
			},
			isPassed(results1, results2) {
				const longer = results1.length >= results2.length ? results1 : results2;
				const shorter = results1.length >= results2.length ? results2 : results1;

				for (const res1 of longer) {
					let total = res1.subject_result_attribute.total_marks;
					let obtained = res1.total_obtain_marks;

					const match = shorter.find(r => r.subject_id === res1.subject_id);
					if (match) {
						total += match.subject_result_attribute.total_marks;
						obtained += match.total_obtain_marks;
					}

					const percentage = ((obtained / total) * 100).toFixed(2);
					if (this.Grade(percentage).toLowerCase() === 'f') {
						return false;
					}
				}
				return true;
			}
		}
	});
</script>
@endsection

@section('script')
@endsection
