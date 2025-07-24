@php use Illuminate\Support\Str; @endphp
@extends('admin.layouts.master')

@section('title', 'Notifications |')

@section('head')
    <link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    @include('admin.includes.side_navbar')
    <div id="page-wrapper" class="gray-bg">
        @include('admin.includes.top_navbar')

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-md-6">
                <h2>Notifications</h2>
                <ol class="breadcrumb">
                    <li>Home</li>
                    <li class="active"><a>Send</a></li>
                </ol>
            </div>
            <div class="col-lg-4 col-md-6">
                @include('admin.includes.academic_session')
            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h2>Notifications Send</h2>
                        </div>
                        <div class="ibox-content">
                            <form id="tchr_rgstr" method="post" action="{{ route('notifications.send') }}"
                                class="form-horizontal">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="type">Type</label>
                                    <div class="col-sm-8">
                                        <select name="type" id="type" class="form-control" v-model="type" @change="handleTypeChange" required>
                                            <option value="">-- Select Type --</option>
                                            <option value="students">Student</option>
                                            <option value="guardians">Guardian</option>
                                            <option value="teachers">Teacher</option>
                                            <option value="employees">Employee</option>
                                        </select>
                                    </div>
                                </div>

                                <div v-if="type == 'students'" class="form-group">
                                    <label class="col-sm-2 control-label" for="type">Students</label>
                                    <div class="col-sm-8">
                                        <select name="type" id="students" class="form-control" v-model="type"  required>
                                            <option value="">-- Select --</option>
                                        </select>
                                    </div>
                                </div>
                                <div  v-if="type == 'guardians'" class="form-group">
                                    <label class="col-sm-2 control-label" for="type">Guardian</label>
                                    <div class="col-sm-8">
                                        <select name="type" id="guardians" class="form-control" v-model="type"  required>
                                            <option value="">-- Select --</option>
                                        </select>
                                    </div>
                                </div>
                                <div  v-if="type == 'teachers'" class="form-group">
                                    <label class="col-sm-2 control-label" for="type">Teachers</label>
                                    <div class="col-sm-8">
                                        <select name="type" id="teachers" class="form-control" v-model="type"  required>
                                            <option value="">-- Select --</option>
                                        </select>
                                    </div>
                                </div>

                                <div  v-if="type == 'employees'" class="form-group">
                                    <label class="col-sm-2 control-label" for="type">Employees</label>
                                    <div class="col-sm-8">
                                        <select name="type" id="employees" class="form-control" v-model="type"  required>
                                            <option value="">-- Select --</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Message</label>
                                    <div class="col-sm-8">
                                        <textarea name="message" class="form-control" rows="4" required></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-6">
                                        <button class="btn btn-primary" type="submit">
                                            <span class="glyphicon glyphicon-send"></span> Send
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

@endsection

@section('script')
    <!-- Mainly scripts -->
    <script src="{{ URL::to('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/dataTables/datatables.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/axios-1.11.0/axios.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
@endsection

@section('vue')
    <script type="text/javascript">
        var app = new Vue({
            el: "#app",
            data: {
                type: '',
            },
            methods: {
                handleTypeChange() {
                    this.getData();
                    console.log("Selected type changed to:", this.type);
                },
                getData() {
                    axios.post('/notifications/get/data', {
                        type: this.type
                    })
                    .then(response => {
                        const res = response.data;
                        console.log(res);
                    })
                    .catch(error => {
                        console.error('Failed to fetch', error);
                    });
                },
            }
        });
    </script>
@endsection
