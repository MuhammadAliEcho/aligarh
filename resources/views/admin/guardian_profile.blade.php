@extends('admin.layouts.master')

  @section('title', 'Guardians |')

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
                  <h2>Guardians</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                    <li><a href="{{ URL('guardians') }}"> Guardian </a></li>
                      <li Class="active">
                          <a>Profile</a>
                      </li>
                      <li Class="active">
                        <strong>
                          {{ $guardian->name }}
                        </strong>
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
                <div class="row">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h4>Guardian Details</h4>
                        </div>
                        <div class="ibox-content">

                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <th>Name :</th>
                                        <td>{{ $guardian->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email :</th>
                                        <td>{{ $guardian->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Profession :</th>
                                        <td>{{ $guardian->profession }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address :</th>
                                        <td>{{ $guardian->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Contact NO :</th>
                                        <td>{{ $guardian->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Income :</th>
                                        <td>{{ $guardian->income }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>

                <div class="row">
                  <h1>Students</h1>
                  @foreach($guardian->Student AS $student)
                  <div class="col-lg-4">
                      <div class="contact-box">
                          <a href="{{ URL('students/profile/'.$student->id) }}">
                          <div class="col-sm-4">
                              <div class="text-center">
                                <center>
                                  <img alt="image" class="img-circle m-t-xs img-responsive" src="{{ URL(($student->image_url == '')? 'img/avatar.jpg' : $student->image_url) }}">
                                </center>
                                  <div class="m-t-xs font-bold">{{ $student->name }}</div>
                              </div>
                          </div>
                          <div class="col-sm-8">
                              <p><strong>Class. </strong>{{ $student->Std_Class->name }} {{ $student->Section->nick_name }} </p>
                              <p><strong>GR NO.</strong>{{ $student->gr_no }} </p>
                              <p><strong>Gender.</strong>{{ $student->gender }} </p>
                              <p><strong>Fee.</strong>{{ $student->net_amount }} </p>
                          </div>
                          <div class="clearfix"></div>
                          </a>
                      </div>
                  </div>
                  @endforeach

                </div>
            </div>
        </div>

          


        </div>

    @endsection

