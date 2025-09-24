@extends('admin.layouts.master')
@section('head')
	<style>
		body {
			background-color: #f8f9fa;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}
		.navbar-brand {
			font-weight: bold;
			font-size: 20px;
		}
		.stats-card {
			background: white;
			border-radius: 8px;
			padding: 20px;
			margin-bottom: 20px;
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
			transition: transform 0.2s;
		}
		.stats-card:hover {
			transform: translateY(-2px);
			box-shadow: 0 4px 8px rgba(0,0,0,0.15);
		}
		.stats-number {
			font-size: 2.5em;
			font-weight: bold;
			margin-bottom: 5px;
		}
		.stats-label {
			color: #666;
			font-size: 14px;
			text-transform: uppercase;
		}
		.card-icon {
			font-size: 2.5em;
			opacity: 0.8;
		}
		.primary { color: #007bff; }
		.success { color: #28a745; }
		.info { color: #17a2b8; }
		.warning { color: #ffc107; }
		.danger { color: #dc3545; }
		.purple { color: #6f42c1; }
		.orange { color: #fd7e14; }
		.teal { color: #20c997; }

		.sidebar {
			background: #343a40;
			min-height: 100vh;
			padding: 20px 0;
		}
		.sidebar .nav-link {
			color: #adb5bd;
			padding: 10px 20px;
			margin: 2px 0;
			border-radius: 0;
		}
		.sidebar .nav-link:hover {
			background: #495057;
			color: white;
		}
		.sidebar .nav-link.active {
			background: #007bff;
			color: white;
		}
		.main-content {
			padding: 20px;
		}
		.chart-container {
			background: white;
			border-radius: 8px;
			padding: 20px;
			margin-bottom: 20px;
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
		}
		.section-title {
			color: #343a40;
			margin-bottom: 20px;
			padding-bottom: 10px;
			border-bottom: 2px solid #007bff;
		}
		.quick-actions {
			background: white;
			border-radius: 8px;
			padding: 20px;
			margin-bottom: 20px;
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
		}
		.btn-action {
			margin: 5px;
			padding: 10px 20px;
		}
		.recent-activity {
			background: white;
			border-radius: 8px;
			padding: 20px;
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
		}
		.activity-item {
			padding: 10px 0;
			border-bottom: 1px solid #eee;
		}
		.activity-item:last-child {
			border-bottom: none;
		}
		.activity-time {
			color: #666;
			font-size: 12px;
		}
		.w-100{
			widows: 100%;
		}
		.d-none {
			display: none;
		}
		.header{
			padding-bottom: 9px;
    		border-bottom: 1px solid #eee;
			margin-right: 0;
		}
		.text-lg{
			font-size: large
		}
		.stats-card .panel-heading {
			padding: 0px;
		}
	</style>
	{{-- notic-board TimeLines --}}
	<style>
		.notic-board-container {
			background: linear-gradient(135deg, #ffffff, #f8f9fa);
			border-radius: 12px;
			padding: 30px;
			margin-bottom: 30px;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
		}

		.section-title {
			font-size: 1.8rem;
			color: #1a1a1a;
			margin-bottom: 30px;
			display: flex;
			align-items: center;
			gap: 10px;
			font-weight: 600;
		}

		.timeline-modern {
			position: relative;
			display: flex;
			flex-direction: row;
			overflow-x: auto;
			padding: 20px 20px;
			justify-content: flex-start;
			white-space: nowrap;
			scrollbar-width: thin;
			scrollbar-color: #007bff #f8f9fa;
		}

		.timeline-modern::-webkit-scrollbar {
			height: 8px;
		}

		.timeline-modern::-webkit-scrollbar-thumb {
			background: #007bff;
			border-radius: 4px;
		}

		/* .timeline-modern::before {
			content: '';
			position: absolute;
			top: 18%;
			left: 0;
			height: 6px;
			width: 100%;
			background: linear-gradient(to right, #007bff, #00c4ff);
			z-index: 1;
		} */

		.timeline-node {
			position: relative;
			width: 324px;
			flex-shrink: 0;
			margin: 20px 30px 0px 0px;
			text-align: center;
		}

		.timeline-dot {
			width: 20px;
			height: 20px;
			background: linear-gradient(45deg, #007bff, #00c4ff);
			border-radius: 50%;
			z-index: 2;
			position: absolute;
			top: 4%;
			left: 50%;
			transform: translate(-50%, -50%);
			border: 4px solid #fff;
			transition: transform 0.3s ease, box-shadow 0.3s ease;
		}

		.timeline-node:hover .timeline-dot {
			transform: translate(-50%, -50%) scale(1.3);
			box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
		}

		.timeline-branch {
			margin-top: 35px;
		}

		.notice-card {
			background: #fff;
			padding: 20px;
			border-radius: 8px;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
			min-height: 160px;
			transition: transform 0.3s ease, box-shadow 0.3s ease;
			display: flex;
			flex-direction: column;
			gap: 12px;
		}

		.notice-card:hover {
			transform: translateY(-8px);
			box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
		}

		.notice-card h4 {
			font-size: 1.25rem;
			color: #1a1a1a;
			margin: 0;
			display: flex;
			align-items: center;
			gap: 8px;
			font-weight: 500;
		}

		.notice-card p {
			margin: 8px 0;
			color: #4a4a4a;
			line-height: 1.5;
			flex-grow: 1;
			font-size: 0.95rem;
			text-wrap: auto;
		}

		.notice-card small {
			color: #6c757d;
			font-size: 0.85rem;
			font-style: italic;
		}

		.timeline-date {
			position: absolute;
			left: 50%;
			margin-top: 25px; 
			transform: translate(-50%, -60px);
			font-size: 0.9rem;
			color: #1a1a1a;
			font-weight: 500;
			background: #fff;
			padding: 4px 10px;
			border-radius: 4px;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
			z-index: 2;
		}

		@media (max-width: 768px) {
			.notic-board-container {
				padding: 20px;
				/* overflow-x: auto; */
			}

			.timeline-modern {
				padding: 20px 10px;
				overflow-x: auto;
				min-width: 600px;
				position: relative;
			}

			.timeline-modern::before {
				top: 12%;
				height: 4px;
				width: 100%;
			}

			.timeline-node {
				width: 220px;
				margin: 0 15px;
				overflow: scroll !important;  
			}

			.notice-card {
				min-height: 140px;
				padding: 15px;
			}

			.timeline-date {
				font-size: 0.8rem;
				padding: 3px 8px;
				transform: translate(-50%, -50px);
			}
		}

		@media (max-width: 1024px) {

			.timeline-modern {
				overflow: scroll;
			}

			.timeline-modern::before {
				overflow: scroll;
				width: 226%;
			}
		}
	</style>
@endsection
@section('content')
  	@include('admin.includes.side_navbar')
	
	<div id="page-wrapper" class="gray-bg">
		@include('admin.includes.top_navbar')
		<div class="container-fluid">
			<div class="w-100">
				<div class="main-content">
					<div class="row">
						<div class="col-lg-4 col-md-6">
							<h1 class="header">Dashboard</h1>
						</div>	
						@can('user-settings.change.session')
							<div class="col-lg-4 col-md-6" style="float:right;">
								@include('admin.includes.academic_session')
							</div>
						@endcan
					</div>
					@can('dashboard.top_content')
						<div class="row">
							<div class="col-md-3 col-sm-6">
								<div class="stats-card text-center">
									<div class="row">
										<div class="col-xs-3">
											<div>
												<i class="fa fa-users card-icon primary"></i>
											</div>
											<div>
												<span class="text-lg" data-placement="right" data-toggle="tooltip" title="Total Capacity" style="color: #007bff;">
													{{ $student_capacity }}
												</span>
											</div>
											<div>
												<span class="text-lg" data-placement="right" data-toggle="tooltip" title="Attendance" style="color: #007bffb5;">
													{{ $daily_attendance['student']['percent'] }}%
												</span>
											</div>
										</div>
										<div class="col-xs-9 text-right">
											<div class="stats-number primary"><span data-placement="top" data-toggle="tooltip" title="Today Presents" style="color: #007bffb5;">{{$daily_attendance['student']['present']}}</span>/{{$no_of_students}}</div>
											<div class="stats-label">Total Students</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-6">
								<div class="stats-card text-center">
									<div class="row">
										<div class="col-xs-3">
											<div class="mb-10">
												<i class="fa fa-user-plus card-icon success"></i>
											</div>
											<div>
												<span class="text-lg" data-placement="right" data-toggle="tooltip" title="Attendance" style="color: #28a7458f;">{{ $daily_attendance['teacher']['percent'] }}%</span>
											</div>
										</div>
										<div class="col-xs-9 text-right">
											<div class="stats-number success"><span data-placement="top" data-toggle="tooltip" title="Today Presents" style="color: #28a7458f;">{{$daily_attendance['teacher']['present']}}</span>/{{$no_of_teachers}}</div>
											<div class="stats-label">Total Teachers</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-6">
								<div class="stats-card text-center">
									<div class="row">
										<div class="col-xs-3">
											<div class="mb-10">
												<i class="fa fa-briefcase card-icon info"></i>
											</div>
											<span class="text-lg" data-placement="right" data-toggle="tooltip" title="Attendance" style="color: #17a2b8ad;">{{ $daily_attendance['employee']['percent'] }}%</span>
										</div>
										<div class="col-xs-9 text-right">
											<div class="stats-number info"><span data-placement="top" data-toggle="tooltip" title="Today Presents" style="color: #17a2b8ad;">{{$daily_attendance['employee']['present']}}</span>/{{$no_of_employees}}</div>
											<div class="stats-label">Total Employees</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-6">
								<div class="stats-card text-center">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-home card-icon warning"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="stats-number warning">{{$no_of_guardians}}</div>
											<div class="stats-label">Total Guardians</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3 col-sm-6">
								<div class="stats-card text-center">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-building card-icon danger"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="stats-number danger">{{$no_of_classes}}</div>
											<div class="stats-label">Total Classes</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-6">
								<div class="stats-card text-center" style="padding-top:10px !important ">
									<div class="panel-body" style="padding: 0px 15px 0 15px !important;">
										<div class="row">
											<!-- Vendors -->
											<div class="col-xs-4">
												<i class="fa fa-users card-icon green"></i>
												<div class="stats-number green" style="font-size: 16px;">{{$no_of_vendors}}</div>
												<div class="stats-label">Vendors</div>
											</div>

											<!-- Items -->
											<div class="col-xs-4">
												<i class="fa fa-cube card-icon purple"></i>
												<div class="stats-number purple" style="font-size: 16px;">{{$no_of_items}}</div>
												<div class="stats-label">Items</div>
											</div>

											<!-- Vouchers -->
											<div class="col-xs-4">
												<i class="fa fa-ticket card-icon orange"></i>
												<div class="stats-number orange" style="font-size: 16px;">{{$no_of_vouchers}}</div>
												<div class="stats-label">Vouchers</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-6">
								<div class="stats-card text-center">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-book card-icon orange"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="stats-number orange">{{$no_of_books}}</div>
											<div class="stats-label">Library Books</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-6">
								<div class="stats-card text-center">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-users card-icon teal"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="stats-number teal">{{$no_of_users}}</div>
											<div class="stats-label">Total Users</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					@endcan
					@can('dashboard.timeline')
						@if ($timelines->count() > 0)
							<div class="row">
								<div class="col-md-12">
									<div class="notic-board-container">
										<h3 class="section-title">
											<i class="fa fa-clipboard"></i> Notice Board
										</h3>
										<div class="timeline-modern">
											@foreach ($timelines as $timeline)
												<div class="timeline-node">
													<div class="timeline-date">{{ $timeline->timeline_date}}</div>
													<div class="timeline-dot"></div>
													<div class="timeline-branch">
														<div class="notice-card">
															<h4><i class="fa fa-bullhorn"></i> {{ $timeline->title}}</h4>
															<p>{{ $timeline->notice }}</p>
															<small>{{$timeline->till_date_formatted}}</small>
														</div>
													</div>
												</div>
											@endforeach
										</div>
									</div>
								</div>
							</div>
						@endif
					@endcan
					@can('dashboard.monthly_attendance')
						<!-- Charts Section -->
						<div class="row">
							<div class="col-md-12">
								<div class="chart-container">
									<h3 class="section-title"><i class="fa fa-line-chart"></i> Monthly Attendance Trends</h3>
									<canvas id="attendanceChart" height="100"></canvas>
								</div>
							</div>
						</div>
					@endcan
					@canany(['dashboard.fee_Collection','dashboard.monthly_expenses'])
						<div class="row">
							@can('dashboard.fee_Collection')
								<div class="col-md-6">
									<div class="chart-container">
										<h3 class="section-title"><i class="fa fa-bar-chart"></i> Fee Collection Status</h3>
										<canvas id="feeChart" height="150"></canvas>
									</div>
								</div>
							@endcan
							@can('dashboard.monthly_expenses')
								<div class="col-md-6">
									<div class="chart-container">
										<h3 class="section-title"><i class="fa fa-area-chart"></i> Monthly Expenses</h3>
										<canvas id="expenseChart" height="150"></canvas>
									</div>
								</div>
							@endcan

						</div>
					@endcanany

					<!-- Quick Actions -->
					{{-- <div class="row d-none">
						<div class="col-md-8">
							<div class="quick-actions">
								<h3 class="section-title"><i class="fa fa-bolt"></i> Quick Actions</h3>
								<div class="row">
									<div class="col-md-4">
										<h4>Student Management</h4>
										<button class="btn btn-primary btn-action btn-block"><i class="fa fa-plus"></i> Add Student</button>
										<button class="btn btn-info btn-action btn-block"><i class="fa fa-check"></i> Mark Attendance</button>
										<button class="btn btn-success btn-action btn-block"><i class="fa fa-file-text"></i> View Results</button>
									</div>
									<div class="col-md-4">
										<h4>Academic</h4>
										<button class="btn btn-warning btn-action btn-block"><i class="fa fa-calendar"></i> Schedule Exam</button>
										<button class="btn btn-danger btn-action btn-block"><i class="fa fa-book"></i> Add Library Book</button>
										<button class="btn btn-purple btn-action btn-block" style="background-color: #6f42c1; border-color: #6f42c1; color: white;"><i class="fa fa-bullhorn"></i> Post Notice</button>
									</div>
									<div class="col-md-4">
										<h4>Finance</h4>
										<button class="btn btn-success btn-action btn-block"><i class="fa fa-money"></i> Collect Fee</button>
										<button class="btn btn-info btn-action btn-block"><i class="fa fa-calculator"></i> Add Expense</button>
										<button class="btn btn-warning btn-action btn-block"><i class="fa fa-file-pdf-o"></i> Generate Report</button>
									</div>
								</div>
							</div>
						</div>
					</div> --}}
				</div>
			</div>
		</div>
    </div>
@endsection
@section('script')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js"></script>
	<script>
		const studentAttendance = @json($student_attendance);
		const teacherAttendance = @json($teacher_attendance);
    	const employeeAttendance = @json($employee_attendance);
	</script>
	<script>

		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();
		});	
		const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

		@can('dashboard.monthly_attendance')
        // Attendance Trend Chart
        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    	new Chart(attendanceCtx, {
        type: 'line',
			data: {
				labels: months,
				datasets: [{
					label: 'Students',
					data: studentAttendance,
					borderColor: '#007bff',
					backgroundColor: 'rgba(0, 123, 255, 0.1)',
					tension: 0.4
				}, {
					label: 'Teachers',
					data: teacherAttendance,
					borderColor: '#28a745',
					backgroundColor: 'rgba(40, 167, 69, 0.1)',
					tension: 0.4
				}, {
					label: 'Employees',
					data: employeeAttendance,
					borderColor: '#17a2b8',
					backgroundColor: 'rgba(23, 162, 184, 0.1)',
					tension: 0.4
				}]
			},
			options: {
				responsive: true,
				plugins: {
					legend: {
						position: 'top',
					}
				},
				scales: {
					y: {
						beginAtZero: false,
						min: 0,
						suggestedMax: 100
					}
				}
			}
		});
		@endcan

		@canany(['dashboard.fee_Collection','dashboard.monthly_expenses'])
			@can('dashboard.fee_Collection')
				// Fee Collection Chart
				const feeCtx = document.getElementById('feeChart').getContext('2d');
				new Chart(feeCtx, {
				type: 'bar',
				data: {
					labels: months,
					datasets: [{
						label: 'Collected',
						data: @json($fee_collections['collected']),
						backgroundColor: '#28a745'
					}, {
						label: 'Pending',
						data: @json($fee_collections['pending']),
						backgroundColor: '#dc3545'
					}]
				},
				options: {
					responsive: true,
					scales: {
						x: { stacked: true },
						y: { stacked: true }
					}
				}
				});
			@endcan
			@can('dashboard.monthly_expenses')
				// Expense Chart
				const expenseCtx = document.getElementById('expenseChart').getContext('2d');
				new Chart(expenseCtx, {
					type: 'line',
					data: {
						labels: months,
						datasets: [
							{
								label: 'Salary',
								data: @json($expense['Salary']),
								borderColor: '#007bff',
								backgroundColor: 'rgba(0, 123, 255, 0.1)',
								fill: true
							},
							{
								label: 'Utilities',
								data: @json($expense['Utilities']),
								borderColor: '#28a745',
								backgroundColor: 'rgba(40, 167, 69, 0.1)',
								fill: true
							},
							{
								label: 'Maintenance',
								data: @json($expense['Maintenance']),
								borderColor: '#ffc107',
								backgroundColor: 'rgba(255, 193, 7, 0.1)',
								fill: true
							},
							{
								label: 'Others',
								data: @json($expense['Others']),
								borderColor: '#dc3545',
								backgroundColor: 'rgba(220, 53, 69, 0.1)',
								fill: true
							}
						]
					},
					options: {
						responsive: true,
						plugins: {
							legend: { position: 'top' }
						},
						scales: {
							y: {
								beginAtZero: true
							}
						}
					}
				});
			@endcan
		@endcanany

        // Sidebar navigation
        $('.sidebar .nav-link').on('click', function(e) {
            e.preventDefault();
            $('.sidebar .nav-link').removeClass('active');
            $(this).addClass('active');
        });
    </script>
@endsection
