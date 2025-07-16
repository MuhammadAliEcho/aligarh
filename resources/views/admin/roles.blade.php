@php use Illuminate\Support\Str; @endphp
@extends('admin.layouts.master')

@section('title', 'Roles |')

@section('head')
    <link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
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
        .permission-group h4 {
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .permission-group .row {
            margin-top: 10px;
        }
        .permission-group label {
            cursor: pointer;
        }
        .permission-group input[type="checkbox"] {
            margin-right: 8px;
        }
        .select-all {
            margin-right: 10px !important;
        }
    </style>
@endsection

@section('content')

    @include('admin.includes.side_navbar')

    <div id="page-wrapper" class="gray-bg">

        @include('admin.includes.top_navbar')

        <!-- Heading -->
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-md-6">
                <h2>Roles</h2>
                <ol class="breadcrumb">
                    <li>Home</li>
                    <li Class="active">
                        <a>Roles</a>
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
                            <li class="">
                                <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Roles</a>
                            </li>
                            @can('roles.create')
                                <li class="add-role">
                                    <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Add Roles</a>
                                </li>
                            @endcan
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover dataTables-role">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Created At</th>
                                                    <th>Options</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                </div>
                            </div>
                            @can('roles.create')
                                <div id="tab-11" class="tab-pane fade add-role">
                                    <div class="panel-body">
                                        <h2> Role Registration </h2>
                                        <div class="hr-line-dashed"></div>

                                        <form id="tchr_rgstr" method="post" action="{{ URL('roles/create') }}"
                                            class="form-horizontal">
                                            {{ csrf_field() }}

                                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Role</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="name" placeholder="Role Name"
                                                        value="{{ old('name') }}" class="form-control" />
                                                    @if ($errors->has('name'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- Permissions Section -->
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Permissions</label>
                                                <div class="col-md-10">
                                                    <div class="panel panel-default">
                                                        <div class="panel-body">

                                                            @foreach ($permissions as $groupName => $groupPermissions)
                                                                <div class="permission-group" style="margin-bottom: 30px;">
                                                                    <h4>
                                                                        <label style="font-weight: bold; color: #337ab7;">
                                                                            <input type="checkbox" class="select-all"
                                                                                data-group="{{ Str::slug($groupName) }}">
                                                                            {{ $groupName }}
                                                                        </label>
                                                                    </h4>
                                                                    <div class="row" style="margin-left: 20px;">
                                                                        @foreach ($groupPermissions as $permission => $label)
                                                                            <div class="col-md-4" style="margin-bottom: 10px;">
                                                                                <label style="font-weight: normal;">
                                                                                    <input type="checkbox" name="permissions[]"
                                                                                        value="{{ $permission }}"
                                                                                        class="{{ Str::slug($groupName) }}"
                                                                                        {{ is_array(old('permissions')) && in_array($permission, old('permissions')) ? 'checked' : '' }}>
                                                                                    {{ $label }}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-offset-2 col-md-6">
                                                    <button class="btn btn-primary" type="submit"><span
                                                            class="glyphicon glyphicon-save"></span> Register </button>
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
    <script src="{{ URL::to('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>

    <script src="{{ URL::to('src/js/plugins/dataTables/datatables.min.js') }}"></script>

    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- Input Mask-->
    <script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>


    <script type="text/javascript">
        var tbl;

        function select2template(data) {
            if (!data.id) {
                return data.text;
            }
            var $data = $(
                data.htm1 + data.text + data.htm2
            );
            return $data;
        };

        function loadOptions(data, type, full, meta) {
            opthtm = '';
            @can('roles.update')
                opthtm = '<a href="{{ URL('roles/edit') }}/' + full.id + '" data-toggle="tooltip" title="Edit" class="btn btn-';
                opthtm += ' btn-circle btn-xs edit-option"><span class="fa fa-edit"></span></a>';
            @endcan
            return opthtm;
        }

        $(document).ready(function() {
            tbl = $('.dataTables-role').DataTable({
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [{
                    extend: 'print',
                    customize: function(win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '12px');

                        $(win.document.body).find('table')
                            .addClass('print-table')
                            .removeClass('table')
                            .removeClass('table-striped')
                            .removeClass('table-bordered')
                            .removeClass('table-hover')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    },
                    exportOptions: {
                        columns: [0, 1, 2]
                    },
                    title: "roles | {{ config('systemInfo.title') }}",
                }],
                Processing: true,
                serverSide: true,
                ajax: '{{ URL('roles') }}',
                columns: [{
                        data: 'name'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        render: loadOptions,
                        className: 'hidden-print',
                        "orderable": false
                    },
                ],
            });

            $(".dataTables-role tbody").on('mouseenter', "[data-toggle='tooltip']", function() {
                $(this).tooltip('show');
            });

            $("#tchr_rgstr").validate({
                ignore: ":not(:visible)",
                rules: {
                    name: {
                        required: true,
                    },
                    teacher: {
                        required: true,
                    }
                }
            });

            @if (collect($errors)->count() >= 1 && !$errors->has('toastrmsg'))
                $('a[href="#tab-10"]').tab('show');
            @else
                $('a[href="#tab-11"]').tab('show');
            @endif

            // Handle Select All functionality
            $('.select-all').change(function() {
                var group = $(this).data('group');
                var isChecked = $(this).is(':checked');
                
                $('.' + group).prop('checked', isChecked);
            });
            
            // Handle individual checkbox changes
            $('input[name="permissions[]"]').change(function() {
                var group = $(this).attr('class');
                var totalInGroup = $('.' + group).length;
                var checkedInGroup = $('.' + group + ':checked').length;
                
                // Update the select all checkbox
                if (checkedInGroup === totalInGroup) {
                    $('.select-all[data-group="' + group + '"]').prop('checked', true);
                } else {
                    $('.select-all[data-group="' + group + '"]').prop('checked', false);
                }
            });
        });
    </script>

@endsection
