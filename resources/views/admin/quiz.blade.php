@php use Illuminate\Support\Str; @endphp
@extends('admin.layouts.master')

@section('title', 'Quizzes |')

@section('head')
    <link href="{{ URL::to('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('src/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
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

        .print-table>tbody>tr>td {
            padding: 1px;
        }

        .print-table>thead>tr>th {
            padding: 3px;
        }
    </style>
    <style>
        .quiz-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            margin-bottom: 30px;
            width: 350px;
            height: 245px;
            animation: fadeInUp 0.6s ease-out;
        }

        .quiz-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15), 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .quiz-card-header {
            background: rgb(255, 255, 255);
            height: 70px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
        }

        .quiz-card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.15"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }

        .quiz-title {
            color: #2d3436;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 2;
            position: relative;
        }

        .quiz-date-badge {
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



        .quiz-card-body {
            padding: 0 25px 25px 25px;
            text-align: center;
        }

        .quiz-name {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 8px;
            letter-spacing: -0.5px;
        }

        .quiz-info-list {
            text-align: left;
            margin: 0;
            padding: 0;
            list-style: none;
            height: 140px;
        }

        .quiz-info-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f8f9fa;
            transition: all 0.3s ease;
        }


        .quiz-info-item-remark {
            display: flex;
            align-items: center;
            padding: 0 0 12px 0;
            border-bottom: 1px solid #f8f9fa;
            transition: all 0.3s ease;
            overflow: auto;
            text-wrap: auto;
        }

        .quiz-info-item:last-child {
            border-bottom: none;
        }

        .quiz-info-icon {
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

        .quiz-info-content {
            flex: 1;
            height: 15px;
            /* height: -webkit-fill-available; */
        }

        .quiz-info-label {
            font-size: 12px;
            color: #74b9ff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .quiz-info-value {
            font-size: 12px;
            color: #2d3436;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .quiz-date-range:first-child {
            text-align: left;
        }

        .quiz-date-range:last-child {
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

        .marks-badge {
            background-color: #5cb85c;
            color: white;
            margin-left: 8px;
            font-size: 8px;
            font-weight: 800;
        }
    </style>

    {{-- quiz-result-model --}}
    <style>
        .quiz-result-model-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: quiz-result-model-fadeInUp 0.5s ease-out;
        }

        .quiz-result-model-card-header {
            background: linear-gradient(135deg, #009486 0%, #1ab394 100%);
            display: flex;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            position: relative;
        }

        .quiz-result-model-title {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            text-transform: uppercase;
        }

        .quiz-result-model-card-body {
            padding: 20px;
        }

        .quiz-result-model-name {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }

        .quiz-result-model-status-select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .quiz-result-model-info-divider {
            border: none;
            height: 2px;
            background: #667eea;
            margin-bottom: 15px;
        }

        .quiz-result-model-form-group {
            margin-bottom: 15px;
            flex: 1;
        }

        .quiz-result-model-submit-btn {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, #009486 0%, #1ab394 100%);
            color: #fff;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
        }

        .quiz-result-model-submit-btn:hover {
            opacity: 0.9;
        }

        @keyframes quiz-result-model-fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* override */
        .modal-dialog {
            width: 180vh !important;
            margin: 30px auto;
            height: 100vh !important;
        }

        .modal-body {
            max-height: 100vh !important;
        }

        .modal-open .modal {
            overflow-x: hidden !important;
            overflow-y: hidden !important;
            height: 97% !important;
        }

        /* Responsive inside modal */
        @media (max-width: 768px) {
            .quiz-result-model-card {
                font-size: 14px;
            }
        }

        .scrollable-table-wrapper thead th {
            position: sticky;
            top: 0;
            background: #f9f9f9;
            z-index: 1;
        }

        .scrollable-table-wrapper {
            max-height: 58vh;
            overflow-y: auto;
            overflow-x: auto;
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
                    <li>Home</li>
                    <li Class="active">
                        <a>Quiz</a>
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
                                <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Quizzes</a>
                            </li>
                            @can('quizzes.create')
                                <li class="add-role">
                                    <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Make Quiz</a>
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
                                                <input type="text" v-model="search_quiz" @input="triggerSearch"
                                                    class="form-control input-sm"
                                                    style="width: 200px; display: inline-block; margin-right: 35px;"
                                                    title="Search" placeholder="Search...">
                                            </div>
                                        </div>
                                    </div>
                                    <div style="display: ruby">
                                        <div class="m-2" v-for="quiz in quizzes" :key="quiz.id">
                                            <div class="quiz-panel quiz-card">
                                                <div class="quiz-card-header">
                                                    <div class="quiz-title">
                                                        <span class="font-small">@{{ quiz.teacher?.name || '-' }} <br></span>
                                                        @{{ quiz.class.name + ' - ' + (quiz.section?.name || '') }}
                                                    </div>
                                                    <div class="quiz-date-badge">@{{ quiz.date }}</div>
                                                </div>
                                                <div class="quiz-card-body">
                                                    <ul class="quiz-info-list">
                                                        <li class="quiz-info-item-title">
                                                            <div class="quiz-info-label">Title <span
                                                                    class="badge marks-badge">@{{ quiz.total_marks }}</span>
                                                            </div>
                                                        </li>
                                                        <li class="quiz-info-item-remark">
                                                            <div class="quiz-info-value">@{{ quiz.title }}</div>
                                                        </li>
                                                    </ul>
                                                    <div class="text-end mt-3">
                                                        @can('quizzes.update')
                                                            <a :href="'{{ url('quizzes/edit') }}/' + quiz.id"
                                                                class="btn btn-sm btn-outline-primary">
                                                                <i class="fa fa-pencil"></i> Edit
                                                            </a>
                                                        @endcan
                                                        @can('quizzes.delete')
                                                            <a data-placement="top" data-toggle="tooltip" title="Delete"
                                                                @click.prevent="deleteQuiz(quiz.id)" href="#"
                                                                class="btn btn-sm btn-outline-danger">
                                                                <i class="fa fa-trash"></i> Delete
                                                            </a>
                                                        @endcan
                                                        @can('quizresult.index')
                                                            <a data-placement="top" data-toggle="tooltip" title="Add Result"
                                                                @click.prevent="getQuiz(quiz.id)" href="#"
                                                                class="btn btn-sm btn-outline-danger">
                                                                <i class="fa fa-trophy"></i> Add Result
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

                            @can('quizzes.create')
                                <div id="tab-11" class="tab-pane fade make-quiz">
                                    <div class="panel-body" style="min-height: 400px">
                                        <h2> Create Quiz</h2>
                                        <div class="hr-line-dashed"></div>
                                        <form method="post" id="create_quiz" action="{{ route('quizzes.create') }}"
                                            class="form-horizontal jumbotron" role="form">
                                            @csrf

                                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label"> Title </label>
                                                <div class="col-md-6">
                                                    <textarea name="title" required class="form-control" rows="2">{{ old('title') }}</textarea>
                                                    @if ($errors->has('title'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('title') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('teacher') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Teacher</label>
                                                <div class="col-md-6">
                                                    <select class="form-control select2" name="teacher">
                                                        <option value="">{{ '--- Select ---' }}</option>
                                                        @foreach ($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}"
                                                                {{ old('teacher') == $teacher->id ? 'selected' : '' }}>
                                                                {{ $teacher->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('teacher'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('teacher') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('class') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Class</label>
                                                <div class="col-md-6">
                                                    <select class="form-control select2" name="class" required="true">
                                                        <option value="">{{ '--- Select ---' }}</option>
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
                                            <div class="form-group{{ $errors->has('section') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label"> Section </label>
                                                <div class="col-md-6">
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
                                                </div>
                                            </div>


                                            <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Date </label>
                                                <div class="col-md-6">
                                                    <input id="datetimepicker" type="text" name="date"
                                                        class="form-control" placeholder="Date" value="{{ old('date') }}"
                                                        required="true" autocomplete="off">
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
                                                    <input type="number" name="total_marks" class="form-control"
                                                        placeholder="Total Marks" value="{{ old('total_marks') }}"
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
                                                        Create Quiz
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endcan
                            @can('quizresult.index')
                                <!-- Modal -->
                                <div class="modal fade" id="quizResultModal" tabindex="-1" role="dialog"
                                    aria-labelledby="quizResultModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="quizResultModalLabel">Add Result</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="quiz-result-model-card">
                                                    <div class="quiz-result-model-card-header">
                                                        <div class="quiz-result-model-title">Quiz Result</div>
                                                    </div>
                                                    <div class="quiz-result-model-card-body">
                                                        <h3 class="quiz-result-model-name">Students Information</h3>
                                                        <hr class="quiz-result-model-info-divider">
                                                        <div class="scrollable-table-wrapper">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Name</th>
                                                                        <th>GR Number</th>
                                                                        <th>Obtained Marks</th>
                                                                        <th>Present</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="studentResultsTable">
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @can('quizresult.create')
                                                            <div class="quiz-result-model-form-group" style="margin-top: 15px;">
                                                                <button type="button" :disabled="isSubmitting"
                                                                    id="quizResultModelSubmit" @click="createResult()"
                                                                    class="quiz-result-model-submit-btn">Add Result</button>
                                                            </div>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
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

    <!-- Mainly scripts -->
    <script src="{{ URL::to('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}", "Validation Error");
            @endforeach
        </script>
    @endif

    <script type="text/javascript">
        $(document).ready(function() {

            $('#datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD',
                defaultDate: moment()
            });

            $('[name="class"]').on('change', function() {
                clsid = $(this).val();
                $('[name="class"]').val(clsid);
                $('[name="section"]').html('<option></option>');
                if (sections['class_' + clsid].length > 0 && clsid > 0) {
                    $.each(sections['class_' + clsid], function(k, v) {
                        $('[name="section"]').append('<option value="' + v['id'] + '">' + v[
                            'name'] + '</option>');
                    });
                }
            });

            $("#create_quiz").validate({
                ignore: ":not(:visible)",
                rules: {
                    class: {
                        required: true
                    },
                    date: {
                        required: true
                    },
                    title: {
                        required: true
                    },
                    total_marks: {
                        required: true,
                        number: true
                    }
                }
            });

            @if ($errors->any())
                $('a[href="#tab-11"]').tab('show');
            @else
                $('a[href="#tab-10"]').tab('show');
            @endif
        });
    </script>
@endsection
@section('vue')
    <script src="{{ URL::to('src/js/plugins/axios-1.11.0/axios.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/lodash-4.17.15/min.js') }}"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                options: [5, 10, 25, 50, 100],
                search_quiz: '',
                per_page: 5,
                page: 1,
                quizzes: [],
                current_page: 1,
                last_page: 1,
                to: 0,
                from: 0,
                total: 0,
                pagination_links: [],
                isSubmitting: false
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

                    axios.get('/quizzes/getData', {
                            params: {
                                per_page: this.per_page,
                                page: page,
                                search_quiz: this.search_quiz,
                            }
                        })
                        .then(response => {
                            const res = response.data;
                            this.quizzes = res.data;
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
                deleteQuiz(deleteId) {
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

                            axios.post("{{ url('quizzes/delete') }}", {
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
                getQuiz(quizId) {
                    $('#quizResultModal').modal('show');
                    axios.get(`/quizresult/${quizId}`)
                        .then(response => {
                            const students = response.data;
                            const tableBody = $('#studentResultsTable');
                            tableBody.empty();
                            const quizIdMarkInput =
                                `<input type="hidden" name="quiz_id" id="quiz_id" value="${quizId}">`;
                            tableBody.append(quizIdMarkInput);

                            students.forEach(student => {
                                const row = `
                                <tr>
                                    <td>${student.name}</td>
                                    <td>${student.gr_no}</td>
                                    <td><input type="number" class="form-control" name="marks[${student.id}]" value="" id="marks-${student.id}" /></td>
                                    <td><input type="checkbox" class="form-check-input" name="present[${student.id}]" id="present-${student.id}" /></td>
                                </tr>
                            `;
                                tableBody.append(row);
                            });
                        })
                        .catch(error => {
                            console.error('Failed to fetch records:', error);
                        });
                },
                createResult() {
                    this.isSubmitting = true;
                    const rows = $('#studentResultsTable tr');
                    const results = [];
                    rows.each(function() {
                        const studentId = $(this).find('input[type=number]').attr('id')?.split('-')[1];
                        if (!studentId) return;

                        const marks = $(this).find('input[type=number]').val();
                        const present = $(this).find('input[type=checkbox]').is(':checked') ? 1 : 0;

                        results.push({
                            student_id: studentId,
                            obtain_marks: marks,
                            present: present
                        });
                    });

                    axios.post('/quizresult/create', {
                            quiz_id: $('#quiz_id').val(),
                            results: results
                        })
                        .then(response => {
                            toastr.success("Results added successfully");
                            $('#quizResultModal').modal('hide');
                        })
                        .catch(error => {
                            this.isSubmitting = false;
                            if (error.response && error.response.status === 422) {
                                const errors = error.response.data.errors;
                                Object.keys(errors).forEach(field => {
                                    errors[field].forEach(msg => {
                                        toastr.error(msg, "Validation Error");
                                    });
                                });
                            } else {
                                toastr.error("Something went wrong. Please try again.");
                                console.error('Error:', error);
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
