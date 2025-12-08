@php use Illuminate\Support\Str; @endphp
@extends('admin.layouts.master')

@section('title', __('modules.pages_roles_title').' |')

@section('head')
    <link href="{{ asset('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
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
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 12px;
            margin-bottom: 18px;
            font-weight: 600;
            font-size: 14px;
        }
        .permission-group .row {
            margin-top: 10px;
        }
        .permission-group label {
            cursor: pointer;
            user-select: none;
        }
        .permission-group input[type="checkbox"] {
            margin-right: 8px;
            cursor: pointer;
        }
        .select-all {
            margin-right: 10px !important;
            font-weight: 600;
        }

        /* Modern Permission Dependency Styling */
        .permission-with-dependencies {
            background: linear-gradient(135deg, #f5f9ff 0%, #f0f6ff 100%);
            border: 1px solid #d4e6f1;
            border-left: 4px solid #2980b9;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 10px;
            transition: all 0.2s ease;
        }

        .permission-with-dependencies:hover {
            background: linear-gradient(135deg, #eef5ff 0%, #e8f1ff 100%);
            border-left-color: #3498db;
            box-shadow: 0 2px 6px rgba(52, 152, 219, 0.1);
        }

        .permission-with-dependencies input[type="checkbox"]:checked ~ .permission-label {
            color: #2980b9;
            font-weight: 600;
        }

        .permission-with-dependencies.checked {
            background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);
            border-color: #c8e6c9;
            border-left-color: #4caf50;
        }

        .permission-label {
            font-weight: 500;
            display: block;
            margin-bottom: 6px;
            color: #2c3e50;
            font-size: 13px;
        }

        .dependency-badge {
            display: inline-block;
            background-color: #3498db;
            color: white;
            font-size: 10px;
            padding: 3px 10px;
            border-radius: 12px;
            margin-left: 6px;
            font-weight: 600;
        }

        .dependency-list {
            font-size: 12px;
            color: #555;
            margin-top: 8px;
            padding: 8px 12px;
            background-color: rgba(52, 152, 219, 0.05);
            border-radius: 3px;
            display: none;
        }

        .permission-with-dependencies.expanded .dependency-list {
            display: block;
        }

        .dependency-item {
            display: inline-block;
            background-color: #ecf0f1;
            border: 1px solid #bdc3c7;
            padding: 4px 8px;
            border-radius: 3px;
            margin: 4px 4px 4px 0;
            font-size: 11px;
            color: #34495e;
            font-weight: 500;
        }

        .dependency-item.auto-granted {
            background-color: #d5f4e6;
            border-color: #27ae60;
            color: #27ae60;
        }

        .auto-grant-notice {
            background: linear-gradient(135deg, #f0f7f4 0%, #e8f5f0 100%);
            border: 1px solid #27ae60;
            border-left: 4px solid #27ae60;
            border-radius: 4px;
            padding: 14px;
            margin-bottom: 18px;
            color: #1e5631;
            display: none;
            font-size: 13px;
        }

        .auto-grant-notice.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auto-grant-notice strong {
            color: #27ae60;
        }

        .info-icon {
            display: inline-block;
            width: 18px;
            height: 18px;
            background-color: #3498db;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 18px;
            font-size: 11px;
            font-weight: bold;
            margin-left: 6px;
            cursor: help;
            transition: all 0.2s ease;
        }

        .info-icon:hover {
            background-color: #2980b9;
            transform: scale(1.1);
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
                <h2>{{ __("modules.pages_roles_title") }}</h2>
                <ol class="breadcrumb">
                    <li>{{ __("common.home") }}</li>
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
                                <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> {{ __('modules.tabs_roles') }}</a>
                            </li>
                            @can('roles.create')
                                <li class="add-role">
                                    <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> {{ __('modules.tabs_add_role') }}</a>
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
                                                    <th>{{ __("labels.name") }}</th>
                                                    <th>{{ __('labels.created_at') }}</th>
                                                    <th>{{ __("labels.options") }}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                </div>
                            </div>
                            @can('roles.create')
                                <div id="tab-11" class="tab-pane fade add-role">
                                    <div class="panel-body">
                                        <h2> {{ __('modules.forms_role_registration') }} </h2>
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
                                                    <!-- Auto-Grant Notice -->
                                                    <div class="auto-grant-notice" id="autoGrantNotice">
                                                        <strong>📌 Note:</strong> When you select permissions with dependencies, 
                                                        the required permissions will be automatically granted. 
                                                        For example, selecting "Create" will automatically grant "View" access.
                                                    </div>

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
                                                                        @foreach ($groupPermissions as $permission => $details)
                                                                            @php
                                                                                // Check if permission has dependencies
                                                                                $isDependency = is_array($details);
                                                                                $label = $isDependency ? $details['label'] : $details;
                                                                                $dependencies = $isDependency ? ($details['dependencies'] ?? []) : [];
                                                                                $hasDependencies = !empty($dependencies);
                                                                            @endphp
                                                                            <div class="col-md-4" style="margin-bottom: 10px;">
                                                                                @if($hasDependencies)
                                                                                    <div class="permission-with-dependencies">
                                                                                        <label class="permission-label">
                                                                                            <input type="checkbox" name="permissions[]"
                                                                                                value="{{ $permission }}"
                                                                                                class="{{ Str::slug($groupName) }} permission-with-deps"
                                                                                                data-permission="{{ $permission }}"
                                                                                                data-dependencies="{{ implode(',', $dependencies) }}"
                                                                                                {{ is_array(old('permissions')) && in_array($permission, old('permissions')) ? 'checked' : '' }}>
                                                                                            {{ $label }}
                                                                                            <span class="info-icon" title="Will auto-grant dependencies">✓</span>
                                                                                        </label>
                                                                                        <div class="dependency-list">
                                                                                            <strong style="color: #2980b9; font-size: 12px;">Requires:</strong>
                                                                                            @foreach($dependencies as $dep)
                                                                                                <span class="dependency-item" data-dependency="{{ $dep }}" title="Auto-granted when parent is selected">
                                                                                                    ✓ {{ $dep }}
                                                                                                </span>
                                                                                            @endforeach
                                                                                        </div>
                                                                                    </div>
                                                                                @else
                                                                                    <label style="font-weight: normal;">
                                                                                        <input type="checkbox" name="permissions[]"
                                                                                            value="{{ $permission }}"
                                                                                            class="{{ Str::slug($groupName) }}"
                                                                                            {{ is_array(old('permissions')) && in_array($permission, old('permissions')) ? 'checked' : '' }}>
                                                                                        {{ $label }}
                                                                                    </label>
                                                                                @endif
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
                                                            class="glyphicon glyphicon-save"></span> @lang('modules.buttons_register') </button>
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
    <script src="{{ asset('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>

    <script src="{{ asset('src/js/plugins/dataTables/datatables.min.js') }}"></script>

    <script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- Input Mask-->
    <script src="{{ asset('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>


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
                    title: "roles | {{ tenancy()->tenant->system_info['general']['title'] }}",
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
                updateDependencyIndicators();
            });
            
            // Handle individual checkbox changes with auto-check for dependencies
            $('input[name="permissions[]"]').change(function() {
                var permission = $(this).data('permission');
                var isChecked = $(this).is(':checked');
                var dependencyMap = buildDependencyMap();
                
                // Auto-check dependencies if this permission is checked
                if (isChecked && dependencyMap[permission]) {
                    dependencyMap[permission].forEach(function(dep) {
                        var $depCheckbox = $('input[data-permission="' + dep + '"]');
                        if ($depCheckbox.length && !$depCheckbox.is(':checked')) {
                            $depCheckbox.prop('checked', true);
                            $depCheckbox.closest('.permission-with-dependencies')
                                .addClass('checked').addClass('expanded');
                        }
                    });
                }
                
                // Update group select-all checkbox
                var group = $(this).attr('class');
                var totalInGroup = $('.' + group).length;
                var checkedInGroup = $('.' + group + ':checked').length;
                
                if (checkedInGroup === totalInGroup) {
                    $('.select-all[data-group="' + group + '"]').prop('checked', true);
                } else {
                    $('.select-all[data-group="' + group + '"]').prop('checked', false);
                }
                
                updateDependencyIndicators();
            });

            // Build dependency map from data attributes
            function buildDependencyMap() {
                var dependencyMap = {};
                $('input[data-dependencies]').each(function() {
                    var permission = $(this).data('permission');
                    var deps = $(this).data('dependencies');
                    if (deps && typeof deps === 'string') {
                        dependencyMap[permission] = deps.split(',').map(function(d) { return d.trim(); });
                    } else if (deps && Array.isArray(deps)) {
                        dependencyMap[permission] = deps;
                    }
                });
                return dependencyMap;
            }

            // Function to update dependency indicators and visual feedback
            function updateDependencyIndicators() {
                var dependencyMap = buildDependencyMap();
                var checkedPermissions = [];
                var hasDependencies = false;
                var autoGrantedDependencies = {};

                // Collect all checked permissions
                $('input[name="permissions[]"]:checked').each(function() {
                    var permission = $(this).data('permission');
                    checkedPermissions.push(permission);
                    
                    // Track auto-granted dependencies
                    if (dependencyMap[permission]) {
                        hasDependencies = true;
                        dependencyMap[permission].forEach(function(dep) {
                            if (!autoGrantedDependencies[dep]) {
                                autoGrantedDependencies[dep] = [];
                            }
                            autoGrantedDependencies[dep].push(permission);
                        });
                    }
                });

                // Show/hide auto-grant notice
                if (hasDependencies) {
                    $('#autoGrantNotice').addClass('show');
                } else {
                    $('#autoGrantNotice').removeClass('show');
                }

                // Update visual indicators for each permission with dependencies
                $('input[data-dependencies]').each(function() {
                    var $container = $(this).closest('.permission-with-dependencies');
                    var permission = $(this).data('permission');
                    var isChecked = $(this).is(':checked');
                    
                    if (isChecked) {
                        $container.addClass('checked');
                        if (dependencyMap[permission]) {
                            $container.addClass('expanded');
                        }
                    } else {
                        $container.removeClass('checked');
                        // Only keep expanded if showing dependencies needed
                        if (!dependencyMap[permission]) {
                            $container.removeClass('expanded');
                        }
                    }
                });

                // Highlight dependency items that are auto-granted
                $('.dependency-item').each(function() {
                    var depName = $(this).data('dependency');
                    if (depName && autoGrantedDependencies[depName]) {
                        $(this).addClass('auto-granted');
                    } else {
                        $(this).removeClass('auto-granted');
                    }
                });
            }

            // Initialize on page load
            updateDependencyIndicators();
        });
    </script>

@endsection
