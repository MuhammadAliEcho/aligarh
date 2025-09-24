@extends('admin.layouts.master')

@section('title', 'Guardians |')

@section('head')
    <style>
        .guardian-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            margin-bottom: 30px;
            width: 100%;
            max-width: 90vw;
        }

        .guardian-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15), 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(135deg, #009486 0%, #1ab394 100%);
            height: 170px;
            position: relative;
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
            bottom: -40px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
        }

        .profile-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 5px solid #ffffff;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            object-fit: cover;
        }

        .guardian-card:hover .profile-image {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            padding: 50px 25px 25px;
            text-align: center;
            /* This centers all content */
        }

        .guardian-name {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 8px;
            letter-spacing: -0.5px;
            text-align: center;
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
            margin: 20px 0;
        }

        .info-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }

        .info-column {
            display: flex;
            flex-direction: column;
        }

        .info-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 12px 8px;
            margin-bottom: 12px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 12px;
            border: 1px solid #f1f3f4;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background: rgba(102, 126, 234, 0.08);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }

        .info-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 14px;
            color: white;
            flex-shrink: 0;
        }

        .icon-education {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .icon-id {
            background: linear-gradient(135deg, #00cec9, #55a3ff);
        }

        .icon-address {
            background: linear-gradient(135deg, #fd79a8, #fdcb6e);
        }

        .icon-students {
            background: linear-gradient(135deg, #db1251be, #753a88);
        }

        .icon-fee {
            background: linear-gradient(135deg, #00b894, #55efc4);
        }

        .info-content {
            flex: 1;
            min-width: 0;
            /* Allows text to wrap properly */
        }

        .info-label {
            font-size: 10px;
            color: #74b9ff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
            text-align: left;
        }

        .info-value {
            font-size: 12px;
            color: #2d3436;
            font-weight: 600;
            line-height: 1.3;
            word-break: break-word;
            text-align: left;
        }

        .fee-amount {
            color: #00b894;
            font-weight: 700;
        }

        .address-text {
            font-size: 12px !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .guardian-card {
                width: 350px;
                margin: 0 10px 20px;
            }

            .card-body {
                padding: 45px 20px 20px;
            }

            .guardian-name {
                font-size: 18px;
            }

            .info-container {
                grid-template-columns: 1fr;
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            .guardian-card {
                width: 300px;
            }

            .info-value {
                font-size: 11px;
            }

            .status-badge {
                font-size: 10px;
                padding: 4px 12px;
            }

            .info-container {
                grid-template-columns: 1fr;
            }
        }

        .guardian-card {
            animation: fadeInUp 0.6s ease-out;
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

        .students-heading {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            letter-spacing: -0.5px;
            position: relative;
            display: inline-block;
            padding-bottom: 8px;
        }

        .students-heading::after {
            content: '';
            width: 60%;
            height: 3px;
            background: linear-gradient(90deg, #009486, #1ab394);
            position: absolute;
            bottom: 0;
            left: 20%;
            border-radius: 5px;
        }
        /* .profile-heading {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            letter-spacing: -0.5px;
            position: relative;
            display: inline-block;
            padding-bottom: 8px;
            margin: 0px 0px 5px 0px;
        }
        .profile-heading::after {
            content: '';
            width: 60%;
            height: 3px;
            background: linear-gradient(90deg, #009486, #1ab394);
            position: absolute;
            bottom: 0;
            left: 20%;
            border-radius: 5px;
        } */

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

        .student-card-header {
            background: linear-gradient(135deg, #009486 0%, #1ab394 100%);
            height: 70px;
            position: relative;
            /* overflow: hidden; */
        }

        .student-card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.15"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }

        .student-profile-image-container {
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            /* z-index: 10; */
        }

        .student-profile-image {
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

        .student-card-body {
            padding: 60px 25px 25px;
            text-align: center;
        }

        .student-student-name {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 8px;
            letter-spacing: -0.5px;
        }

        .student-status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 25px;
        }

        .student-tatus-active {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
        }

        .student-status-inactive {
            background: linear-gradient(135deg, #e17055, #fdcb6e);
            color: white;
        }

        .student-info-divider {
            border: none;
            height: 2px;
            background: linear-gradient(90deg, transparent, #667eea, transparent);
            margin: 0px 0;
        }

        .student-info-list {
            text-align: left;
            margin: 0;
            padding: 0;
        }

        .student-info-item {
            display: flex;
            align-items: center;
            padding: 7px 0;
            border-bottom: 1px solid #f8f9fa;
            transition: all 0.3s ease;
        }

        .student-info-item:last-child {
            border-bottom: none;
        }

        .student-info-item:hover {
            background: rgba(102, 126, 234, 0.05);
            padding-left: 10px;
            border-radius: 8px;
        }

        .student-info-icon {
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

        .student-icon-education {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .student-icon-id {
            background: linear-gradient(135deg, #00cec9, #55a3ff);
        }

        .student-icon-gender {
            background: linear-gradient(135deg, #fd79a8, #fdcb6e);
        }

        .student-icon-fee {
            background: linear-gradient(135deg, #00b894, #55efc4);
        }

        .student-icon-guardian {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            ;
        }

        .student-info-content {
            flex: 1;
        }

        .student-info-label {
            font-size: 12px;
            color: #74b9ff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.student-5px;
            margin-bottom: 2px;
        }

        .student-info-value {
            font-size: 15px;
            color: #2d3436;
            font-weight: 600;
        }

        .student-fee-amount {
            color: #00b894;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .student-card {
                margin: 0 10px 20px;
            }

            .student-card-body {
                padding: 50px 20px 20px;
            }
        }

        .student-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .student-card {
            width: 250px;
        }

        .student-profile-image {
            width: 80px;
            height: 80px;
        }

        .student-card-body {
            padding: 25px 20px 10px;
        }

        .student-name {
            font-size: 18px;
        }

        .student-info-value {
            font-size: 14px;
        }

        .student-status-badge {
            font-size: 10px;
            padding: 4px 12px;
        }

        .student-m-2 {
            margin: 1rem 1rem 0rem 1rem;
        }

        .student-pagination nav {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .student-pagination {
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
    </style>
@endsection

@section('content')

    @include('admin.includes.side_navbar')

    <div id="page-wrapper" class="gray-bg">

        @include('admin.includes.top_navbar')

        <!-- Heading -->
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-md-6">
                <h2>Guardians</h2>
                <ol class="breadcrumb">
                    <li>Home</li>
                    <li><a href="{{ URL('guardians') }}"> Guardian </a></li>
                    <li Class="active">
                        <a>Profile</a>
                    </li>
                    <li Class="active">
                        <strong>
                            {{ $guardian->name }}
                        </strong>
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
        <div class="wrapper wrapper-content">
            <div class="guardian-card">
                <div class="card-header">
                    <div class="profile-image-container">
                        <img src="/img/avatar.jpg" alt="Guardian Photo" class="profile-image">
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="guardian-name">{{ $guardian->name }}</h4>
                    <span class="status-badge status-active">Active Guardian</span>
                    <hr class="info-divider">
                    <div class="info-container">
                        <!-- Left Column -->
                        <div class="info-column">
                            <ul class="info-list">
                                <!-- Profession -->
                                <li class="info-item">
                                    <div class="info-icon icon-education">
                                        <i class="fa fa-briefcase"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Profession</div>
                                        <div class="info-value">{{ $guardian->profession }}</div>
                                    </div>
                                </li>

                                <!-- Contact Number -->
                                <li class="info-item">
                                    <div class="info-icon icon-id">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Contact</div>
                                        <div class="info-value">{{ $guardian->phone }}</div>
                                    </div>
                                </li>

                                <!-- Monthly Income -->
                                <li class="info-item">
                                    <div class="info-icon icon-fee">
                                        <i class="fa fa-money"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Monthly Income</div>
                                        <div class="info-value fee-amount">PKR {{ $guardian->income }}</div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Right Column -->
                        <div class="info-column">
                            <ul class="info-list">
                                <!-- Email -->
                                <li class="info-item">
                                    <div class="info-icon icon-id">
                                        <i class="fa fa-envelope-o"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Email</div>
                                        <div class="info-value">{{ $guardian->email?? '-' }}</div>
                                    </div>
                                </li>

                                <!-- Address -->
                                <li class="info-item">
                                    <div class="info-icon icon-address">
                                        <i class="fa fa-address-card-o"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Address</div>
                                        <div class="info-value address-text">
                                            {{ $guardian->address?? '-' }}
                                        </div>
                                    </div>
                                </li>
                                <li class="info-item">
                                    <div class="info-icon icon-students">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">No of Students</div>
                                        <div class="info-value address-text">
                                            {{ $guardian->Student->count() }}
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Students --}}
            <div class="container text-left" style="margin: 30px 0;">
                <h2 class="students-heading">
                    <i class="fa fa-users text-primary"></i> Students
                </h2>
            </div>

            <div class="students" style="display: ruby">
                @foreach ($guardian->Student as $student)
                    <div class="student-m-2">
                        <a href="/students/profile/{{$student->id}}" class="text-decoration-none">
                            <div class="student-panel student-card">
                                <div class="student-card-header">
                                    <div class="student-profile-image-container">
                                        <img src="{{$student->image_url ? '/'.$student->image_url : '/img/avatar.jpg' }}" alt="Student Photo"
                                            class="profile-image">
                                    </div>
                                </div>
                                <div class="student-card-body">
                                    <h4 class="student-name">{{ $student->name }}</h4>
                                    <hr class="student-info-divider">
                                    <ul class="student-list-unstyled info-list">
                                        <li class="student-info-item">
                                            <div class="student-info-icon icon-education">
                                                <i class="fa fa-graduation-cap"></i>
                                            </div>
                                            <div class="student-info-content">
                                                <div class="student-info-label">Class</div>
                                                <div class="student-info-value">{{ $student->std_class->name }}</div>
                                            </div>
                                        </li>
                                        <li class="student-info-item">
                                            <div class="student-info-icon icon-id">
                                                <i class="fa fa-id-card-o"></i>
                                            </div>
                                            <div class="student-info-content">
                                                <div class="student-info-label">GR No</div>
                                                <div class="student-info-value">{{ $student->gr_no }}</div>
                                            </div>
                                        </li>
                                        <li class="student-info-item">
                                            <div class="student-info-icon student-icon-gender">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <div class="student-info-content">
                                                <div class="student-info-label">Gender</div>
                                                <div class="student-info-value">{{ $student->gender }}</div>
                                            </div>
                                        </li>
                                        <li class="student-info-item">
                                            <div class="student-info-icon student-icon-guardian">
                                                <i class="fa fa-users"></i>
                                            </div>
                                            <div class="student-info-content">
                                                <div class="student-info-label">Guardian</div>
                                                <div class="student-info-value student-info-value">{{ $guardian->name }}</div>
                                            </div>
                                        </li>
                                        <li class="student-info-item">
                                            <div class="student-info-icon icon-fee">
                                                <i class="fa fa-money"></i>
                                            </div>
                                            <div class="student-info-content">
                                                <div class="student-info-label">Monthly Fee</div>
                                                <div class="student-info-value fee-amount">PKR {{ $student->tuition_fee }}</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
