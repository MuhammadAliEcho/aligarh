@php use Illuminate\Support\Str; @endphp
@extends('admin.layouts.master')

@section('title', __('modules.pages_roles_title').' |')

@section('head')
    <link href="{{ asset('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">

    {{-- Modernized UI for Permissions --}}
    <style>
        /* -------------------------------------------
           Modern Card-Based Permissions UI (Bootstrap 3 Safe)
        --------------------------------------------- */

        .permission-group {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 22px;
            margin-bottom: 35px;
        }

        .permission-group h4 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #333;
            display: flex;
            align-items: center;
            cursor: pointer;
            user-select: none;
        }

        .permission-group h4:hover {
            color: #10b981;
        }

        .permission-group h4 input[type="checkbox"]:checked ~ * {
            color: #10b981;
        }

        .permission-group .select-all {
            margin-right: 8px;
            cursor: pointer;
        }

        .permission-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px 16px;
            transition: .2s ease;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .permission-card:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .permission-card.checked {
            background: #ecfdf5 !important;
            border-color: #34d399 !important;
        }

        .permission-card-header {
            font-size: 14px;
            font-weight: 600;
            color: #222;
            display: flex;
            align-items: center;
        }

        .permission-card-header input {
            margin-right: 10px;
        }

        .permission-card-header input:checked ~ span {
            color: #10b981;
            font-weight: 700;
        }

        .info-icon {
            margin-left: 8px;
            width: 18px;
            height: 18px;
            background: #3b82f6;
            color: #fff;
            border-radius: 50%;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: help;
        }

        .dependency-list {
            padding: 10px;
            background: #eef2ff;
            border-radius: 6px;
            border-left: 3px solid #6366f1;
            margin-top: 10px;
            display: none;
        }

        .permission-card.expanded .dependency-list {
            display: block !important;
        }

        .dependency-item {
            display: inline-block;
            padding: 4px 10px;
            background: #e5e7eb;
            border-radius: 4px;
            font-size: 12px;
            margin: 4px 6px 4px 0;
            color: #374151;
        }

        .dependency-item.auto-granted {
            background: #d1fae5;
            border: 1px solid #10b981;
            color: #059669;
        }

        .auto-grant-notice {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            border-radius: 6px;
            padding: 14px 18px;
            font-size: 14px;
            color: #065f46;
            margin-bottom: 20px;
            display: none;
        }

        .auto-grant-notice.show {
            display: block;
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
                <li class="active"><a>Roles</a></li>
            </ol>
        </div>
        <div class="col-lg-4 col-md-6">
            @include('admin.includes.academic_session')
        </div>
    </div>

    <!-- Main Content -->
    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
            <div class="col-lg-12">

                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li><a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> {{ __('modules.tabs_roles') }}</a></li>

                        @can('roles.create')
                        <li class="add-role">
                            <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> {{ __('modules.tabs_add_role') }}</a>
                        </li>
                        @endcan
                    </ul>

                    <div class="tab-content">

                        {{-- Roles List --}}
                        <div id="tab-10" class="tab-pane fade">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover dataTables-role">
                                        <thead>
                                            <tr>
                                                <th>{{ __("labels.name") }}</th>
                                                <th>{{ __("labels.created_at") }}</th>
                                                <th>{{ __("labels.options") }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Add Role --}}
                        @can('roles.create')
                        <div id="tab-11" class="tab-pane fade add-role">
                            <div class="panel-body">

                                <h2>{{ __('modules.forms_role_registration') }}</h2>
                                <div class="hr-line-dashed"></div>

                                <form id="tchr_rgstr" method="post" action="{{ URL('roles/create') }}" class="form-horizontal">
                                    {{ csrf_field() }}

                                    {{-- Role Name --}}
                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Role</label>
                                        <div class="col-md-6">
                                            <input type="text" name="name" placeholder="Role Name"
                                                value="{{ old('name') }}" class="form-control">
                                            @if ($errors->has('name'))
                                                <span class="help-block"><strong>
                                                    <span class="fa fa-exclamation-triangle"></span>
                                                    {{ $errors->first('name') }}
                                                </strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Permissions Section --}}
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Permissions</label>

                                        <div class="col-md-10">

                                            <div id="autoGrantNotice" class="auto-grant-notice">
                                                <strong>Note:</strong> Some permissions require others.  
                                                When selected, required permissions will be automatically granted.
                                            </div>

                                            <!-- Permission Groups -->
                                            @foreach ($permissions as $groupName => $groupPermissions)
                                                <div class="permission-group">

                                                    <label class="permission-group-label" style="margin: 0; cursor: pointer;">
                                                        <input type="checkbox"
                                                            class="select-all"
                                                            data-group="{{ Str::slug($groupName) }}">
                                                        <h4 style="margin: 0; display: inline-block; margin-left: 8px;">{{ $groupName }}</h4>
                                                    </label>

                                                    <div class="row" style="margin-top: 8px">
                                                        @foreach ($groupPermissions as $permission => $details)
                                                            @php
                                                                $isObject = is_array($details);
                                                                $label = $isObject ? $details['label'] : $details;
                                                                $dependencies = $isObject ? ($details['dependencies'] ?? []) : [];
                                                                $hasDependencies = !empty($dependencies);
                                                            @endphp

                                                            <div class="col-md-4">
                                                                <div class="permission-card">

                                                                    <label class="permission-card-header" style="margin: 0; cursor: pointer;">

                                                                        <input type="checkbox"
                                                                            name="permissions[]"
                                                                            value="{{ $permission }}"
                                                                            class="{{ Str::slug($groupName) }} {{ $hasDependencies ? 'permission-with-deps' : '' }}"
                                                                            data-permission="{{ $permission }}"
                                                                            data-dependencies="{{ implode(',', $dependencies) }}"
                                                                            {{ is_array(old('permissions')) && in_array($permission, old('permissions')) ? 'checked' : '' }}>

                                                                        <span style="user-select: none;">{{ $label }}</span>

                                                                        @if($hasDependencies)
                                                                            <span class="info-icon" title="Auto-grants required permissions">i</span>
                                                                        @endif
                                                                    </label>

                                                            @if($hasDependencies)
                                                            <div class="dependency-list">
                                                                <strong>Requires:</strong><br>
                                                                @foreach($dependencies as $dep)
                                                                    <span class="dependency-item" data-dependency="{{ $dep }}">✓ {{ $permissionLabels[$dep] ?? $dep }}</span>
                                                                @endforeach
                                                            </div>
                                                            @endif                                                                </div>
                                                            </div>

                                                        @endforeach
                                                    </div>

                                                </div>
                                            @endforeach

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-offset-2 col-md-6">
                                            <button class="btn btn-primary" type="submit">
                                                <span class="glyphicon glyphicon-save"></span>
                                                @lang('modules.buttons_register')
                                            </button>
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

            // Make permission group label clickable for select-all
            $('.permission-group-label').on('click', function(e) {
                if ($(e.target).is('input[type="checkbox"]')) {
                    return;
                }
                
                var $checkbox = $(this).find('.select-all');
                if ($checkbox.length) {
                    $checkbox.prop('checked', !$checkbox.is(':checked')).trigger('change');
                    e.preventDefault();
                }
            });

            // Make entire permission card clickable
            $('.permission-card').on('click', function(e) {
                // Don't double-trigger if clicking the checkbox or info icon
                if ($(e.target).is('input[type="checkbox"]') || $(e.target).closest('.info-icon').length) {
                    return;
                }
                
                var $checkbox = $(this).find('input[type="checkbox"]');
                if ($checkbox.length) {
                    $checkbox.prop('checked', !$checkbox.is(':checked')).trigger('change');
                    e.preventDefault();
                }
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
                            $depCheckbox.closest('.permission-card')
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
                    var $container = $(this).closest('.permission-card');
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
