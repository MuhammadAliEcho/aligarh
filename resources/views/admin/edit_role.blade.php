@php use Illuminate\Support\Str; @endphp
@extends('admin.layouts.master')

@section('title', 'Edit Role |')

@section('head')
    <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
    <style>
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
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
              <div class="col-lg-12">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <h2>Edit Role</h2>
                          <div class="hr-line-dashed"></div>
                      </div>
                      <div class="ibox-content">
                        <form id="role_update" method="post" action="{{ URL('roles/update/' . $role->id) }}" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
                                <label class="col-md-2 control-label">Role Name</label>
                                <div class="col-md-6">
                                    <input type="text" 
                                        name="name" 
                                        placeholder="Role Name" 
                                        value="{{ old('name', $role->name) }}" 
                                        class="form-control"
                                        disabled/>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('name') }}</strong>
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
                                            @foreach($permissions as $groupName => $groupPermissions)
                                                <div class="permission-group" style="margin-bottom: 30px;">
                                                    <h4>
                                                        <label style="font-weight: bold; color: #337ab7;">
                                                            <input type="checkbox" class="select-all" data-group="{{ Str::slug($groupName) }}"> 
                                                            {{ $groupName }}
                                                        </label>
                                                    </h4>
                                                    <div class="row" style="margin-left: 20px;">
                                                        @foreach($groupPermissions as $permission => $label)
                                                            <div class="col-md-4" style="margin-bottom: 10px;">
                                                                <label style="font-weight: normal;">
                                                                    <input type="checkbox" 
                                                                        name="permissions[]" 
                                                                        value="{{ $permission }}" 
                                                                        class="{{ Str::slug($groupName) }}"
                                                                        {{ 
                                                                            (old('permissions') ? 
                                                                                (is_array(old('permissions')) && in_array($permission, old('permissions'))) : 
                                                                                in_array($permission, $rolePermissions)
                                                                            ) ? 'checked' : '' 
                                                                        }}> 
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
                    <button class="btn btn-primary" type="submit">
                        <span class="glyphicon glyphicon-save"></span> Update Role
                    </button>
                    <a href="{{ URL('roles') }}" class="btn btn-default">
                        <span class="glyphicon glyphicon-arrow-left"></span> Back to Roles
                    </a>
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
<script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>
<!-- Input Mask-->
<script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ URL::to('src/js/plugins/select2/select2.full.min.js') }}"></script>
@if ($errors->any())
    <script>
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}", "Validation Error");
        @endforeach
    </script>
@endif
<script type="text/javascript">

$(document).ready(function() {

    $("#tchr_rgstr").validate({
        rules: {
            name: {
                required: true,
            }
        }
    });

    // Initialize select-all checkboxes based on existing permissions
    $('.select-all').each(function() {
        var group = $(this).data('group');
        var totalInGroup = $('.' + group).length;
        var checkedInGroup = $('.' + group + ':checked').length;
        
        if (checkedInGroup === totalInGroup && totalInGroup > 0) {
            $(this).prop('checked', true);
        }
    });
    
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
