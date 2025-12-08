@php use Illuminate\Support\Str; @endphp
@extends('admin.layouts.master')

  @section('title', __('modules.pages_edit_quiz_title').' |')@section('head')
    <link href="{{ asset('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
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
@endsection

@section('content')
    @include('admin.includes.side_navbar')

    <div id="page-wrapper" class="gray-bg">

        @include('admin.includes.top_navbar')

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-md-6">
                <h2>Quizzes</h2>
                <ol class="breadcrumb">
                    <li>{{ __("common.home") }}</li>
                    <li Class="active">
                        <a>Edit</a>
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
                            <h2>Edit Quizzes</h2>
                            <div class="hr-line-dashed"></div>
                        </div>
                        <div class="ibox-content">
                            <form method="post" id="quiz_update" action="{{ route('quizzes.update', $quiz->id) }}"
                                class="form-horizontal jumbotron" role="form">
                                @csrf

                                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                    <label class="col-md-2 control-label"> Title </label>
                                    <div class="col-md-6">
                                        <textarea name="title" required class="form-control" rows="2">{{ old('title', $quiz->title) }}</textarea>
                                        @if ($errors->has('title'))
                                            <span class="help-block">
                                                <strong><span class="fa fa-exclamation-triangle"></span>
                                                    {{ $errors->first('title') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label"> Teacher </label>
                                    <div class="col-md-6">
                                        <select class="form-control select2" name="teacher">
                                            <option value="">{{ '--- Select ---' }}</option>
                                            @foreach ($teachers as $teacher)
                                                <option {{ old('section', $quiz->teacher_id) == $teacher->id ? 'selected' : '' }} value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-md-2 control-label"> Class </label>
                                    <div class="col-md-6">
                                        @if ($quiz->quizResults->count() == 0)
                                            <select class="form-control select2" name="class" required="true">
                                                <option value="">{{ '--- Select ---' }}</option>
                                                @foreach ($classes as $class)
                                                    <option {{ $quiz->class_id == $class->id ? 'selected' : '' }} value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('class'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span>
                                                        {{ $errors->first('class') }}</strong>
                                                </span>
                                            @endif
                                        @endif
                                        @if($quiz->quizResults->count() > 0)
                                            <input type="text" disabled class="form-control" value="{{ $quiz->class->name }}">
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('section') ? ' has-error' : '' }}">
                                    <label class="col-md-2 control-label"> Section </label>
                                    <div class="col-md-6">
                                        @if ($quiz->quizResults->count() == 0)
                                            <select class="form-control select2" name="section">
                                                <option value="" disabled selected>{{ '--- Select ---' }}
                                                </option>
                                            </select>
                                            @if ($errors->has('section'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span>
                                                        {{ $errors->first('section') }}</strong>
                                                </span>
                                            @endif
                                        @endif
                                        @if($quiz->quizResults->count() > 0)
                                            <input type="text" disabled class="form-control" value="{{ $quiz->section->name?? '' }}">
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
                                    <label class="col-md-2 control-label">{{ __('labels.date') }} </label>
                                    <div class="col-md-6">
                                        <input id="datetimepicker" type="text" name="date" class="form-control"
                                            placeholder="From Date"
                                            value="{{ old('date', $quiz->date) }}" required="true"
                                            autocomplete="off">
                                        @if ($errors->has('date'))
                                            <span class="help-block">
                                                <strong><span class="fa fa-exclamation-triangle"></span>
                                                    {{ $errors->first('date') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('total_marks') ? ' has-error' : '' }}">
                                    <label class="col-md-2 control-label">Total Marks </label>
                                    <div class="col-md-6">
                                        <input  type="number" name="total_marks"
                                            class="form-control" placeholder="Total Marks" value="{{ old('total_marks',$quiz->total_marks) }}"
                                            required="true" autocomplete="off">
                                        @if ($errors->has('total_marks'))
                                            <span class="help-block">
                                                <strong><span class="fa fa-exclamation-triangle"></span>
                                                    {{ $errors->first('total_marks') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-6">
                                        <button class="btn btn-primary" type="submit">
                                            <span class="glyphicon glyphicon-save"></span>
                                            Update Quiz
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
    <script src="{{ asset('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>
    <script src="{{ asset('src/js/plugins/dataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}", "Validation Error");
            @endforeach
        </script>
    @endif

    <script type="text/javascript">

        var sections = {!! json_encode($sections ?? []) !!};
        var quiz = {!! json_encode($quiz ?? []) !!};

        function populateSections(clsid) {
            $('[name="section"]').html('<option value="" selected>--- Select ---</option>');
            if (sections['class_' + clsid] && sections['class_' + clsid].length > 0) {
                $.each(sections['class_' + clsid], function(k, v) {
                    var selected = (quiz['section_id'] == v['id']) ? 'selected' : '';
                    $('[name="section"]').append(
                        '<option ' + selected + ' value="' + v['id'] + '">' + v['name'] + '</option>'
                    );
                });
            }
        }

        $(document).ready(function() {
            $('#datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD',
                defaultDate: moment()
            });

            $("#quiz_update").validate({
                ignore: ":not(:visible)",
                rules: {
                    date: {
                        required: true,
                    },
                    to_date: {
                        required: true,
                    }
                }
            });

            $('[name="class"]').on('change', function() {
                var clsid = $(this).val();
                populateSections(clsid);
            });
            // On page load, if quiz has class_id and section_id, prepopulate
            if (quiz['class_id']) {
                $('[name="class"]').val(quiz['class_id']).trigger('change');
                populateSections(quiz['class_id']);
            }
        });
    </script>
@endsection
