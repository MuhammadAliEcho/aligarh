@extends('admin.layouts.master')

  @section('title', 'Employee |')

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
                    <li>Home</li>
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
                <div class="col-md-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Profile Detail</h5>
                        </div>
                        <div>
                            <div class="ibox-content no-padding border-left-right">
                              <center>
                                <img alt="image" class="img-responsive" src="{{ URL(($employee->img_url == '')? 'img/avatar.jpg' : $employee->img_url) }}">
                              </center>
                            </div>
                            <div class="ibox-content profile-content">
                                <h4><strong>{{ $employee->name }}</strong></h4>
                                <p><i class="fa fa-map-marker"></i> {{ $employee->address }}</p>
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
                                        <td>{{ $employee->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Religion :</th>
                                        <td>{{ $employee->religion }}</td>
                                    </tr>
                                    <tr>
                                        <th>Gender :</th>
                                        <td>{{ $employee->gender }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email :</th>
                                        <td>{{ $employee->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Employee Role :</th>
                                        <td>{{ $employee->role }}</td>
                                    </tr>
                                    <tr>
                                        <th>Qualification :</th>
                                        <td>{{ $employee->qualification }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address :</th>
                                        <td>{{ $employee->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Contact No :</th>
                                        <td>{{ $employee->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Salary :</th>
                                        <td>{{ $employee->salary }} /=</td>
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

