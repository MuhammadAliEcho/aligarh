@extends('admin.layouts.master')

  @section('title', __('modules.pages_employee_title').' |')

  @section('head')
  <!-- HEAD -->
  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('admin.includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Employees</h2>
                  <ol class="breadcrumb">
                    <li>{{ __("common.home") }}</li>
                    <li><a href="{{ URL('employee') }}"> Employee </a></li>
                      <li Class="active">
                          <a>Profile</a>
                      </li>
                      <li Class="active">
                          {{ $employee->name }}
                      </li>
                  </ol>
              </div>
              @can('user-settings.change.session')
              <div class="col-lg-4 col-md-6">
                @include('admin.includes.academic_session')
              </div>
              @endcan
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
                                <img alt="{{ $employee->name }}" 
                                     class="tw-w-32 tw-h-32 tw-rounded-full tw-border-4 tw-border-white tw-shadow-xl tw-object-cover" 
                                     src="{{ URL(($employee->img_url == '')? 'img/avatar.jpg' : $employee->img_url) }}">
                            </div>
                        </div>
                        
                        <!-- Profile Info -->
                        <div class="tw-px-6 tw-pb-6 tw-text-center">
                            <h3 class="tw-text-2xl tw-font-bold tw-text-gray-800 tw-mb-1">{{ $employee->name }}</h3>
                            <p class="tw-text-sm tw-font-medium tw-text-indigo-600 tw-mb-3 tw-bg-indigo-50 tw-inline-block tw-px-4 tw-py-1 tw-rounded-full">
                                {{ $employee->role }}
                            </p>
                            
                            <!-- Quick Stats -->
                            <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-mt-6 tw-pt-4 tw-border-t tw-border-gray-200">
                                <div class="tw-text-center">
                                    <div class="tw-text-xs tw-text-gray-500 tw-uppercase tw-tracking-wide tw-mb-1">Qualification</div>
                                    <div class="tw-text-sm tw-font-semibold tw-text-gray-800">{{ $employee->qualification ?: 'N/A' }}</div>
                                </div>
                                <div class="tw-text-center">
                                    <div class="tw-text-xs tw-text-gray-500 tw-uppercase tw-tracking-wide tw-mb-1">Salary</div>
                                    <div class="tw-text-sm tw-font-semibold tw-text-gray-800">{{ $employee->salary }} /=</div>
                                </div>
                            </div>
                            
                            <!-- Contact Actions -->
                            <div class="tw-mt-6 tw-space-y-2">
                                @if($employee->email)
                                <a href="mailto:{{ $employee->email }}" 
                                   class="tw-flex tw-items-center tw-justify-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-indigo-50 hover:tw-bg-indigo-100 tw-text-indigo-600 tw-rounded-lg tw-transition-colors tw-text-sm tw-font-medium">
                                    <i class="fa fa-envelope"></i>
                                    Send Email
                                </a>
                                @endif
                                @if($employee->phone)
                                <a href="tel:{{ $employee->phone }}" 
                                   class="tw-flex tw-items-center tw-justify-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-green-50 hover:tw-bg-green-100 tw-text-green-600 tw-rounded-lg tw-transition-colors tw-text-sm tw-font-medium">
                                    <i class="fa fa-phone"></i>
                                    Call Now
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Details Card -->
                <div class="col-md-8">
                    <div class="tw-bg-white tw-rounded-xl tw-shadow-lg tw-overflow-hidden tw-border tw-border-gray-100">
                        <!-- Header -->
                        <div class="tw-bg-gradient-to-r tw-from-gray-50 tw-to-gray-100 tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                            <h5 class="tw-text-lg tw-font-bold tw-text-gray-800 tw-flex tw-items-center tw-gap-2">
                                <i class="fa fa-user-circle tw-text-indigo-600"></i>
                                Employee Details
                            </h5>
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
                                            <p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">{{ $employee->name }}</p>
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
                                            <p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">{{ ucfirst($employee->gender) }}</p>
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
                                            <p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">{{ $employee->religion }}</p>
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
                                            <label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Email Address</label>
                                            <p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1 tw-break-all">{{ $employee->email ?: 'Not provided' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Phone -->
                                <div class="tw-group">
                                    <div class="tw-flex tw-items-start tw-gap-3">
                                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-green-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-green-100 tw-transition-colors">
                                            <i class="fa fa-phone tw-text-green-600"></i>
                                        </div>
                                        <div class="tw-flex-1">
                                            <label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Contact Number</label>
                                            <p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">{{ $employee->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Role -->
                                <div class="tw-group">
                                    <div class="tw-flex tw-items-start tw-gap-3">
                                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-indigo-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-indigo-100 tw-transition-colors">
                                            <i class="fa fa-briefcase tw-text-indigo-600"></i>
                                        </div>
                                        <div class="tw-flex-1">
                                            <label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Employee Role</label>
                                            <p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">{{ $employee->role }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Qualification -->
                                <div class="tw-group">
                                    <div class="tw-flex tw-items-start tw-gap-3">
                                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-yellow-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-yellow-100 tw-transition-colors">
                                            <i class="fa fa-graduation-cap tw-text-yellow-600"></i>
                                        </div>
                                        <div class="tw-flex-1">
                                            <label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Qualification</label>
                                            <p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1">{{ $employee->qualification ?: 'Not specified' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Salary -->
                                <div class="tw-group">
                                    <div class="tw-flex tw-items-start tw-gap-3">
                                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-emerald-50 tw-flex tw-items-center tw-justify-center tw-flex-shrink-0 group-hover:tw-bg-emerald-100 tw-transition-colors">
                                            <i class="fa fa-money tw-text-emerald-600"></i>
                                        </div>
                                        <div class="tw-flex-1">
                                            <label class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wide">Salary</label>
                                            <p class="tw-text-sm tw-font-bold tw-text-emerald-600 tw-mt-1">{{ number_format($employee->salary) }} /=</p>
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
                                            <p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-mt-1 tw-leading-relaxed">{{ $employee->address }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

          


        </div>

    @endsection

