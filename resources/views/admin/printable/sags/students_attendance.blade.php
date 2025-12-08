@extends('admin.layouts.printable')
@section('title', 'Student Attendance | ')

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

        .table > thead > tr > th,
  .table > tbody > tr > th
   {
            padding: 2px;
        }
        .table > tbody > tr > td {
            padding: 2px;
        }
        a[href]:after {
            content: none;
        }

        .h {
            background-color: #f0f0f0;
        }

    </style>

@endsection

@section('content')
    <div class="container-fluid">

        <div class="row">
            <h3 class="text-center">{{ tenancy()->tenant->system_info['general']['title'] }}</h3>
            <div class="col-md-4">
                <h4>Attendance Of Class: {{ $selected_class->name . ' ' . $section_nick }} ({{ $input['date'] }})</h4>
                <h4>No Of Students: {{ count($students) }}</h4>
                <h4>Teacher: {{ $selected_class->Teacher->name ?? '' }}</h4>
            </div>
            <div class="col-md-4 pull-right">
                <h4>Abbreviation</h4>
                <h5>H*: Holiday. R*: Regular</h5>
                <h5>A*: Absent. P*: Present</h5>
                <h5>L*: Leave.</h5>
            </div>
            <table id="rpt-att" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Students</th>
                        <th width="50px">Gr No</th>
                        @for ($i = 1; $i <= $noofdays; $i++)
                            <th class="{{ in_array($i, $weekends) ? 'h' : '' }}">{{ $i }}</th>
                        @endfor
                        <th>H*</th>
                        <th>A*</th>
                        <th>P*</th>
                        <th>R*</th>
                        <th>L*</th>
                        <th>%P*</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->gr_no }}</td>
                            @for ($i = 1; $i <= $noofdays; $i++)
                                <td
                                    class="std_{{ $student->id }}_dat_{{ $i }} col_dat_{{ $i }} {{ in_array($i, $weekends) ? 'h' : '' }}">
                                </td>
                            @endfor
                            <td>{{ $noofweekends }}</td>
                            <td class="std_{{ $student->id }}_a"></td>
                            <td class="std_{{ $student->id }}_p"></td>
                            <td class="std_{{ $student->id }}_r"></td>
                            <td class="std_{{ $student->id }}_l"></td>
                            <td class="std_{{ $student->id }}_percent"></td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Present</th>
                        @for ($i = 1; $i <= $noofdays; $i++)
                            <td class="p_std_dat_{{ $i }} {{ in_array($i, $weekends) ? 'h' : '' }}"></td>
                        @endfor
                        <td colspan="6"></td>
                    </tr>
                    <tr>
                        <th colspan="2">Absent</th>
                        @for ($i = 1; $i <= $noofdays; $i++)
                            <td class="a_std_dat_{{ $i }} {{ in_array($i, $weekends) ? 'h' : '' }}"></td>
                        @endfor
                        <td colspan="6"></td>
                    </tr>
                    <tr>
                        <th colspan="2">Leave</th>
                        @for ($i = 1; $i <= $noofdays; $i++)
                            <td class="l_std_dat_{{ $i }} {{ in_array($i, $weekends) ? 'h' : '' }}"></td>
                        @endfor
                        <td colspan="6"></td>
                    </tr>
                    <tr>
                        <th colspan="2">% Attendance</th>
                        @for ($i = 1; $i <= $noofdays; $i++)
                            <td class="percent_std_dat_{{ $i }} {{ in_array($i, $weekends) ? 'h' : '' }}"></td>
                        @endfor
                        <td colspan="6"></td>
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
			students: {!! json_encode($students, JSON_NUMERIC_CHECK) !!},
			noofdays: {!! json_encode($noofdays, JSON_NUMERIC_CHECK) !!},
			weekends: {!! json_encode($weekends, JSON_NUMERIC_CHECK) !!}
		},

		mounted: function () {
			this.checkattendance(this.noofdays);
			this.calcattendance();
			$('tbody .h').html('H');
			window.print();
		},

		methods: {
			checkattendance: function (noofdays) {
				const self = this;
				this.students.forEach(function (student) {
					let totp = 0;
					let tota = 0;
					let totl = 0;
					let totr = 0;

					if (student.student_attendance && Array.isArray(student.student_attendance)) {
						student.student_attendance.forEach(function (attendance) {
							let date = new Date(attendance.date);
							let day = date.getDate();
							let prefix = '';

							if (attendance.leave_id && attendance.leave_id != null && attendance.leave_id != 0) {
								prefix = 'L';
								totl++;
								totr++; 
							} else if (attendance.status == 1) {
								prefix = 'P';
								totp++;
								totr++;
							} else {
								prefix = 'A';
								tota++;
								totr++;
							}

							$('.std_' + student.id + '_dat_' + day).text(prefix);
						});
					}

					$('.std_' + student.id + '_a').text(tota);
					$('.std_' + student.id + '_p').text(totp);
					$('.std_' + student.id + '_l').text(totl);
					$('.std_' + student.id + '_r').text(totr);

					let attendanceDays = totp + tota;
					if (attendanceDays > 0) {
						$('.std_' + student.id + '_percent').text(((totp / attendanceDays) * 100).toFixed(1) + '%');
					} else {
						$('.std_' + student.id + '_percent').text('0.0%');
					}
				});
			},

			calcattendance: function () {
				for (let i = 1; i <= this.noofdays; i++) {
					let p = 0, a = 0, l = 0;

					$('.col_dat_' + i).each(function () {
						let val = $(this).text().trim();
						if (val === 'P') p++;
						else if (val === 'A') a++;
						else if (val === 'L') l++;
					});

					$('.p_std_dat_' + i).text(p);
					$('.a_std_dat_' + i).text(a);
					$('.l_std_dat_' + i).text(l);

					let attendanceCount = p + a;
					if (attendanceCount > 0) {
						$('.percent_std_dat_' + i).text(((p / attendanceCount) * 100).toFixed(1) + '%');
					} else {
						$('.percent_std_dat_' + i).text('0.0%');
					}
				}
			}
		}
	});
</script>

@endsection