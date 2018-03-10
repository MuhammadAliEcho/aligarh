@extends('layouts.master')

  @section('title', 'Student Restults Manage |')

  @section('head')
    <link href="{{ URL::to('src/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">

  @endsection

  @section('content')

  @include('includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Students Result</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li Class="active">
                          <a>Students Result</a>
                      </li>
                  </ol>
              </div>
              <div class="col-lg-4 col-md-6">
                @include('includes.academic_session')
              </div>
          </div>

          <!-- main Section -->

          <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row ">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class="make-result">
                              <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Make Result </a>
                            </li>
                            <li class="get-result">
                              <a data-toggle="tab" href="#tab-11"><span class="fa fa-bar-chart"></span> Result Reports</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade make-result">
                                <div class="panel-body" style="min-height: 400px">
                                  <h2> Make Result </h2>
                                  <div class="hr-line-dashed"></div>

                                    <form id="mk_result_frm" method="GET" action="{{ URL('manage-result/make') }}" class="form-horizontal jumbotron" role="form" >

                                      <div class="form-group{{ ($errors->has('exam'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label"> Exam </label>
                                        <div class="col-md-6">
                                          <select class="form-control select2" name="exam" required="true">
                                            <option value="" disabled selected>Exam</option>
                                            @foreach($exams AS $exam)
                                              <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                            @endforeach
                                          </select>
                                          @if ($errors->has('exam'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('exam') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('class'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label"> Class </label>
                                        <div class="col-md-6">
                                          <select class="form-control select2" name="class" required="true">
                                            <option value="" disabled selected>Class</option>
                                            @foreach($classes AS $class)
                                              <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                          </select>
                                          @if ($errors->has('class'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('class') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('subject'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label"> Subject </label>
                                        <div class="col-md-6">
                                          <select class="form-control select2" name="subject" required="true">
                                          <option value="" disabled selected>Subject</option>
                                          </select>
                                          @if ($errors->has('subject'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('subject') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group">
                                          <div class="col-md-offset-2 col-md-6">
                                              <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Make Result </button>
                                          </div>
                                      </div>

                                    </form>

                                    @if($root['job'] == 'make')
                                    <div class="row">
                                      <h3>Exam: {{ $selected_exam->name }}, Class: {{ $selected_class->name }} ({{ $selected_subject->name }})</h3>
                                      <div class="hr-line-dashed"></div>

                                      <form action="{{ URL('manage-result/make') }}" class="form-horizontal" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="exam" value="{{ $selected_exam->id }}">
                                        <input type="hidden" name="subject" value="{{ $selected_subject->id }}">

                                        <div class="form-group{{ ($errors->has('total_marks'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label"> Total marks </label>
                                          <div class="col-md-6">
                                            <input type="number" id="total_marks" class="form-control" name="total_marks" value="{{ old('total_marks') }}" required="true">
                                            @if ($errors->has('total_marks'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('total_marks') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <table class="table table-striped table-bordered table-hover">
                                          <thead>
                                            <tr>
                                              <th>GR No</th>
                                              <th>Name</th>
                                              <th>Obtain Marks</th>
                                              <th>Remarks</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                          @foreach($students as $student)
                                            <tr>
                                              <td>{{ $student->gr_no }}</td>
                                              <td>{{ $student->name }}</td>
                                              <td>
                                                <input type="text" class="form-control obtain_marks" name="student[{{ $student->id }}][obtain_marks]" value="{{ isset($result[$student->id])? $result[$student->id]->obtain_marks : 0 }}">
                                              </td>
                                              <td>
                                                <input type="text" class="form-control" name="student[{{ $student->id }}][remarks]" value="{{ isset($result[$student->id])? $result[$student->id]->remarks : '' }}">
                                              </td>
                                            </tr>
                                          @endforeach
                                          </tbody>
                                        </table>

                                        <div class="form-group">
                                            <div class="col-md-offset-4 col-md-4">
                                                <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Make Result </button>
                                            </div>
                                        </div>

                                      </form>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div id="tab-11" class="tab-pane fade get-result">
                                <div class="panel-body" style="min-height: 400px">
                                  <h2> Search Fields </h2>
                                  <div class="hr-line-dashed"></div>

                                    <form id="rpt_result_frm" method="GET" action="{{ URL('manage-result/report') }}" class="form-horizontal jumbotron" role="form" >

                                      <div class="form-group{{ ($errors->has('exam'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label"> Exam </label>
                                        <div class="col-md-6">
                                          <select class="form-control select2" name="exam" required="true">
                                            <option value="" disabled selected>Exam</option>
                                            @foreach($exams AS $exam)
                                              <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                            @endforeach
                                          </select>
                                          @if ($errors->has('exam'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('exam') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('class'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label"> Class </label>
                                        <div class="col-md-6">
                                          <select class="form-control select2" name="class" required="true">
                                            <option value="" disabled selected>Class</option>
                                            @foreach($classes AS $class)
                                              <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                          </select>
                                          @if ($errors->has('class'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('class') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group">
                                          <div class="col-md-offset-2 col-md-4">
                                              <button class="btn btn-primary btn-block" type="submit"><span class="fa fa-list"></span> Show Result </button>
                                              <a href="#" id="result_report_print" ><span class="glyphicon glyphicon-print"></span> Print</a>
                                          </div>
                                      </div>

                                    </form>

                                    @if($root['job'] == 'report')
                                    <div class="row">
                                    <div id="result_report">
                                      <h3>Exam: {{ $selected_exam->name }}, Class: {{ $selected_class->name }}</h3>
                                      <div class="hr-line-dashed"></div>
                                        <div class="table-responsive">
                                          <table id="rpt-result" class="table table-striped table-bordered table-hover">
                                            <thead>
                                              <tr>
                                                <th style="text-align: center;">
                                                  Students <i class="entypo-down-thin"></i> | Sub <i class="entypo-right-thin"></i>
                                                </th>
                                                @foreach($subjects['class_'.$input['class']] AS $subject)
                                                  <th>{{ $subject->name }}</th>
                                                @endforeach
                                                  <th>Total | %</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              @foreach($students AS $student)
                                              <tr>
                                                <td>{{ $student->name }}</td>
                                                @foreach($subjects['class_'.$input['class']] AS $subject)
                                                  <td class="std_{{ $student->id }}_sub_{{ $subject->id }}"></td>
                                                @endforeach
                                                @if($result[$student->id]->sum('total_marks') !== 0)
                                                  <td>
                                                    {{ $result[$student->id]->sum('obtain_marks') }}
                                                  | {{ number_format(($result[$student->id]->sum('obtain_marks')/$result[$student->id]->sum('total_marks') * 100), 2, '.', ',') }} %
                                                  </td>
                                                @endif
                                              </tr>
                                              @endforeach
                                            </tbody>
                                          </table>
                                        </div>
                                    </div>
                                    </div>
                                    @endif

                                  </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

          </div>


          @include('includes.footercopyright')


        </div>

    @endsection

    @section('script')

    <script src="{{ URL::to('src/js/jquery.print.js') }}"></script>

    <script type="text/javascript">
      $('#result_report_print').click(function(e){
        e.preventDefault();
        $("#result_report").print({
            globalStyles: true,
            mediaPrint: false,
            stylesheet: null,
            noPrintSelector: ".no-print",
            iframe: true,
            append: null,
            prepend: null,
            manuallyCopyFormValues: true,
            deferred: $.Deferred(),
            timeout: 250,
                title: null,
                doctype: '<!doctype html>'
        });
      });
    </script>

    <script type="text/javascript">

    var tbl;
    var attendancerpt;
      $(document).ready(function(){

      var subjects = {!! json_encode($subjects) !!};

        $('[data-toggle="tooltip"]').tooltip();

      $('[name="class"]').on('change', function(){
        clsid = $(this).val();
        $('[name="class"]').val(clsid);
          $('[name="subject"]').html('<option></option>');
          if(subjects['class_'+clsid].length > 0 && clsid > 0){          
            $.each(subjects['class_'+clsid], function(k, v){
              $('[name="subject"]').append('<option value="'+v['id']+'">'+v['name']+'</option>');
            });
          }
      });

      @if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
        $('[name="class"]').val("{{ old('class') }}");
        $('[name="class"]').change();
        $('[name="exam"]').val("{{ old('exam') }}");
        $('[name="subject"]').val('{{ old('subject') }}');

      @elseif(isset($input) && $input !== null)
        $('[name="class"]').val("{{ $input['class'] }}");
        $('[name="class"]').change();
        $('[name="exam"]').val("{{ $input['exam'] }}");
        @if($root['job'] == 'make')
        $('[name="subject"]').val("{{ $input['subject'] }}");
        @endif
      @endif

      $('.obtain_marks').on('change', function(){
        marks = $(this).val();
        totmarks = parseInt($('#total_marks').val());
        if((isNaN(marks) && !parseFloat(marks)) || marks == ''){ $(this).val(0); $(".obtain_marks").change(); return false;}
        if(marks <= totmarks){
          $(this).val(parseFloat(marks).toFixed(2));
        } else {
          alert('Obtain marks Should be <= total marks !');
          $(this).val(parseFloat(0).toFixed(2));
        }
      });


      @if($root['job'] == 'report')

        resultrpt = {!! json_encode($result) !!};
        // console.log(resultrpt);
        $.each(resultrpt, function(k, v){
          $.each(v, function(i, d){
            sub = d.subject_id;
/*            console.log(d);
            alert(day);
*/
            $('.std_'+k+'_sub_'+sub).html(d.obtain_marks);
          });
        });

        $('.nav-tabs a[href="#tab-11"]').tab('show');
      @else
        $('.nav-tabs a[href="#tab-10"]').tab('show');
      @endif


      @if(Auth::user()->privileges->{$root['content']['id']}->make == 0)
        $('.make-result').hide();
      @endif

      @if(Auth::user()->privileges->{$root['content']['id']}->report == 0)
        $('.get-result').hide();
      @endif

      });
    </script>

    @endsection