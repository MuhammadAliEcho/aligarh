@extends('admin.layouts.master')

  @section('title', 'Routine |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <script type="text/javascript">
      var sections = {!! json_encode($sections ?? '') !!};
      var subjects = {!! json_encode($subjects ?? '') !!};
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
                        <h2>Edit Routine</h2>
                        <div class="hr-line-dashed"></div>
                    </div>

                    <div class="ibox-content">

                                    <form id="tchr_rgstr" method="post" action="{{ URL('routines/edit/'.$routine->id) }}" class="form-horizontal">
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
                                            <option></option>
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
                                            <option></option>
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
                                              <option>{{ $day }}</option>
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
                                          <input type="text" name="from_time" placeholder="Time" value="{{ old('from_time', $routine->from_time) }}" class="form-control timepicker"/>
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
                                          <input type="text" name="to_time" placeholder="Time" value="{{ old('to_time', $routine->to_time) }}" class="form-control timepicker"/>
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

    @endsection

    @section('script')

    <!-- Mainly scripts -->
    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- require with bootstrap-datetimepicker -->
    <script src="{{ URL::to('src/js/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ URL::to('src/js/plugins/select2/select2.full.min.js') }}"></script>

    <script type="text/javascript">

      $(document).ready(function(){

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

        $('#tchr_rgstr [name="class"]').change(function(){
          clsid = $(this).val();
          $('#tchr_rgstr [name="section"]').html('');
          $('#tchr_rgstr [name="subject"]').html('<option></option>');
          
          $.each(sections['class_'+clsid], function(k, v){
            $('#tchr_rgstr [name="section"]').append('<option value="'+v['id']+'">'+v['name']+'</option>');
          });
          $.each(subjects['class_'+clsid], function(k, v){
            $('#tchr_rgstr [name="subject"]').append('<option value="'+v['id']+'">'+v['name']+'</option>');
          });
          $('#tchr_rgstr [name="section"]').select2('val', $('#tchr_rgstr [name="section"] option:first').val());
          $('#tchr_rgstr [name="subject"]').select2('val', $('#tchr_rgstr [name="subject"] option:first').val());
        });

        $('#tchr_rgstr [name="class"]').val("{{ old('class', $routine->class_id) }}");
        $('#tchr_rgstr [name="class"]').change();
        $('#tchr_rgstr [name="section"]').select2('val', "{{ old('section', $routine->section_id) }}");
        $('#tchr_rgstr [name="subject"]').select2('val', "{{ old('subject', $routine->subject_id) }}");

        $('#tchr_rgstr [name="teacher"]').select2('val', '{{ old('teacher', $routine->teacher_id) }}');
        $('#tchr_rgstr [name="day"]').select2('val', "{{ old('subject', $routine->day) }}");


      @if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
        $('a[href="#tab-11"]').click();
      @endif

      });

    </script>

    @endsection
