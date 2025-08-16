@extends('admin.layouts.master')

  @section('title', 'Edit Vendor |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('admin.includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Vedors</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li>
                          <a>Vednors</a>
                      </li>
                      <li Class="active">
                          <a>Edit</a>
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

          <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row ">
               <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>Edit Vednor</h2>
                        <div class="hr-line-dashed"></div>
                    </div>

                    <div class="ibox-content">

                                    <form id="tchr_rgstr" method="post" action="{{ URL('vendors/edit/'.$vendor->id) }}" class="form-horizontal" >
                                      {{ csrf_field() }}

                                      <div class="form-group{{ ($errors->has('v_name'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Vendor Name</label>
                                        <div class="col-md-6">
                                          <input type="text" name="v_name" placeholder="V Name" value="{{ old('v_name', $vendor['v_name']) }}" class="form-control"/>
                                          @if ($errors->has('v_name'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('v_name') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('c_name'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Contact Name</label>
                                        <div class="col-md-6">
                                          <input type="text" name="c_name" placeholder="C Name" value="{{ old('c_name', $vendor['c_name']) }}" class="form-control"/>
                                          @if ($errors->has('c_name'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('c_name') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('email'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">E-Mail</label>
                                        <div class="col-md-6">
                                          <input type="text" name="email" placeholder="E-Mail" value="{{ old('email', $vendor['email']) }}" class="form-control"/>
                                          @if ($errors->has('email'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('email') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('phone'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Contact No</label>
                                        <div class="col-md-6">
                                          <input type="text" name="phone" value="{{ old('phone', $vendor['phone']) }}" placeholder="Contact No" class="form-control" data-mask="(999) 999-9999"/>
                                          @if ($errors->has('phone'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('phone') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group">
                                        <label class="col-md-2 control-label">Address</label>
                                        <div class="col-md-6">
                                          <textarea type="text" name="address" placeholder="Address" class="form-control">{{ old('address', $vendor['address']) }}</textarea>
                                        </div>
                                      </div>

                                      <div class="form-group">
                                          <div class="col-md-offset-2 col-md-6">
                                              <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Save Changes </button>
                                          </div>
                                      </div>
                                    </form>

                        </div>
                    </div>
                </div>
            </div>

          </div>


          


        </div>

    @endsection

    @section('script')


    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- Input Mask-->
     <script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

    <script type="text/javascript">

      $(document).ready(function(){

        $("#tchr_rgstr").validate({
            rules: {
              v_name: {
                required: true,
              },
              c_name: {
                required: true,
              },
              email: {
                email: true
              },
            },
        });

      });
    </script>

    @endsection
