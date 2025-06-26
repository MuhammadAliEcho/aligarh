@extends('admin.layouts.master')

  @section('title', 'Routine |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/hrtab/hrtab.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
  <!-- Sweet Alert -->
  <link href="{{ URL::to('src/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <script type="text/javascript">
      var sections = {!! json_encode($sections) !!};
      var subjects = {!! json_encode($subjects) !!};
    </script>
  @endsection

  @section('content')

  @include('admin.includes.side_navbar')


        <div id="page-wrapper" class="gray-bg">

          @include('admin.includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Routines</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li Class="active">
                          <a>Routines</a>
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
                            <li class="active">
                              <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Routines</a>
                            </li>
                            @can('routines.add')
                            <li class="add-routine-tab">
                              <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Add Routine</a>
                            </li>
                            @endcan
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade in active">
                                <div class="panel-body">
                                  @foreach($classes AS $ck=>$class)
                                    <div class="ibox fload-e-margins">
                                      <div class="ibox-title back-change">
                                        <h5 class="collapse-link">Class: {{ $class->name }}</h5>
                                        <div class="ibox-tools">
                                          @can('routines.add')
                                          <a class-id="{{ $class->id }}" data-type="class" class="add-routine">
                                            <span class="fa fa-plus" data-toggle="tooltip" title="Add Routine"></span>
                                          </a>
                                          @endcan
                                          <a class="collapse-link">
                                            <i data-toggle="tooltip" title="Collapse" class="fa fa-chevron-up"></i>
                                          </a>
                                        </div>
                                      </div>
                                      <div class="ibox-content collapse" style="padding: 0px;">
                                        
                                        <div class="container-fluid bhoechie-tab-container">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 bhoechie-tab-menu">
                                              <div class="list-group">
                                              @foreach($sections['class_'.$class->id] AS $sk=>$section)
                                                <a data-toggle="tab" data-cid="{{ $class->id }}" href="#tab-section-{{ $section->id }}" class="list-group-item text-center {{ ($sk == 0)? 'active' : '' }}">
                                                  {{ $section->name }}
                                                </a>
                                              @endforeach
                                              </div>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-9 bhoechie-tab">
                                              @foreach($sections['class_'.$class->id] AS $sk=>$section)
                                              <div id="tab-section-{{ $section->id }}" class="bhoechie-tab-content {{ ($sk == 0)? 'active' : '' }}">                                            
                                                <div class="table-responsive">
                                                  <table class="table table-striped table-bordered table-hover dataTables-teacher" >
                                                    <thead>
                                                      <tr>
                                                        <th style="width: 90px">Days</th>
                                                        <th>Descriptions</th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($routines['section_'.$section->id] AS $day => $routine)
                                                      <tr>
                                                        <td>{{ $day }}</td>
                                                        <td>

                                                          @foreach($routine AS $routin)
                                                            <div class="btn-group">
                                                              <button data-toggle="dropdown" routine-id-btn="{{ $routin->id }}" class="btn btn-default btn-sm dropdown-toggle" aria-expanded="false">
                                                              {{ $routin->subject_name.' ( '.$routin->from_time.'-'.$routin->to_time.' ) ' }}
                                                              <span class="caret"></span></button>
                                                              <ul class="dropdown-menu">
                                                                @can('routines.edit.post')
                                                                <li class="edit-routine"><a href="{{ URL('routines/edit/'.$routin->id) }}"><span class="fa fa-edit"></span> Edit</a></li>
                                                                @endcan
                                                                @can('routines.delete')
                                                                <li class="delete-routine"><a routine-id="{{ $routin->id }}" href="#" class="delete-btn" ><span class="fa fa-trash"></span> Delete</a></li>
                                                                @endcan
                                                              </ul>
                                                            </div>
                                                          @endforeach
                                                          @can('routines.add')
                                                          <a data-toggle="tooltip" title="Add Routine" class-id="{{ $class->id }}" section-id="{{ $section->id }}" day="{{ $day }}" data-type="day" class="add-routine pull-right">
                                                            <span class="fa fa-plus"></span>
                                                          </a>
                                                          @endcan

                                                        </td>
                                                      </tr>
                                                    @endforeach
                                                    </tbody>
                                                  </table>
                                                </div>
                                              </div>
                                              @endforeach

                                            </div>
                                        </div>

                                      </div>
                                    </div>
                                  @endforeach
                                </div>
                            </div>
                            <div id="tab-11" class="tab-pane fade add-routine-tab">
                                <div class="panel-body">
                                  <h2> Routine Registration </h2>
                                  <div class="hr-line-dashed"></div>

                                    <form id="tchr_rgstr" method="post" action="{{ URL('routines/add') }}" class="form-horizontal">
                                      {{ csrf_field() }}

                                      <div class="form-group{{ ($errors->has('class'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Class</label>
                                        <div class="col-md-6 select2-div">
                                          <select class="form-control select2" name="class">
                                            <option></option>
                                            @foreach($classes AS $class)
                                              <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                          </select>
                                          @if ($errors->has('class'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('class') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('section'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Section</label>
                                        <div class="col-md-6 select2-div">
                                          <select class="form-control select2" name="section">
                                          </select>
                                          @if ($errors->has('section'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('section') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('subject'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Subject</label>
                                        <div class="col-md-6 select2-div">
                                          <select class="form-control select2" name="subject">
                                          </select>
                                          @if ($errors->has('subject'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('subject') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('teacher'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Teacher</label>
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

                                      <div class="form-group{{ ($errors->has('day'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Day</label>
                                        <div class="col-md-6 select2-div">
                                          <select class="form-control select2" name="day">
                                            <option></option>
                                            @foreach($days AS $day)
                                              <option value="{{ $day }}">{{ $day }}</option>
                                            @endforeach
                                          </select>
                                          @if ($errors->has('day'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('day') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('from_time'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Start At</label>
                                        <div class="col-md-6">
                                          <input type="text" name="from_time" placeholder="Time" value="{{ old('from_time') }}" class="form-control timepicker"/>
                                          @if ($errors->has('from_time'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('from_time') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('to_time'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">End At</label>
                                        <div class="col-md-6">
                                          <input type="text" name="to_time" placeholder="Time" value="{{ old('to_time') }}" class="form-control timepicker"/>
                                          @if ($errors->has('to_time'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('to_time') }}</strong>
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
    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>
{{--
    <script src="{{ URL::to('src/js/plugins/hrtab/hrtab.js') }}"></script>
--}}

    <!-- require with bootstrap-datetimepicker -->
    <script src="{{ URL::to('src/js/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

    <!-- Sweet alert -->
    <script src="{{ URL::to('src/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ URL::to('src/js/plugins/select2/select2.full.min.js') }}"></script>

    <script type="text/javascript">

      $(document).ready(function(){

        $('[data-toggle="tooltip"]').tooltip();

        $('[data-toggle="tab"]').click(function(){
          cid =  $(this).attr('data-cid');
          $('[data-cid="'+cid+'"]').removeClass('active');
          $(this).addClass("active");
        });

        $('.timepicker').datetimepicker({
          format: 'LT',
          useCurrent: true
        });

        $("#tchr_rgstr").validate({
            rules: {
              class: {
                required: true,
              },
              section: {
                required: true,
              },
              subject: {
                required: true,
              },
/*              teacher: {
                required: true,
              },
*/              day: {
                required: true,
              },
              from_time: {
                required: true,
              },
              to_time: {
                required: true,
              },
            },
        });

        $('.select2').attr('style', 'width:100%').select2({
                placeholder: "Nothing Selected",
                allowClear: true,
            });

        $('#tchr_rgstr [name="class"]').on('change', function(){
          clsid = $(this).val();

            $('#tchr_rgstr [name="section"]').html('');
            $('#tchr_rgstr [name="subject"]').html('');
          if(sections['class_'+clsid].length > 0){
            $.each(sections['class_'+clsid], function(k, v){
              $('#tchr_rgstr [name="section"]').append('<option value="'+v['id']+'">'+v['name']+'</option>');
            });
          } else {
            $('#tchr_rgstr [name="section"]').html('<option></option>');
          }

          if(subjects['class_'+clsid].length > 0){
            $.each(subjects['class_'+clsid], function(k, v){
              $('#tchr_rgstr [name="subject"]').append('<option value="'+v['id']+'">'+v['name']+'</option>');
            });
          } else {
            $('#tchr_rgstr [name="subject"]').html('<option></option>');
          }
          $('#tchr_rgstr [name="section"]').select2('val', $('#tchr_rgstr [name="section"] option:first').val());
          $('#tchr_rgstr [name="subject"]').select2('val', $('#tchr_rgstr [name="subject"] option:first').val());

        });

        @if(COUNT($errors) >= 1)
          $('#tchr_rgstr [name="class"]').val("{{ old('class') }}");
          $('#tchr_rgstr [name="class"]').change();
          $('#tchr_rgstr [name="section"]').val("{{ old('section') }}");
          $('#tchr_rgstr [name="subject"]').val("{{ old('subject') }}");
          $('#tchr_rgstr [name="teacher"]').val("{{ old('teacher') }}");
          $('#tchr_rgstr [name="day"]').val("{{ old('day') }}");
        @endif


        $('.add-routine').click(function(){
          $('#tchr_rgstr [name="class"]').val($(this).attr('class-id')).change();
          datatype = $(this).attr('data-type');
          switch(datatype){
            case 'section':
              $('#tchr_rgstr [name="section"]').select2('val', $(this).attr('section-id'));
            break;
            case 'day':
              $('#tchr_rgstr [name="section"]').select2('val', $(this).attr('section-id'));
              $('#tchr_rgstr [name="day"]').select2('val', $(this).attr('day'));
            break;
          }
          $('.nav-tabs a[href="#tab-11"]').tab('show');
        });

        $('.delete-btn').click(function(){
          var routineid = $(this).attr('routine-id');
          swal({
                title: "Are you sure?",
                text: "Sure You Want to Delete This Routine",
                type: "warning",
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: false,
                closeOnCancel: false,
              },
            function (isConfirm) {
                if (isConfirm) {
                      swal({ title: "Wait....",   text: "<i class='fa fa-spinner fa-pulse fa-4x' ></i>",   html: true, showConfirmButton: false });
                      $.post( "{{ URL('routines/delete') }}", { id: routineid, _token: "{{ csrf_token() }}" })
                        .done(function( data ) {
                          $('[routine-id-btn="'+routineid+'"]').parent('div').remove();
                          swal("Deleted!", "Routine has been deleted.", "success");
                      })
                        .fail(function(){
                          swal("Error!", "Routine routine is safe :)", "error");
                        });
//                    swal("Deleted!", "Your imaginary file has been deleted.", "success");
                } else {
                  swal("Cancelled!", "Routine routine is safe :)", "error");
                }
            });
        });

      @if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
        $('a[href="#tab-11"]').tab('show');
      @endif

      // "if(Auth::user()->getprivileges->privileges->{$root['content']['id']}->add == 0)"
        // $('.add-routine-tab').hide();
      // "endif"

      // "if(Auth::user()->getprivileges->privileges->{$root['content']['id']}->edit == 0)"
        // $('.edit-routine').hide();
      // "endif"

      // "if(Auth::user()->getprivileges->privileges->{$root['content']['id']}->delete == 0)"
        // $('.delete-routine').hide();
      // "endif"


      });
    </script>

    @endsection
