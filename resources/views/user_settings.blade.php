@extends('layouts.master')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
  @endsection

  @section('content')

  @include('includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>User Settings</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li Class="active">
                          <a>User Settings</a>
                      </li>
                  </ol>
              </div>
              <div class="col-lg-4 col-md-6">
                @include('includes.academic_session')
              </div>
          </div>

          <!-- main Section -->

          <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row ">
               <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>Change Password</h2>
                        <div class="hr-line-dashed"></div>
                    </div>

                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-12">


                                <form method="POST" id="chng_pwd" class="form-horizontal" action="{{ URL('user-settings/changepwd') }}" role="form">
                                    {{ csrf_field() }}
                                    <div class="form-group{{ ($errors->has('cr_pwd'))? ' has-error' : '' }}">

									<label class="col-md-2 control-label">Current password</label>

									<div class="col-md-6">
									<input type="password" name="cr_pwd" placeholder="Current Password" class="form-control">
                  @if ($errors->has('cr_pwd'))
                      <span class="help-block">
                          <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('cr_pwd') }}</strong>
                      </span>
                  @endif
                </div>
									</div>



									<div class="form-group{{ ($errors->has('new_pwd'))? ' has-error' : '' }}">
									<label class="col-md-2 control-label">New Password</label>
									<div class="col-md-6">
									<input type="password" id="new_pwd" name="new_pwd" placeholder="New Password" class="form-control">
                  @if ($errors->has('new_pwd'))
                      <span class="help-block">
                          <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('new_pwd') }}</strong>
                      </span>
                  @endif
									</div>
									</div>



									<div class="form-group{{ ($errors->has('re_pwd'))? ' has-error' : '' }}">
									<label class="col-md-2 control-label">Re-Type Password</label>
									<div class="col-md-6">
									<input type="password" name="re_pwd" placeholder="Re-Type Password" class="form-control">
                  @if ($errors->has('re_pwd'))
                      <span class="help-block">
                          <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('re_pwd') }}</strong>
                      </span>
                  @endif
                </div>
                                    </div>
									<div class="form-group">
									<div class="col-md-offset-2 col-md-2">

                                        <button class="btn btn-sm btn-primary btn-block pull-right m-t-n-xs" type="submit"><span class="glyphicon glyphicon-save"></span> Update </button>

                                    </div>
									</div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            </div>

            <div class="row">

            </div>
          </div>


          @include('includes.footercopyright')


        </div>

    @endsection

    @section('script')

    <script src="src/js/plugins/validate/jquery.validate.min.js"></script>

     <script type="text/javascript">
          $(document).ready(function(){
              $("#chng_pwd").validate({
                  rules: {
                      cr_pwd: {
                          required: true,
                          minlength: 4,
                          maxlength: 12,

                      },
 					 new_pwd: {
                          required: true,
                          minlength: 4,
 						             maxlength: 12,
                      },
 					 re_pwd: {
                          required: true,
                          minlength: 4,
 						 maxlength: 12,
 						equalTo :"#new_pwd"
 					}
 				},
 				messages:{
 					re_pwd:{
 						equalTo:'New password and Re-Type password must be same'
 					}
 				}
              });

         });
     </script>
    @endsection
