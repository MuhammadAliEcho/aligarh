@extends('admin.layouts.master')

  @section('title', __('modules.pages_classes_title').' |')

  @section('head')
  <link href="{{ asset('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
  <link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('admin.includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>{{ __('modules.pages_classes_title') }}</h2>
                  <ol class="breadcrumb">
                    <li>{{ __('common.home') }}</li>
                      <li Class="active">
                          <a>{{ __('modules.pages_classes_title') }}</a>
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
                              <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> {{ __('modules.pages_classes_title') }}</a>
                            </li>
                            @can('manage-classes.add')
                              <li class="add-class">
                                <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> {{ __('modules.forms_add_class') }}</a>
                              </li>
                            @endcan
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane">
                                <div class="panel-body">
                                  <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover dataTables-teacher" >
                                      <thead>
                                        <tr>
                                          <th>{{ __('labels.name') }}</th>
                                          <th>{{ __('labels.prifix') }}</th>
                                          <th>{{ __('labels.numeric_name') }}</th>
                                          <th>{{ __('labels.class_teacher') }}</th>
                                          <th>{{ __('labels.options') }}</th>
                                        </tr>
                                      </thead>
                                    </table>
                                  </div>

                                </div>
                            </div>
                            @can('manage-classes.add')
                              <div id="tab-11" class="tab-pane add-class">
                                  <div class="panel-body">
                                    <h2> {{ __('modules.forms_class_registration') }} </h2>
                                    <div class="hr-line-dashed"></div>

                                      <form id="tchr_rgstr" method="post" action="{{ URL('manage-classes/add') }}" class="form-horizontal" >
                                        {{ csrf_field() }}

                                        <div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">{{ __('labels.name') }}</label>
                                          <div class="col-md-6">
                                            <input type="text" name="name" placeholder="{{ __('labels.name_placeholder') }}" value="{{ old('name') }}" class="form-control"/>
                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group{{ ($errors->has('numeric_name'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">{{ __('labels.numeric_name') }}</label>
                                          <div class="col-md-6">
                                            <input type="number" name="numeric_name" placeholder="{{ __('labels.numeric_name_placeholder') }}" value="{{ old('numeric_name') }}" class="form-control"/>
                                            @if ($errors->has('numeric_name'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('numeric_name') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group{{ ($errors->has('prifix'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">{{ __('labels.prifix') }}</label>
                                          <div class="col-md-6">
                                            <input type="text" name="prifix" placeholder="{{ __('labels.prifix') }}" value="{{ old('prifix') }}" class="form-control" required="true" />
                                            @if ($errors->has('prifix'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('prifix') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group{{ ($errors->has('teacher'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">{{ __('labels.class_teacher') }}</label>
                                          <div class="col-md-6 select2-div">
                                            <select class="form-control select2" name="teacher">
                                              <option></option>
                                              @foreach($teachers AS $teacher)
                                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                              @endforeach
                                            </select>
                                            @if ($errors->has('teacher'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('teacher') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-offset-2 col-md-6">
                                                <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> {{ __('modules.buttons_submit') }} </button>
                                            </div>
                                        </div>
                                      </form>

                                  </div>
                              </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

          </div>


          


        </div>

    @endsection

    @section('script')

    <!-- Mainly scripts -->
    <script src="{{ asset('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>

    <script src="{{ asset('src/js/plugins/dataTables/datatables.min.js') }}"></script>

    <script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('src/js/plugins/select2/select2.full.min.js') }}"></script>

    <script type="text/javascript">
    var tbl;

    function loadOptions(data, type, full, meta) {

        opthtm = '';
        @can('manage-classes.edit.post')
        opthtm += '<a href="{{ URL('manage-classes/edit') }}/'+full.id+'" data-toggle="tooltip" title="{{ __('modules.buttons_edit') }}" class="btn btn-default btn-circle btn-xs"><span class="fa fa-edit"></span></a>';
        @endcan
        return opthtm;
    }

      $(document).ready(function(){
        opthtm = '<a data-toggle="tooltip" title="Edit" class="btn btn-default btn-circle btn-xs edit-option"><span class="fa fa-edit"></span></a>';

        $('.select2').select2({
                placeholder: "Select a Teacher",
                allowClear: true,
            });
        $('.select2-div>span').attr('style', 'width:100%');
     tbl =  $('.dataTables-teacher').DataTable({
          dom: '<"html5buttons"B>lTfgitp',
          buttons: [
            { extend: 'copy'},
            {extend: 'csv'},
            {extend: 'excel', title: 'ExampleFile'},
            {extend: 'pdf', title: 'ExampleFile'},

            {extend: 'print',
              customize: function (win){
                $(win.document.body).addClass('white-bg');
                $(win.document.body).css('font-size', '10px');

                $(win.document.body).find('table')
                .addClass('compact')
                .css('font-size', 'inherit');
              }
            }
          ],
          Processing: true,
          serverSide: true,
          ajax: '{{ URL('manage-classes') }}',
          columns: [
            {data: 'name', name: 'classes.name'},
            {data: 'prifix', name: 'classes.prifix'},
            {data: 'numeric_name', name: 'classes.numeric_name'},
            {data: 'teacher_name', name: 'teachers.name'},
//            {"defaultContent": '<div class="btn-group"><button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle option" aria-expanded="true">Action <span class="caret"></span></button><ul class="dropdown-menu"><li><a href="#"><span class="fa fa-user"></span> Profile</a></li><li class="divider"></li><li><a data-original-title="Edit" class="edit-option"><span class="fa fa-edit"></span> Edit</a></li><li><a href="#"><span class="fa fa-trash"></span> Delete</a></li></ul></div>', className: 'hidden-print'},
//            {"defaultContent": opthtm, className: 'hidden-print'},
            {render: loadOptions, className: 'hidden-print', "orderable": false},

          ],
          order: [1, "asc"],
        });

      $('.dataTables-teacher tbody').on( 'mouseenter', "[data-toggle='tooltip']", function () {
        $(this).tooltip('show');
      });

        $("#tchr_rgstr").validate({
            rules: {
              name: {
                required: true,
              },
              numeric_name: {
                required: true,
              },
              prifix: {
                required: true,
              },
            },
        });

        $('#tchr_rgstr [name="gender"]').val('{{ old('gender') }}');
      @if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
        $('a[href="#tab-11"]').tab("show");
      @else
        $('a[href="#tab-10"]').tab("show");
      @endif
      });
    </script>

    @endsection
