@extends('admin.layouts.master')

@section('title', __('modules.pages_visitor_students_title').' |')
@section('head')
    <link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
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
    <style>
        .visitor-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            margin-bottom: 30px;
        }

        .visitor-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15), 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(135deg, #009486 0%, #1ab394 100%);
            height: 70px;
            position: relative;
            /* overflow: hidden; */
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.15"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }

        .profile-image-container {
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            /* z-index: 10; */
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 5px solid #ffffff;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            object-fit: cover;
        }

        .visitor-card:hover .profile-image {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            padding: 60px 25px 25px;
            text-align: center;
        }

        .visitor-name {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 8px;
            letter-spacing: -0.5px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 25px;
        }

        .status-active {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
        }

        .status-inactive {
            background: linear-gradient(135deg, #e17055, #fdcb6e);
            color: white;
        }

        .info-divider {
            border: none;
            height: 2px;
            background: linear-gradient(90deg, transparent, #667eea, transparent);
            margin: 0px 0;
        }

        .info-list {
            text-align: left;
            margin: 0;
            padding: 0;
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 7px 0;
            border-bottom: 1px solid #f8f9fa;
            transition: all 0.3s ease;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item:hover {
            background: rgba(102, 126, 234, 0.05);
            padding-left: 10px;
            border-radius: 8px;
        }

        .info-icon {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 16px;
            color: white;
            flex-shrink: 0;
        }

        .icon-education {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .icon-id {
            background: linear-gradient(135deg, #00cec9, #55a3ff);
        }

        .icon-gender {
            background: linear-gradient(135deg, #fd79a8, #fdcb6e);
        }

        .icon-fee {
            background: linear-gradient(135deg, #00b894, #55efc4);
        }

        .icon-father-name {
            background: linear-gradient(135deg, #ff6a00, #ee0979);
            /* Vibrant orange to pink */
        }

        .icon-guardian {
            background: linear-gradient(135deg, #43cea2, #185a9d);
            /* Aqua green to deep blue */
        }

        .icon-email {
            background: linear-gradient(135deg, #f7971e, #ffd200);
            /* Bright orange to yellow */
        }

        .icon-phone {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            /* Fresh light blue to royal blue */
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 12px;
            color: #74b9ff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 15px;
            color: #2d3436;
            font-weight: 600;
        }

        .fee-amount {
            color: #00b894;
            font-weight: 700;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .visitor-card {
                margin: 0 10px 20px;
            }

            .card-body {
                padding: 50px 20px 20px;
            }
        }

        .visitor-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .visitor-card {
            width: 250px;
        }

        .profile-image {
            width: 80px;
            height: 80px;
        }

        .card-body {
            padding: 25px 20px 10px;
        }

        .visitor-name {
            font-size: 18px;
        }

        .info-value {
            font-size: 14px;
        }

        .status-badge {
            font-size: 10px;
            padding: 4px 12px;
        }

        .m-2 {
            margin: 1rem 1rem 0rem 1rem;
        }

        .pagination nav {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: inline !important;
            padding-left: 0;
            margin: 20px 0;
            border-radius: 4px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .unpaid-badge {
            background-color: #d9534f;
            color: white;
            padding: 5px;
            font-size: 8px;
            font-weight: 800;
        }

        .paid-badge {
            background-color: #5cb85c;
            color: white;
            margin-left: 8px;
            font-size: 8px;
            font-weight: 800;
        }

        .not-created-badge {
            background-color: #feee0a;
            color: white;
            margin-left: 8px;
            font-size: 8px;
            font-weight: 800;
        }

        /* ribbon */
        .ribbon {
            position: absolute;
            right: -5px;
            top: -5px;
            z-index: 1;
            overflow: hidden;
            width: 93px;
            height: 93px;
            text-align: right;
        }

        .ribbon span {
            font-size: 0.8rem;
            color: #fff;
            text-transform: uppercase;
            text-align: center;
            font-weight: bold;
            line-height: 32px;
            transform: rotate(45deg);
            width: 125px;
            display: block;
            background: linear-gradient(#d10fbe 0%, #8a3b72 100%);
            box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 1);
            position: absolute;
            top: 17px;
            right: -29px;
        }

        .ribbon span::before {
            content: '';
            position: absolute;
            left: 0px;
            top: 100%;
            z-index: -1;
            border-left: 3px solid #1c5b97;
            border-right: 3px solid transparent;
            border-bottom: 3px solid transparent;
            border-top: 3px solid #1c5b97;
        }

        .ribbon span::after {
            content: '';
            position: absolute;
            right: 0%;
            top: 100%;
            z-index: -1;
            border-right: 3px solid #1c5b97;
            border-left: 3px solid transparent;
            border-bottom: 3px solid transparent;
            border-top: 3px solid #1c5b97;
        }

        .color-grey-70 {
            color: #b3b3b3 !important;
        }
    </style>
@endsection

@section('content')
    @include('admin.includes.side_navbar')
    <div id="page-wrapper" class="gray-bg">
        @include('admin.includes.top_navbar')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-md-6">
                <h2>Visitors</h2>
                <ol class="breadcrumb">
                    <li>Home</li>
                    <li Class="active">
                        <a>Visitors</a>
                    </li>
                </ol>
            </div>
            @can('user-settings.change.session')
                <div class="col-lg-4 col-md-6">
                    @include('admin.includes.academic_session')
                </div>
            @endcan
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row ">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            @can('visitors.grid')
                                <li class="">
                                    <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Visitors</a>
                                </li>
                            @endcan
                            @can('visitors.create')
                                <li class="add-visitor">
                                    <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Create Visitors</a>
                                </li>
                            @endcan
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade">
                                <div class="panel-body">
                                    <div class="row" id="app">
                                        <div class="col-md-4" v-show="layout === 'grid'">
                                            <div class="row">
                                                <label style="margin-right: 20px; margin-left: 20px;">
                                                    Show
                                                    <select v-model="per_page" class="form-control input-sm"
                                                        style="width: auto; display: inline-block;"
                                                        @change="handleLayoutChange">
                                                        <option v-for="option in options" :key="option"
                                                            :value="option">
                                                            @{{ option }}
                                                        </option>
                                                    </select>
                                                    entries
                                                </label>
                                                <label>
                                                    Showing @{{ from }} to @{{ to }} of
                                                    @{{ total }} entries
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-right pull-right">
                                            <div class="row">
                                                <input type="text" v-model="search_visitors" @input="debouncedSearch"
                                                    class="form-control input-sm"
                                                    style="width: 200px; display: inline-block;" placeholder="Search...">
                                                <div class="form-group pull-right">
                                                    <label class="control-label"
                                                        style="margin: 0 10px 0 20px; line-height: 34px;cursor: pointer;">
                                                        <span
                                                            :class="['fa', 'fa-th', { 'color-grey-70': layout !== 'grid' }]"
                                                            style="margin-right: 2px;" data-toggle="tooltip"
                                                            title="Grid Layout">
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid" id="gridLayout">
                                        <div class="" style="display: ruby">
                                            <div class="m-2" v-for="visitor in visitors" :key="visitor.id">
                                                <a :href="'{{ url('visitors/profile') }}/' + visitor.id"
                                                    class="text-decoration-none">
                                                    <div class="panel visitor-card">
                                                        <div class="ribbon"><span>Visitor Student</span></div>
                                                        <div class="card-header">
                                                            <div class="profile-image-container">
                                                                <img :src="visitor.image_url || 'img/avatar.jpg'"
                                                                    alt="Student Photo" class="profile-image">
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <h4 class="visitor-name">@{{ visitor.name }}</h4>
                                                            <hr class="info-divider">
                                                            <ul class="list-unstyled info-list">
                                                                <li class="info-item">
                                                                    <div class="info-icon icon-education">
                                                                        <i class="fa fa-graduation-cap"></i>
                                                                    </div>
                                                                    <div class="info-content">
                                                                        <div class="info-label">Class</div>
                                                                        <div class="info-value">@{{ visitor.std_class.name }}
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="info-item">
                                                                    <div class="info-icon icon-guardian">
                                                                        <i class="fa fa-id-card-o"></i>
                                                                    </div>
                                                                    <div class="info-content">
                                                                        <div class="info-label">Father Name</div>
                                                                        <div class="info-value">@{{ visitor.father_name }}
                                                                        </div>
                                                                    </div>
                                                                </li>

                                                                <li class="info-item">
                                                                    <div class="info-icon icon-email">
                                                                        <i class="fa fa-envelope-o"></i>
                                                                    </div>
                                                                    <div class="info-content">
                                                                        <div class="info-label">Email</div>
                                                                        <div class="info-value">@{{ visitor.email }}
                                                                        </div>
                                                                    </div>
                                                                </li>

                                                                <li class="info-item">
                                                                    <div class="info-icon icon-phone">
                                                                        <i class="fa fa-id-card-o"></i>
                                                                    </div>
                                                                    <div class="info-content">
                                                                        <div class="info-label">Phone</div>
                                                                        <div class="info-value">@{{ visitor.phone }}
                                                                        </div>
                                                                    </div>
                                                                </li>

                                                                <li class="info-item">
                                                                    <div class="info-icon icon-gender">
                                                                        <i class="fa fa-user"></i>
                                                                    </div>
                                                                    <div class="info-content">
                                                                        <div class="info-label">Gender</div>
                                                                        <div class="info-value">@{{ visitor.gender }}
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                            <div class="text-end mt-3">
                                                                @can('students.create.visitor')
                                                                    <a v-if="visitor.student_id == null" :href="`{{ url('students') }}/${visitor.id}/visitor`"  
                                                                        class="btn btn-sm btn-outline-primary" 
                                                                        title="Add Student New (Admission)">
                                                                        <i class="fa fa-plus"></i> Add
                                                                    </a>
                                                                @endcan
                                                                @can('visitors.update')
                                                                    <a :href="'{{ url('visitors/edit') }}/' + visitor.id"
                                                                        class="btn btn-sm btn-outline-primary">
                                                                        <i class="fa fa-pencil"></i> Edit
                                                                    </a>
                                                                @endcan
                                                                @can('visitors.delete')
                                                                    <a  title="Delete"
                                                                        @click.prevent="deleteVisitor(visitor.id)" href="#"
                                                                        class="btn btn-sm btn-outline-danger">
                                                                        <i class="fa fa-trash"></i> Delete
                                                                    </a>
                                                                @endcan
                                                            </div>
                                                           
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="pagination" id="app">
                                            <nav class="text-center">
                                                <ul class="pagination">
                                                    <li v-for="(link, index) in pagination_links" :key="index"
                                                        :class="['page-item', { active: link.active, disabled: !link.url }]">
                                                        <a class="page-link" href="#"
                                                            @click.prevent="goToPage(link)" v-html="link.label"></a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @can('visitors.create')
                                <div id="tab-11" class="tab-pane fade add-visitor">
                                    <div class="panel-body">
                                        <h2> Create Visitor Student </h2>
                                        <div class="hr-line-dashed"></div>
                                        <form id="tchr_rgstr" method="post" action="{{ route('visitors.create') }}"
                                            class="form-horizontal">
                                            {{ csrf_field() }}

                                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Student Name</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="name" placeholder="Name"
                                                        value="{{ old('name') }}" class="form-control" />
                                                    @if ($errors->has('name'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('father_name') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Father Name</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="father_name" placeholder="Father Name"
                                                        value="{{ old('father_name') }}" class="form-control" />
                                                    @if ($errors->has('father_name'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('father_name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                        

                                            <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Gender</label>
                                                <div class="col-md-6">
                                                    <select class="form-control" name="gender" placeholder="Gender">
                                                        <option value="" disabled selected>Gender</option>
                                                        <option>Male</option>
                                                        <option>Female</option>
                                                    </select>
                                                    @if ($errors->has('gender'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('gender') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('date_of_birth') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Date Of Birth</label>
                                                <div class="col-md-6">
                                                    <input type="text" id="date_of_birth" name="date_of_birth"
                                                        placeholder="DOB" value="{{ old('date_of_birth') }}"
                                                        class="form-control" />
                                                    @if ($errors->has('date_of_birth'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('date_of_birth') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('place_of_birth') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Place Of Birth</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="place_of_birth" placeholder="Place Of Birth"
                                                        value="{{ old('place_of_birth') }}" class="form-control" />
                                                    @if ($errors->has('place_of_birth'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('place_of_birth') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('religion') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Religion</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="religion" placeholder="Religion"
                                                        value="{{ old('religion') }}" class="form-control" />
                                                    @if ($errors->has('religion'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('religion') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('last_school') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Last School</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="last_school"
                                                        placeholder="Last School Attendent" value="{{ old('last_school') }}"
                                                        class="form-control" />
                                                    @if ($errors->has('last_school'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('last_school') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('seeking_class') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Seeking Class</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="seeking_class" placeholder="Seeking Class"
                                                        value="{{ old('seeking_class') }}" class="form-control" />
                                                    @if ($errors->has('seeking_class'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('seeking_class') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('class') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Class</label>
                                                <div class="col-md-6 select2-div">
                                                    <select class="form-control select2" name="class">
                                                        <option value="" disabled selected>Class</option>
                                                        @foreach ($classes as $class)
                                                            <option value="{{ $class->id }}">{{ $class->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('class'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('class') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Contact No</label>
                                                <div class="col-md-6">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon">+92</span>
                                                        <input type="text" name="phone" value="{{ old('phone') }}"
                                                            placeholder="Contact No" class="form-control"
                                                            data-mask="9999999999" />
                                                    </div>
                                                    @if ($errors->has('phone'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('phone') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>


                                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Email</label>
                                                <div class="col-md-6">
                                                    <input type="email" name="email" placeholder="Email"
                                                        value="{{ old('email') }}" class="form-control" />
                                                    @if ($errors->has('email'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Address</label>
                                                <div class="col-md-6">
                                                    <textarea type="text" name="address" placeholder="Address" class="form-control">{{ old('address') }}</textarea>
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('date_of_visiting') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Date Of Visiting</label>
                                                <div class="col-md-6">
                                                    <input type="text" id="date_of_visiting" name="date_of_visiting"
                                                        placeholder="Date of Visiting" value="{{ old('date_of_visiting') }}"
                                                        class="form-control" required="true" />
                                                    @if ($errors->has('date_of_visiting'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('date_of_visiting') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Remarks</label>
                                                <div class="col-md-6">
                                                    <textarea type="text" name="remarks" placeholder="Remarks" class="form-control">{{ old('remarks') }}</textarea>
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
    <script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>


    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}", "Validation Error");
            @endforeach
        </script>
    @endif


    <script type="text/javascript">
        $(document).ready(function() {

            @if ($errors->any())
                $('a[href="#tab-11"]').tab('show');
            @else
                $('a[href="#tab-10"]').tab('show');
            @endif

            $('[data-toggle="tooltip"]').tooltip();

            $('#date_of_birth').datetimepicker({
                format: 'YYYY-MM-DD',
            });
            $('#date_of_visiting').datetimepicker({
                format: 'YYYY-MM-DD',
                defaultDate: moment()
            });


            $("#tchr_rgstr").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    father_name: {
                        required: true,
                    },
                    gender: {
                        required: true,
                    },
                    class: {
                        required: true,
                    },
                    religion: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                    seeking_class: {
                        required: true,
                    },
                    last_school: {
                        required: true,
                    },
                    date_of_birth: {
                        required: true,
                    },
                    date_of_visiting: {
                        required: true,
                    },
                    phone: {
                        required: true,
                    }
                },
            });

           

            @if (COUNT($errors) >= 1)
                $('#tchr_rgstr [name="gender"]').val('{{ old('gender') }}');
                $('#tchr_rgstr [name="class"]').val("{{ old('class') }}");
                $('#tchr_rgstr [name="class"]').change();
            @endif


            @if (COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
                $('.nav-tabs a[href="#tab-11"]').tab('show');
            @else
                $('.nav-tabs a[href="#tab-10"]').tab('show');
            @endif
        });
    </script>

@endsection

@section('vue')
    <script src="{{ asset('src/js/plugins/axios-1.11.0/axios.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

    <script type="text/javascript">
                var app = new Vue({
            el: '#app',
            data: {
                visitor_capacity: {{ tenancy()->tenant->system_info['general']['student_capacity'] }},
                layout: 'grid',
                options: [5, 10, 25, 50, 100],
                per_page: 10,
                current_page: 1,
                last_page: 1,
                total: 0,
                to: 0,
                from: 0,
                visitors: [],
                pagination_links: [],
                search_visitors: '',
            },

            methods: {
                handleLayoutChange(page = 1) {
                    axios.get('/visitors/grid', {
                            params: {
                                per_page: this.per_page,
                                page: page,
                                search_visitors: this.search_visitors,
                            }
                        })
                        .then(response => {
                            const res = response.data;
                            this.visitors = res.data;
                            this.current_page = res.current_page;
                            this.last_page = res.last_page;
                            this.to = res.to;
                            this.from = res.from;
                            this.total = res.total;
                            this.pagination_links = res.links;
                        })
                        .catch(error => {
                            console.error('Failed to fetch visitors:', error);
                        });
                },
                deleteVisitor(deleteId) {
                    swal({
                        title: "Are you sure?",
                        text: "You are about to delete this entry.",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel!"
                    }, (isConfirm) => {
                        if (isConfirm) {
                            swal({
                                title: "Deleting...",
                                text: "<i class='fa fa-spinner fa-pulse fa-4x'></i>",
                                html: true,
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });

                            axios.post("{{ url('visitors/delete') }}", {
                                    id: deleteId,
                                    _token: "{{ csrf_token() }}"
                                })
                                .then(() => {
                                    swal("Deleted!", "Record has been deleted.", "success");
                                    this.handleLayoutChange();
                                })
                                .catch(() => {
                                    swal("Error!", "Something went wrong. Please try again.", "error");
                                });

                        } else {
                            swal("Cancelled", "The record is safe :)", "error");
                        }
                    });
                },
                debouncedSearch(page = 1) {
                    clearTimeout(this.debounceTimeout);
                    this.debounceTimeout = setTimeout(() => {
                        this.handleLayoutChange(page);
                    }, 300);
                },
                goToPage(link) {
                    if (!link.url) return;
                    const url = new URL(link.url);
                    const page = url.searchParams.get('page');
                    this.handleLayoutChange(page);
                }
            },
            mounted: function() {
                this.handleLayoutChange();
            }
        });
    </script>
@endsection