@extends('admin.layouts.master')

  @section('title', 'Teachers |')

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
                  <h2>Teachers</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                    <li><a href="{{ URL('teacher') }}"> Teacher </a></li>
                      <li Class="active">
                          <a>Profile</a>
                      </li>
                      <li Class="active">
                          {{ $teacher->name }}
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
                <div class="col-md-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Profile Detail</h5>
                        </div>
                        <div>
                            <div class="ibox-content no-padding border-left-right">
                              <center>
                                <img alt="image" class="img-responsive" src="{{ URL(($teacher->image_url == '')? 'img/avatar.jpg' : $teacher->image_url) }}">
                              </center>
                            </div>
                            <div class="ibox-content profile-content">
                                <h4><strong>{{ $teacher->name }}</strong></h4>
                                <p><i class="fa fa-map-marker"></i> {{ $teacher->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Details</h5>
                        </div>
                        <div class="ibox-content">

                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <th>Name :</th>
                                        <td>{{ $teacher->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Relegion :</th>
                                        <td>{{ $teacher->relegion }}</td>
                                    </tr>
                                    <tr>
                                        <th>Father Name :</th>
                                        <td>{{ $teacher->f_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Husband Name :</th>
                                        <td>{{ $teacher->husband_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subject :</th>
                                        <td>{{ $teacher->subject }}</td>
                                    </tr>
                                    <tr>
                                        <th>Gender :</th>
                                        <td>{{ $teacher->gender }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email :</th>
                                        <td>{{ $teacher->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Qualification :</th>
                                        <td>{{ $teacher->qualification }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address :</th>
                                        <td>{{ $teacher->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Contact No :</th>
                                        <td>{{ $teacher->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Salary :</th>
                                        <td>{{ $teacher->salary }} /=</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </div>

          @include('admin.includes.footercopyright')


        </div>

    @endsection

