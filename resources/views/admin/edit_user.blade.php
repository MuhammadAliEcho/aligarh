@extends('admin.layouts.master')

  @section('title', 'Edit User |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('admin.includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Users</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li Class="active">
                          <a>Users</a>
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

            <div class="row">
                <div class="col-lg-12">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <h2>Edit User</h2>
                          <div class="hr-line-dashed"></div>
                      </div>

                      <div class="ibox-content">

                                    <form id="tchr_rgstr" method="post" action="{{ URL('users/edit/'.$user->id) }}" class="form-horizontal" >
                                      {{ csrf_field() }}

                                      <div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">User Name</label>
                                        <div class="col-md-6">
                                          <input type="text" placeholder="User Name" value="{{ old('name', $user->name) }}" class="form-control" readonly="true" />
                                          @if ($errors->has('name'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('name') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('email'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">E-Mail</label>
                                        <div class="col-md-6">
                                          <input type="text" placeholder="E-Mail" value="{{ old('email', $user->email) }}" class="form-control" readonly="true" />
                                          @if ($errors->has('email'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('email') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('allow_session'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Allow Session</label>
                                        <div class="col-md-6">
                                          <select class="select2 form-control" id="allow_session" multiple="multiple" name="allow_session[]" style="width: 100%">
                                          @foreach(App\AcademicSession::UserAllowSession()->get() AS $session)
                                              <option value="{{ $session->id }}">{{ $session->title }}</option>
                                          @endforeach
                                          </select>
                                          @if ($errors->has('allow_session'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('allow_session') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('active'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Status</label>
                                        <div class="col-md-6">
                                          <select name="active" id="status" class="form-control"/>
                                            <option value="0">InActive</option>
                                            <option value="1">Active</option>
                                          </select>
                                          @if ($errors->has('active'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('status') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      @can('users.update.update_passoword')
                                        <div class="form-group{{ ($errors->has('password'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Password</label>
                                          <div class="col-md-6">
                                            <input type="password" id="password" name="password" placeholder="Password" value="{{ old('password') }}" class="form-control"/>
                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>
                                        <div class="form-group{{ ($errors->has('re_password'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Confirm Password</label>
                                          <div class="col-md-6">
                                            <input type="password" name="re_password" placeholder="Confirm Password" value="{{ old('re_password') }}" class="form-control"/>
                                            @if ($errors->has('re_password'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('re_password') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>
                                      @endcan
                                      <div class="form-group">
                                          <div class="col-md-offset-2 col-md-6">
                                              <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Update </button>
                                          </div>
                                      </div>
                                    </form>

                        </div>
                    </div>
                </div>
            </div>

          </div>


          @include('admin.includes.footercopyright')


        </div>

    @endsection

    @section('script')

    <!-- Mainly scripts -->
    <script src="{{ URL::to('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>

    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- Input Mask-->
     <script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ URL::to('src/js/plugins/select2/select2.full.min.js') }}"></script>
    @if ($errors->any())
    <script>
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}", "Validation Error");
        @endforeach
    </script>
    @endif

    <script type="text/javascript">
    // var privileges = 'json_encode($user->getprivileges->privileges)';
    var allow_session = {!! json_encode($user->allow_session) !!};

      $(document).ready(function(){

        $("#tchr_rgstr").validate({
            rules: {
              status: {
                required: true,
              },
           password: {
                          minlength: 6,
                         maxlength: 12,
                      },
           re_password: {
                          minlength: 6,
                          maxlength: 12,
                        equalTo :"#password"
          }
        },
        messages:{
          re_pwd:{
            equalTo:'Password and Confirm password must be same'
          }
        }
        });

        $("#status").val({{ $user->active }});

        // $.each(privileges, function(key, val){
        //   $('#checkbox_'+key).prop('checked', val.default);
        //   $.each(val, function(k, v){
        //     $("#select_"+key+" option[value='"+k+"']").prop("selected", v);
        //   });
        // });

        $.each(allow_session, function(k, v){
          $("#allow_session option[value='"+v+"']").prop("selected", v);
        });

        $(".select2").select2();

      });
    </script>

    @endsection
