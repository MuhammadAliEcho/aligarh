@extends('admin.layouts.master')

  @section('title', 'Students |')

  @section('head')
  <!-- HEAD -->
		<link href="{{ asset('src/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">

	<style type="text/css">
		@media print{
			body {
				padding: 0px 10px;
				margin: 0px;
				font-size: 13px;
			}
			.invoice-title h2, .invoice-title h3 {
				display: inline-block;
			}

			.table > tbody > tr > td, 
			.table > tbody > tr > th {
				border-top: none;
				padding: 3px;
			}

/*			.table > thead > tr > .no-line {
				border-bottom: none;
			}
			.table > tbody > tr > .thick-line {
				border-top: 1px solid;
			}
*/
			.bottom-border {
				border-bottom: 1px solid;
			}


			.table-bordered th,
			.table-bordered td {
				border: 1px solid black !important;
				padding: 0px;
			}   

			.sibling-table > tbody > tr > td {
				padding: 1px;
			}
			.sibling-table > thead > tr > th {
				padding: 2px;
			}
			.sibling-table {
				margin-bottom: 10px;
			}
			a[href]:after {
				content: none;
				/*      content: " (" attr(href) ")";*/
			}
		}
	</style>
  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

		<div id="page-wrapper" class="gray-bg hidden-print">

		  @include('admin.includes.top_navbar')

		  <!-- Heading -->
		  <div class="row wrapper border-bottom white-bg page-heading">
			  <div class="col-lg-8 col-md-6">
				  <h2>Students</h2>
				  <ol class="breadcrumb">
					<li>{{ __("common.home") }}</li>
					<li><a href="{{ URL('students') }}"> Students </a></li>
					<li Class="active">
						<a>Profile</a>
					</li>
					<li Class="active">
						@{{ student.name }}
					</li>
				  </ol>
			  </div>
			  <div class="col-lg-4 col-md-6">
				@include('admin.includes.academic_session')
			  </div>
		  </div>

		  <!-- main Section -->
		<div class="wrapper wrapper-content">
			<div class="row animated fadeInRight">
				<!-- Profile Card -->
				<div class="col-md-4">
					<div class="tw-bg-white tw-rounded-xl tw-shadow-lg tw-overflow-hidden tw-border tw-border-gray-100">
						<!-- Profile Header with Gradient -->
						<div class="tw-bg-gradient-to-br tw-from-blue-500 tw-to-indigo-600 tw-h-32"></div>
						
						<!-- Profile Image -->
						<div class="tw-relative tw--mt-16 tw-mb-4">
							<div class="tw-flex tw-justify-center">
								<img alt="@{{ student.name }}" 
									 class="tw-w-32 tw-h-32 tw-rounded-full tw-border-4 tw-border-white tw-shadow-xl tw-object-cover" 
									 src="{{ URL(($student->image_url == '')? 'img/avatar.jpg' : $student->image_url) }}">
							</div>
						</div>
						
						<!-- Profile Info -->
						<div class="tw-px-6 tw-pb-6 tw-text-center">
							<h3 class="tw-text-2xl tw-font-bold tw-text-gray-800 tw-mb-1">@{{ student.name }}</h3>
							<p class="tw-text-sm tw-font-medium tw-text-indigo-600 tw-mb-2 tw-bg-indigo-50 tw-inline-block tw-px-4 tw-py-1 tw-rounded-full">
								GR: @{{ student.gr_no }}
							</p>
							
							<!-- Status Badge -->
							<div class="tw-mb-3">
								<span v-if="student.active" class="tw-inline-flex tw-items-center tw-gap-1 tw-px-3 tw-py-1 tw-bg-green-50 tw-text-green-700 tw-text-xs tw-font-semibold tw-rounded-full">
									<i class="fa fa-check-circle"></i> Active
								</span>
								<span v-else class="tw-inline-flex tw-items-center tw-gap-1 tw-px-3 tw-py-1 tw-bg-red-50 tw-text-red-700 tw-text-xs tw-font-semibold tw-rounded-full">
									<i class="fa fa-times-circle"></i> Inactive
								</span>
							</div>
							
							<p v-if="student.active == false" class="tw-text-sm tw-text-gray-600 tw-mb-4">
								<b>Date Of Leaving:</b> @{{ student.date_of_leaving }}
							</p>
							
							<!-- Quick Stats -->
							<div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-mt-6 tw-pt-4 tw-border-t tw-border-gray-200">
								<div class="tw-text-center">
									<div class="tw-text-xs tw-text-gray-500 tw-uppercase tw-tracking-wide tw-mb-1">Class</div>
									<div class="tw-text-sm tw-font-semibold tw-text-gray-800">@{{ student.std_class.name }} @{{ student.section.nick_name }}</div>
								</div>
								<div class="tw-text-center">
									<div class="tw-text-xs tw-text-gray-500 tw-uppercase tw-tracking-wide tw-mb-1">Fee</div>
									<div class="tw-text-sm tw-font-semibold tw-text-gray-800">@{{ student.net_amount }} /=</div>
								</div>
							</div>
							
							<!-- Contact Actions -->
							<div class="tw-mt-6 tw-space-y-2">
								<a v-if="student.email" :href="'mailto:' + student.email" 
								   class="tw-flex tw-items-center tw-justify-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-indigo-50 hover:tw-bg-indigo-100 tw-text-indigo-600 tw-rounded-lg tw-transition-colors tw-text-sm tw-font-medium">
									<i class="fa fa-envelope"></i>
									Send Email
								</a>
								<a v-if="student.phone" :href="'tel:' + student.phone" 
								   class="tw-flex tw-items-center tw-justify-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-green-50 hover:tw-bg-green-100 tw-text-green-600 tw-rounded-lg tw-transition-colors tw-text-sm tw-font-medium">
									<i class="fa fa-phone"></i>
									Call Now
								</a>
								<a :href="URL+'/guardians/profile/'+student.guardian.id"
								   class="tw-flex tw-items-center tw-justify-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-purple-50 hover:tw-bg-purple-100 tw-text-purple-600 tw-rounded-lg tw-transition-colors tw-text-sm tw-font-medium">
									<i class="fa fa-users"></i>
									View Guardian
								</a>
							</div>
							
							<!-- Active/Leave Status -->
							<template v-if="student.active && allow_user_leave">
								<div class="tw-mt-6 tw-pt-4 tw-border-t tw-border-gray-200">
									<a href="#" v-on:dblclick="leavingfrm = !leavingfrm" 
									   class="tw-text-sm tw-font-medium tw-text-gray-600 hover:tw-text-gray-800"
									   data-toggle="tooltip" title="DoubleClick to Inactive">
										<i class="fa fa-info-circle"></i> Double-click to mark inactive
									</a>
									<form v-show="leavingfrm" method="post" v-on:submit.prevent="formSubmit($event)" :action="URL+'/students/leave/'+student.id" class="tw-mt-4">
										{{ csrf_field() }}
										<input type="hidden" name="id" v-model="student.id">
										<div class="tw-bg-yellow-50 tw-border tw-border-yellow-200 tw-rounded-lg tw-p-4 tw-mb-4">
											<div class="tw-flex tw-gap-2 tw-mb-2">
												<i class="fa fa-exclamation-triangle tw-text-yellow-600 tw-mt-1"></i>
												<div>
													<h4 class="tw-text-sm tw-font-bold tw-text-yellow-800 tw-mb-1">Important</h4>
													<p class="tw-text-xs tw-text-yellow-700 tw-leading-relaxed">
														Once the Date of Leaving is set, the student becomes inactive and cannot be reactivated.
														<br><b>To rejoin,</b> a new registration form is required.
													</p>
												</div>
											</div>
										</div>
										<div class="tw-mb-4">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-700 tw-mb-2 tw-block">Cause Of Leaving</label>
											<textarea class="form-control tw-text-sm" name="cause_of_leaving" rows="3" style="resize: none"></textarea>
										</div>
										<div class="tw-mb-4">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-700 tw-mb-2 tw-block">Date of Leaving</label>
											<input type="text" name="date_of_leaving" v-model="student.date_of_leaving" autocomplete="off" placeholder="date of leaving" class="form-control tw-text-sm" readonly="true">
										</div>
										<div v-if="student.date_of_leaving" class="tw-mb-2">
											<button v-if="loading" class="tw-w-full tw-px-4 tw-py-2 tw-bg-indigo-500 tw-text-white tw-rounded-lg tw-text-sm tw-font-medium" disabled="true" type="submit">
												<span class="fa fa-pulse fa-spin fa-spinner"></span> Loading...
											</button>
											<button v-else class="tw-w-full tw-px-4 tw-py-2 tw-bg-indigo-600 hover:tw-bg-indigo-700 tw-text-white tw-rounded-lg tw-text-sm tw-font-medium tw-transition-colors" type="submit">
												{{ __("modules.buttons_save") }}
											</button>
										</div>
									</form>
								</div>
							</template>
						</div>
					</div>

					<!-- Siblings Card -->
					<div v-if="siblings.length" class="tw-bg-white tw-rounded-xl tw-shadow-lg tw-overflow-hidden tw-border tw-border-gray-100 tw-mt-4">
						<div class="tw-bg-gradient-to-r tw-from-gray-50 tw-to-gray-100 tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
							<h5 class="tw-text-lg tw-font-bold tw-text-gray-800 tw-flex tw-items-center tw-gap-2">
								<i class="fa fa-users tw-text-indigo-600"></i>
								Siblings
							</h5>
						</div>
						<div class="tw-p-4">
							<div class="tw-space-y-2">
								<div v-for="(std, k) in siblings" :key="std.id" 
									 class="tw-flex tw-items-center tw-gap-3 tw-p-3 tw-bg-gray-50 hover:tw-bg-gray-100 tw-rounded-lg tw-transition-colors">
									<div class="tw-w-8 tw-h-8 tw-rounded-full tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0">
										<span class="tw-text-sm tw-font-bold tw-text-indigo-600">@{{ k + 1 }}</span>
									</div>
									<div class="tw-flex-1">
										<a :href="'/students/profile/' + std.id" class="tw-text-sm tw-font-semibold tw-text-gray-800 hover:tw-text-indigo-600 tw-transition-colors">
											@{{ std.name }}
										</a>
										<p class="tw-text-xs tw-text-gray-500">GR: @{{ std.gr_no }}</p>
									</div>
									<a :href="'/students/profile/' + std.id" class="tw-text-indigo-600 hover:tw-text-indigo-700">
										<i class="fa fa-arrow-right"></i>
									</a>
								</div>
							</div>
						</div>
					</div>

					<!-- Certificates Card -->
					<div class="tw-bg-white tw-rounded-xl tw-shadow-lg tw-overflow-hidden tw-border tw-border-gray-100 tw-mt-4">
						<div class="tw-bg-gradient-to-r tw-from-gray-50 tw-to-gray-100 tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
							<h5 class="tw-text-lg tw-font-bold tw-text-gray-800 tw-flex tw-items-center tw-gap-2">
								<i class="fa fa-certificate tw-text-indigo-600"></i>
								Certificates
							</h5>
						</div>
						<div class="tw-p-4">
							<div v-if="student.certificates.length" class="tw-space-y-2 tw-mb-4">
								<div v-for="certificate in student.certificates" 
									 class="tw-flex tw-items-center tw-justify-between tw-p-3 tw-bg-gray-50 tw-rounded-lg">
									<span class="tw-text-sm tw-font-medium tw-text-gray-800">@{{ certificate.title }}</span>
									@can('students.certificate.create')
									<a :href="URL+'/students/certificate/update?certificate_id='+certificate.id" 
									   class="tw-text-red-600 hover:tw-text-red-700 tw-transition-colors"
									   title="view" data-toggle="tooltip">
										<i class="fa fa-file-pdf-o tw-text-lg"></i>
									</a>
									@endcan
								</div>
							</div>
							@can('students.certificate.create')
							<form :action="URL+'/students/certificate/new'" method="get">
								<input type="hidden" name="student_id" v-model="student.id">
								<button class="tw-w-full tw-px-4 tw-py-2 tw-bg-indigo-600 hover:tw-bg-indigo-700 tw-text-white tw-rounded-lg tw-text-sm tw-font-medium tw-transition-colors">
									<i class="fa fa-plus-circle"></i> Create Certificate
								</button>
							</form>
							@endcan
						</div>
					</div>

				</div>
				
				<!-- Details Card -->
				<div class="col-md-8">
					<div class="tw-bg-white tw-rounded-xl tw-shadow-lg tw-overflow-hidden tw-border tw-border-gray-100">
						<!-- Header -->
						<div class="tw-bg-gradient-to-r tw-from-gray-50 tw-to-gray-100 tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
							<div class="tw-flex tw-items-center tw-justify-between">
								<h5 class="tw-text-lg tw-font-bold tw-text-gray-800 tw-flex tw-items-center tw-gap-2">
									<i class="fa fa-user-circle tw-text-indigo-600"></i>
									Student Details
								</h5>
								<a v-on:click.stop.prevent="print()" 
								   class="tw-px-4 tw-py-2 tw-bg-white tw-border tw-border-gray-300 hover:tw-bg-gray-50 tw-text-gray-700 tw-rounded-lg tw-text-sm tw-font-medium tw-transition-colors tw-cursor-pointer"
								   title="Profile Print" data-toggle="tooltip">
									<i class="fa fa-print"></i> Print
								</a>
							</div>
						</div>
						
						<!-- Details Grid -->
						<div class="tw-p-6">
							<div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6">
								
								<!-- Full Name -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-blue-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-blue-100 tw-transition-colors">
											<i class="fa fa-user tw-text-blue-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Full Name</label>
											<p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">@{{ student.name }}</p>
										</div>
									</div>
								</div>
								
								<!-- Father Name -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-cyan-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-cyan-100 tw-transition-colors">
											<i class="fa fa-male tw-text-cyan-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Father Name</label>
											<p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">@{{ student.father_name }}</p>
										</div>
									</div>
								</div>
								
								<!-- Religion -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-teal-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-teal-100 tw-transition-colors">
											<i class="fa fa-book tw-text-teal-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Religion</label>
											<p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">@{{ student.religion }}</p>
										</div>
									</div>
								</div>
								
								<!-- GR NO -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-indigo-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-indigo-100 tw-transition-colors">
											<i class="fa fa-id-card tw-text-indigo-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">GR NO</label>
											<p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">@{{ student.gr_no }}</p>
										</div>
									</div>
								</div>
								
								<!-- Gender -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-purple-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-purple-100 tw-transition-colors">
											<i class="fa fa-venus-mars tw-text-purple-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Gender</label>
											<p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">@{{ student.gender }}</p>
										</div>
									</div>
								</div>
								
								<!-- Date of Birth -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-pink-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-pink-100 tw-transition-colors">
											<i class="fa fa-birthday-cake tw-text-pink-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Date Of Birth</label>
											<p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">@{{ student.date_of_birth }}</p>
										</div>
									</div>
								</div>
								
								<!-- Date of Admission -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-green-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-green-100 tw-transition-colors">
											<i class="fa fa-calendar-check-o tw-text-green-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Date Of Admission</label>
											<p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">@{{ student.date_of_admission }}</p>
										</div>
									</div>
								</div>
								
								<!-- Date of Enrolled -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-lime-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-lime-100 tw-transition-colors">
											<i class="fa fa-calendar-plus-o tw-text-lime-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Date Of Enrolled</label>
											<p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">@{{ student.date_of_enrolled }}</p>
										</div>
									</div>
								</div>
								
								<!-- Place of Birth -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-orange-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-orange-100 tw-transition-colors">
											<i class="fa fa-map-marker tw-text-orange-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Place Of Birth</label>
											<p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">@{{ student.place_of_birth }}</p>
										</div>
									</div>
								</div>
								
								<!-- Last School -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-amber-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-amber-100 tw-transition-colors">
											<i class="fa fa-university tw-text-amber-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Last Attend School</label>
											<p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">@{{ student.last_school || 'N/A' }}</p>
										</div>
									</div>
								</div>
								
								<!-- Seeking Class -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-sky-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-sky-100 tw-transition-colors">
											<i class="fa fa-level-up tw-text-sky-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Seeking Class</label>
											<p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">@{{ student.seeking_class }}</p>
										</div>
									</div>
								</div>
								
								<!-- Receipt No -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-violet-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-violet-100 tw-transition-colors">
											<i class="fa fa-file-text tw-text-violet-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Receipt No</label>
											<p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">@{{ student.receipt_no }}</p>
										</div>
									</div>
								</div>
								
								<!-- Class -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-blue-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-blue-100 tw-transition-colors">
											<i class="fa fa-graduation-cap tw-text-blue-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Class</label>
											<p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">@{{ student.std_class.name }} @{{ student.section.nick_name }}</p>
										</div>
									</div>
								</div>
								
								<!-- Guardian -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-purple-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-purple-100 tw-transition-colors">
											<i class="fa fa-users tw-text-purple-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Guardian</label>
											<p class="tw-text-sm tw-font-medium tw-mt-1">
												<a :href="URL+'/guardians/profile/'+student.guardian.id" class="tw-text-indigo-600 hover:tw-text-indigo-700 tw-transition-colors">
													@{{ student.guardian.name }} (@{{ student.guardian_relation }})
												</a>
											</p>
										</div>
									</div>
								</div>
								
								<!-- Email -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-red-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-red-100 tw-transition-colors">
											<i class="fa fa-envelope tw-text-red-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Email</label>
											<p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1 tw-break-all">@{{ student.email || 'Not provided' }}</p>
										</div>
									</div>
								</div>
								
								<!-- Contact -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-green-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-green-100 tw-transition-colors">
											<i class="fa fa-phone tw-text-green-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Contact</label>
											<p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">@{{ student.phone }}</p>
										</div>
									</div>
								</div>
								
								<!-- Fee -->
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-emerald-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-emerald-100 tw-transition-colors">
											<i class="fa fa-money tw-text-emerald-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Fee</label>
											<p class="tw-text-sm tw-font-bold tw-text-emerald-600 tw-mt-1">@{{ student.net_amount }} /=</p>
										</div>
									</div>
								</div>
								
							</div>
							
							<!-- Address - Full Width -->
							<div class="tw-mt-6 tw-pt-6 tw-border-t tw-border-gray-200">
								<div class="tw-group">
									<div class="tw-flex tw-items-start tw-gap-3">
										<div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-orange-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-orange-100 tw-transition-colors">
											<i class="fa fa-map-marker tw-text-orange-600"></i>
										</div>
										<div class="tw-flex-1">
											<label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Address</label>
											<p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1 tw-leading-relaxed">@{{ student.address }}</p>
										</div>
									</div>
								</div>
							</div>
							
							<!-- Action Button -->
							@can('students.interview.update.create')
							<div class="tw-mt-6">
								<a :href="URL+'/students/interview/'+student.id" 
								   class="tw-flex tw-items-center tw-justify-center tw-gap-2 tw-px-6 tw-py-3 tw-bg-indigo-600 hover:tw-bg-indigo-700 tw-text-white tw-rounded-lg tw-text-sm tw-font-medium tw-transition-colors">
									<i class="fa fa-podcast"></i> Parent Interview
								</a>
							</div>
							@endcan
						</div>
					</div>
				</div>
			</div>
		</div>

		  

		</div>

		<div id="student_profile_printable" class="visible-print">
			@include('admin.printable.include.student_profile')
		</div>

	@endsection

	@section('script')

	<!-- Data picker -->
	<script src="{{ asset('src/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>

	@endsection

	@section('vue')
	<script type="text/javascript">
		var app = new Vue({
			el: "#app",
			data: {
				URL: "{{ URL('/') }}",
				student: {!! json_encode($student, JSON_NUMERIC_CHECK) !!},
				leavingfrm: false,
				loading: false,
				allow_user_leave: {{ Auth::user()->hasPermissionTo('students.leave') ? 'true' : 'false' }},
				allow_user_certificate: {{ Auth::user()->hasPermissionTo('students.certificate.get') ? 'true' : 'false' }},
			},
			mounted: function(){
				$("[data-toggle='tooltip']").on('mouseenter', function(){
						$(this).tooltip('show');
					}).mouseleave(function(){
						$(this).tooltip('destroy');
					});
//				window.print();
			},
			updated: function(){
				$('input[name="date_of_leaving"]').datepicker({
						format: 'yyyy-mm-dd',
						keyboardNavigation: false,
						forceParse: false,
						autoclose: true,

						todayHighlight: true
					}).change(function(){
						app.student.date_of_leaving = $(this).val();
					});
			},
			computed: {
				siblings: function(){
					return this.student.guardian.student;
/*					vm = this;
					return	_.filter(this.student.guardian.student, function(std){
								return std.id !== vm.student.id;
							});*/
				}
			},
			methods: {
				formSubmit: function(e){
					this.loading = true;
					$.ajax({
					type: e.target.method,
					url:  e.target.action,
					data: $(e.target).serialize(),
					success: function(dta){
						msg = dta.toastrmsg;
						toastr.options = {
							closeButton: true,
							progressBar: true,
							showMethod: 'slideDown',
							timeOut: 8000
						};
						toastr[msg.type](msg.msg, msg.title);

						if(dta.updated){
							app.student.active = 0;
						}

						app.loading = false;
					},
					error: function(){
							alert("failure");
							app.loading = false;
						}
					});
				},
				print: 	function(){
					window.print();
				}
			}

		});
	</script>
	@endsection

