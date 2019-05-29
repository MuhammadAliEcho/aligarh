@extends('admin.layouts.master')

	@section('content')

	@include('admin.includes.side_navbar')

		<div id="page-wrapper" class="gray-bg">

			@include('admin.includes.top_navbar')

			<div class="wrapper wrapper-content animated fadeInRight">
				<div class="row">

					<div class="col-lg-3">
						<div class="widget style1 navy-bg">
							<div class="row">
								<div class="col-xs-4">
									<i class="fa fa-group fa-5x"></i>
								</div>
								<div class="col-xs-8 text-right">
									<span> Students </span>
									<h2 class="font-bold">{{ $no_of_student }}</h2>
								</div>
							</div>
						</div>

						<div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-success pull-right">Today</span>
                                <h5>Attendance</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"></h1>
                                <div class="stat-percent font-bold text-success">{{ round(($student_attendance/$no_of_student)*100, 2) }}%</div>
                                <small>{{ $student_attendance }}</small>
                            </div>
                        </div>

					</div>

					<div class="col-lg-3">
						<div class="widget style1 navy-bg">
							<div class="row">
								<div class="col-xs-4">
									<i class="fa entypo-users fa-5x"></i>
								</div>
								<div class="col-xs-8 text-right">
									<span> Teachers </span>
									<h2 class="font-bold">{{ $no_of_teachers }}</h2>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-3">
						<div class="widget style1 navy-bg">
							<div class="row">
								<div class="col-xs-4">
									<i class="fa fa-user-circle-o fa-5x"></i>
								</div>
								<div class="col-xs-8 text-right">
									<span> Employee </span>
									<h2 class="font-bold">{{ $no_of_employee }}</h2>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
	 
			@include('admin.includes.footercopyright')

		</div>

				
	@endsection

	@section('script')


	@endsection
