@extends('admin.layouts.master')

  @section('title', 'Users |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
    <style type="text/css">
  .print-table {
    width: 100%;
  }
  .print-table th,
  .print-table td {
    border: 1px solid black !important;
    padding: 0px;
  }   

  .print-table > tbody > tr > td {
      padding: 1px;
    }
  .print-table > thead > tr > th {
      padding: 3px;
    }
  </style>
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

            <div class="row ">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class="">
                              <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Users</a>
                            </li>
                            <li class="add-user">
                              <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Add Users</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade">
                                <div class="panel-body">
                                  <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover dataTables-user" >
                                      <thead>
                                        <tr>
                                          <th>User Name</th>
                                          <th>E-Mail</th>
                                          <th>Role</th>
                                          <th>Options</th>
                                        </tr>
                                      </thead>
                                    </table>
                                  </div>

                                </div>
                            </div>
                            <div id="tab-11" class="tab-pane fade add-user">
                                <div class="panel-body">
                                  <h2> User Registration </h2>
                                  <div class="hr-line-dashed"></div>

                                    <form id="tchr_rgstr" method="post" action="{{ URL('users/create') }}" class="form-horizontal" >
                                      {{ csrf_field() }}

                                      <div class="form-group">
                                        <label class="col-md-2 control-label">Role</label>
                                        <div class="col-md-6">
                                          <select id="type" name="type" value="{{ old('type') }}" class="form-control" required="true" />
                                            <option></option>
                                            <option value="teacher">Teacher</option>
                                            <option value="employee">Employee</option>
                                          </select>
                                          @if ($errors->has('type'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('type') }} </strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div id="teacher" class="form-group{{ ($errors->has('teacher'))? ' has-error' : '' }}" style="display:none;">
                                        <label class="col-md-2 control-label"> Teacher </label>
                                        <div class="col-md-6">
                                          <select class="form-control select" id="select2_teacher" name="teacher" required="true"></select>
                                          @if ($errors->has('teacher'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('teacher') }} </strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div id="employee" class="form-group{{ ($errors->has('employee'))? ' has-error' : '' }}" style="display:none;">
                                        <label class="col-md-2 control-label"> Employee </label>
                                        <div class="col-md-6">
                                          <select class="form-control select" id="select2_employee" name="employee" required="true"></select>
                                          @if ($errors->has('employee'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('employee') }} </strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">User Name</label>
                                        <div class="col-md-6">
                                          <input type="text" name="name" placeholder="User Name" value="{{ old('name') }}" class="form-control"/>
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
                                          <input type="text" name="email" placeholder="E-Mail" value="{{ old('email') }}" class="form-control"/>
                                          @if ($errors->has('email'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('email') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

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

<!--                                       <div class="form-group{{ ($errors->has('contact_no'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Contact No</label>
                                        <div class="col-md-6">
                                          <input type="text" name="contact_no" value="{{ old('contact_no') }}" placeholder="Contact No" class="form-control" data-mask="(999) 999-9999"/>
                                          @if ($errors->has('contact_no'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('contact_no') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('role'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">User Role</label>
                                        <div class="col-md-6">
                                          <input type="text" name="role" placeholder="User Role" value="{{ old('role') }}" class="form-control"/>
                                          @if ($errors->has('role'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('role') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>
 -->

                                      <div class="form-group{{ ($errors->has('allow_session'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Allow Session</label>
                                        <div class="col-md-6">
                                          <select class="select2 form-control" multiple="multiple" name="allow_session[]" style="width: 100%">
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

                                      <div class="form-group{{ ($errors->has('status'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Status</label>
                                        <div class="col-md-6">
                                          <select name="status" value="{{ old('status') }}" class="form-control"/>
                                            <option value="0">InActive</option>
                                            <option value="1">Active</option>
                                          </select>
                                          @if ($errors->has('status'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('status') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>
                                      <div class="form-group">
                                          <div class="col-md-offset-2 col-md-6">
                                              <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Register </button>
                                          </div>
                                      </div>
                                    </form>

                                </div>
                            </div>
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

    <script src="{{ URL::to('src/js/plugins/dataTables/datatables.min.js') }}"></script>

    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- Input Mask-->
     <script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ URL::to('src/js/plugins/select2/select2.full.min.js') }}"></script>

    <script type="text/javascript">
    var tbl;

      function select2template(data) {
        if (!data.id) { return data.text; }
        var $data = $(
          // '<span class="text-danger">'+data.text+'</span>'
          data.htm1+data.text+data.htm2
        );
        return $data;
      };

    function loadOptions(data, type, full, meta) {

        opthtm = '<a href="{{ URL('users/edit') }}/'+full.id+'" data-toggle="tooltip" title="Edit" class="btn btn-';
        opthtm  +=   (full.active == 1)? 'default' : 'danger';
        opthtm  +=  ' btn-circle btn-xs edit-option"><span class="fa fa-edit"></span></a>';
        
        switch(full.user_type) {
            case 'teacher':
              opthtm += '<a href="{{ URL('teacher/profile') }}/'+full.foreign_id+'" data-toggle="tooltip" title="Profile" class="btn btn-default btn-circle btn-xs profile"><span class="fa fa-user"></span></a>';
                break;

            case 'employee':
              opthtm += '<a href="{{ URL('employee/profile') }}/'+full.foreign_id+'" data-toggle="tooltip" title="Profile" class="btn btn-default btn-circle btn-xs profile"><span class="fa fa-user"></span></a>';
                break;

            default:
            opthtm += '';       

        }
        return opthtm;
    }

      $(document).ready(function(){
        tbl = $('.dataTables-user').DataTable({
          dom: '<"html5buttons"B>lTfgitp',
          buttons: [
            //{ extend: 'copy'},
            //{extend: 'csv'},
            //{extend: 'excel', title: 'ExampleFile'},
            //{extend: 'pdf', title: 'ExampleFile'},

            {extend: 'print',
              customize: function (win){
                $(win.document.body).addClass('white-bg');
                $(win.document.body).css('font-size', '12px');

                $(win.document.body).find('table')
                .addClass('print-table')
                .removeClass('table')
                .removeClass('table-striped')
                .removeClass('table-bordered')
                .removeClass('table-hover')
                .addClass('compact')
                .css('font-size', 'inherit');
              },
              exportOptions: {
                columns: [ 0, 1, 2]
              },
              title: "Users | {{ config('systemInfo.title') }}",
            }
          ],
          Processing: true,
          serverSide: true,
          ajax: '{{ URL('users') }}',
          columns: [
            { data: 'name' },
            { data: 'email' },
            { data: 'roles', name: 'roles.name', orderable: false, searchable: false },
            // {"defaultContent": '<div class="btn-group"><button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle option" aria-expanded="true">Action <span class="caret"></span></button><ul class="dropdown-menu"><li><a href="#"><span class="fa fa-user"></span> Profile</a></li><li class="divider"></li><li><a data-original-title="Edit" class="edit-option"><span class="fa fa-edit"></span> Edit</a></li><li><a href="#"><span class="fa fa-trash"></span> Delete</a></li></ul></div>', className: 'hidden-print'},
            {render: loadOptions, className: 'hidden-print', "orderable": false},
          ],
        });

/*      $('.dataTables-user tbody').on( 'mouseenter', '.edit-option', function () {
        $(this).attr('href','{{ URL('users/edit') }}/'+tbl.row( $(this).parents('tr') ).data().id);
        $(this).tooltip('show');
      });
*/

      $(".dataTables-user tbody").on('mouseenter', "[data-toggle='tooltip']", function(){
          $(this).tooltip('show');
      });

        $("#tchr_rgstr").validate({
            ignore:":not(:visible)",
            rules: {
              type: {
                required: true,
              },
              teacher: {
                required: true,
              },
              employee: {
                required: true,
              },
              name: {
                required: true,
              },
              email: {
                required: true,
                email: true
              },
              status: {
                required: true,
              },
           password: {
                          required: true,
                          minlength: 6,
                         maxlength: 12,
                      },
           re_password: {
                          required: true,
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

        $(".select2").select2();

        $('#select2_teacher').attr('style', 'width:100%').select2({
            placeholder: 'Search contacts',
            minimumInputLength: 3,
            Html: true,
            ajax: {
                url: "{{ URL('teacher/find') }}",

              processResults: function (data) {
                return {
                  results: data
                };
              }
            },
            tags: true,
        });

        $('#select2_employee').attr('style', 'width:100%').select2({
            placeholder: 'Search contacts',
            minimumInputLength: 3,
            Html: true,
            ajax: {
                url: "{{ URL('employee/find') }}",
/*                dataType: 'json',
                data: function (term, page) {
                    return {
                        contact_names_value: term
                    };
                },
                results: function (data, page) {
                    return {results: data.data};
                }*/
              processResults: function (data) {
                return {
                  results: data
                };
              }
            },
            tags: true,
            // templateResult: select2template,
        });

      $(".select").change(function(){
        if((data  =  $(this).select2('data')) != ''){
          $("input[name='email']").val(data[0]['email']);
//          $("input[name='role']").val(data[0]['role']);
          $("input[name='name']").val(data[0]['name'].replace(' ', '_')+'_'+data[0]['id']);
        } else {
          $("input[name='email']").val('');
//          $("input[name='role']").val('');
          $("input[name='name']").val('');
        }

      });

      $("#type").change(function(){
        switch(this.value){
         
          case 'teacher':
            $("#teacher").show();
            $("#employee").hide();
            $("#select2_employee").select2('val', 'ALL');
            $("#select2_employee").select2('val', 'ALL');
            break;

          case 'employee':
            $("#employee").show();
            $("#teacher").hide();
            $("#select2_teacher").select2('val', 'ALL');
            break;

          default:
            $("#employee").hide();
            $("#teacher").hide();
            $("#select2_teacher").select2('val', 'ALL');
            $("#select2_employee").select2('val', 'ALL');
            break;          

        }
        $(".select").change();
      });

      @if(collect($errors)->count() >= 1 && !$errors->has('toastrmsg'))
        $('a[href="#tab-10"]').tab('show');
      @else
        $('a[href="#tab-11"]').tab('show');
      @endif


      // "Auth::user()->getprivileges->privileges->{$root['content']['id']}->add == 0)"
      //   $('.add-user').hide();
      // "endif"

      // "Auth::user()->getprivileges->privileges->{$root['content']['id']}->edit == 0)"
      //   $('.edit-user').hide();
      // "endif"


      });
    </script>

    @endsection
