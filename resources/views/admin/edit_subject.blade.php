@extends('admin.layouts.master')

  @section('title', __('modules.pages_classes_title').' |')

  @section('head')
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
                  <h2>Subjects</h2>
                  <ol class="breadcrumb">
                    <li>{{ __("common.home") }}</li>
                      <li Class="active">
                          <a>Subjects</a>
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
                        <h2>{{ __('modules.forms_edit_subject') }}</h2>
                        <div class="hr-line-dashed"></div>
                    </div>

                    <div class="ibox-content">

                                    <form id="tchr_rgstr" method="post" action="{{ URL('manage-subjects/edit/'.$subject['id']) }}" class="form-horizontal">
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

                                      <div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">{{ __('labels.name') }}</label>
                                        <div class="col-md-6">
                                          <input type="text" name="name" placeholder="{{ __("labels.name_placeholder") }}" value="{{ old('name', $subject['name']) }}" class="form-control"/>
                                          @if ($errors->has('name'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('name') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('book'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Book</label>
                                        <div class="col-md-6">
                                          <input type="text" name="book" placeholder="Nick Name" value="{{ old('book', $subject['book']) }}" class="form-control"/>
                                          @if ($errors->has('book'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('book') }}</strong>
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

                                      <div class="form-group{{ ($errors->has('examinable'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Examinable</label>
                                        <div class="col-md-6">
                                          <select class="form-control" name="examinable">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                          </select>
                                          @if ($errors->has('examinable'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('examinable') }}</strong>
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
    <script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <script src="{{ asset('src/js/plugins/hrtab/hrtab.js') }}"></script>


    <!-- Select2 -->
    <script src="{{ asset('src/js/plugins/select2/select2.full.min.js') }}"></script>

    <script type="text/javascript">

      $(document).ready(function(){

        $("#tchr_rgstr").validate({
            rules: {
              name: {
                required: true,
              },
/*              teacher: {
                required: true,
              },
*/              class: {
                required: true,
              },
              book: {
                required: true,
              },
            },
        });

        $('#tchr_rgstr [name="teacher"]').val("{{ old('teacher', $subject['teacher_id']) }}");
        $('#tchr_rgstr [name="class"]').val("{{ old('class', $subject['class_id']) }}");
        $('#tchr_rgstr [name="examinable"]').val("{{ old('examinable', $subject['examinable']) }}");
        $('.select2').select2({
                placeholder: "Nothing Selected",
                allowClear: true,
            });
        $('.select2-div>span').attr('style', 'width:100%');
      });
    </script>

    @endsection
