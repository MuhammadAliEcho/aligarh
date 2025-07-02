@extends('admin.layouts.master')

  @section('title', 'Students Attendance |')

  @section('head')
    <link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
  <script type="text/javascript">
      var sections = {!! json_encode($sections) !!};
  </script>
  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('admin.includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Students Attendance</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li Class="active">
                          <a>Students Attendance</a>
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
                            <li class="make-attendance">
                              <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Make Attendance </a>
                            </li>
                            @can('student-attendance.report')
                              <li class="get-attendance">
                                <a data-toggle="tab" href="#tab-11"><span class="fa fa-bar-chart"></span> Attendance Reports</a>
                              </li>
                            @endcan
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade make-attendance">
                                <div class="panel-body" style="min-height: 400px">
                                  <h2> Make Attendance </h2>
                                  <div class="hr-line-dashed"></div>

                                    <form id="mk_att_frm" method="GET" action="{{ URL('student-attendance/make') }}" class="form-horizontal jumbotron" role="form" >

                                      <div class="form-group{{ ($errors->has('class'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label"> Class </label>
                                        <div class="col-md-6">
                                          <select class="form-control select2" name="class" required="true">
                                            <option value="" disabled selected>Class</option>
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
                                        <label class="col-md-2 control-label"> Section </label>
                                        <div class="col-md-6">
                                          <select class="form-control select2" name="section">
                                          <option value="" disabled selected>Section</option>
                                          </select>
                                          @if ($errors->has('section'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('section') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('date'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label"> Date </label>
                                        <div class="col-md-6">
                                        <input id="datetimepicker4" type="text" name="date" class="form-control" placeholder="Date" value="{{ old('date') }}" required="true" autocomplete="off">
                                          @if ($errors->has('date'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('date') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group">
                                          <div class="col-md-offset-2 col-md-6">
                                              <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Make Attendance </button>
                                          </div>
                                      </div>

                                    </form>

                                    {{--Permission will be applied later on controller --}}
                                    @if($root)
                                    <div class="row">
                                      <h3>Class: {{ $selected_class->name.' '.$section_nick }} ({{ $input['date'] }})</h3>
                                      <div class="hr-line-dashed"></div>
                                      <form id="submitAttenFrm" action="{{ URL('student-attendance/make') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="date" value="{{ $input['date'] }}">
                                        <table class="table table-striped table-bordered table-hover">
                                          <thead>
                                            <tr>
                                              <th>GR No</th>
                                              <th>Name</th>
                                              <th>
                                                <div class="checkbox checkbox-success">
                                                  <input class="select-all" id="checkbox" type="checkbox" />
                                                  <label data-toggle="tooltip" title="select all" for="checkbox">
                                                    <b>Attendance</b>
                                                  </label>
                                                </div>
                                              </th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                          @foreach($students as $student)
                                            <tr>
                                              <td>{{ $student->gr_no }}</td>
                                              <td>{{ $student->name }}</td>
                                              <td>
                                                <input type="hidden" name="student_id[{{ $student->id }}]" value="{{ $student->id }}">
                                                <div class="pull-left checkbox checkbox-success">
                                                  <input id="checkbox{{ $student->id }}" class="selectAtt" type="checkbox" name="attendance{{ $student->id }}" value="1" {{ (isset($student->StudentAttendanceByDate->status) && $student->StudentAttendanceByDate->status)? 'checked' : '' }} />
                                                  <label for="checkbox{{ $student->id }}">
                                                  </label>
                                                </div>
                                                <div class="pull-right">
                                                	<a href="#" class="btn text-danger remvoebtn" data-id="{{ $student->id }}" data-toggle="tooltip" title="remove" ><span class="fa fa-remove"></span></a>
                                                </div>
                                              </td>
                                            </tr>
                                          @endforeach
                                          </tbody>
                                        </table>

                                        <div class="form-group">
                                            <div class="col-md-offset-4 col-md-4">
                                                <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Make Attendance </button>
                                            </div>
                                        </div>

                                      </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @can('student-attendance.report')
                              <div id="tab-11" class="tab-pane fade get-attendance">
                                  <div class="panel-body" style="min-height: 400px">
                                    <h2> Search Fields </h2>
                                    <div class="hr-line-dashed"></div>

                                      <form id="rpt_att_frm" method="GET" action="{{ URL('student-attendance/report') }}" class="form-horizontal jumbotron" target="_blank" role="form" >

                                        <div class="form-group{{ ($errors->has('class'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label"> Class </label>
                                          <div class="col-md-6">
                                            <select class="form-control select2" name="class" required="true">
                                              <option value="" disabled selected>Class</option>
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
                                          <label class="col-md-2 control-label"> Section </label>
                                          <div class="col-md-6">
                                            <select class="form-control select2" name="section">
                                            <option value="" disabled selected>Section</option>
                                            </select>
                                            @if ($errors->has('section'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('section') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group{{ ($errors->has('date'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label"> Date Month </label>
                                          <div class="col-md-6">
                                          <input id="datetimepicker4r" type="text" name="date" class="form-control" placeholder="Date" value="{{ old('date') }}" required="true" autocomplete="off">
                                            @if ($errors->has('date'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('date') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-offset-2 col-md-6">
                                                <button class="btn btn-primary" type="submit"><span class="fa fa-list"></span> Show Report </button>
                                            </div>
                                        </div>

                                      </form>
                                      {{-- disable --}}
                                      @php
                                        $job = 0;
                                      @endphp
                                      @if($job)
                                      <div class="row">
                                      <h3>Class: {{ $selected_class->name.' '.$section_nick }} ({{ $input['date'] }})</h3>
                                      <h4>No Of Students: {{ COUNT($students) }}</h3>
                                      <h4>Teacher: {{ $selected_class->Teacher->name }}</h3>
                                        <div class="hr-line-dashed"></div>
                                          <div class="table-responsive">
                                            <table id="rpt-att" class="table table-striped table-bordered table-hover">
                                              <thead>
                                                <tr>
                                                  <td style="text-align: center;">
                                                    Students <i class="entypo-down-thin"></i> | Date <i class="entypo-right-thin"></i>
                                                  </td>
                                                  @for($i=1; $i <= $noofdays; $i++)
                                                    <th>{{ $i }}</th>
                                                  @endfor
                                                </tr>
                                              </thead>
                                              <tbody>
                                                @foreach($students as $student)
                                                <tr>
                                                  <td>{{ $student->name }}</td>
                                                  @for($i=1; $i <= $noofdays; $i++)
                                                    <th  class="std_{{ $student->id }}_dat_{{ $i }} {{ in_array($i, $sundays)? 'h' : '' }}"></th>
                                                  @endfor
                                                </tr>
                                                @endforeach
                                              </tbody>
                                            </table>
                                          </div>
                                      </div>
                                      @endif

                                    </div>
                              </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

          </div>


          @include('admin.includes.footercopyright')


        </div>

    @endsection

    @section('script')

    <script src="{{ URL::to('src/js/plugins/dataTables/datatables.min.js') }}"></script>

    <!-- require with bootstrap-datetimepicker -->
    <script src="{{ URL::to('src/js/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

    <script type="text/javascript">

    var tbl;
    var attendancerpt;
      $(document).ready(function(){

		$(".remvoebtn").click(function(){
			var stdid =	$(this).attr('data-id');
      		$(this).closest('tr').remove();
      		$("#submitAttenFrm").append('<input type="hidden" name="delete['+stdid+']" value="'+stdid+'" />');
		});	
      
        $('[data-toggle="tooltip"]').tooltip();
        $('div .checkbox').css('margin', '0px');

        $('.select-all').change(function(){
          $('.selectAtt').prop('checked', $(this).prop("checked"));
        });

      $('[name="class"]').on('change', function(){
        clsid = $(this).val();
        $('[name="class"]').val(clsid);
          $('[name="section"]').html('<option></option>');
          if(sections['class_'+clsid].length > 0 && clsid > 0){          
            $.each(sections['class_'+clsid], function(k, v){
              $('[name="section"]').append('<option value="'+v['id']+'">'+v['name']+'</option>');
            });
          }
      });

      @if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
        $('#mk_att_frm [name="class"]').val("{{ old('class') }}");
        $('[name="class"]').change();
        $('[name="section"]').val('{{ old('section') }}');
        @if($root['job'] == 'report')
          $('#rpt_att_frm [name="date"]').val("{{ old('date') }}");
        @else
          $('#mk_att_frm [name="date"]').val("{{ old('date') }}");
        @endif
      @elseif(isset($input) && $input !== null)
        $('#mk_att_frm [name="class"]').val("{{ $input['class'] }}");
        $('[name="class"]').change();
        $('[name="section"]').val("{{ $input['section'] }}");

        $('#mk_att_frm [name="date"]').val("{{ $input['date'] }}");

      @endif
{{--
      @if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
        $('.nav-tabs a[href="#tab-11"]').tab('show');
      @else

      @endif
--}}


//        $('.nav-tabs a[href="#tab-11"]').tab('show');
        $('.nav-tabs a[href="#tab-10"]').tab('show');

        $('#datetimepicker4').datetimepicker({
                 format: 'DD/MM/YYYY'
           });
        $('#datetimepicker4r').datetimepicker({
                 format: 'MM/YYYY',
           });

        $('table#rpt-att').DataTable({
                dom: '<"html5buttons"B>lTfgitp',
                bSort : false,
                searching: false,
                paging: false,
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'Attendance Report'},
                    {extend: 'pdf', title: 'Attendance Report'},

                    {extend: 'print',
                      customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        $(win.document.body).find('table td')
                                .css('padding', '2px');
                        $(win.document.body).find('table th')
                                .css('padding', '2px');
                        $(win.document.body).find('h1')
                                .css('font-size', '20px');

                      }
                    }
                ]

            });
      });
    </script>

    @endsection
