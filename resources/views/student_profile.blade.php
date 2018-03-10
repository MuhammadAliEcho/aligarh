@extends('layouts.master')

  @section('title', 'Students |')

  @section('head')
  <!-- HEAD -->
  @endsection

  @section('content')

  @include('includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Students</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                    <li><a href="{{ URL('students') }}"> Student </a></li>
                      <li Class="active">
                          <a>Profile</a>
                      </li>
                      <li Class="active">
                          {{ $student->name }}
                      </li>
                  </ol>
              </div>
              <div class="col-lg-4 col-md-6">
                @include('includes.academic_session')
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
                                <img alt="image" class="img-responsive" src="{{ URL(($student->image_url == '')? 'img/avatar.jpg' : $student->image_url) }}">
                            </div>
                            <div class="ibox-content profile-content">
                                <h4><strong>{{ $student->name }}</strong></h4>
                                <p><i class="fa fa-map-marker"></i> {{ $student->address }}</p>
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
                                        <td>{{ $student->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Relegion :</th>
                                        <td>{{ $student->relegion }}</td>
                                    </tr>
                                    <tr>
                                        <th>GR NO :</th>
                                        <td>{{ $student->gr_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Gender :</th>
                                        <td>{{ $student->gender }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date Of Birth :</th>
                                        <td>{{ $student->date_of_birth }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date Of Admission :</th>
                                        <td>{{ $student->date_of_admission }}</td>
                                    </tr>
                                    <tr>
                                        <th>Place Of Birth :</th>
                                        <td>{{ $student->place_of_birth }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Attend School :</th>
                                        <td>{{ $student->last_school }}</td>
                                    </tr>
                                    <tr>
                                        <th>Class :</th>
                                        <td>{{ $student->Std_Class->name }} {{ $student->Section->nick_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Parent :</th>
                                        <td>
                                          <a href="{{ URL('parents/profile/'.$student->Guardian->id) }}">
                                            {{ $student->Guardian->name }}. ({{ $student->parent_relation }})
                                          </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Email :</th>
                                        <td>{{ $student->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Contact :</th>
                                        <td>{{ $student->contact_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Fee :</th>
                                        <td>{{ $student->net_amount }} /=</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </div>

          @include('includes.footercopyright')


        </div>

    @endsection

