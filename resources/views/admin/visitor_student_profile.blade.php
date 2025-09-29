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
                    <li>Home</li>
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
            <div class="row animated fadeInRight">
                <div class="col-md-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Profile Detail</h5>
                        </div>
                        <div>
                            <div class="ibox-content no-padding border-left-right">
                                <center>
                                    <img alt="image" class="img-responsive"
                                        src="{{ URL($visitorStudents->img_url == '' ? 'img/avatar.jpg' : $visitorStudents->img_url) }}">
                                </center>
                            </div>
                            <div class="ibox-content profile-content">
                                <h4><strong>{{ $visitorStudents->name }}</strong></h4>
                                <p><i class="fa fa-map-marker"></i> {{ $visitorStudents->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Details <a v-on:click.stop.prevent="printForm()" title="Profile Print"
                                    data-toggle="tooltip"><span class="fa fa-print"></span></a> </h5>

                        </div>
                        <div class="ibox-content">

                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <th>Name :</th>
                                        <td>@{{ student.name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Father Name :</th>
                                        <td>@{{ student.father_name }}</td>
                                    </tr>

                                    <tr>
                                        <th>Class :</th>
                                        <td>@{{ student.std_class.name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Seeking Class :</th>
                                        <td>@{{ student.seeking_class }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date Of Birth :</th>
                                        <td>@{{ student.date_of_birth }}</td>
                                    </tr>
                                    <tr>
                                        <th>Place Of Birth :</th>
                                        <td>@{{ student.place_of_birth }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last School :</th>
                                        <td>@{{ student.last_school }}</td>
                                    </tr>
                                    <tr>
                                        <th>Religion :</th>
                                        <td>@{{ student.religion }}</td>
                                    </tr>

                                    <tr>
                                        <th>Gender :</th>
                                        <td>@{{ student.gender }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email :</th>
                                        <td>@{{ student.email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address :</th>
                                        <td>@{{ student.address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Contact No :</th>
                                        <td>@{{ student.phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date Of Visiting :</th>
                                        <td>@{{ student.date_of_visiting }}</td>
                                    </tr>
                                    <tr>
                                        <th>Remarks :</th>
                                        <td>@{{ student.remarks }}</td>
                                    </tr>
                                </tbody>
                            </table>
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
