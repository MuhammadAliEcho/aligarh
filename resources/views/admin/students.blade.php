@extends('admin.layouts.master')

@section('title', 'Students |')
@section('head')
    <link href="{{ asset('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <script type="text/javascript">
        var sections = {!! json_encode($sections ?? '') !!};
    </script>
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
    <style>
        .student-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            margin-bottom: 30px;
        }

        .student-card:hover {
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

        .student-card:hover .profile-image {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            padding: 60px 25px 25px;
            text-align: center;
        }

        .student-name {
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

        .icon-guardian {
            background: linear-gradient(135deg, #f093fb, #f5576c);
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
            .student-card {
                margin: 0 10px 20px;
            }

            .card-body {
                padding: 50px 20px 20px;
            }
        }

        .student-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .student-card {
      width: 250px;
        }

        .profile-image {
            width: 80px;
            height: 80px;
        }

        .card-body {
            padding: 25px 20px 10px;
        }

        .student-name {
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
            background: linear-gradient(#0f73d1 0%, #1c5b97 100%);
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

      .color-grey-70{
            color: #b3b3b3 !important;
        }
    </style>

    {{-- guardian-model --}}
    <style>

        .guardian-model-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: guardian-model-fadeInUp 0.5s ease-out;
        }

        .guardian-model-card-header {
            background: linear-gradient(135deg, #009486 0%, #1ab394 100%);
            display: flex;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            position: relative;
        }

        .guardian-model-profile-image-container {
            margin-bottom: 10px;
        }

        .guardian-model-profile-image {
            /* width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: 5px solid #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #fff; */
        }

        .guardian-model-title {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            text-transform: uppercase;
        }

        .guardian-model-card-body {
            padding: 20px;
        }

        .guardian-model-name {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }

        .guardian-model-status-select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .guardian-model-info-divider {
            border: none;
            height: 2px;
            background: #667eea;
            margin-bottom: 15px;
        }

        /* Form */
        .guardian-model-form-group {
            margin-bottom: 15px;
            flex: 1;
        }

        .guardian-model-form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .guardian-model-form-control {
            width: 100%;
            padding: 8px 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .guardian-model-form-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .guardian-model-submit-btn {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, #009486 0%, #1ab394 100%);
            color: #fff;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
        }

        .guardian-model-submit-btn:hover {
            opacity: 0.9;
        }

        @keyframes guardian-model-fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive inside modal */
        @media (max-width: 768px) {
            .guardian-model-card {
                font-size: 14px;
            }

            .guardian-model-profile-image {
                width: 80px;
                height: 80px;
                font-size: 30px;
            }
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
                <h2>Students</h2>
                <ol class="breadcrumb">
                    <li>Home</li>
                    <li Class="active">
                        <a>Students</a>
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
                            @canany(['students.index','students.grid'])
                                <li class="">
                                    <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Students</a>
                                </li>
                            @endcanany
                            @can('students.add')
                                <li class="add-student">
                                    <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Admit Students</a>
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
                                                <input v-show="layout === 'grid'" type="text" v-model="search_students"
                                                    @input="debouncedSearch" class="form-control input-sm"
                                                    style="width: 200px; display: inline-block;" placeholder="Search...">
                                                <div class="form-group pull-right">
                                                    <label class="control-label"
                                                        style="margin: 0 10px 0 20px; line-height: 34px;cursor: pointer;">
                                                        <span
                                                            :class="['fa', 'fa-th', { 'color-grey-70': layout !== 'grid' }]"
                                                            style="margin-right: 2px;" data-toggle="tooltip"
                                                            title="Grid Layout" @click="isGrid('grid')">
                                                        </span>
                                                        <span
                                                            :class="['fa', 'fa-list',
                                                                { 'color-grey-70': layout !== 'list' }
                                                            ]"
                                                            data-toggle="tooltip" title="List Layout"
                                                            @click="isGrid('list')">
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid" id="gridLayout" v-show="layout === 'grid'">
                                        <div class="" style="display: ruby">
                                            <div class="m-2" v-for="student in students" :key="student.id">
                                                <a :href="'{{ url('students/profile') }}/' + student.id"
                                                    class="text-decoration-none">
                                                    <div class="panel student-card">
                                                        <div class="ribbon"><span>Student</span></div>
                                                        <div class="card-header">
                                                            <div class="profile-image-container">
                                                                <img :src="student.image_url || 'img/avatar.jpg'"
                                                                    alt="Student Photo" class="profile-image">
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <h4 class="student-name">@{{ student.name }}</h4>
                                                            <hr class="info-divider">
                                                            <ul class="list-unstyled info-list">
                                                                <li class="info-item">
                                                                    <div class="info-icon icon-education">
                                                                        <i class="fa fa-graduation-cap"></i>
                                                                    </div>
                                                                    <div class="info-content">
                                                                        <div class="info-label">Class</div>
                                                                        <div class="info-value">@{{ student.std_class.name }}
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="info-item">
                                                                    <div class="info-icon icon-id">
                                                                        <i class="fa fa-id-card-o"></i>
                                                                    </div>
                                                                    <div class="info-content">
                                                                        <div class="info-label">GR No</div>
                                                                        <div class="info-value">@{{ student.gr_no }}
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="info-item">
                                                                    <div class="info-icon icon-gender">
                                                                        <i class="fa fa-user"></i>
                                                                    </div>
                                                                    <div class="info-content">
                                                                        <div class="info-label">Gender</div>
                                                                        <div class="info-value">@{{ student.gender }}
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                @can('guardian.profile')
                                                                    <a :href="'{{ url('guardians/profile') }}/' + student.guardian
                                                                        .id"
                                                                        class="text-decoration-none">
                                                                        <li class="info-item">
                                                                            <div class="info-icon icon-guardian">
                                                                                <i class="fa fa-users"></i>
                                                                            </div>
                                                                            <div class="info-content">
                                                                                <div class="info-label">Guardian</div>
                                                                                <div class="info-value info-value">
                                                                                    @{{ student.guardian.name }}</div>
                                                                            </div>
                                                                        </li>
                                                                    </a>
                                                                @endcan
                                                                @cannot('guardian.profile')
                                                                    <li class="info-item">
                                                                        <div class="info-icon icon-guardian">
                                                                            <i class="fa fa-money"></i>
                                                                        </div>
                                                                        <div class="info-content">
                                                                            <div class="info-label">Guardian</div>
                                                                            <div class="info-value info-value">
                                                                                @{{ student.guardian.name }}</div>
                                                                        </div>
                                                                    </li>
                                                                @endcan
                                                                @can('fee.create.store')
                                                                    <li v-if="student.invoice_status === 'unpaid'"
                                                                        class="info-item">
                                                                        <div class="info-icon icon-fee">
                                                                            <i class="fa fa-money"></i>
                                                                        </div>
                                                                        <div class="info-content">
                                                                            <div class="info-label">Monthly Fee</div>
                                                                            <div class="info-value fee-amount">PKR
                                                                                @{{ student.tuition_fee }}</div>
                                                                            <span data-placement="right" data-toggle="tooltip"
                                                                                title="Please Pay Fee"
                                                                                class="badge unpaid-badge">Unpaid</span>
                                                                        </div>
                                                                    </li>

                                                                    <li v-else-if="student.invoice_status === 'paid'"
                                                                        class="info-item">
                                                                        <div class="info-icon icon-fee">
                                                                            <i class="fa fa-money"></i>
                                                                        </div>
                                                                        <div class="info-content">
                                                                            <div class="info-label">Monthly Fee</div>
                                                                            <div class="info-value fee-amount">PKR
                                                                                @{{ student.tuition_fee }}</div>
                                                                            <span data-placement="right" data-toggle="tooltip"
                                                                                title="Fee Paid"
                                                                                class="badge paid-badge">Paid</span>
                                                                        </div>
                                                                    </li>
                                                                    <a v-else-if="student.invoice_status === 'not_created'"
                                                                        :href="'{{ url('fee/create?gr_no=') }}' + student.id"
                                                                        class="text-decoration-none">
                                                                        <li class="info-item">
                                                                            <div class="info-icon icon-fee">
                                                                                <i class="fa fa-money"></i>
                                                                            </div>
                                                                            <div class="info-content">
                                                                                <div class="info-label">Monthly Fee</div>
                                                                                <div class="info-value fee-amount">PKR
                                                                                    @{{ student.tuition_fee }}</div>
                                                                            </div>
                                                                        </li>
                                                                    </a>
                                                                @endcan
                                                                @cannot('fee.create.store')
                                                                    <li class="info-item">
                                                                        <div class="info-icon icon-fee">
                                                                            <i class="fa fa-money"></i>
                                                                        </div>
                                                                        <div class="info-content">
                                                                            <div class="info-label">Monthly Fee</div>
                                                                            <div class="info-value fee-amount">PKR
                                                                                @{{ student.tuition_fee }}</div>
                                                                        </div>
                                                                    </li>
                                                                @endcan
                                                            </ul>
                                                            <div class="text-end mt-3">
                                                                @can('students.edit.post')
                                                                    <a :href="'{{ url('students/edit') }}/' + student.id"
                                                                        class="btn btn-sm btn-outline-primary">
                                                                        <i class="fa fa-pencil"></i> Edit
                                                                    </a>
                                                                @endcan
                                                                @can('students.card')
                                                                    <a :href="'{{ url('students/id-card') }}/' + student.id"
                                                                        class="btn btn-sm btn-outline-primary" target="_blank">
                                                                        <i class="fa fa-id-card-o"></i> ID Card
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
                                    <div id="listLayout" v-show="layout === 'list'">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-striped table-bordered table-hover dataTables-student"
                                                width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Class</th>
                                                        <th>GR No</th>
                                                        <th>Name</th>
                                                        <th>Father Name</th>
                                                        <th>Religion</th>
                                                        <th>Contact</th>
                                                        <th>Address</th>
                                                        <th>Birth Date</th>
                                                        <th>Place Of Birth</th>
                                                        <th>Last School</th>
                                                        <th>Admission Date</th>
                                                        <th>Enrolled Date</th>
                                                        <th>Remove Date</th>
                                                        <th>Cause Of Removal</th>
                                                        <th>Active</th>
                                                        <th>Options</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>
                                                            <select id="filterClass">
                                                                <option value="">All</option>
                                                                @foreach ($classes as $class)
                                                                    <option value="{{ $class->id }}">{{ $class->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </th>
                                                        <th><input type="text" placeholder="Gr No..."
                                                                autocomplete="off"></th>
                                                        <th><input type="text" placeholder="Name..."
                                                                autocomplete="off"></th>
                                                        <th><input type="text" placeholder="Father Name..."
                                                                autocomplete="off"></th>
                                                        <th><input type="text" placeholder="Religion..."
                                                                autocomplete="off"></th>
                                                        <th><input type="text" placeholder="Contact..."
                                                                autocomplete="off"></th>
                                                        <th><input type="text" placeholder="Address..."
                                                                autocomplete="off"></th>
                                                        <th>DOB</th>
                                                        <th>Place Of Birth</th>
                                                        <th>Last School</th>
                                                        <th>Admission Date</th>
                                                        <th>Enrolled Date</th>
                                                        <th>Remove Date</th>
                                                        <th>Cause Of Removal</th>
                                                        <th></th>
                                                        <th>
                                                            <select id="filterActive">
                                                                <option value="">All</option>
                                                                <option value="1">Active</option>
                                                                <option value="0">InActive</option>
                                                            </select>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @can('students.add')
                                <div id="tab-11" class="tab-pane fade add-student">
                                    <div class="panel-body">
                                        <h2> Admit Student </h2>
                                        <div class="hr-line-dashed"></div>

                                        <form v-if="admission_allow" id="tchr_rgstr" method="post"
                                            action="{{ URL('students/add') }}" class="form-horizontal"
                                            enctype="multipart/form-data">
                                            {{ csrf_field() }}

                                            <div class="form-group{{ $errors->has('guardian') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Guardian</label>
                                                <div class="col-md-6">
                                                    <select class="form-control" name="guardian" id="guardian-select">
                                                        <option value="" disabled selected>Guardian</option>
                                                        @foreach ($guardians as $guardian)
                                                            <option 
                                                                value="{{ $guardian->id }}"
                                                                data-address="{{ e($guardian->address ?? '') }}"
                                                                data-phone="{{ e($guardian->phone ?? '') }}">
                                                                {{ $guardian->name . ' | ' . $guardian->email }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('guardian'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('guardian') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                @can('guardian.add')
                                                    <div style="padding-top: 5px; cursor: pointer;"
                                                        data-toggle="modal" data-target="#guardianModal">
                                                        <i data-placement="top" data-toggle="tooltip" title="Add Guardian" id="addGuardian" class="fa fa-plus"></i>
                                                    </div>
                                                @endcan
                                            </div>

                                            <div
                                                class="form-group{{ $errors->has('guardian_relation') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Guardian Relation</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="guardian_relation"
                                                        placeholder="Guardian Relation"
                                                        value="{{ old('guardian_relation') }}" class="form-control" />
                                                    @if ($errors->has('guardian_relation'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('guardian_relation') }}</strong>
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

                                            <div class="form-group{{ $errors->has('dob') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Date Of Birth</label>
                                                <div class="col-md-6">
                                                    <input type="text" id="datetimepicker4" name="dob"
                                                        placeholder="DOB" value="{{ old('dob') }}"
                                                        class="form-control" />
                                                    @if ($errors->has('dob'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('dob') }}</strong>
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

                                            <div class="form-group{{ $errors->has('img') ? ' has-error' : '' }}">
                                                <div class="col-md-2">
                                                    <span class="btn btn-default btn-block btn-file">
                                                        <input type="file" name="img" accept="image/*"
                                                            id="imginp" />
                                                        <span class="fa fa-image"></span>
                                                        Upload Image
                                                    </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <img id="img" src="" alt="Item Image..."
                                                        class="img-responsive img-thumbnail" 
                                                        style="max-width:100px !important;min-width:105px !important;"/>
                                                    @if ($errors->has('img'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('img') }}</strong>
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

                                            <div class="alert alert-warning ">
                                                <h4>Note! </h4>
                                                <p>
                                                    Once the class is set, it cannot be edited until the session ends.
                                                </p>

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

                                                <div class="form-group{{ $errors->has('section') ? ' has-error' : '' }}">
                                                    <label class="col-md-2 control-label">Section</label>
                                                    <div class="col-md-6 select2-div">
                                                        <select class="form-control select2" name="section">
                                                            <option value="" disabled selected>Section</option>
                                                        </select>
                                                        @if ($errors->has('section'))
                                                            <span class="help-block">
                                                                <strong><span class="fa fa-exclamation-triangle"></span>
                                                                    {{ $errors->first('section') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-group{{ $errors->has('gr_no') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">GR No</label>
                                                <div class="col-md-6">
                                                    <input type="number" name="gr_no" placeholder="GR NO"
                                                        value="{{ old('gr_no') }}" class="form-control" />
                                                    @if ($errors->has('gr_no'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('gr_no') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('doa') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Date Of Admission</label>
                                                <div class="col-md-6">
                                                    <input type="text" id="datetimepicker5" name="doa"
                                                        placeholder="Date of Admission" value="{{ old('doa') }}"
                                                        class="form-control" required="true" />
                                                    @if ($errors->has('doa'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('doa') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('doe') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Date Of Enrolled</label>
                                                <div class="col-md-6">
                                                    <input type="text" id="datetimepicker6" name="doe"
                                                        placeholder="Date of Enrolled" value="{{ old('doe') }}"
                                                        class="form-control" required="true" />
                                                    @if ($errors->has('doe'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('doe') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('receipt_no') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Receipt No</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="receipt_no" placeholder="Receipt NO"
                                                        value="{{ old('receipt_no') }}" class="form-control" />
                                                    @if ($errors->has('receipt_no'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('receipt_no') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-lg-8">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading">
                                                        Additional Feeses <a href="#" id="addfee"
                                                            data-toggle="tooltip" title="Add Fee" @click="addAdditionalFee()"
                                                            style="color: #ffffff"><span class="fa fa-plus"></span></a>
                                                    </div>
                                                    <div class="panel-body">
                                                        <table id="additionalfeetbl"
                                                            class="table table-bordered table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th width="40%">Name</th>
                                                                    <th width="40%">Amount</th>
                                                                    <th width="20%">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Tuition Fee</td>
                                                                    <td>
                                                                        <div>
                                                                            <input type="number" name="tuition_fee"
                                                                                v-model.number="fee.tuition_fee"
                                                                                placeholder="Tuition Fee" min="1"
                                                                                class="form-control" />
                                                                            @if ($errors->has('tuition_fee'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('tuition_fee') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                    <td></td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Late Fee</td>
                                                                    <td>
                                                                        <div>
                                                                            <input type="number" name="late_fee"
                                                                                v-model.number="fee.late_fee"
                                                                                placeholder="Tuition Fee" min="1"
                                                                                class="form-control" />
                                                                            @if ($errors->has('late_fee'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('late_fee') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                    <td></td>
                                                                </tr>

                                                                <tr v-for="(fee, k) in fee.additionalfee">
                                                                    <td><input type="hidden" :name="'fee[' + k + '][id]'"
                                                                            value="0"><input type="text"
                                                                            :name="'fee[' + k + '][fee_name]'"
                                                                            class="form-control" required="true"
                                                                            v-model="fee.fee_name"></td>
                                                                    <td><input type="number" :name="'fee[' + k + '][amount]'"
                                                                            class="form-control additfeeamount"
                                                                            required="true" min="1"
                                                                            v-model.number="fee.amount"></td>
                                                                    <td>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"
                                                                                data-toggle="tooltip"
                                                                                title="select if onetime charge">
                                                                                <input type="checkbox"
                                                                                    :name="'fee[' + k + '][onetime]'"
                                                                                    value="1" :checked="fee.onetime">
                                                                            </span>
                                                                            <span class="input-group-addon"
                                                                                data-toggle="tooltip" title="Active">
                                                                                <input type="checkbox"
                                                                                    :name="'fee[' + k + '][active]'"
                                                                                    value="1" :checked="fee.active"
                                                                                    @click="fee.active = !fee.active">
                                                                            </span>
                                                                            <a href="javascript:void(0);"
                                                                                class="btn btn-default text-danger removefee"
                                                                                data-toggle="tooltip"
                                                                                @click="removeAdditionalFee(k)"
                                                                                title="Remove">
                                                                                <span class="fa fa-trash"></span>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>

                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th>Total</th>
                                                                    <th>@{{ total_amount }}</th>
                                                                    <th></th>
                                                                </tr>
                                                                <tr>
                                                                    <td>Discount</td>
                                                                    <td><input type="number" name="discount"
                                                                            class="form-control" placeholder="Discount"
                                                                            min="0" v-model.number="fee.discount"></td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Net Amount</th>
                                                                    <th>@{{ net_amount }}</th>
                                                                    <th></th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="net_amount" v-model="net_amount">
                                            <input type="hidden" name="total_amount" v-model="total_amount">

                                            <div class="form-group">
                                                <div class="col-md-offset-2 col-md-6">
                                                    <button class="btn btn-primary" type="submit"><span
                                                            class="glyphicon glyphicon-save"></span> Register </button>
                                                </div>
                                            </div>

                                            <!-- Modal -->
                                            <div class="modal fade" id="guardianModal" tabindex="-1" role="dialog" aria-labelledby="guardianModalLabel">
                                              <div class="modal-dialog" role="document">
                                                  <div class="modal-content">
                                                      <div class="modal-header">
                                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                              <span aria-hidden="true">&times;</span>
                                                          </button>
                                                          <h4 class="modal-title" id="guardianModalLabel">Add Guardian</h4>
                                                      </div>
                                                    <div class="modal-body">
                                                        <div class="guardian-model-card">
                                                            <div class="guardian-model-card-header">
                                                                <div class="guardian-model-profile-image-container">
                                                                    <div class="guardian-model-profile-image">
                                                                        <img src="/img/avatar.jpg" alt="Guardian Photo" class="profile-image">
                                                                        {{-- <i class="fas fa-user-shield"></i> --}}
                                                                    </div>
                                                                </div>
                                                                <div class="guardian-model-title">Guardian Info</div>
                                                            </div>

                                                            <div class="guardian-model-card-body">
                                                                <h3 class="guardian-model-name">Guardian Information</h3>

                                                                <hr class="guardian-model-info-divider">

                                                                <form id="guardianModalForm" class="guardian-model-form">
                                                                    <div class="guardian-model-form-row">
                                                                        <div class="guardian-model-form-group">
                                                                            <label class="guardian-model-form-label">Name<span class="text-danger">*</span></label>
                                                                            <input id="guardian_name" required type="name" class="guardian-model-form-control" placeholder="Enter Name">
                                                                        </div>
                                                                        <div class="guardian-model-form-group">
                                                                            <label class="guardian-model-form-label">Email Address</label>
                                                                            <input id="guardian_email" required type="email" class="guardian-model-form-control" placeholder="Enter Email">
                                                                        </div>
                                                                    </div>

                                                                    <div class="guardian-model-form-row">
                                                                        <div class="guardian-model-form-group">
                                                                            <label class="guardian-model-form-label">Profession</label>
                                                                            <input id="guardian_profession" type="profession" class="guardian-model-form-control" placeholder="Enter Profession">
                                                                        </div>
                                                                        <div class="guardian-model-form-group">
                                                                            <label class="guardian-model-form-label">Phone No</label>
                                                                            <input id="guardian_phone" type="tel" class="guardian-model-form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="guardian-model-form-row">
                                                                        <div class="guardian-model-form-group">
                                                                            <label class="guardian-model-form-label">Address</label>
                                                                             <textarea id="guardian_address" style="height: 34px; width: 246px;" type="text" name="address" placeholder="Address" class="form-control"></textarea>
                                                                        </div>
                                                                        <div class="guardian-model-form-group">
                                                                            <label class="guardian-model-form-label">Income</label>
                                                                            <input id="guardian_income" type="number" value="0" name="income" class="guardian-model-form-control" placeholder="Enter Income">
                                                                        </div>
                                                                    </div>

                                                                    <div class="guardian-model-form-group" style="margin-top: 15px;">
                                                                        <button type="button" @click="addGuardian()" class="guardian-model-submit-btn">Add Guardian Information</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                  </div>
                                              </div>
                                            </div>
                                        </form>

                                        <div v-else class="alert alert-info">
                                            Student admission limit is over.
                                        </div>

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

    <!-- Mainly scripts
            <script src="{{ asset('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>
            -->

    <script src="{{ asset('src/js/plugins/dataTables/datatables.min.js') }}"></script>

    <script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- Input Mask-->
    <script src="{{ asset('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('src/js/plugins/select2/select2.full.min.js') }}"></script>

    <!-- require with bootstrap-datetimepicker -->
    <script src="{{ asset('src/js/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>



    <script type="text/javascript">
        var tbl;
        var tr;

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function loadOptions(data, type, full, meta) {
            opthtm = '';
            @can('students.profile')
                opthtm = '<a href="{{ URL('students/profile') }}/' + full.id +
                    '" data-toggle="tooltip" title="Profile" class="btn btn-' + ((full.active == 1) ? 'default' :
                        'danger') + ' btn-circle btn-xs profile"><span class="fa fa-user"></span></a>';
            @endcan
            @can('students.edit.post')
                opthtm += '<a href="{{ URL('students/edit') }}/' + full.id +
                    '" data-toggle="tooltip" title="Edit Student" class="btn btn-default btn-circle btn-xs"><span class="fa fa-edit"></span></a>';
            @endcan
            return opthtm;
        }

        $(document).ready(function() {

            $('[data-toggle="tooltip"]').tooltip();

            $('#datetimepicker4').datetimepicker({
                format: 'DD/MM/YYYY',
            });
            $('#datetimepicker5').datetimepicker({
                format: 'DD/MM/YYYY',
                defaultDate: moment()
            });

            $('#datetimepicker6').datetimepicker({
                format: 'YYYY-MM-DD',
                defaultDate: moment()

            });

            $('#guardian-select').on('change', function() {
                var selected = $(this).find('option:selected');
                var address = selected.data('address');
                var phone = selected.data('phone');

                $('textarea[name="address"]').val(address);
                $('input[name="phone"]').val(phone);
            });

            /*    For Column Search  */
            /*        $('.dataTables-student tfoot th').each( function () {
                        var title = $('.dataTables-student tfoot th').eq( $(this).index() ).text();
                      if (title !== 'Options') {
                        $(this).html( '<input type="text" placeholder="'+title+'" />' );
                      }
                    });
            */


            tbl = $('.dataTables-student').DataTable({
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    //  {extend: 'copy'},
                    //  {extend: 'csv'},
                    //  {extend: 'excel', title: 'Students List'},
                    //  {extend: 'pdf', title: 'Students List'},

                    {
                        extend: 'print',
                        customize: function(win) {
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '12px');

                            $(win.document.body).find('table')
                                .addClass('compact')
                                .addClass('print-table')
                                .removeClass('table')
                                .removeClass('table-striped')
                                .removeClass('table-bordered')
                                .removeClass('table-hover')
                                .css('font-size', 'inherit');
                        },
                        exportOptions: {
                            columns: ":visible"
                        },
                        title: "Student Register | {{ tenancy()->tenant->system_info['general']['title'] }}",
                    },
                    'colvis'
                ],
                Processing: true,
                serverSide: true,
                ajax: '{{ URL('students') }}',
                columns: [{
                        data: 'class_name',
                        name: 'academic_session_history.class_id'
                    },
                    {
                        data: 'gr_no',
                        name: 'students.gr_no'
                    },
                    {
                        data: 'name',
                        name: 'students.name'
                    },
                    {
                        data: 'father_name',
                        name: 'students.father_name'
                    },
                    {
                        data: 'religion',
                        name: 'students.religion',
                        visible: false
                    },
                    {
                        data: 'phone',
                        name: 'students.phone'
                    },
                    {
                        data: 'address',
                        name: 'students.address'
                    },
                    {
                        data: 'date_of_birth',
                        name: 'students.date_of_birth'
                    },
                    {
                        data: 'place_of_birth',
                        name: 'students.place_of_birth'
                    },
                    {
                        data: 'last_school',
                        name: 'students.last_school'
                    },
                    {
                        data: 'date_of_admission',
                        name: 'students.date_of_admission'
                    },
                    {
                        data: 'date_of_enrolled',
                        name: 'students.date_of_enrolled',
                        visible: false
                    },
                    {
                        data: 'date_of_leaving',
                        name: 'students.date_of_leaving',
                        visible: false
                    },
                    {
                        data: 'cause_of_leaving',
                        name: 'students.cause_of_leaving',
                        visible: false
                    },
                    {
                        data: 'active',
                        name: 'students.active',
                        visible: false
                    },
                    //            {"defaultContent": opthtm, className: 'hidden-print'},
                    {
                        render: loadOptions,
                        className: 'hidden-print',
                        "orderable": false
                    },

                ],
                "order": [
                    [1, "asc"]
                ],
                "scrollY": "450px",
                "scrollX": true,
                "scrollCollapse": true,
                "paging": true,
                /*          "columnDefs": [
                            {
                                // The `data` parameter refers to the data for the cell (defined by the
                                // `data` option, which defaults to the column being worked with, in
                                // this case `data: 0`.
                                "render": function ( data, type, row ) {
                                    return data +' ('+ row.section_nick +')';
                                },
                                "targets": 0
                            },
                            { "visible": false,  "targets": [ 1 ] }
                          ]*/
            });

            var search = $.fn.dataTable.util.throttle(
                function(colIdx, val, exactmatch = false) {
                    regExSearch = '^' + val + '$';
                    tbl
                        .column(colIdx)
                        .search(exactmatch ? regExSearch : val, true, false)
                        .draw();
                },
                1000
            );

            //    for Column search
            tbl.columns().eq(0).each(function(colIdx) {
                $('input', tbl.column(colIdx).footer()).on('keyup change', function() {
                    search(colIdx, this.value);
                });
            });
            $("#filterActive").on('change', function() {
                search((tbl.columns.length - 2), this.value);
            });

            $("#filterClass").on('change', function() {
                search(0, this.value, (this.value == '') ? false : true);
            });


            /*    tbl.columns().every( function () {
                    var that = this;
                    $( 'input', this.footer() ).on( 'keyup change', function () {
                        if ( that.search() !== this.value ) {
                            that
                                .search( this.value )
                                .draw();
                        }
                    });
                });*/

            $('.dataTables-student tbody').on('mouseenter', '[data-toggle="tooltip"]', function() {
                $(this).tooltip('show');
            });

            $("#tchr_rgstr").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    email: {
                        email: true,
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
                    section: {
                        required: true,
                    },
                    guardian: {
                        required: true,
                    },
                    guardian_relation: {
                        required: true,
                    },
                    tuition_fee: {
                        required: true,
                    },
                    dob: {
                        required: true,
                    },
                    doa: {
                        required: true,
                    },
                    doe: {
                        required: true,
                    },
                    gr_no: {
                        required: true,
                        number: true,
                    },
                },
            });

            $('#tchr_rgstr [name="class"]').on('change', function() {
                clsid = $(this).val();
                $('#tchr_rgstr [name="section"]').html('');
                if (sections['class_' + clsid].length > 0) {
                    $.each(sections['class_' + clsid], function(k, v) {
                        $('#tchr_rgstr [name="section"]').append('<option value="' + v['id'] +
                            '">' + v['name'] + '</option>');
                    });
                }
            });

            @if (COUNT($errors) >= 1)
                $('#tchr_rgstr [name="gender"]').val('{{ old('gender') }}');
                $('#tchr_rgstr [name="guardian"]').val('{{ old('guardian') }}');
                $('#tchr_rgstr [name="class"]').val("{{ old('class') }}");
                $('#tchr_rgstr [name="class"]').change();
                $('#tchr_rgstr [name="section"]').val('{{ old('section') }}');
            @endif

            $('#tchr_rgstr [name="guardian"]').attr('style', 'width:100%').select2({
                placeholder: "Nothing Selected",
                allowClear: true,
            });

            @if (COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
                $('.nav-tabs a[href="#tab-11"]').tab('show');
            @else
                $('.nav-tabs a[href="#tab-10"]').tab('show');
            @endif

            $("#imginp").change(function() {
                readURL(this);
            });
        });
    </script>

@endsection

@section('vue')
    <script src="{{ asset('src/js/plugins/axios-1.11.0/axios.min.js') }}"></script>
    <script type="text/javascript">
        var app = new Vue({
            el: '#app',
            data: {
                fee: {
                    additionalfee: {!! old('fee', config('feeses.additional_fee')) !!},
                    tuition_fee: {{ old('tuition_fee', config('feeses.compulsory.tuition_fee')) }},
                    late_fee: {{ old('late_fee', config('feeses.compulsory.late_fee')) }},
                    discount: {{ old('discount', 0) }},
                },
                no_of_active_students: {{ $no_of_active_students }},
                student_capacity: {{ tenancy()->tenant->system_info['general']['student_capacity'] }},
                layout: 'grid',
                options: [5, 10, 25, 50, 100],
                per_page: 10,
                current_page: 1,
                last_page: 1,
                total: 0,
                to: 0,
                from: 0,
                students: [],
                pagination_links: [],
                search_students: '',
            },

            methods: {
                addAdditionalFee: function() {
                    this.fee.additionalfee.push({
                        id: 0,
                        fee_name: '',
                        amount: 0,
                        active: 1,
                        onetime: 1
                    });
                },
                removeAdditionalFee: function(k) {
                    this.fee.additionalfee.splice(k, 1);
                },
                handleLayoutChange(page = 1) {
                    axios.get('/students/grid', {
                            params: {
                                per_page: this.per_page,
                                page: page,
                                search_students: this.search_students,
                            }
                        })
                        .then(response => {
                            const res = response.data;
                            this.students = res.data;
                            this.current_page = res.current_page;
                            this.last_page = res.last_page;
                            this.to = res.to;
                            this.from = res.from;
                            this.total = res.total;
                            this.pagination_links = res.links;
                        })
                        .catch(error => {
                            console.error('Failed to fetch students:', error);
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
                },
                isGrid(val = 'grid') {
                    this.layout = val === 'grid' ? 'grid' : 'list';
                    this.$nextTick(() => {
                        $('.dataTables-student').DataTable().columns.adjust().draw();
                    });
                },
                addGuardian() {
                    axios.post('/guardians/add', {
                        name: $('#guardian_name').val(),
                        email: $('#guardian_email').val(),
                        phone: $('#guardian_phone').val(),
                        profession: $('#guardian_profession').val(),
                        address: $('#guardian_address').val(),
                        income: $('#guardian_income').val(),
                    }, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        toastr.success("Guardian added successfully");
                        $('#guardianModal').modal('hide');
                        axios.get('students/guardians/list')
                            .then(res => {
                                const dropdown = $('select[name="guardian"]');
                                dropdown.empty();
                                dropdown.append('<option value="" disabled selected>Select Guardian</option>');
                                res.data.forEach(guardian => {
                                    dropdown.append(
                                        `<option value="${guardian.id}">${guardian.name} | ${guardian.email}</option>`
                                    );
                                });
                            })
                            .catch(err => {
                                toastr.error("Could not refresh guardian list.");
                                console.error(err);
                            });
                    })
                    .catch(error => {
                        if (error.response && error.response.status === 422) {
                            const errors = error.response.data.errors;

                            Object.keys(errors).forEach(field => {
                                errors[field].forEach(msg => {
                                    toastr.error(msg, "Validation Error");
                                });
                            });
                        } else {
                            toastr.error("Something went wrong. Please try again.", "Error");
                            console.error('Error:', error);
                        }
                    });
                },
            },

            computed: {
                total_amount: function() {
                    tot_amount = Number(this.fee.tuition_fee);
                    for (k in this.fee.additionalfee) {
                        if (this.fee.additionalfee[k].active) {
                            tot_amount += Number(this.fee.additionalfee[k].amount);
                        }
                    }
                    return tot_amount;
                },
                net_amount: function() {
                    return Number(this.total_amount) - Number(this.fee.discount);
                },
                admission_allow: function() {
                    return this.no_of_active_students < this.student_capacity
                }
            },
            mounted: function() {
                this.handleLayoutChange();
            }
        });
    </script>
@endsection
