@extends('admin.layouts.master')

@section('title', 'Message Logs |')

@section('head')
    <link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <style type="text/css">
        .print-table {
            width: 100%;
        }

        .print-table th,
        .print-table td {
            border: 1px solid black !important;
            padding: 0px;
        }

        .print-table > tbody > tr > td {
            padding: 1px;
        }

        .print-table > thead > tr > th {
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
                <h2>Logs</h2>
                <ol class="breadcrumb">
                    <li>Home</li>
                    <li class="active"><a>Message Logs</a></li>
                </ol>
            </div>
            @can('user-settings.change.session')
                <div class="col-lg-4 col-md-6">
                    @include('admin.includes.academic_session')
                </div>
            @endcan
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <div class="content">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover dataTables-log">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Message</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Status Code</th>
                                                <th>Response</th>
                                                <th>Created By</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::to('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/dataTables/datatables.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.dataTables-log').DataTable({
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    {
                        extend: 'print',
                        customize: function (win) {
                            const $body = $(win.document.body);
                            const $table = $body.find('table');

                            $body.addClass('white-bg').css('font-size', '12px');

                            if ($table.length > 0) {
                                $table
                                    .addClass('print-table compact')
                                    .removeClass('table table-striped table-bordered table-hover')
                                    .css('font-size', 'inherit');
                            }
                        },
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6] 
                        },
                        title: "Notifications | {{ config('systemInfo.general.title') }}"
                    }
                ],
                processing: true,
                serverSide: true,
                ajax: '{{ url('msg-notifications/logs') }}',
                columns: [
                    { data: 'type' },
                    { data: 'message' },
                    { data: 'email' },
                    { data: 'phone' },
                    { data: 'status_code' },
                    { data: 'response' },
                    { data: 'created_by_name' }
                ]
            });
        });
    </script>
@endsection
