@extends('admin.layouts.master')

@section('title', 'System Settings |')

@section('head')
    <link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">

    <style>
        .general-nav-pills-vertical {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .general-nav-pills-vertical>li {
            float: none;
        }

        .general-nav-pills-vertical>li+li {
            margin-top: 2px;
        }

        .general-nav-pills-vertical>li>a {
            background: white;
            color: #555;
            display: block;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
            position: relative;
        }

        .general-nav-pills-vertical>li>a:hover {
            opacity: 0.9;
            text-decoration: none;
        }

        .general-nav-pills-vertical>li.active>a {
            font-weight: bold;
            background: #009486;
            color: white;
        }

        .general-nav-pills-vertical>li.active>a::after {
            content: '';
            position: absolute;
            top: 50%;
            right: -10px;
            transform: translateY(-50%);
            border-top: 10px solid transparent;
            border-bottom: 10px solid transparent;
            border-left: 10px solid #009486;
        }

        .d-none {
            display: none !important;
        }

				</style>
				@can('system-setting.module.permissions')
					<style>
				/* Module Permissions Styles */
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

            .permission-group h4 input[type="checkbox"]:checked~* {
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

            .permission-card-header input:checked~span {
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

            .tenant-permission-notice {
                background: #fef3c7;
                border-left: 4px solid #f59e0b;
                border-radius: 6px;
                padding: 14px 18px;
                font-size: 14px;
                color: #92400e;
                margin-bottom: 20px;
            }
					</style>
			@endcan
@endsection
@section('content')
    @include('admin.includes.side_navbar')
    <div id="page-wrapper" class="gray-bg hidden-print">
        @include('admin.includes.top_navbar')
        <!-- Heading -->
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-md-6">
                <h2>{{ __('modules.pages_settings_title') }}</h2>
                <ol class="breadcrumb">
                    <li>{{ __('common.home') }}</li>
                    <li Class="active">
                        <a>{{ __('modules.pages_settings_title') }}</a>
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
                            @can('system-setting.update')
                                <li class="active">
                                    <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span>
                                        {{ __('modules.tabs_general_info') }} </a>
                                </li>
                            @endcan
                            @can('system-setting.update')
                                <li>
                                    <a data-toggle="tab" href="#tab-15"><span class="fa fa-plug"></span> Integrations</a>
                                </li>
                            @endcan
                            @can('system-setting.print.invoice.history')
                                <li>
                                    <a data-toggle="tab" href="#tab-11"><span class="fa fa-list"></span>
                                        {{ __('modules.tabs_package_info') }} </a>
                                </li>
                            @endcan
                            @can('system-setting.history')
                                <li>
                                    <a data-toggle="tab" href="#tab-12"><span class="fa fa-list"></span>
                                        {{ __('modules.tabs_sms_package') }} </a>
                                </li>
                            @endcan
                            @can('system-setting.notification.settings')
                                <li>
                                    <a data-toggle="tab" href="#tab-13"><span class="fa fa-list"></span> Notification
                                        Settings</a>
                                </li>
                            @endcan
                            @can('system-setting.module.permissions')
                                <li>
                                    <a data-toggle="tab" href="#tab-14"><span class="fa fa-lock"></span>
                                        {{ __('modules.module_permissions') }}</a>
                                </li>
                            @endcan
                        </ul>
                        <div class="tab-content">
                            @can('system-setting.update')
                                <div id="tab-10" class="tab-pane fade fade in active add-guardian">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <ul class="general-nav-pills-vertical">
                                                            <li class="active">
                                                                <a href="#general" data-toggle="tab">
                                                                    <i class="fa fa-cog"></i> General
                                                                </a>
                                                            </li>
                                                            <!-- SMTP, SMS and WhatsApp moved to Integrations tab -->
                                                            <li>
                                                                <a href="#contact" data-toggle="tab">
                                                                    <i class="fa fa-user"></i> Contact
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#bank" data-toggle="tab">
                                                                    <i class="fa fa-bank"></i> Bank Info

                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#miscellaneous" data-toggle="tab">
                                                                    <i class="fa fa-cogs"></i> Miscellaneous
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <form id="tchr_rgstr" method="POST"
                                                            action="{{ URL('system-setting/update') }}" class="form-horizontal"
                                                            enctype="multipart/form-data">
                                                            {{ csrf_field() }}

                                                            <div class="tab-content">
                                                                <!-- General Tab -->
                                                                <div id="general" class="tab-pane fade in active">
                                                                    {{-- <h2>General</h2>
                                                                    <div class="hr-line-dashed"></div> --}}

                                                                    <div
                                                                        class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">System
                                                                            Name</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="name"
                                                                                placeholder="{{ __('labels.name_placeholder') }}"
                                                                                value="{{ old('name', $system_info['general']['name']) }}"
                                                                                class="form-control" />
                                                                            @if ($errors->has('name'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('name') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">System
                                                                            Title</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="title"
                                                                                placeholder="Title"
                                                                                value="{{ old('title', $system_info['general']['title']) }}"
                                                                                class="form-control" />
                                                                            @if ($errors->has('title'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('title') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">Address</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="address"
                                                                                placeholder="{{ __('labels.address_placeholder_ellipsis') }}"
                                                                                value="{{ old('address', $system_info['general']['address']) }}"
                                                                                class="form-control" />
                                                                            @if ($errors->has('address'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('address') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group{{ $errors->has('student_capacity') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">Student
                                                                            Capacity</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="student_capacity"
                                                                                value="{{ $system_info['general']['student_capacity'] }}"
                                                                                readonly="true" class="form-control" />
                                                                            @if ($errors->has('student_capacity'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('student_capacity') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    {{-- <div class="form-group">
                                                                        <label class="col-md-2 control-label">Available
                                                                            SMS</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text"
                                                                                value="{{ $system_info['general']['available_sms'] . ' till ' . $system_info['general']['sms_validity']}}"
                                                                                readonly="true" class="form-control" />
                                                                        </div>
                                                                    </div> --}}

                                                                    {{-- <div class="form-group">
                                                                        <label class="col-md-2 control-label">Next
                                                                            Chalan No</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text"
                                                                                value="{{ $system_info['general']['next_chalan_no']}}"
                                                                                readonly="true" class="form-control" />
                                                                        </div>
                                                                    </div> --}}

                                                                    <div
                                                                        class="form-group{{ $errors->has('logo') ? ' has-error' : '' }}">
                                                                        <div class="col-md-2">
                                                                            <span class="btn btn-default btn-block btn-file">
                                                                                <input type="file" name="logo"
                                                                                    accept="image/*" id="logoinp" />
                                                                                <span class="fa fa-image"></span>
                                                                                {{ __('modules.settings_upload_logo') }}
                                                                            </span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <img id="logo"
                                                                                src="{{ $system_info['general']['logo'] ? route('system-setting.logo') : '' }}"
                                                                                alt="Logo Preview"
                                                                                class="img-responsive img-thumbnail"
                                                                                style="max-width:100px !important; {{ isset($system_info['general']['logo']) && $system_info['general']['logo'] ? 'display: block;' : 'display: none;' }}" />

                                                                            @if (isset($system_info['general']['logo']) && $system_info['general']['logo'])
                                                                                <div class="mt-2" id="deleteLogoContainer">
                                                                                    <button type="button"
                                                                                        class="btn btn-danger btn-sm"
                                                                                        id="deleteLogo">
                                                                                        <span class="fa fa-trash"></span>
                                                                                    </button>
                                                                                    <input type="hidden" name="removeImage"
                                                                                        id="removeImageInput" value="">
                                                                                </div>
                                                                            @endif
                                                                            @if ($errors->has('logo'))
                                                                                <span class="help-block">
                                                                                    <strong>
                                                                                        <span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('logo') }}
                                                                                    </strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- SMTP/SMS/WhatsApp panes moved to top-level Integrations tab -->


                                                                <!-- Contact Tab -->
                                                                <div id="contact" class="tab-pane fade">
                                                                    <div
                                                                        class="form-group{{ $errors->has('contact_name') ? ' has-error' : '' }}">
                                                                        <label
                                                                            class="col-md-2 control-label">{{ __('labels.contact_name') }}</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="contact_name"
                                                                                placeholder="{{ __('labels.contact_name_placeholder') }}"
                                                                                value="{{ old('contact_name', $system_info['general']['contact_name']) }}"
                                                                                class="form-control" />
                                                                            @if ($errors->has('contact_name'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('contact_name') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group{{ $errors->has('contact_no') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">Contact
                                                                            No</label>
                                                                        <div class="col-md-6">
                                                                            <div class="input-group m-b">
                                                                                <span class="input-group-addon">+92</span>
                                                                                <input type="text" name="contact_no"
                                                                                    value="{{ old('contact_no', $system_info['general']['contact_no']) }}"
                                                                                    placeholder="{{ __('labels.contact_no_placeholder') }}"
                                                                                    class="form-control"
                                                                                    data-mask="9999999999" />
                                                                            </div>
                                                                            @if ($errors->has('contact_no'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('contact_no') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                                                        <label
                                                                            class="col-md-2 control-label">{{ __('labels.email_address') }}</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="email"
                                                                                placeholder="E-Mail"
                                                                                value="{{ old('email', $system_info['general']['contact_email']) }}"
                                                                                class="form-control" />
                                                                            @if ($errors->has('email'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('email') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <!-- Bank Info Tab -->
                                                                <div id="bank" class="tab-pane fade">
                                                                    <div
                                                                        class="form-group{{ $errors->has('bank_name') ? ' has-error' : '' }}">
                                                                        <label
                                                                            class="col-md-2 control-label">{{ __('labels.bank_name') }}</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="bank_name"
                                                                                placeholder="{{ __('labels.name_placeholder') }}"
                                                                                value="{{ old('bank_name', $system_info['general']['bank']['name']) }}"
                                                                                class="form-control" />
                                                                            @if ($errors->has('bank_name'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('bank_name') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group{{ $errors->has('bank_address') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">Bank
                                                                            Address</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="bank_address"
                                                                                placeholder="{{ __('labels.address_placeholder_ellipsis') }}"
                                                                                value="{{ old('bank_address', $system_info['general']['bank']['address']) }}"
                                                                                class="form-control" />
                                                                            @if ($errors->has('bank_address'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('bank_address') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group{{ $errors->has('bank_account_no') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">Bank Account
                                                                            No</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="bank_account_no"
                                                                                placeholder="{{ __('labels.account_no_placeholder') }}"
                                                                                value="{{ old('bank_account_no', $system_info['general']['bank']['account_no']) }}"
                                                                                class="form-control" />
                                                                            @if ($errors->has('bank_account_no'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('bank_account_no') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <!-- Misc Info Tab -->
                                                                <div id="miscellaneous" class="tab-pane fade">
                                                                    <div
                                                                        class="form-group{{ $errors->has('bank_account_no') ? ' has-error' : '' }}">
                                                                        <label title="Term and Condition of Fee Chalan"
                                                                            class="col-md-2 control-label">
                                                                            Term and Condition
                                                                            <span class="text-info" data-toggle="tooltip"
                                                                                title="Term and Condition of Fee Chalan">
                                                                                <i class="fa fa-info-circle"></i>
                                                                            </span>
                                                                        </label>
                                                                        <div class="col-md-6">
                                                                            <textarea type="text" rows="10" name="chalan_term_and_Condition" placeholder="Term and Condition"
                                                                                class="form-control">{{ old('chalan_term_and_Condition', $system_info['general']['chalan_term_and_Condition']) }}</textarea>
                                                                            @if ($errors->has('chalan_term_and_Condition'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('chalan_term_and_Condition') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <!-- Submit Button -->
                                                            <div class="form-group">
                                                                <div class="col-md-offset-2 col-md-6">
                                                                    <button class="btn btn-primary" type="submit">
                                                                        <span class="glyphicon glyphicon-save"></span>
                                                                        @lang('modules.buttons_update')
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
                            @endcan
                            @can('system-setting.print.invoice.history')
                                <div id="tab-11" class="tab-pane fade fade in ">
                                    <div class="panel-body">
                                        <h2> Invoices <small> 4000/month billing backage </small> <a class=""
                                                title="Download" data-toggle="tooltip"
                                                href="{{ URL('system-setting/print-invoice-history') }}" target="_blank">
                                                <span class="fa fa-download"> </span> </a> </h2>
                                        <div class="hr-line-dashed"></div>
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Billing Month</th>
                                                    <th>Amount</th>
                                                    <th>{{ __('labels.status') }}</th>
                                                    <th>Date Of Payment</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="invoice in system_invoices">
                                                    <td>@{{ invoice.id }}</td>
                                                    <td>@{{ invoice.billing_month }}</td>
                                                    <td>@{{ invoice.amount }}</td>
                                                    <td>@{{ invoice.status }}</td>
                                                    <td>@{{ invoice.date_of_payment }}</td>
                                                    <td>@{{ invoice.created_at }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endcan
                            @can('system-setting.history')
                                <div id="tab-12" class="tab-pane fade in">
                                    <div class="panel-body">
                                        <h2> SMS Package <small> <span class="label label-info">PREMIUM</span> </small> </h2>
                                        <div class="hr-line-dashed"></div>
                                        <div class="container">
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    <b>Package Name: </b>PREMIUM
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Amount: </b>2700/=
                                                </li>
                                                <li class="list-group-item">
                                                    <b>No Of SMS: </b>3030
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Package Activation Date: </b>2019-01-19
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Validity: </b>{{ $system_info['general']['sms_validity'] }}
                                                    @if ($system_info['general']['sms_validity'] >= Carbon\Carbon::now()->todateString() == false)
                                                        <span class="label label-danger">Expired</span>
                                                    @endif
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Remain SMS: </b>{{ $system_info['general']['available_sms'] }}
                                                </li>
                                            </ul>
                                        </div>
                                        <h2> SMS History </h2>
                                        <div class="hr-line-dashed"></div>
                                        <form id="sms_history_form" method="POST"
                                            action="{{ URL('smsnotifications/history') }}" class="form-horizontal"
                                            target="_blank">
                                            {{ csrf_field() }}

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">From</label>
                                                <div class="col-md-6">
                                                    <div class="input-daterange input-group" style="width: 100%"
                                                        id="datepicker">
                                                        <input type="text" class="input-sm form-control" name="start"
                                                            required="true" readonly="" placeholder="From Date" />
                                                        <span class="input-group-addon">to</span>
                                                        <input type="text" class="input-sm form-control" name="end"
                                                            required="true" readonly="" placeholder="To Date" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-offset-2 col-md-6">
                                                    <button class="btn btn-primary btn-block" type="submit"><span
                                                            class="fa fa-file"></span> Show </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endcan
                            @can('system-setting.notification.settings')
                                <div id="tab-13" class="tab-pane fade fade in ">
                                    <div id="app" class="panel-body">
                                        <h2> Notifications Configuration</h2>
                                        <div class="hr-line-dashed"></div>
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ __('labels.name') }}</th>
                                                    <th>
                                                        <input id="select-all-mail" class="d-none" type="checkbox"
                                                            @change="toggleSelectAll('mail', $event)" @click.stop>
                                                        <label for="select-all-mail" data-toggle="tooltip"
                                                            title="select all">
                                                            <b>Mail</b>
                                                        </label>
                                                    </th>
                                                    <th>
                                                        <input id="select-all-sms" class="d-none" type="checkbox"
                                                            @change="toggleSelectAll('sms', $event)" @click.stop>
                                                        <label for="select-all-sms" data-toggle="tooltip" title="select all">
                                                            <b>SMS</b>
                                                        </label>
                                                    </th>
                                                    <th>
                                                        <input id="select-all-whatsapp" class="d-none" type="checkbox"
                                                            @change="toggleSelectAll('whatsapp', $event)" @click.stop>
                                                        <label for="select-all-whatsapp" data-toggle="tooltip"
                                                            title="select all">
                                                            <b>WhatsApp</b>
                                                        </label>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(notification, index) in notifications" :key="notification.id">
                                                    <td>@{{ index + 1 }}</td>
                                                    <td><span @click="selectRow(notification)"
                                                            style="cursor: pointer;">@{{ formatName(notification.name) }}</span></td>
                                                    <td><input type="checkbox" v-model="notification.mail"
                                                            @change="updateSetting(notification, 'mail')" @click.stop></td>
                                                    <td><input type="checkbox" v-model="notification.sms"
                                                            @change="updateSetting(notification, 'sms')" @click.stop></td>
                                                    <td><input type="checkbox" v-model="notification.whatsapp"
                                                            @change="updateSetting(notification, 'whatsapp')" @click.stop></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endcan
                            @can('system-setting.module.permissions')
                                <div id="tab-14" class="tab-pane fade fade in">
                                    <div class="panel-body">
                                        <h2>{{ __('modules.module_permissions') }}</h2>
                                        <div class="hr-line-dashed"></div>
                                        @include('admin.system_setting_module_permissions')
                                    </div>
                                </div>
                            @endcan
                            @can('system-setting.update')
                                <div id="tab-15" class="tab-pane fade">
                                    <div class="panel-body">
                                        <h2>Integrations</h2>
                                        <div class="hr-line-dashed"></div>

                                        {{-- SMTP Form --}}
                                        <form method="POST" action="{{ URL('system-setting/update') }}" class="form-horizontal">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="section" value="integrations" />
                                            <h4>SMTP Settings</h4>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">SMTP Mailer</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="smtp_mailer" placeholder="smtp.gmail.com" class="form-control" value="{{ old('smtp_mailer', $system_info['smtp']['mailer']) }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">SMTP Host</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="smtp_host" placeholder="smtp.gmail.com" class="form-control" value="{{ old('smtp_host', $system_info['smtp']['host']) }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">SMTP Port</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="smtp_port" placeholder="587" class="form-control" value="{{ old('smtp_port', $system_info['smtp']['port']) }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Mail From Address</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="smtp_from_address" placeholder="mail@domain.com" class="form-control" value="{{ old('smtp_from_address', $system_info['smtp']['from_address']) }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">SMTP Username</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="smtp_username" placeholder="Username" class="form-control" value="{{ old('smtp_username', $system_info['smtp']['username']) }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">SMTP Password</label>
                                                <div class="col-md-6">
                                                    <input type="password" name="smtp_password" placeholder="{{ __('labels.password_placeholder') }}" class="form-control" value="{{ old('smtp_password', $system_info['smtp']['password']) }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">{{ __('labels.encryption') }}</label>
                                                <div class="col-md-6">
                                                    <select name="smtp_encryption" class="form-control">
                                                        <option value="">Select Encryption</option>
                                                        <option value="tls" {{ old('smtp_encryption', $system_info['smtp']['encryption'] == 'tls' ? 'selected' : '') }}>TLS</option>
                                                        <option value="ssl" {{ old('smtp_encryption', $system_info['smtp']['encryption'] == 'ssl' ? 'selected' : '') }}>SSL</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-offset-2 col-md-6">
                                                    <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Save SMTP</button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="hr-line-dashed"></div>

                                        {{-- SMS Form --}}
                                        <form method="POST" action="{{ URL('system-setting/update') }}" class="form-horizontal">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="section" value="integrations" />
                                            <h4>SMS Settings</h4>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">SMS Provider</label>
                                                <div class="col-md-6">
                                                    <select name="sms_provider" class="form-control">
                                                        <option value="">Select Provider</option>
                                                        <option value="lifetimesms" {{ old('sms_provider', $system_info['sms']['provider'] == 'lifetimesms' ? 'selected' : '') }}>Lifetime SMS</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">{{ __('labels.sms_url') }}</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="sms_url" placeholder="{{ __('labels.api_key_placeholder') }}" class="form-control" value="{{ old('sms_url', $system_info['sms']['url']) }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">API Token</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="sms_api_token" placeholder="{{ __('labels.api_token_placeholder') }}" class="form-control" value="{{ old('sms_api_token', $system_info['sms']['api_token']) }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">API Secret</label>
                                                <div class="col-md-6">
                                                    <input type="password" name="sms_api_secret" placeholder="{{ __('labels.api_secret_placeholder') }}" class="form-control" value="{{ old('sms_api_secret', $system_info['sms']['api_secret']) }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">{{ __('labels.sender_id') }}</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="sms_sender" placeholder="Sender Name" class="form-control" value="{{ old('sms_sender', $system_info['sms']['sender']) }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-offset-2 col-md-6">
                                                    <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Save SMS</button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="hr-line-dashed"></div>

                                        {{-- WhatsApp Form --}}
                                        <form method="POST" action="{{ URL('system-setting/update') }}" class="form-horizontal">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="section" value="integrations" />
                                            <h4>WhatsApp Settings</h4>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">WhatsApp Provider</label>
                                                <div class="col-md-6">
                                                    <select name="whatsapp_provider" class="form-control">
                                                        <option value="">Select Provider</option>
                                                        <option value="whatsapp business" {{ old('whatsapp_provider', $system_info['whatsapp']['provider'] == 'whatsapp business' ? 'selected' : '') }}>WhatsApp Business</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">{{ __('labels.sms_url') }}</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="whatsapp_url" placeholder="URL" class="form-control" value="{{ old('whatsapp_url', $system_info['whatsapp']['url']) }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">API Token</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="whatsapp_token" placeholder="{{ __('labels.api_token_placeholder') }}" class="form-control" value="{{ old('whatsapp_token', $system_info['whatsapp']['api_token']) }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Phone Number ID</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="whatsapp_phone_id" placeholder="Phone Number ID" class="form-control" value="{{ old('whatsapp_phone_id', $system_info['whatsapp']['phone_id']) }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Message Type</label>
                                                <div class="col-md-6">
                                                    <select name="whatsapp_mgs_type" class="form-control">
                                                        <option value="">Select Type</option>
                                                        <option value="text" {{ old('whatsapp_mgs_type', $system_info['whatsapp']['type'] == 'text' ? 'selected' : '') }}>Text</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-offset-2 col-md-6">
                                                    <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Save WhatsApp</button>
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

    <script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- Input Mask-->
    <script src="{{ asset('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

    <!-- Data picker -->
    <script src="{{ asset('src/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('src/js/plugins/axios-1.11.0/axios.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/loadash-4.17.15/min.js') }}"></script>

    <script type="text/javascript">
        var tbl;

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#logo').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).ready(function() {

            $("[data-toggle='tooltip']").tooltip();

            $("#logoinp").change(function() {
                readURL(this);
                $('#removeImageInput').val('');
            });

            $('#deleteLogo').click(function() {
                $('#logo').hide();
                $('#logoinp').val('');
                $('#removeImageInput').val('1');
                $('#deleteLogoContainer').hide();
                var newInput = $('#logoinp').clone();
                $('#logoinp').replaceWith(newInput);
                newInput.change(function() {
                    readURL(this);
                    $('#removeImageInput').val('');
                });
            });

            $("#sms_history_form").validate({
                rules: {
                    start: {
                        required: true,
                    },
                    end: {
                        required: true,
                    },
                }
            });

            $('#datepicker').datepicker({

                format: 'yyyy-mm-dd',
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,

                minViewMode: 0,
                todayHighlight: true
            });

            $("#tchr_rgstr").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    /*              profession: {
                    								required: true,
                    							},
                    							email: {
                    								required: true,
                    								email: true
                    							},
                    */
                    income: {
                        number: true,
                    },
                },
                messages: {
                    income: {
                        number: 'Enter valid amount'
                    },
                }
            });

        });
    </script>

@endsection

@section('vue')
    <script type="text/javascript">
        var app = new Vue({
            el: "#app",
            data: {
                system_invoices: {!! json_encode($system_invoices, JSON_NUMERIC_CHECK) !!},
                notifications: {!! json_encode($notifications_setting) !!}
            },
            methods: {
                formatName(name) {
                    return name.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                },

                updateSetting: _.debounce(function(notification, field) {
                    axios.post(`/system-setting/notification-settings/${notification.id}`, {
                            field: field,
                            value: notification[field]
                        })
                        .then(response => {
                            toastr.success("Setting updated successfully", "Notification");
                        })
                        .catch(error => {
                            console.error(error);
                            toastr.error("Update failed. Please try again.", "Error");
                        });
                }, 300),
                selectRow(notification) {
                    const anySelected = notification.mail || notification.sms || notification.whatsapp;
                    const newValue = !anySelected;
                    notification.mail = newValue;
                    notification.sms = newValue;
                    notification.whatsapp = newValue;

                    axios.post(`/system-setting/notification-settings/row`, {
                            id: notification.id,
                            mail: newValue,
                            sms: newValue,
                            whatsapp: newValue
                        })
                        .then(response => {
                            toastr.success("Row settings updated", "Notification");
                        })
                        .catch(error => {
                            console.error(error);
                            toastr.error("Failed to update row", "Error");
                        });
                },

                toggleSelectAll(type, event) {
                    const isChecked = event.target.checked;

                    this.notifications.forEach(notification => {
                        notification[type] = isChecked;
                    });

                    axios.post(`/system-setting/notification-settings/all`, {
                            field: type,
                            value: isChecked
                        })
                        .then(response => {
                            toastr.success(`All ${type} settings updated successfully`, "Notification");
                        })
                        .catch(error => {
                            console.error(error);
                            toastr.error("Update failed. Please try again.", "Error");
                            this.notifications.forEach(notification => {
                                notification[type] = !isChecked;
                            });
                        });
                }
            }
        });
				</script>
			@can('system-setting.module.permissions')
				<script>
            // Module Permissions JavaScript
            // Build dependency map from data attributes
            var dependencyMap = {};
            $('input[name="permissions[]"][data-dependencies]').each(function() {
                var perm = $(this).data('permission');
                var deps = $(this).data('dependencies').split(',');
                dependencyMap[perm] = deps.map(function(d) {
                    return $.trim(d);
                });
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
                var $thisCheckbox = $(this);
                var classes = $thisCheckbox.attr('class').split(' ');
                var groupClass = classes.find(function(c) {
                    return c !== 'permission-with-deps' && c.indexOf('-') > -1;
                });

                if (groupClass) {
                    var totalInGroup = $('.' + groupClass).length;
                    var checkedInGroup = $('.' + groupClass + ':checked').length;

                    if (checkedInGroup === totalInGroup) {
                        $('.select-all[data-group="' + groupClass + '"]').prop('checked', true);
                    } else {
                        $('.select-all[data-group="' + groupClass + '"]').prop('checked', false);
                    }
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
                        dependencyMap[permission] = deps.split(',').map(function(d) {
                            return d.trim();
                        });
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
				</script>
			@endcan
@endsection
