@extends('admin.layouts.master')

@section('title', 'Academic Session |')

@section('head')
    <link href="{{ asset('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
		<link href="{{ asset('src/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
@endsection

@section('content')

    @include('admin.includes.side_navbar')

    <div id="page-wrapper" class="gray-bg">

        @include('admin.includes.top_navbar')

        <!-- Heading -->
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-md-6">
                <h2>Academic Session</h2>
                <ol class="breadcrumb">
                    <li>Home</li>
                    <li Class="active">
                        <a>Session</a>
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
                                <a data-toggle="tab" onClick="drawTable()" href="#tab-10"><span class="fa fa-list"></span> Sessions</a>
                            </li>
                            @can('academic-sessions.create')
                                <li class="add-item">
                                    <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Add Session</a>
                                </li>
                            @endcan
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade ">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table
                                            class="table table-striped table-bordered table-hover dataTables-academic-sessions">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Title</th>
                                                    <th>Start</th>
                                                    <th>End</th>
                                                    <th>Options</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                </div>
                            </div>
                            @can('academic-sessions.create')
                                <div id="tab-11" class="tab-pane fade add-item">
                                    <div class="panel-body">
                                        <h2> Session Registration </h2>
                                        <div class="hr-line-dashed"></div>

                                        <form id="vdr_rgstr" method="post" action="{{ Route('academic-sessions.create') }}"
                                            class="form-horizontal">
                                            {{ csrf_field() }}

                                            <div class="form-group{{ $errors->has('start') ? ' has-error' : '' }}">
                                              <label class="col-md-2 control-label">Session Start</label>
                                              <div class="col-md-6">
                                                <div class="input-group date" id="start">
                                                  <input type="text" class="input-sm form-control" value="{{ old('start') }}" name="start" placeholder="Start Month" required readonly />
                                                  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                </div>
                                                @if ($errors->has('start'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('start') }}</strong>
                                                        </span>
                                                    @endif
                                              </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('end') ? ' has-error' : '' }}">
                                              <label class="col-md-2 control-label">Session End</label>
                                              <div class="col-md-6">
                                                <div class="input-group date" id="end">
                                                  <input type="text" class="input-sm form-control" value="{{ old('end') }}" name="end" placeholder="End Month" required readonly />
                                                  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                </div>
                                                @if ($errors->has('end'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('end') }}</strong>
                                                        </span>
                                                    @endif
                                              </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Title</label>
                                                <div class="col-md-6">
                                                    <input type="text" id="title" readonly 
                                                        value="" class="form-control" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-offset-2 col-md-6">
                                                    <button class="btn btn-primary" type="submit"><span
                                                            class="glyphicon glyphicon-save"></span> Add Session </button>
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
    <script src="{{ asset('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>
    <script src="{{ asset('src/js/plugins/dataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
	<script src="{{ asset('src/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>


    <script type="text/javascript">
    
        var tbl = null;

        function drawTable() {
            if (tbl == null) {
            tbl = $('.dataTables-academic-sessions').DataTable({
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [{
                        extend: 'copy'
                    },
                    {
                        extend: 'csv'
                    },
                    {
                        extend: 'excel',
                        title: 'ExampleFile'
                    },
                    {
                        extend: 'pdf',
                        title: 'ExampleFile'
                    },

                    {
                        extend: 'print',
                        customize: function(win) {
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
                ajax: '{{ URL('academic-sessions') }}',
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'title'
                    },
                    {
                        data: 'start'
                    },
                    {
                        data: 'end'
                    },
                    {
                        render: loadOptions,
                        className: 'hidden-print',
                        "orderable": false
                    },
                ],
            });
            }
        }

        function loadOptions(data, type, full, meta) {

            opthtm = '';
            @can('academic-sessions.update')
                opthtm += '<a href="{{ URL('academic-sessions/edit') }}/' + full.id +
                    '" data-toggle="tooltip" title="Edit Session" class="btn btn-default btn-circle btn-xs"><span class="fa fa-edit"></span></a>';
            @endcan
            // @can('academic-sessions.delete')
            //     opthtm += '<a href="{{ URL('academic-sessions/delete') }}/' + full.id +
            //         '" data-toggle="tooltip" title="Delete Session" class="btn btn-default btn-circle btn-xs"><span class="fa fa-trash-o"></span></a>';
            // @endcan

            return opthtm;
        }

        function updateTitleField() {
          var start = $('[name="start"]').val();
          var end = $('[name="end"]').val();

          if (start && end) {
            var startDate = new Date(start);
            var endDate = new Date(end);

            if (endDate <= startDate) {
              alert("End month must be after Start month.");
              $('[name="end"]').val('');
              $('#title').val('');
              return;
            }
            var startYear = startDate.getFullYear();
            var endYear = endDate.getFullYear();
            $('#title').val(startYear + '-' + endYear);
          }
        }




        $(document).ready(function() {
            @if ($errors->any())
                $('a[href="#tab-11"]').tab('show'); 
            @else    
                $('a[href="#tab-10"]').tab('show');
                drawTable();
            @endif

            $('#start').datepicker({
              format: 'yyyy-mm-01',
              minViewMode: 1,
              autoclose: true,
              todayHighlight: true
            }).on('changeDate', function(e) {
              if (!e.date) return; 
              updateTitleField();
            });


            $('#end').datepicker({
            format: 'yyyy-mm-dd',
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            minViewMode: 1,
            todayHighlight: true
            }).on('changeDate', function(e) {
                if (!e.date) return;
                var year = e.date.getFullYear();
                var month = e.date.getMonth() + 1;
                var lastDay = new Date(year, month, 0).getDate(); 
                var mm = month < 10 ? '0' + month : month;
                var dd = lastDay < 10 ? '0' + lastDay : lastDay;
                var formatted = year + '-' + mm + '-' + dd;
                $(this).datepicker('update', formatted); 
                $(this).find('input').val(formatted);
                updateTitleField();
            });

            $('.dataTables-academic-sessions tbody').on('mouseenter', '[data-toggle="tooltip"]', function() {
                $(this).tooltip('show');
            });

            $("#vdr_rgstr").validate({
                rules: {
                    title: {
                        required: true,
                    },
                    qty: {
                        required: true,
                    },
                },
            });
        });
    </script>
@endsection
