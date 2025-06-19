@extends('admin.layouts.master')

  @section('title', 'Teachers Attendance |')

  @section('head')
    <link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('admin.includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Teachers Attendance</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li Class="active">
                          <a>Teachers Attendance</a>
                      </li>
                  </ol>
              </div>
              <div class="col-lg-4 col-md-6">
                @include('admin.includes.academic_session')
              </div>
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
                            <li class="get-attendance">
                              <a data-toggle="tab" href="#tab-11"><span class="fa fa-bar-chart"></span> Attendance Reports</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade make-attendance">
                                <div class="panel-body" style="min-height: 400px">
                                  <h2> Make Attendance </h2>
                                  <div class="hr-line-dashed"></div>

                                    <form id="mk_att_frm" method="GET" action="{{ URL('teacher-attendance/make') }}" class="form-horizontal jumbotron" role="form" >

                                      <div class="form-group{{ ($errors->has('date'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label"> Date </label>
                                        <div class="col-md-6">
                                        <input id="datetimepicker4" type="text" name="date" class="form-control" placeholder="Date" value="{{ old('date') }}" required="true">
                                          @if ($errors->has('date'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('date') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group">
                                          <div class="col-md-offset-2 col-md-6">
                                              <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Make Attendence </button>
                                          </div>
                                      </div>

                                    </form>

                                    @if($root == 'make')
                                    <div class="row">
                                      <h3>Teachers Attendance Date: ({{ $input['date'] }})</h3>
                                      <div class="hr-line-dashed"></div>
                                      <form action="{{ URL('teacher-attendance/make') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="date" value="{{ $input['date'] }}">
                                        <table class="table table-striped table-bordered table-hover">
                                          <thead>
                                            <tr>
                                              <th>ID</th>
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
                                          @foreach($teachers as $teacher)
                                            <tr>
                                              <td>{{ $teacher->email }}</td>
                                              <td>{{ $teacher->name }}</td>
                                              <td>
                                                <input type="hidden" name="teacher_id[{{ $teacher->id }}]" value="{{ $teacher->id }}">
                                                <div class="checkbox checkbox-success">
                                                  <input id="checkbox{{ $teacher->id }}" class="selectAtt" type="checkbox" name="attendance{{ $teacher->id }}" value="1" {{ (isset($attendance[$teacher->id]->status) && $attendance[$teacher->id]->status)? 'checked' : '' }} />
                                                  <label for="checkbox{{ $teacher->id }}">
                                                  </label>
                                                </div>
                                              </td>
                                            </tr>
                                          @endforeach
                                          </tbody>
                                        </table>

                                        <div class="form-group">
                                            <div class="col-md-offset-4 col-md-4">
                                                <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Make Attendence </button>
                                            </div>
                                        </div>

                                      </form>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div id="tab-11" class="tab-pane fade get-attendance">
                                <div class="panel-body" style="min-height: 400px">
                                  <h2> Search Fields </h2>
                                  <div class="hr-line-dashed"></div>

                                    <form id="rpt_att_frm" method="GET" action="{{ URL('teacher-attendance/report') }}" class="form-horizontal jumbotron" role="form" >

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

                                    @if($root == 'report')
                                    <div class="row">
                                    <h3>Teachers Attendance Date: ({{ $input['date'] }})</h3>
                                      <div class="hr-line-dashed"></div>
                                        <div class="table-responsive">
                                          <table id="rpt-att" class="table table-striped table-bordered table-hover">
                                            <thead>
                                              <tr>
                                                <td style="text-align: center;">Teachers<i class="entypo-down-thin"></i>|Date<i class="entypo-right-thin"></i></td>
                                                @for($i=1; $i <= $dbdate['noofdays']; $i++)
                                                  <th>{{ $i }}</th>
                                                @endfor
                                              </tr>
                                            </thead>
                                            <tbody>
                                              @foreach($teachers as $teacher)
                                              <tr>
                                                <td>{{ $teacher->name }}</td>
                                                @for($i=1; $i <= $dbdate['noofdays']; $i++)
                                                  <th class="tchr_{{ $teacher->id }}_dat_{{ $i }}"></th>
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
      
        $('[data-toggle="tooltip"]').tooltip();
        $('div .checkbox').css('margin', '0px');

        $('.select-all').change(function(){
          $('.selectAtt').prop('checked', $(this).prop("checked"));
        });

        @if($root == 'report')
          $('#rpt_att_frm [name="date"]').val("{{ $input['date'] }}");
        @elseif($root == 'make')
          $('#mk_att_frm [name="date"]').val("{{ $input['date'] }}");
        @endif

      @if($root == 'report')
        attendancerpt = {!! json_encode($attendance) !!};
        // console.log(attendancerpt);
        $.each(attendancerpt, function(k, v){
          $.each(v, function(i, d){
            date = new Date(d.date);
            day = date.getDate();
/*            console.log(d);
            alert(day);
*/
            prefix = (d.status)? 'P' : '<span class="text-danger">A</span>';
            $('.tchr_'+k+'_dat_'+day).html(prefix);
          });
        });
        $('.nav-tabs a[href="#tab-11"]').tab('show');
      @else
//        $('.nav-tabs a[href="#tab-11"]').tab('show');
        $('.nav-tabs a[href="#tab-10"]').tab('show');
      @endif

        $('#datetimepicker4').datetimepicker({
                 format: 'DD/MM/YYYY'
           });
        $('#datetimepicker4r').datetimepicker({
                 format: 'MM/YYYY'
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
                    {extend: 'pdfHtml5', title: 'Attendance Report', orientation: 'landscape'},


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

      //Permission will be applied later
      // "Auth::user()->getprivileges->privileges->{$root['content']['id']}->make == 0)"
        // $('.make-attendance').hide();

      // "(Auth::user()->getprivileges->privileges->{$root['content']['id']}->report == 0)"
        // $('.get-attendance').hide();

      });
    </script>

    @endsection
