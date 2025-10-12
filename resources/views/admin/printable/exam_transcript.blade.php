@extends('admin.layouts.printable')
@section('title', 'Exam Transcript | ')

@section('head')
<style type="text/css">
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
    .table > tbody > tr > td,
    .table > thead > tr > th,
    .table > tbody > tr > th {
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
        <h4 class="text-center"><u>@{{ exam_title }}</u></h4>
        <h4 class="text-center"><u>Session: @{{ selected_exams[0].academic_session.title }}</u></h4>
        <h4 class="text-center">Transcript for: <u>@{{ student_class.name }}</u></h4>

        <div>
            <hr style="margin-top: 10px; margin-bottom: 5px">
            <table style="width: 100%; margin-bottom: 10px">
                <tbody>
                    <tr>
                        <td><h4>Academic Record Of: <u>@{{ student.name }}</u></h4></td>
                        <td><h4>G.R.No: <u>@{{ student.gr_no }}</u></h4></td>
                    </tr>
                    <tr>
                        <td><h4> S/O - D/O: <u>@{{ student.father_name }}</u></h4></td>
                        <td><h4>Attendance: 
                            <u>@{{ StudentAttendance(attendance.total) + '/' + attendance.total.length }}</u></h4></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th rowspan="2">Subject</th>
                    <th v-for="exam in selected_exams" colspan="3" v-if="selected_exams.length == 2" class="text-center">@{{ exam.name }}</th> {{-- this line also show  when we have more than one selected_exams --}}
                    <th v-if="selected_exams.length == 2" colspan="3" class="text-center">Aggregate Result</th>
                </tr>
                <tr>
                    <template v-for="exam in selected_exams">
                        <th>Max Marks</th>
                        <th>Obtain Marks</th>
                        <th>Grade</th>
                    </template>
                    <template v-if="selected_exams.length == 2">
                        <th>Max Marks</th>
                        <th>Obtain Marks</th>
                        <th>Grade</th>
                    </template>
                </tr>
            </thead>
            <tbody>
                <template v-for="(result, k) in computed_result">
                    <tr>
                        <td>@{{ result.subject_name }}</td>
                        <template v-for="(exam, index) in selected_exams">
                            <td>@{{ result.total_marks[index] }}</td>
                            <td>@{{ result.total_obtain_marks[index] }}</td>
                            <td>@{{ result.grade[index] }}</td>
                        </template>
                        <template v-if="selected_exams.length == 2">
                            <td>@{{ result.total_marks_sum }}</td>
                            <td>@{{ result.total_obtain_marks_sum }}</td>
                            <td>@{{ result.grade_sum }}</td>
                        </template>
                    </tr>
                </template>

                <!-- Grand Total -->
                <tr>
                    <th>Grand Total:</th>
                    <template v-for="(exam, index) in selected_exams">
                        <th>@{{ grand_total.total_marks[index] }}</th>
                        <th>@{{ grand_total.total_obtain_marks[index] }}</th>
                        <th>@{{ grand_total.pass[index] ? Grade(grand_total.total_percentage[index]) : 'F' }}</th>
                    </template>
                    <template v-if="selected_exams.length == 2">
                        <th>@{{ grand_total.total_marks[2] }}</th>
                        <th>@{{ grand_total.total_obtain_marks[2] }}</th>
                        <th>@{{ grand_total.pass[2] ? Grade(grand_total.total_percentage[2]) : 'F' }}</th>
                    </template>
                </tr>

                <!-- Percentage Row -->
                <tr>
                    <th>Percentage:</th>
                    <template v-for="(exam, index) in selected_exams">
                        <th colspan="3" v-if="grand_total.pass[index]">
                            @{{ !isNaN(grand_total.total_percentage[index]) ? grand_total.total_percentage[index] + '%' : '-' }}
                        </th>
                        <th colspan="3" v-else>-</th>
                    </template>
                    <template v-if="selected_exams.length == 2">
                        <th colspan="3">@{{ grand_total.total_percentage[2] ? grand_total.total_percentage[2] + '%' : '-' }}</th>
                    </template>
                </tr>

                <!-- Attendance Row -->
                <tr>
                    <th>Attendance</th>
                    <template v-for="(exam, index) in selected_exams">
                        <th colspan="3">@{{ StudentAttendance(attendance[exam.id]) + '/' + attendance[exam.id].length }}</th>
                    </template>
                    <template v-if="selected_exams.length == 2">
                        <th colspan="3">@{{ StudentAttendance(attendance.total) + '/' + attendance.total.length }}</th>
                    </template>
                </tr>

                <!-- Result Status Row -->
                <tr>
                    <th>Result</th>
                    <template v-for="(exam, index) in selected_exams">
                        <template v-if="grand_total.pass[index]">
                            <th colspan="3" v-if="Grade(grand_total.total_percentage[index]) == '-'">-</th>
                            <th colspan="3" v-else>
                                <u>Passed</u>, Grade: <u>@{{ Grade(grand_total.total_percentage[index]) }}</u>, 
                                Rank: <u class="text-capitalize">@{{ stringifyNumber(grand_total.ranks[index]) }} (@{{ grand_total.ranks[index] }})</u>
                            </th>
                        </template>
                        <th colspan="3" v-else>Failed</th>
                    </template>
                    <template v-if="selected_exams.length == 2">
                        <th colspan="3" v-if="grand_total.pass[2]">
                            Passed, Grade: @{{ Grade(grand_total.total_percentage[2]) }}
                        </th>
                        <th colspan="3" v-else>Failed</th>
                    </template>
                </tr>
            </tbody>
        </table>

        <table class="table">
            <tbody>
                <tr>
                    <td colspan="2">
                        Remarks: <u>@{{ exam_remarks }}</u>
                    </td>
                </tr>
                <tr>
                    <td class="text-center" width="100px">
                        <h3 style="margin-top: 50px">__________________</h3><p><b>Principal Sign</b></p>
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
			special: ['zeroth','first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth', 'eleventh', 'twelvth', 'thirteenth', 'fourteenth'],
			deca: ['twent', 'thirt', 'fourt', 'fift', 'sixt', 'sevent', 'eight', 'ninet'],
			student: @json($student, JSON_NUMERIC_CHECK),
			attendance: @json($attendance, JSON_NUMERIC_CHECK),
			selected_exams: @json($selected_exams, JSON_NUMERIC_CHECK),
			results: @json($results, JSON_NUMERIC_CHECK),
			grades: @json($grades, JSON_NUMERIC_CHECK),
			exam_title: '{{ $exam_title }}',
			student_class: @json($student_class),
			exam_remarks: '',
			computed_result: [],
			grand_total: {
				total_marks: [0, 0, 0],
				total_obtain_marks: [0, 0, 0],
				total_percentage: [0, 0, 0],
				ranks: ['-', '-', '-'],
				pass: [true, true, true],
			},
		},
		mounted: function(){
			for (let i = 0; i < this.results.length; i++) {
				if (this.results[i]) {
					if (this.results[i].remarks) this.exam_remarks += this.results[i].remarks + ', ';
					if (this.results[i].rank) this.grand_total.ranks[i] = this.results[i].rank;
				}
			}

			this.computed_result = this.compute_result();
			this.grand_total.total_percentage = this.grand_total_percentage();

			if (this.selected_exams.length == 2) {
				this.grand_total.total_marks[2] = this.grand_total.total_marks[0] + this.grand_total.total_marks[1];
				this.grand_total.total_obtain_marks[2] = this.grand_total.total_obtain_marks[0] + this.grand_total.total_obtain_marks[1];
				this.grand_total.total_percentage[2] = parseFloat(((this.grand_total.total_obtain_marks[2]/this.grand_total.total_marks[2])*100).toFixed(2));
			}

			window.print();
		},
		methods: {
			Grade(percentage) {
				let grad = '-';
				this.grades.forEach(function(grade) {
					if (Number(grade.from_percent) <= percentage && percentage <= Number(grade.to_percent)) {
						grad = grade.prifix;
					}
				});
				return grad;
			},
			grand_total_percentage() {
				return this.selected_exams.map((exam, i) => {
					if (this.grand_total.total_marks[i] === 0) return 0;
					return parseFloat(((this.grand_total.total_obtain_marks[i] / this.grand_total.total_marks[i]) * 100).toFixed(2));
				});
			},
			stringifyNumber(n) {
				if (typeof n !== 'number' || n < 0 || isNaN(n)) return '-';

				const special = this.special;
				const deca = this.deca;

				if (n < 20) return special[n];

				const tens = Math.floor(n / 10) - 2;
				const units = n % 10;

				if (tens < 0 || tens >= deca.length || units < 0 || units >= special.length) return '-';

				return units === 0
					? deca[tens] + 'ieth'
					: deca[tens] + 'y-' + special[units];
			},
			StudentAttendance(attendance) {
				return _.filter(attendance, function(a) { return a.status }).length;
			},
			compute_result() {
				let results = this.results;
				let baseIndex = results[0]?.student_result?.length >= (results[1]?.student_result?.length || 0) ? 0 : 1;
				let otherIndex = 1 - baseIndex;

				let computed_result = [];
				let vm = this;

				if (!results[baseIndex]) return [];

				results[baseIndex].student_result.forEach((res, k) => {
					let entry = {
						subject_name: res.subject.name,
						total_marks: [],
						total_obtain_marks: [],
						grade: [],
						total_marks_sum: 0,
						total_obtain_marks_sum: 0,
						grade_sum: '-'
					};

					// Base exam data
					entry.total_marks[baseIndex] = res.subject_result_attribute.total_marks;
					entry.total_obtain_marks[baseIndex] = res.total_obtain_marks;
					entry.grade[baseIndex] = vm.Grade((res.total_obtain_marks / res.subject_result_attribute.total_marks) * 100);
					if (entry.grade[baseIndex].toLowerCase() === 'f') vm.grand_total.pass[baseIndex] = false;

					vm.grand_total.total_marks[baseIndex] += entry.total_marks[baseIndex];
					vm.grand_total.total_obtain_marks[baseIndex] += entry.total_obtain_marks[baseIndex];

					// Other exam (if exists)
					entry.total_marks[otherIndex] = 0;
					entry.total_obtain_marks[otherIndex] = 0;
					entry.grade[otherIndex] = '-';

					if (results[otherIndex]) {
						let res2 = results[otherIndex].student_result.find(r => r.subject_id === res.subject_id);
						if (res2) {
							entry.total_marks[otherIndex] = res2.subject_result_attribute.total_marks;
							entry.total_obtain_marks[otherIndex] = res2.total_obtain_marks;
							entry.grade[otherIndex] = vm.Grade((res2.total_obtain_marks / res2.subject_result_attribute.total_marks) * 100);
							if (entry.grade[otherIndex].toLowerCase() === 'f') vm.grand_total.pass[otherIndex] = false;

							vm.grand_total.total_marks[otherIndex] += entry.total_marks[otherIndex];
							vm.grand_total.total_obtain_marks[otherIndex] += entry.total_obtain_marks[otherIndex];
						}
					}

					// Aggregate if 2 exams
					if (vm.selected_exams.length == 2) {
						entry.total_marks_sum = entry.total_marks[0] + entry.total_marks[1];
						entry.total_obtain_marks_sum = entry.total_obtain_marks[0] + entry.total_obtain_marks[1];
						let percent = (entry.total_obtain_marks_sum / entry.total_marks_sum) * 100;
						entry.grade_sum = vm.Grade(percent);
						if (entry.grade_sum.toLowerCase() === 'f') vm.grand_total.pass[2] = false;
					}

					computed_result.push(entry);
				});

				return computed_result;
			}
		}
	});
</script>
@endsection

