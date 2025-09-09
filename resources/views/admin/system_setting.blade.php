@extends('admin.layouts.master')

@section('title', 'System Settings |')

@section('head')
    <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">

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
@endsection
@section('content')
    @include('admin.includes.side_navbar')
    <div id="page-wrapper" class="gray-bg hidden-print">
        @include('admin.includes.top_navbar')
        <!-- Heading -->
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-md-6">
                <h2>Settings</h2>
                <ol class="breadcrumb">
                    <li>Home</li>
                    <li Class="active">
                        <a>System Settings</a>
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
                                    <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> General Info</a>
                                </li>
                            @endcan
                            @can('system-setting.print.invoice.history')
                                <li>
                                    <a data-toggle="tab" href="#tab-11"><span class="fa fa-list"></span> Package Info</a>
                                </li>
                            @endcan
                            @can('system-setting.history')
                                <li>
                                    <a data-toggle="tab" href="#tab-12"><span class="fa fa-list"></span> SMS Package Info</a>
                                </li>
                            @endcan
                            @can('system-setting.notification.settings')
                                <li>
                                    <a data-toggle="tab" href="#tab-13"><span class="fa fa-list"></span> Notification
                                        Settings</a>
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
                                                            <li>
                                                                <a href="#smtp" data-toggle="tab">
                                                                    <i class="fa fa-envelope"></i> SMTP
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#sms" data-toggle="tab">
                                                                    <i class="fa fa-mobile"></i> SMS
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#whatsapp" data-toggle="tab">
                                                                    <i class="fa fa-whatsapp"></i> WhatsApp
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <form id="tchr_rgstr" method="POST"
                                                            action="{{ URL('system-setting/update') }}" class="form-horizontal" enctype="multipart/form-data">
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
                                                                                placeholder="Name"
                                                                                value="{{ old('name', config('systemInfo.general.name')) }}"
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
                                                                                value="{{ old('name', config('systemInfo.general.title')) }}"
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
                                                                        class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">E-Mail</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="email"
                                                                                placeholder="E-Mail"
                                                                                value="{{ old('email', config('systemInfo.general.email')) }}"
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

                                                                    <div
                                                                        class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">Address</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="address"
                                                                                placeholder="Address"
                                                                                value="{{ old('address', config('systemInfo.general.address')) }}"
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
                                                                        class="form-group{{ $errors->has('contact_no') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">Contact
                                                                            No</label>
                                                                        <div class="col-md-6">
                                                                            <div class="input-group m-b">
                                                                                <span class="input-group-addon">+92</span>
                                                                                <input type="text" name="contact_no"
                                                                                    value="{{ old('contact_no', config('systemInfo.general.contact_no')) }}"
                                                                                    placeholder="Contact No"
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
                                                                        class="form-group{{ $errors->has('bank_name') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">Bank</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="bank_name"
                                                                                placeholder="Name"
                                                                                value="{{ old('bank_name', config('systemInfo.general.bank.name')) }}"
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
                                                                                placeholder="Address"
                                                                                value="{{ old('bank_address', config('systemInfo.general.bank.address')) }}"
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
                                                                        <label class="col-md-2 control-label">Bank
                                                                            Account No</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="bank_account_no"
                                                                                placeholder="Account no"
                                                                                value="{{ old('bank_account_no', config('systemInfo.general.bank.account_no')) }}"
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

                                                                    <div
                                                                        class="form-group{{ $errors->has('student_capacity') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">Student
                                                                            Capacity</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="student_capacity"
                                                                                value="{{ config('systemInfo.general.student_capacity') }}"
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

                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label">Available
                                                                            SMS</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text"
                                                                                value="{{ config('systemInfo.general.available_sms') . ' till ' . config('systemInfo.general.sms_validity') }}"
                                                                                readonly="true" class="form-control" />
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label">Next
                                                                            Chalan No</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text"
                                                                                value="{{ config('systemInfo.general.next_chalan_no') }}"
                                                                                readonly="true" class="form-control" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group{{ $errors->has('logo') ? ' has-error' : '' }}">
                                                                        <div class="col-md-2"> 
                                                                            <span class="btn btn-default btn-block btn-file">
                                                                                <input type="file" name="logo" accept="image/*" id="logoinp" /> 
                                                                                <span class="fa fa-image"></span> Upload Logo
                                                                            </span> 
                                                                        </div>
                                                                        <div class="col-md-6"> 
                                                                            <img id="logo" 
                                                                                src="{{ config('systemInfo.general.logo_url') ?? '' }}" 
                                                                                alt="Logo Preview" 
                                                                                class="img-responsive img-thumbnail"
                                                                                style="{{ config('systemInfo.general.logo') ? 'display: block;' : 'display: none;' }}" />
                                                                            
                                                                            @if(config('systemInfo.general.logo'))
                                                                                <div class="mt-2" id="deleteLogoContainer">
                                                                                    <button type="button" class="btn btn-danger btn-sm" id="deleteLogo">
                                                                                        <span class="fa fa-trash"></span>
                                                                                    </button>
                                                                                    <input type="hidden" name="removeImage" id="removeImageInput" value="">
                                                                                </div>
                                                                            @endif
                                                                            @if ($errors->has('logo'))
                                                                                <span class="help-block"> 
                                                                                    <strong>
                                                                                        <span class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('logo') }}
                                                                                    </strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- SMTP Tab -->
                                                                <div id="smtp" class="tab-pane fade">
                                                                    <div
                                                                        class="form-group{{ $errors->has('smtp_mailer') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">SMTP
                                                                            Mailer</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="smtp_mailer"
                                                                                placeholder="smtp.gmail.com"
                                                                                class="form-control"
                                                                                value="{{ old('smtp_mailer', config('systemInfo.smtp.mailer')) }}" />
                                                                            @if ($errors->has('smtp_mailer'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('smtp_mailer') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="form-group{{ $errors->has('smtp_host') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">SMTP
                                                                            Host</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="smtp_host"
                                                                                placeholder="smtp.gmail.com"
                                                                                class="form-control"
                                                                                value="{{ old('smtp_host', config('systemInfo.smtp.host')) }}" />
                                                                            @if ($errors->has('smtp_host'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('smtp_host') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group{{ $errors->has('smtp_port') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">SMTP
                                                                            Port</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="smtp_port"
                                                                                placeholder="587" class="form-control"
                                                                                value="{{ old('smtp_port', config('systemInfo.smtp.port')) }}" />
                                                                            @if ($errors->has('smtp_port'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('smtp_port') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group{{ $errors->has('smtp_username') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">SMTP
                                                                            Username</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="smtp_username"
                                                                                placeholder="Username" class="form-control"
                                                                                value="{{ old('smtp_username', config('systemInfo.smtp.username')) }}" />
                                                                            @if ($errors->has('smtp_username'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('smtp_username') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group{{ $errors->has('smtp_password') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">SMTP
                                                                            Password</label>
                                                                        <div class="col-md-6">
                                                                            <input type="password" name="smtp_password"
                                                                                placeholder="Password" class="form-control"
                                                                                value="{{ old('smtp_password', config('systemInfo.smtp.password')) }}" />
                                                                            @if ($errors->has('smtp_password'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('smtp_password') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group{{ $errors->has('smtp_encryption') ? ' has-error' : '' }}">
                                                                        <label
                                                                            class="col-md-2 control-label">Encryption</label>
                                                                        <div class="col-md-6">
                                                                            <select name="smtp_encryption"
                                                                                class="form-control">
                                                                                <option value="">Select Encryption
                                                                                </option>
                                                                                <option value="tls"
                                                                                    {{ old('smtp_encryption', config('systemInfo.smtp.encryption')) == 'tls' ? 'selected' : '' }}>
                                                                                    TLS</option>
                                                                                <option value="ssl"
                                                                                    {{ old('smtp_encryption', config('systemInfo.smtp.encryption')) == 'ssl' ? 'selected' : '' }}>
                                                                                    SSL</option>
                                                                            </select>
                                                                            @if ($errors->has('smtp_encryption'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('smtp_encryption') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- SMS Tab -->
                                                                <div id="sms" class="tab-pane fade">
                                                                    <div
                                                                        class="form-group{{ $errors->has('sms_provider') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">SMS
                                                                            Provider</label>
                                                                        <div class="col-md-6">
                                                                            <select name="sms_provider" class="form-control">
                                                                                <option value="">Select Provider
                                                                                </option>
                                                                                <option value="lifetimesms"
                                                                                    {{ old('sms_provider', config('systemInfo.sms.provider')) == 'lifetimesms' ? 'selected' : '' }}>
                                                                                    Lifetime SMS
                                                                                </option>
                                                                            </select>
                                                                            @if ($errors->has('sms_provider'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('sms_provider') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group{{ $errors->has('sms_url') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">URL</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="sms_url"
                                                                                placeholder="API Key" class="form-control"
                                                                                value="{{ old('sms_url', config('systemInfo.sms.url')) }}" />
                                                                            @if ($errors->has('sms_url'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('sms_url') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group{{ $errors->has('sms_api_token') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">API
                                                                            Token</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="sms_api_token"
                                                                                placeholder="API Token" class="form-control"
                                                                                value="{{ old('sms_api_token', config('systemInfo.sms.api_token')) }}" />
                                                                            @if ($errors->has('sms_api_token'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('sms_api_token') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group{{ $errors->has('sms_api_secret') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">API
                                                                            Secret</label>
                                                                        <div class="col-md-6">
                                                                            <input type="password" name="sms_api_secret"
                                                                                placeholder="API Secret" class="form-control"
                                                                                value="{{ old('sms_api_secret', config('systemInfo.sms.api_secret')) }}" />
                                                                            @if ($errors->has('sms_api_secret'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('sms_api_secret') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group{{ $errors->has('sms_sender') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">Sender</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="sms_sender"
                                                                                placeholder="Sender Name" class="form-control"
                                                                                value="{{ old('sms_sender', config('systemInfo.sms.sender')) }}" />
                                                                            @if ($errors->has('sms_sender'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('sms_sender') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- WhatsApp Tab -->
                                                                <div id="whatsapp" class="tab-pane fade">
                                                                    <div
                                                                        class="form-group{{ $errors->has('whatsapp_provider') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">WhatsApp
                                                                            Provider</label>
                                                                        <div class="col-md-6">
                                                                            <select name="whatsapp_provider"
                                                                                class="form-control">
                                                                                <option value="">Select Provider
                                                                                </option>
                                                                                <option value="whatsapp business"
                                                                                    {{ old('whatsapp_provider', config('systemInfo.whatsapp.provider')) == 'whatsapp business' ? 'selected' : '' }}>
                                                                                    WhatsApp Business</option>
                                                                            </select>
                                                                            @if ($errors->has('whatsapp_provider'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('whatsapp_provider') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="form-group{{ $errors->has('whatsapp_url') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">URL</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="whatsapp_url"
                                                                                placeholder="URL" class="form-control"
                                                                                value="{{ old('whatsapp_url', config('systemInfo.whatsapp.url')) }}" />
                                                                            @if ($errors->has('whatsapp_url'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('whatsapp_url') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="form-group{{ $errors->has('whatsapp_token') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">API
                                                                            Token</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="whatsapp_token"
                                                                                placeholder="API Token" class="form-control"
                                                                                value="{{ old('whatsapp_token', config('systemInfo.whatsapp.api_token')) }}" />
                                                                            @if ($errors->has('whatsapp_token'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('whatsapp_token') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group{{ $errors->has('whatsapp_phone_id') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">Phone Number
                                                                            ID</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="whatsapp_phone_id"
                                                                                placeholder="Phone Number ID"
                                                                                class="form-control"
                                                                                value="{{ old('whatsapp_phone_id', config('systemInfo.whatsapp.phone_id')) }}" />
                                                                            @if ($errors->has('whatsapp_phone_id'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('whatsapp_phone_id') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="form-group{{ $errors->has('whatsapp_mgs_type') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">Message
                                                                            Type</label>
                                                                        <div class="col-md-6">
                                                                            <select name="whatsapp_mgs_type"
                                                                                class="form-control">
                                                                                <option value="">Select Type
                                                                                </option>
                                                                                <option value="text"
                                                                                    {{ old('whatsapp_mgs_type', config('systemInfo.whatsapp.type')) == 'text' ? 'selected' : '' }}>
                                                                                    Text</option>
                                                                            </select>
                                                                            @if ($errors->has('whatsapp_mgs_type'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('whatsapp_mgs_type') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    {{-- <div
                                                                        class="form-group{{ $errors->has('whatsapp_webhook') ? ' has-error' : '' }}">
                                                                        <label class="col-md-2 control-label">Webhook
                                                                            URL</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="whatsapp_webhook"
                                                                                placeholder="Webhook URL"
                                                                                class="form-control"
                                                                                value="{{ old('whatsapp_webhook', config('systemInfo.general.whatsapp.webhook_url')) }}" />
                                                                            @if ($errors->has('whatsapp_webhook'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('whatsapp_webhook') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div> --}}
                                                                </div>
                                                            </div>

                                                            <!-- Submit Button -->
                                                            <div class="form-group">
                                                                <div class="col-md-offset-2 col-md-6">
                                                                    <button class="btn btn-primary" type="submit">
                                                                        <span class="glyphicon glyphicon-save"></span>
                                                                        Update
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
                                                    <th>Status</th>
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
                                                    <b>Validity: </b>{{ config('systemInfo.general.sms_validity') }}
                                                    @if (config('systemInfo.general.sms_validity') >= Carbon\Carbon::now()->todateString() == false)
                                                        <span class="label label-danger">Expired</span>
                                                    @endif
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Remain SMS: </b>{{ config('systemInfo.general.available_sms') }}
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
                                    <div id= "app" class="panel-body">
                                        <h2> Notifications Configuration</h2>
                                        <div class="hr-line-dashed"></div>
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>
                                                        <input id="select-all-mail" class="d-none"  type="checkbox" @change="toggleSelectAll('mail', $event)" @click.stop>
                                                        <label for="select-all-mail" data-toggle="tooltip" title="select all">
                                                            <b>Mail</b>
                                                        </label>
                                                    </th>
                                                    <th>
                                                        <input id="select-all-sms" class="d-none" type="checkbox" @change="toggleSelectAll('sms', $event)" @click.stop>
                                                        <label for="select-all-sms" data-toggle="tooltip" title="select all">
                                                            <b>SMS</b>
                                                        </label>
                                                    </th>
                                                    <th>
                                                        <input id="select-all-whatsapp" class="d-none" type="checkbox" @change="toggleSelectAll('whatsapp', $event)" @click.stop>
                                                        <label for="select-all-whatsapp" data-toggle="tooltip" title="select all">
                                                            <b>WhatsApp</b>
                                                        </label>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(notification, index) in notifications" :key="notification.id">
                                                    <td>@{{ index + 1 }}</td>
                                                    <td><span @click="selectRow(notification)" style="cursor: pointer;">@{{ formatName(notification.name) }}</span></td>
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

    <!-- Data picker -->
    <script src="{{ URL::to('src/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/axios-1.11.0/axios.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/loadash-4.17.15/min.js') }}"></script>

    <script type="text/javascript">
        var tbl;

        function readURL(input) { 
            if (input.files && input.files[0]) { 
                var reader = new FileReader(); 
                reader.onload = function (e) { 
                    $('#logo').attr('src', e.target.result).show(); 
                } 
                reader.readAsDataURL(input.files[0]); 
            } 
        }

        $(document).ready(function() {

            $("[data-toggle='tooltip']").tooltip();

            $("#logoinp").change(function(){
                    readURL(this);
                    $('#removeImageInput').val('');
            });

            $('#deleteLogo').click(function() {
                if (confirm('Are you sure you want to remove the logo?')) {
                    $('#logo').hide();
                    $('#logoinp').val(''); 
                    $('#removeImageInput').val('1'); 
                    $('#deleteLogoContainer').hide();
                    var newInput = $('#logoinp').clone();
                    $('#logoinp').replaceWith(newInput);
                    newInput.change(function(){
                        readURL(this);
                        $('#removeImageInput').val('');
                    });
                }
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
@endsection
