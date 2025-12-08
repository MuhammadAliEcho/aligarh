@php use Illuminate\Support\Str; @endphp
@extends('admin.layouts.master')

@section('title', __('modules.pages_attendance_leave_title').' |')

@section('head')
    <link href="{{ asset('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
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
        .leave-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            margin-bottom: 30px;
            width: 350px;
            height: 254px;
            animation: fadeInUp 0.6s ease-out;
        }

        .leave-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15), 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .leave-card-header {
            background: rgb(255, 255, 255);
            height: 70px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
        }

        .leave-card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.15"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }

        .leave-title {
            color: #2d3436;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 2;
            position: relative;
        }

        .leave-date-badge {
            background: rgb(234 234 234);
            color: #2d3436;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            z-index: 2;
            position: relative;
            backdrop-filter: blur(10px);
        }



        .leave-card-body {
            padding: 0 25px 25px 25px;
            text-align: center;
        }

        .leave-name {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 8px;
            letter-spacing: -0.5px;
        }

        .leave-info-list {
            text-align: left;
            margin: 0;
            padding: 0;
            list-style: none;
            height: 150px;
        }

        .leave-info-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f8f9fa;
            transition: all 0.3s ease;
        }


        .leave-info-item-remark{
            display: flex;
            align-items: center;
            padding: 0 0 12px 0;
            border-bottom: 1px solid #f8f9fa;
            transition: all 0.3s ease;
            height: 110px;
        }

        .leave-info-item:last-child {
            border-bottom: none;
        }

        .leave-info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
            color: white;
            flex-shrink: 0;
        }

        .leave-info-content {
            flex: 1;
        }

        .leave-info-label {
            font-size: 12px;
            color: #74b9ff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .leave-info-value {
            font-size: 12px;
            color: #2d3436;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        /* .leave-date-range {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    color: grey;
                    font-weight: 700;
                } */

        .leave-date-range {
            flex: 1;
            text-align: center;
        }

        .leave-date-range:first-child {
            text-align: left;
        }

        .leave-date-range:last-child {
            text-align: right;
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

        .m-2 {
            margin: 3.375rem 1rem 1rem 1rem;
        }

        .font-small {
            font-size: 0.8rem;
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
    </style>
@endsection

@section('content')
    @include('admin.includes.side_navbar')

    <div id="page-wrapper" class="gray-bg">

        @include('admin.includes.top_navbar')

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-md-6">
                <h2>Attendance Leaves</h2>
                <ol class="breadcrumb">
                    <li>Home</li>
                    <li Class="active">
                        <a>Attendance Leaves</a>
                    </li>
                </ol>
            </div>
            <div class="col-lg-4 col-md-6">
                @include('admin.includes.academic_session')
            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row ">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class="">
                                <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Attendance Leave</a>
                            </li>
                            @can('attendance-leave.make')
                                <li class="add-role">
                                    <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Make Attendance
                                        Leave</a>
                                </li>
                            @endcan
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade">
                                <div class="panel-body">
                                    <div class="row" id="app">
                                        <div class="col-md-4">
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
                                                <input type="text" v-model="search_attendance_leaves"
                                                    @input="triggerSearch" class="form-control input-sm"
                                                    style="width: 200px; display: inline-block; margin-right: 35px;"
                                                    title="Search" placeholder="Search...">
                                            </div>
                                        </div>
                                    </div>
                                    <div style="display: ruby">
                                        <div class="m-2" v-for="attendance_leave in attendance_leaves"
                                            :key="attendance_leave.id">
                                            <div class="leave-panel leave-card">
                                                <div class="leave-card-header">
                                                    <div class="leave-title">
                                                        <span class="font-small">@{{ attendance_leave.person_type }} <br></span>
                                                        @{{ attendance_leave.person.name }}
                                                    </div>
                                                    <div class="leave-date-badge">@{{ attendance_leave.date }}</div>
                                                </div>
                                                <div class="leave-card-body">
                                                    <ul class="leave-info-list">
                                                        <li class="leave-info-item-remark">
                                                            <div class="leave-info-content">
                                                                <div class="leave-info-label">Reason</div>
                                                                <div class="leave-info-value">@{{ attendance_leave.remarks }}</div>
                                                            </div>
                                                        </li>
                                                        <li class="leave-info-item">
                                                            <div class="leave-info-content">
                                                                <div class="leave-info-value">
                                                                    <div class="leave-date-range">
                                                                        @{{ attendance_leave.from_date }}
                                                                    </div>
                                                                    <div class="leave-date-range">
                                                                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                                                    </div>
                                                                    <div class="leave-date-range">
                                                                        @{{ attendance_leave.to_date }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <div class="text-end mt-3">
                                                        @can('attendance-leave.update')
                                                            <a :href="'{{ url('attendance-leave/edit') }}/' + attendance_leave.id"
                                                                class="btn btn-sm btn-outline-primary">
                                                                <i class="fa fa-pencil"></i> Edit
                                                            </a>
                                                        @endcan
                                                        @can('attendance-leave.delete')
                                                            <a data-placement="top" data-toggle="tooltip" title="Delete"
                                                                @click.prevent="deleteAttendanceLeave(attendance_leave.id)"
                                                                href="#" class="btn btn-sm btn-outline-danger">
                                                                <i class="fa fa-trash"></i> Delete
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pagination" id="app">
                                        <nav class="text-center">
                                            <ul class="pagination">
                                                <li v-for="(link, index) in pagination_links" :key="index"
                                                    :class="['page-item', { active: link.active, disabled: !link.url }]">
                                                    <a class="page-link" href="#" @click.prevent="goToPage(link)"
                                                        v-html="link.label"></a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                            @can('attendance-leave.make')
                                <div id="tab-11" class="tab-pane fade make-attendance">
                                    <div class="panel-body" style="min-height: 400px">
                                        <h2> {{ __('modules.forms_make_attendance') }} </h2>
                                        <div class="hr-line-dashed"></div>
                                        <form method="post" id="mk_att_frm" action="{{ route('attendance-leave.make') }}"
                                            class="form-horizontal jumbotron" role="form">
                                            @csrf
                                            <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label"> Type </label>
                                                <div class="col-md-6">
                                                    <select class="form-control" v-model="type" name="type"
                                                        required="true">
                                                        <option value="">{{ '--- Select Type ---' }}</option>
                                                        <option value="{{ 'Student' }}">{{ 'Student' }}</option>
                                                        <option value="{{ 'Teacher' }}">{{ 'Teacher' }}</option>
                                                        <option value="{{ 'Employee' }}">{{ 'Employee' }}</option>
                                                    </select>
                                                    @if ($errors->has('class'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('class') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div v-show="type == 'Student'"
                                                class="form-group{{ $errors->has('person_id') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label"> Student </label>
                                                <div class="col-md-6">
                                                    <select class="form-control select2" name="person_id"
                                                        :required="type === 'Student'">
                                                        <option value="" disabled selected>-- Select Student --</option>
                                                        @foreach ($classStudents as $classStudent)
                                                            <optgroup label="{{ $classStudent['class_name'] }}">
                                                                @foreach ($classStudent['students'] as $student)
                                                                    <option value="{{ $student['id'] }}">
                                                                        {{ $student['name'] }} | {{ $student['gr_no'] }}
                                                                    </option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('person_id'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('person_id') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>


                                            <div v-if="type == 'Teacher'"
                                                class="form-group{{ $errors->has('person_id') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label"> Teacher </label>
                                                <div class="col-md-6">
                                                    <select class="form-control select2" name="person_id"
                                                        :required="type === 'Teacher'">
                                                        <option value="" disabled selected>--Select--</option>
                                                        @foreach ($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('person_id'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('person_id') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div v-if="type == 'Employee'"
                                                class="form-group{{ $errors->has('person_id') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label"> Employee </label>
                                                <div class="col-md-6">
                                                    <select class="form-control select2" name="person_id"
                                                        :required="type === 'Employee'">
                                                        <option value="" disabled selected>--Select--</option>
                                                        @foreach ($employees as $employee)
                                                            <option value="{{ $employee->id }}">{{ $employee->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('person_id'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('person_id') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>


                                            <div class="form-group{{ $errors->has('from_date') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">From Date </label>
                                                <div class="col-md-6">
                                                    <input id="from_datetimepicker" type="text" name="from_date"
                                                        class="form-control" placeholder="From Date"
                                                        value="{{ old('from_date') }}" required="true" autocomplete="off">
                                                    @if ($errors->has('from_date'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('from_date') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group{{ $errors->has('to_date') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label"> To Date </label>
                                                <div class="col-md-6">
                                                    <input id="to_datetimepicker" type="text" name="to_date"
                                                        class="form-control" placeholder="To Date"
                                                        value="{{ old('to_date') }}" required="true" autocomplete="off">
                                                    @if ($errors->has('to_date'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('to_date') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label"> Remarks </label>
                                                <div class="col-md-6">
                                                    <textarea name="remarks" required class="form-control" rows="4" ref="messageBox">{{ old('remarks') }}</textarea>
                                                    @if ($errors->has('remarks'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('remarks') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-offset-2 col-md-6">
                                                    <button class="btn btn-primary" type="submit">
                                                        <span class="glyphicon glyphicon-save"></span>
                                                        Make Attendance Leave
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

    <!-- Mainly scripts -->
    <script src="{{ asset('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>
    <script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    	<!-- Select2 -->
	<script src="{{ asset('src/js/plugins/select2/select2.full.min.js') }}"></script>
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}", "Validation Error");
            @endforeach
        </script>
    @endif

    <script type="text/javascript">
        $(document).ready(function() {

            $('#to_datetimepicker, #from_datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD',
                defaultDate: moment()
            });

            $("#mk_att_frm").validate({
                ignore: ":not(:visible)",
                rules: {
                    type: {
                        required: true
                    },
                    to_date: {
                        required: true
                    },
                    from_date: {
                        required: true
                    }
                }
            });

            @if ($errors->any())
                $('a[href="#tab-11"]').tab('show');
            @else
                $('a[href="#tab-10"]').tab('show');
            @endif

            $('.select2').attr('style', 'width:100%').select2({
    			placeholder: 'Search contacts',
            });
        });
    </script>
@endsection
@section('vue')
<script src="{{ asset('src/js/plugins/axios-1.11.0/axios.min.js') }}"></script>
<script src="{{ asset('src/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('src/js/plugins/lodash-4.17.15/min.js') }}"></script>

<script>
    new Vue({
        el: '#app',
        data: {
            type: '',
            options: [5, 10, 25, 50, 100],
            search_attendance_leaves: '',
            per_page: 10,
            page: 1,
            attendance_leaves: [],
            current_page: 1,
            last_page: 1,
            to: 0,
            from: 0,
            total: 0,
            pagination_links: []
        },
        created() {
            this.debouncedSearch = _.debounce(() => {
                this.handleLayoutChange(this.page);
            }, 300);
        },
        methods: {
            triggerSearch(page = 1) {
                this.page = page;
                this.debouncedSearch(); 
            },

            handleLayoutChange(page = 1) {
                this.page = page;

                axios.get('/attendance-leave/getData', {
                    params: {
                        per_page: this.per_page,
                        page: page,
                        search_attendance_leaves: this.search_attendance_leaves,
                    }
                })
                .then(response => {
                    const res = response.data;
                    this.attendance_leaves = res.data;
                    this.current_page = res.current_page;
                    this.last_page = res.last_page;
                    this.to = res.to;
                    this.from = res.from;
                    this.total = res.total;
                    this.pagination_links = res.links;
                })
                .catch(error => {
                    console.error('Failed to fetch records:', error);
                });
            },

            goToPage(link) {
                if (!link.url) return;
                const url = new URL(link.url);
                const page = url.searchParams.get('page');
                this.handleLayoutChange(page);
            },
            deleteAttendanceLeave(deleteId) {
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

                        axios.post("{{ url('attendance-leave/delete') }}", {
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
            }
            },

            computed: {},
            mounted: function() {
                this.handleLayoutChange();
        }
    });
</script>
@endsection
