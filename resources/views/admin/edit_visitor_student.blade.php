@extends('admin.layouts.master')
@section('title', 'Visitors |')
@section('head')
    <link href="{{ asset('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
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
            <div class="col-lg-4 col-md-6">
                @include('admin.includes.academic_session')
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h2>Edit Visitors</h2>
                            <div class="hr-line-dashed"></div>
                        </div>

                        <div class="ibox-content">
                            <form id="tchr_rgstr" method="post" action="{{ URL('visitors/update/' . $visitorStudents->id) }}"
                                class="form-horizontal" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label class="col-md-2 control-label">Name</label>
                                    <div class="col-md-6">
                                        <input type="text" name="name" placeholder="Name"
                                            value="{{ old('name', $visitorStudents->name) }}" class="form-control" />
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
                                            value="{{ old('father_name', $visitorStudents->father_name) }}"
                                            class="form-control" />
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
                                        <input type="text" id="date_of_birth" name="date_of_birth" placeholder="DOB"
                                            value="{{ old('date_of_birth', $visitorStudents->date_of_birth) }}"
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
                                            value="{{ old('place_of_birth', $visitorStudents->place_of_birth) }}"
                                            class="form-control" />
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
                                            value="{{ old('religion', $visitorStudents->religion) }}"
                                            class="form-control" />
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
                                        <input type="text" name="last_school" placeholder="Last School Attendent"
                                            value="{{ old('last_school', $visitorStudents->last_school) }}"
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
                                            value="{{ old('seeking_class', $visitorStudents->seeking_class) }}"
                                            class="form-control" />
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
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
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

                                <div class="form-group{{ $errors->has('guardian_relation') ? ' has-error' : '' }}">
                                    <label class="col-md-2 control-label">Guardian Relation</label>
                                    <div class="col-md-6">
                                        <input type="text" name="guardian_relation" placeholder="guardian Relation"
                                            value="{{ old('guardian_relation', $visitorStudents->guardian_relation) }}"
                                            class="form-control" />
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
                                        <textarea type="text" name="address" placeholder="Address" class="form-control">{{ old('address', $visitorStudents->address) }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                    <label class="col-md-2 control-label">Contact No</label>
                                    <div class="col-md-6">
                                        <div class="input-group m-b">
                                            <span class="input-group-addon">+92</span>
                                            <input type="text" name="phone"
                                                value="{{ old('phone', $visitorStudents->phone) }}"
                                                placeholder="Contact No" class="form-control" data-mask="9999999999" />
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
                                        <input type="email" name="email" placeholder="guardian Relation"
                                            value="{{ old('email', $visitorStudents->email) }}" class="form-control" />
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong><span class="fa fa-exclamation-triangle"></span>
                                                    {{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-6">
                                        <button class="btn btn-primary" type="submit"><span
                                                class="glyphicon glyphicon-save"></span> Update </button>
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
    <!-- Mainly scripts
      {{ asset('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>
      -->
    <script src="{{ asset('src/js/plugins/dataTables/datatables.min.js') }}"></script>
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
        var tr;
        no = 1;

        $(document).ready(function() {

            $('[data-toggle="tooltip"]').tooltip();
            $('#date_of_birth').datetimepicker({
                format: 'YYYY-MM-DD',
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
                    religion: {
                        required: true,
                    },
                    guardian_relation: {
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
                    // date_of_visiting: {
                    //     required: true,
                    // },
                    phone: {
                        required: true,
                    }
                },
            });


            $('#tchr_rgstr [name="gender"]').val('{{ old('gender', $visitorStudents->gender) }}');
            $('#tchr_rgstr [name="class"]').val("{{ old('class', $visitorStudents->class_id) }}");

        });
    </script>
@endsection
