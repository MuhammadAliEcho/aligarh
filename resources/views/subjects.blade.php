@extends('layouts.master')

  @section('title', 'Classes |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/hrtab/hrtab.css') }}" rel="stylesheet">
  @endsection

  @section('content')

  @include('includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Subjects</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li Class="active">
                          <a>Subjects</a>
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
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class="">
                              <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Subjects</a>
                            </li>
                            <li class="add-subject">
                              <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Add Subject</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade ">
                                <div class="panel-body">

                                    <div class="container-fluid bhoechie-tab-container">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 bhoechie-tab-menu">
                                          <div class="list-group">
                                          @foreach($classes AS $k=>$class)
                                            <a href="#" class="list-group-item text-center {{ ($k == 0)? 'active' : '' }}">
                                              {{ $class->name }}
                                            </a>
                                          @endforeach
                                          </div>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-9 bhoechie-tab">
                                          @foreach($classes AS $k=>$class)
                                          <div class="bhoechie-tab-content {{ ($k == 0)? 'active' : '' }}">                                            
                                            <div class="table-responsive">
                                              <table class="table table-striped table-bordered table-hover dataTables-teacher" >
                                                <thead>
                                                  <tr>
                                                    <th>Name</th>
                                                    <th>Book</th>
                                                    <th>Teacher</th>
                                                    <th class="edit-subject">Options</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($subjects['class_'.$class->id] AS $k => $subject)
                                                  <tr>
                                                    <td>{{ $subject->name }}</td>
                                                    <td>{{ $subject->book }}</td>
                                                    <td>{{ $subject->teacher_name }}</td>
                                                    <td class="edit-subject">
                                                      <a href="{{ URL('manage-subjects/edit/'.$subject->id) }}" data-toggle="tooltip" title="Edit" class="btn btn-default btn-circle btn-xs edit-option">
                                                        <span class="fa fa-edit"></span>
                                                      </a>
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
                            <div id="tab-11" class="tab-pane fade add-subject">
                                <div class="panel-body">
                                  <h2> Subject Registration </h2>
                                  <div class="hr-line-dashed"></div>

                                    <form id="tchr_rgstr" method="post" action="{{ URL('manage-subjects/add') }}" class="form-horizontal">
                                      {{ csrf_field() }}

                                      <div class="form-group{{ ($errors->has('class'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Class</label>
                                        <div class="col-md-6 select2-div">
                                          <select class="form-control select2" name="class" style="width: 100%">
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
                                        <label class="col-md-2 control-label">Name</label>
                                        <div class="col-md-6">
                                          <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" class="form-control"/>
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
                                          <input type="text" name="book" placeholder="Book" value="{{ old('book') }}" class="form-control"/>
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
                                          <select class="form-control select2" name="teacher" style="width: 100%">
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


          @include('includes.footercopyright')


        </div>

    @endsection

    @section('script')

    <!-- Mainly scripts -->
    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <script src="{{ URL::to('src/js/plugins/hrtab/hrtab.js') }}"></script>


    <!-- Select2 -->
    <script src="{{ URL::to('src/js/plugins/select2/select2.full.min.js') }}"></script>

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

        $('#tchr_rgstr [name="teacher"]').val('{{ old('teacher') }}');
        $('#tchr_rgstr [name="class"]').val("{{ old('class') }}");
        $('.select2').select2({
            placeholder: "Nothing Selected",
            allowClear: true,
        });
//        $('.select2-div>span').attr('style', 'width:100%');
        $('[data-toggle="tooltip"]').tooltip();

      @if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
        $('a[href="#tab-11"]').tab('show');
      @else
        $('a[href="#tab-10"]').tab('show');
      @endif

      @if(Auth::user()->privileges->{$root['content']['id']}->add == 0)
        $('.add-subject').hide();
      @endif

      @if(Auth::user()->privileges->{$root['content']['id']}->edit == 0)
        $('.edit-subject').hide();
      @endif

      });
    </script>

    @endsection
