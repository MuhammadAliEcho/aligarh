@extends('admin.layouts.master')

@section('title', 'Visitor |')

@section('head')
    <!-- HEAD -->
@endsection

@section('content')

    @include('admin.includes.side_navbar')

    <div id="page-wrapper" class="gray-bg hidden-print">

        @include('admin.includes.top_navbar')

        <!-- Heading -->
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-md-6">
                <h2>Visitors</h2>
                <ol class="breadcrumb">
                    <li>{{ __("common.home") }}</li>
                    <li><a href="{{ URL('visitors') }}"> Visitor </a></li>
                    <li Class="active">
                        <a>Profile</a>
                    </li>
                    <li Class="active">
                        {{ $visitorStudents->name }}
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
            <div class="row">
                <!-- Profile Card -->
                <div class="col-md-4">
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-lg tw-overflow-hidden tw-transition-all tw-duration-300 hover:tw-shadow-2xl">
                        <!-- Gradient Header -->

                        <div class="tw-bg-gradient-to-br tw-from-purple-500 tw-to-indigo-600 tw-h-32"></div>
                        
                        <!-- Profile Image -->
                        <div class="tw-relative tw--mt-16 tw-mb-4">
                            <div class="tw-flex tw-justify-center">
                                <img class="tw-w-32 tw-h-32 tw-rounded-full tw-border-4 tw-border-white tw-shadow-xl tw-object-cover" 
                                     src="{{ URL($visitorStudents->img_url == '' ? 'img/avatar.jpg' : $visitorStudents->img_url) }}" 
                                     alt="{{ $visitorStudents->name }}">
                            </div>
                        </div>

                        <!-- Profile Info -->
                        <div class="tw-px-6 tw-pb-6 tw-text-center">
                            <h3 class="tw-text-2xl tw-font-bold tw-text-gray-800 tw-mb-2">{{ $visitorStudents->name }}</h3>
                            <p class="tw-text-gray-600 tw-flex tw-items-center tw-justify-center tw-gap-2">
                                <i class="fa fa-map-marker tw-text-purple-500"></i>
                                <span>{{ $visitorStudents->address }}</span>
                            </p>
                            
                            <!-- Status Badge -->
                            <div class="tw-mt-4">
                                <span class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-rounded-full tw-text-sm tw-font-semibold tw-bg-purple-100 tw-text-purple-800">
                                    <i class="fa fa-eye tw-mr-2"></i>
                                    Visitor
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Details Card -->
                <div class="col-md-8">
                    <div class="tw-bg-white tw-rounded-2xl tw-shadow-lg tw-overflow-hidden">
                        <!-- Card Header -->
                        <div class="tw-bg-gradient-to-r tw-from-purple-500 tw-to-indigo-600 tw-px-6 tw-py-4 tw-flex tw-justify-between tw-items-center">
                            <h3 class="tw-text-xl tw-font-bold tw-text-white tw-flex tw-items-center tw-gap-2">
                                <i class="fa fa-info-circle"></i>
                                Visitor Details
                            </h3>
                            <button @click="printForm()" 
                                    class="tw-bg-white tw-text-purple-600 tw-px-4 tw-py-2 tw-rounded-lg tw-font-semibold tw-flex tw-items-center tw-gap-2 tw-transition-all tw-duration-200 hover:tw-bg-purple-50 hover:tw-shadow-md"
                                    title="Print Profile">
                                <i class="fa fa-print"></i>
                                Print
                            </button>
                        </div>

                        <!-- Details Grid -->
                        <div class="tw-p-6">
                            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4">
                                <!-- Name -->
                                <div class="tw-flex tw-items-start tw-gap-3 tw-p-4 tw-rounded-xl tw-bg-gray-50 tw-transition-all tw-duration-200 hover:tw-bg-purple-50 hover:tw-shadow-md">
                                    <div class="tw-flex-shrink-0">
                                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-purple-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fa fa-user tw-text-purple-600"></i>
                                        </div>
                                    </div>
                                    <div class="tw-flex-1 tw-min-w-0">
                                        <p class="tw-text-sm tw-text-gray-500 tw-mb-1">Name</p>
                                        <p class="tw-text-base tw-font-semibold tw-text-gray-800 tw-truncate">@{{ student.name }}</p>
                                    </div>
                                </div>

                                <!-- Father Name -->
                                <div class="tw-flex tw-items-start tw-gap-3 tw-p-4 tw-rounded-xl tw-bg-gray-50 tw-transition-all tw-duration-200 hover:tw-bg-indigo-50 hover:tw-shadow-md">
                                    <div class="tw-flex-shrink-0">
                                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fa fa-male tw-text-indigo-600"></i>
                                        </div>
                                    </div>
                                    <div class="tw-flex-1 tw-min-w-0">
                                        <p class="tw-text-sm tw-text-gray-500 tw-mb-1">Father Name</p>
                                        <p class="tw-text-base tw-font-semibold tw-text-gray-800 tw-truncate">@{{ student.father_name }}</p>
                                    </div>
                                </div>

                                <!-- Current Class -->
                                <div class="tw-flex tw-items-start tw-gap-3 tw-p-4 tw-rounded-xl tw-bg-gray-50 tw-transition-all tw-duration-200 hover:tw-bg-green-50 hover:tw-shadow-md">
                                    <div class="tw-flex-shrink-0">
                                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-green-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fa fa-graduation-cap tw-text-green-600"></i>
                                        </div>
                                    </div>
                                    <div class="tw-flex-1 tw-min-w-0">
                                        <p class="tw-text-sm tw-text-gray-500 tw-mb-1">Current Class</p>
                                        <p class="tw-text-base tw-font-semibold tw-text-gray-800 tw-truncate">@{{ student.std_class.name }}</p>
                                    </div>
                                </div>

                                <!-- Seeking Class -->
                                <div class="tw-flex tw-items-start tw-gap-3 tw-p-4 tw-rounded-xl tw-bg-gray-50 tw-transition-all tw-duration-200 hover:tw-bg-yellow-50 hover:tw-shadow-md">
                                    <div class="tw-flex-shrink-0">
                                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-yellow-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fa fa-bullseye tw-text-yellow-600"></i>
                                        </div>
                                    </div>
                                    <div class="tw-flex-1 tw-min-w-0">
                                        <p class="tw-text-sm tw-text-gray-500 tw-mb-1">Seeking Class</p>
                                        <p class="tw-text-base tw-font-semibold tw-text-gray-800 tw-truncate">@{{ student.seeking_class }}</p>
                                    </div>
                                </div>

                                <!-- Date of Birth -->
                                <div class="tw-flex tw-items-start tw-gap-3 tw-p-4 tw-rounded-xl tw-bg-gray-50 tw-transition-all tw-duration-200 hover:tw-bg-blue-50 hover:tw-shadow-md">
                                    <div class="tw-flex-shrink-0">
                                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-blue-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fa fa-birthday-cake tw-text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="tw-flex-1 tw-min-w-0">
                                        <p class="tw-text-sm tw-text-gray-500 tw-mb-1">Date of Birth</p>
                                        <p class="tw-text-base tw-font-semibold tw-text-gray-800">@{{ student.date_of_birth }}</p>
                                    </div>
                                </div>

                                <!-- Place of Birth -->
                                <div class="tw-flex tw-items-start tw-gap-3 tw-p-4 tw-rounded-xl tw-bg-gray-50 tw-transition-all tw-duration-200 hover:tw-bg-purple-50 hover:tw-shadow-md">
                                    <div class="tw-flex-shrink-0">
                                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-purple-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fa fa-map-marker tw-text-purple-600"></i>
                                        </div>
                                    </div>
                                    <div class="tw-flex-1 tw-min-w-0">
                                        <p class="tw-text-sm tw-text-gray-500 tw-mb-1">Place of Birth</p>
                                        <p class="tw-text-base tw-font-semibold tw-text-gray-800 tw-truncate">@{{ student.place_of_birth }}</p>
                                    </div>
                                </div>

                                <!-- Last School -->
                                <div class="tw-flex tw-items-start tw-gap-3 tw-p-4 tw-rounded-xl tw-bg-gray-50 tw-transition-all tw-duration-200 hover:tw-bg-indigo-50 hover:tw-shadow-md">
                                    <div class="tw-flex-shrink-0">
                                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fa fa-university tw-text-indigo-600"></i>
                                        </div>
                                    </div>
                                    <div class="tw-flex-1 tw-min-w-0">
                                        <p class="tw-text-sm tw-text-gray-500 tw-mb-1">Last School</p>
                                        <p class="tw-text-base tw-font-semibold tw-text-gray-800 tw-truncate">@{{ student.last_school }}</p>
                                    </div>
                                </div>

                                <!-- Religion -->
                                <div class="tw-flex tw-items-start tw-gap-3 tw-p-4 tw-rounded-xl tw-bg-gray-50 tw-transition-all tw-duration-200 hover:tw-bg-green-50 hover:tw-shadow-md">
                                    <div class="tw-flex-shrink-0">
                                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-green-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fa fa-book tw-text-green-600"></i>
                                        </div>
                                    </div>
                                    <div class="tw-flex-1 tw-min-w-0">
                                        <p class="tw-text-sm tw-text-gray-500 tw-mb-1">Religion</p>
                                        <p class="tw-text-base tw-font-semibold tw-text-gray-800">@{{ student.religion }}</p>
                                    </div>
                                </div>

                                <!-- Gender -->
                                <div class="tw-flex tw-items-start tw-gap-3 tw-p-4 tw-rounded-xl tw-bg-gray-50 tw-transition-all tw-duration-200 hover:tw-bg-pink-50 hover:tw-shadow-md">
                                    <div class="tw-flex-shrink-0">
                                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-pink-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fa fa-venus-mars tw-text-pink-600"></i>
                                        </div>
                                    </div>
                                    <div class="tw-flex-1 tw-min-w-0">
                                        <p class="tw-text-sm tw-text-gray-500 tw-mb-1">Gender</p>
                                        <p class="tw-text-base tw-font-semibold tw-text-gray-800">@{{ student.gender }}</p>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="tw-flex tw-items-start tw-gap-3 tw-p-4 tw-rounded-xl tw-bg-gray-50 tw-transition-all tw-duration-200 hover:tw-bg-blue-50 hover:tw-shadow-md">
                                    <div class="tw-flex-shrink-0">
                                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-blue-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fa fa-envelope tw-text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="tw-flex-1 tw-min-w-0">
                                        <p class="tw-text-sm tw-text-gray-500 tw-mb-1">Email</p>
                                        <p class="tw-text-base tw-font-semibold tw-text-gray-800 tw-truncate">@{{ student.email }}</p>
                                    </div>
                                </div>

                                <!-- Contact No -->
                                <div class="tw-flex tw-items-start tw-gap-3 tw-p-4 tw-rounded-xl tw-bg-gray-50 tw-transition-all tw-duration-200 hover:tw-bg-purple-50 hover:tw-shadow-md">
                                    <div class="tw-flex-shrink-0">
                                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-purple-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fa fa-phone tw-text-purple-600"></i>
                                        </div>
                                    </div>
                                    <div class="tw-flex-1 tw-min-w-0">
                                        <p class="tw-text-sm tw-text-gray-500 tw-mb-1">Contact No</p>
                                        <p class="tw-text-base tw-font-semibold tw-text-gray-800">@{{ student.phone }}</p>
                                    </div>
                                </div>

                                <!-- Date of Visiting -->
                                <div class="tw-flex tw-items-start tw-gap-3 tw-p-4 tw-rounded-xl tw-bg-gray-50 tw-transition-all tw-duration-200 hover:tw-bg-yellow-50 hover:tw-shadow-md">
                                    <div class="tw-flex-shrink-0">
                                        <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-yellow-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fa fa-calendar tw-text-yellow-600"></i>
                                        </div>
                                    </div>
                                    <div class="tw-flex-1 tw-min-w-0">
                                        <p class="tw-text-sm tw-text-gray-500 tw-mb-1">Date of Visiting</p>
                                        <p class="tw-text-base tw-font-semibold tw-text-gray-800">@{{ student.date_of_visiting }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Address - Full Width -->
                            <div class="tw-mt-4 tw-flex tw-items-start tw-gap-3 tw-p-4 tw-rounded-xl tw-bg-gray-50 tw-transition-all tw-duration-200 hover:tw-bg-indigo-50 hover:tw-shadow-md">
                                <div class="tw-flex-shrink-0">
                                    <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center">
                                        <i class="fa fa-home tw-text-indigo-600"></i>
                                    </div>
                                </div>
                                <div class="tw-flex-1 tw-min-w-0">
                                    <p class="tw-text-sm tw-text-gray-500 tw-mb-1">Address</p>
                                    <p class="tw-text-base tw-font-semibold tw-text-gray-800">@{{ student.address }}</p>
                                </div>
                            </div>

                            <!-- Remarks - Full Width -->
                            <div v-if="student.remarks" class="tw-mt-4 tw-flex tw-items-start tw-gap-3 tw-p-4 tw-rounded-xl tw-bg-gray-50 tw-transition-all tw-duration-200 hover:tw-bg-amber-50 hover:tw-shadow-md">
                                <div class="tw-flex-shrink-0">
                                    <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-bg-amber-100 tw-flex tw-items-center tw-justify-center">
                                        <i class="fa fa-comment tw-text-amber-600"></i>
                                    </div>
                                </div>
                                <div class="tw-flex-1 tw-min-w-0">
                                    <p class="tw-text-sm tw-text-gray-500 tw-mb-1">Remarks</p>
                                    <p class="tw-text-base tw-font-semibold tw-text-gray-800">@{{ student.remarks }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="visitor_student_profile_printable" class="visible-print">
        @include('admin.printable.include.visitor_student_profile')
    </div>
@endsection
@section('vue')
    <script type="text/javascript">
        var app = new Vue({
            el: "#app",
            data: {
                student: {!! json_encode($visitorStudents, JSON_NUMERIC_CHECK) !!},
            },
            mounted: function() {
                $("[data-toggle='tooltip']").on('mouseenter', function() {
                    $(this).tooltip('show');
                }).mouseleave(function() {
                    $(this).tooltip('destroy');
                });
            },

            methods: {
                printForm: function() {
                    window.print();
                }
            }
        });
    </script>
@endsection
