@php use Illuminate\Support\Str; @endphp
@extends('admin.layouts.master')

@section('title', 'Attendance Leave |')

@section('head')
    <link href="{{ URL::to('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    <style type="text/css">
        .print-table {
            width: 100%;
        }

        .print-table th,
        .print-table td {
            border: 1px solid black !important;
            padding: 0px;
        }

        .print-table>tbody>tr>td {
            padding: 1px;
        }

        .print-table>thead>tr>th {
            padding: 3px;
        }
    </style>
@endsection

@section('content')
    @include('admin.includes.side_navbar')

    <div id="page-wrapper" class="gray-bg">

        @include('admin.includes.top_navbar')

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-md-6">
                <h2>Attendance Leaves</h2>
                <ol class="breadcrumb">
                    <li>Home</li>
                    <li Class="active">
                        <a>Attendance Leaves</a>
                    </li>
                </ol>
            </div>
            <div class="col-lg-4 col-md-6">
                @include('admin.includes.academic_session')
            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h2>Edit Attendance Leave</h2>
                            <div class="hr-line-dashed"></div>
                        </div>
                        <div class="ibox-content">
                            <form method="post" id="mk_att_frm" action="{{ route('attendance-leave.update', $attendanceLeave->id) }}"
                                class="form-horizontal jumbotron" role="form">
                                @csrf
                                <div class="form-group">
                                    <label class="col-md-2 control-label"> Type </label>
                                    <div class="col-md-6">
                                        <input type="text" disabled class="form-control" value="{{ class_basename($attendanceLeave->person_type) }}">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-md-2 control-label"> Person </label>
                                    <div class="col-md-6">
                                        <input type="text" disabled class="form-control" value="{{ $attendanceLeave->person->name }}">
                                    </div>
                                </div>


                                <div class="form-group{{ $errors->has('from_date') ? ' has-error' : '' }}">
                                    <label class="col-md-2 control-label">From Date </label>
                                    <div class="col-md-6">
                                        <input id="from_datetimepicker" type="text" name="from_date" class="form-control"
                                            placeholder="From Date"
                                            value="{{ old('from_date', $attendanceLeave->from_date) }}" required="true"
                                            autocomplete="off">
                                        @if ($errors->has('from_date'))
                                            <span class="help-block">
                                                <strong><span class="fa fa-exclamation-triangle"></span>
                                                    {{ $errors->first('from_date') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('to_date') ? ' has-error' : '' }}">
                                    <label class="col-md-2 control-label"> To Date </label>
                                    <div class="col-md-6">
                                        <input id="to_datetimepicker" type="text" name="to_date" class="form-control"
                                            placeholder="To Date" value="{{ old('to_date', $attendanceLeave->to_date) }}"
                                            required="true" autocomplete="off">
                                        @if ($errors->has('to_date'))
                                            <span class="help-block">
                                                <strong><span class="fa fa-exclamation-triangle"></span>
                                                    {{ $errors->first('to_date') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                                    <label class="col-md-2 control-label"> Remarks </label>
                                    <div class="col-md-6">
                                        <textarea name="remarks" required class="form-control" rows="4">{{ old('remarks', $attendanceLeave->remarks) }}</textarea>
                                        @if ($errors->has('remarks'))
                                            <span class="help-block">
                                                <strong><span class="fa fa-exclamation-triangle"></span>
                                                    {{ $errors->first('remarks') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-6">
                                        <button class="btn btn-primary" type="submit">
                                            <span class="glyphicon glyphicon-save"></span>
                                            Update Attendance Leave
                                        </button>
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
    <script src="{{ URL::to('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/dataTables/datatables.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}", "Validation Error");
            @endforeach
        </script>
    @endif

    <script type="text/javascript">
        $(document).ready(function() {
            $('#to_datetimepicker, #from_datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD',
                defaultDate: moment()
            });

            $("#mk_att_frm").validate({
                ignore: ":not(:visible)",
                rules: {
                    from_date: {
                        required: true,
                    },
                    to_date: {
                        required: true,
                    }
                }
            });
        });
    </script>
@endsection
