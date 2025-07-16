@extends('admin.layouts.master')

  @section('title', 'Sections |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/hrtab/hrtab.css') }}" rel="stylesheet">
  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('admin.includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Sections</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li Class="active">
                          <a>Sections</a>
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
                              <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Sections</a>
                            </li>
                            @can('manage-sections.add')
                              <li class="add-section">
                                <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Add Section</a>
                              </li>
                            @endcan
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade">
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
                                                    <th>Nick Name</th>
                                                    <th>Capacity</th>
                                                    <th>Teacher</th>
                                                    <th class="edit-section">Options</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($class->Section AS $k => $section)
                                                  <tr>
                                                    <td>{{ $section->name }}</td>
                                                    <td>{{ $section->nick_name }}</td>
                                                    <td>{{ $section->Students->count() }} | {{ $section->capacity }}</td>
                                                    <td>{{ $section->Teacher['name'] }}</td>
                                                    <td class="edit-section">
                                                      @can('manage-sections.edit.post')
                                                      <a href="{{ URL('manage-sections/edit/'.$section->id) }}" data-toggle="tooltip" title="Edit" class="btn btn-default btn-circle btn-xs edit-option">
                                                        <span class="fa fa-edit"></span>
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
                            @can('manage-sections.add')
                              <div id="tab-11" class="tab-pane fade add-section">
                                  <div class="panel-body">
                                    <h2> Section Registration </h2>
                                    <div class="hr-line-dashed"></div>

                                      <form id="tchr_rgstr" method="post" action="{{ URL('manage-sections/add') }}" class="form-horizontal">
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

                                        <div class="form-group{{ ($errors->has('nick_name'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Nick Name</label>
                                          <div class="col-md-6">
                                            <input type="text" name="nick_name" placeholder="Nick Name" value="{{ old('nick_name') }}" class="form-control"/>
                                            @if ($errors->has('nick_name'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('nick_name') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group{{ ($errors->has('capacity'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Student Capacity</label>
                                          <div class="col-md-6">
                                            <input type="number" name="capacity" placeholder="Student Capacity" value="{{ old('capacity') }}" class="form-control" min="1" />
                                            @if ($errors->has('capacity'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('capacity') }}</strong>
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

                                        <div class="form-group">
                                            <div class="col-md-offset-2 col-md-6">
                                                <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Register </button>
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
    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <script src="{{ URL::to('src/js/plugins/hrtab/hrtab.js') }}"></script>


    <!-- Select2 -->
    <script src="{{ URL::to('src/js/plugins/select2/select2.full.min.js') }}"></script>

    <script type="text/javascript">

      $(document).ready(function(){


        $('.select2').select2({
                placeholder: "Nothing Selected",
                allowClear: true,
            });

        $('.select2-div>span').attr('style', 'width:100%');


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
              nick_name: {
                required: true,
              },
            },
        });

        $('#tchr_rgstr [name="teacher"]').val('{{ old('teacher') }}');
        $('#tchr_rgstr [name="class"]').val("{{ old('class') }}");
        $('[data-toggle="tooltip"]').tooltip();

      @if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
        $('a[href="#tab-11"]').tab('show');
      @else
        $('a[href="#tab-10"]').tab('show');
      @endif
      });
    </script>

    @endsection
