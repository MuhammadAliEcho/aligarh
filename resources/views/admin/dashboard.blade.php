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
					@can('dashboad.top_content')
						<div class="row">
							<div class="col-md-3 col-sm-6">
								<div class="stats-card text-center">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-users card-icon primary"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="stats-number primary">{{$no_of_students}}</div>
											<div class="stats-label">Total Students</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-6">
								<div class="stats-card text-center">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-user-plus card-icon success"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="stats-number success">{{$no_of_teachers}}</div>
											<div class="stats-label">Total Teachers</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-6">
								<div class="stats-card text-center">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-briefcase card-icon info"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="stats-number info">{{$no_of_employees}}</div>
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
								<div class="stats-card text-center">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-cube card-icon purple"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="stats-number purple">{{$no_of_items}}</div>
											<div class="stats-label">Total Inventory</div>
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
					@can('dashboad.monthly_attendance')
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
					@canany(['dashboad.fee_Collection','dashboad.monthly_expenses'])
						<div class="row">
							@can('dashboad.fee_Collection')
								<div class="col-md-6">
									<div class="chart-container">
										<h3 class="section-title"><i class="fa fa-bar-chart"></i> Fee Collection Status</h3>
										<canvas id="feeChart" height="150"></canvas>
									</div>
								</div>
							@endcan
							@can('dashboad.monthly_expenses')
								<div class="col-md-6">
									<div class="chart-container">
										<h3 class="section-title"><i class="fa fa-area-chart"></i> Monthly Expenses</h3>
										<canvas id="expenseChart" height="150"></canvas>
									</div>
								</div>
							@endcan

						</div>
					@endcan

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

					<!-- Detailed Stats -->
					@canany(['dashboad.inventory_summary', 'dashboad.daily_attendance'])
						<div class="row">
							@can('dashboad.inventory_summary')
								<div class="col-md-6">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title"><i class="fa fa-list"></i> Inventory Summary</h3>
										</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-sm-4 text-center">
													<h4 class="success">{{$no_of_vendors}}</h4>
													<p>Vendors</p>
												</div>
												<div class="col-sm-4 text-center">
													<h4 class="primary">{{$no_of_items}}</h4>
													<p>Items</p>
												</div>
												<div class="col-sm-4 text-center">
													<h4 class="warning">{{$no_of_vouchers}}</h4>
													<p>Vouchers</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							@endcan
							@can('dashboad.inventory_summary')
								<div class="col-md-6">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title"><i class="fa fa-check-circle"></i> Daily Attendance</h3>
										</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-sm-4 text-center">
													<h4 class="success">{{ $daily_attendance['student'] }}%</h4>
													<p>Students</p>
												</div>
												<div class="col-sm-4 text-center">
													<h4 class="info">{{ $daily_attendance['teacher'] }}%</h4>
													<p>Teachers</p>
												</div>
												<div class="col-sm-4 text-center">
													<h4 class="primary">{{ $daily_attendance['employee'] }}%</h4>
													<p>Employees</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							@endcan
						</div>
					@endcan
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
		const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
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

        // Sidebar navigation
        $('.sidebar .nav-link').on('click', function(e) {
            e.preventDefault();
            $('.sidebar .nav-link').removeClass('active');
            $(this).addClass('active');
        });
    </script>
@endsection
