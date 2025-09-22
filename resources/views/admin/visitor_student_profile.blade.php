@extends('admin.layouts.master')

  @section('title', 'Visitor |')

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
                                <img alt="image" class="img-responsive" src="{{ URL(($visitorStudents->img_url == '')? 'img/avatar.jpg' : $visitorStudents->img_url) }}">
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
                            <h5>Details</h5>
                        </div>
                        <div class="ibox-content">

                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <th>Name :</th>
                                        <td>{{ $visitorStudents->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Father Name :</th>
                                        <td>{{ $visitorStudents->father_name }}</td>
                                    </tr>

                                    <tr>
                                        <th>Class :</th>
                                        <td>{{ $visitorStudents->StdClass->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Seeking Class :</th>
                                        <td>{{ $visitorStudents->seeking_class }}</td>
                                    </tr>
                                     <tr>
                                        <th>Date Of Birth :</th>
                                        <td>{{ $visitorStudents->date_of_birth }}</td>
                                    </tr>
                                    <tr>
                                        <th>Place Of Birth :</th>
                                        <td>{{ $visitorStudents->place_of_birth }}</td>
                                    </tr>
                                    <tr>
                                        <th>Guardian Relation :</th>
                                        <td>{{ $visitorStudents->guardian_relation }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last School :</th>
                                        <td>{{ $visitorStudents->last_school }}</td>
                                    </tr>
                                    <tr>
                                        <th>Religion :</th>
                                        <td>{{ $visitorStudents->religion }}</td>
                                    </tr>

                                    <tr>
                                        <th>Gender :</th>
                                        <td>{{ $visitorStudents->gender }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email :</th>
                                        <td>{{ $visitorStudents->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address :</th>
                                        <td>{{ $visitorStudents->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Contact No :</th>
                                        <td>{{ $visitorStudents->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date Of Visiting :</th>
                                        <td>{{ $visitorStudents->date_of_visiting }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        </div>
    @endsection
