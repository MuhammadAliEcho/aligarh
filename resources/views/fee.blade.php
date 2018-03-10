@extends('layouts.master')

  @section('title', 'Fees |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
  @endsection

  @section('content')

  @include('includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Fees</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li Class="active">
                          <a>Fee</a>
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
                            <li class="">
                              <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Invoice</a>
                            </li>
                            <li class="make-fee">
                              <a data-toggle="tab" href="#tab-11"><span class="fa fa-edit"></span> Create Invoice</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade">
                                <div class="panel-body">
                                  <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover dataTables-teacher" width="100%">
                                      <thead>
                                        <tr>
                                          <th>ID</th>
                                          <th>GR-No</th>
                                          <th>Total Amount</th>
                                          <th>Discount</th>
                                          <th>Paid Amount</th>
                                          <th>Payment Month</th>
                                          <th>Created At</th>
                                          <th>Options</th>
                                        </tr>
                                      </thead>
                                    </table>
                                  </div>

                                </div>
                            </div>
                            <div id="tab-11" class="tab-pane fade make-fee">
                                <div class="panel-body">
                                  <h2> Create Invoice </h2>
                                  <div class="hr-line-dashed"></div>

                                    <form id="crt_invoice_frm" method="GET" action="{{ URL('fee/create') }}" class="form-horizontal jumbotron" role="form" >

                                      <div class="form-group{{ ($errors->has('gr_no'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label"> GR-No </label>
                                        <div class="col-md-6">
                                          <select class="form-control" name="gr_no" id="select2" required="true"></select>
                                          @if ($errors->has('gr_no'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('gr_no') }} </strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('month'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label"> Month </label>
                                        <div class="col-md-6">
                                          <select class="form-control" name="month" required="true">
                                            <option></option>
                                            @foreach($months AS $k=>$month)
                                              <option value="{{ $k }}" >{{ $month }}</option>
                                            @endforeach
                                          </select>
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('year'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label"> Year </label>
                                        <div class="col-md-6">
                                          <select class="form-control" name="year" required="true">
                                            <option></option>
                                            <option>{{ $year-1 }}</option>
                                            <option>{{ $year }}</option>
                                            <option>{{ $year+1 }}</option>
                                          </select>
                                        </div>
                                      </div>

                                      <div class="form-group">
                                          <div class="col-md-offset-2 col-md-6">
                                              <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-search"></span> Search </button>
                                          </div>
                                      </div>

                                    </form>

                                    @if($root['job'] == 'create')
                                    <div class="row">
                                    <h3>Student Name: <span class="bg-info"> {{ $student->name }} | {{ $student->gr_no }} </span> / Month: ({{ $months[$Input['month']].'-'.$Input['year'] }})</h3>
                                      <div class="hr-line-dashed"></div>
                                      @if(empty($invoice))
                                      <form action="{{ URL('fee/create/'.$student->id) }}" method="POST" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="date" value="{{ '01/'.$Input['month'].'/'.$Input['year'] }}" required="true">
                                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                                      @endif
                                        <table class="table table-striped table-bordered table-hover">
                                          <thead>
                                            <tr>
                                              <th>Fees Name</th>
                                              <th>Amount</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr>
                                              <td>Tuition Fee</td>
                                              <td>{{ $student->tuition_fee }}</td>
                                            </tr>
                                            @foreach($student->AdditionalFee AS $v)
                                            <tr>
                                              <td>{{ $v->fee_name }}</td>
                                              <td>{{ $v->amount }}</td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                              <th>Discount</th>
                                              <th>{{ $student->discount or '0' }}</th>
                                            </tr>
                                          </tbody>
                                          <tfoot>
                                            <tr class="success">
                                              <th>Total</th>
                                              <th>{{ $student->net_amount }}</th>
                                            </tr>
                                          </tfoot>
                                        </table>

                                      @if(empty($invoice))
                                        <div class="form-group">
                                            <div class="col-md-offset-4 col-md-4">
                                                <button class="btn btn-primary btn-block" type="submit"><span class="glyphicon glyphicon-save"></span> Collect </button>
                                            </div>
                                        </div>

                                        </form>
                                      @else
                                        <div class="alert alert-success">
                                          <h4>Payment Already Paid <a target="_new" href="{{ URL('fee/invoice/'.$invoice->id) }}">Invoice# {{ $invoice->id }}</a></h4>
                                        </div>
                                      @endif
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

    <!-- Mainly scripts -->
    <script src="{{ URL::to('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>

    <script src="{{ URL::to('src/js/plugins/dataTables/datatables.min.js') }}"></script>

    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- Input Mask-->
     <script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ URL::to('src/js/plugins/select2/select2.full.min.js') }}"></script>

    <script type="text/javascript">
    var tbl;

      function select2template(data) {
        if (!data.id) { return data.text; }
        var $data = $(
          // '<span class="text-danger">'+data.text+'</span>'
          data.htm1+data.text+data.htm2
        );
        return $data;
      };


      $(document).ready(function(){

      @if((COUNT($errors) >= 1 && !$errors->has('toastrmsg')) || $root['job'] == 'create')
        $('a[href="#tab-11"]').tab('show');
        @if(isset($Input))
          $('[name="gr_no"').val('{{ $Input['gr_no'] }}');
          $('[name="month"').val('{{ $Input['month'] }}');
          $('[name="year"').val('{{ $Input['year'] }}');
        @else
          $('[name="month"').val('{{ old('month') }}');
          $('[name="year"').val('{{ old('year') }}');
        @endif
      @else
        $('a[href="#tab-10"]').tab('show');
      @endif

        opthtm = '<a data-toggle="tooltip" target="_new" title="View" class="btn btn-default btn-circle btn-xs edit-option"><span class="fa fa-file-pdf-o"></span></a>';
        tbl = $('.dataTables-teacher').DataTable({
          dom: '<"html5buttons"B>lTfgitp',
          buttons: [
            { extend: 'copy'},
            {extend: 'csv'},
            {extend: 'excel', title: 'ExampleFile'},
            {extend: 'pdf', title: 'ExampleFile'},

            {extend: 'print',
              customize: function (win){
                $(win.document.body).addClass('white-bg');
                $(win.document.body).css('font-size', '10px');

                $(win.document.body).find('table')
                .addClass('compact')
                .css('font-size', 'inherit');
              }
            }
          ],
          Processing: true,
          serverSide: true,
          order: [[0, "desc"]],
          ajax: '{{ URL('fee') }}',
          columns: [
            {data: 'id', name: 'invoice_master.id'},
            {data: 'gr_no', name: 'invoice_master.gr_no'},
            {data: 'total_amount', name: 'invoice_master.total_amount'},
            {data: 'discount', name: 'invoice_master.discount'},
            {data: 'paid_amount', name: 'invoice_master.paid_amount'},
            {data: 'payment_month', name: 'invoice_master.payment_month'},
            {data: 'created_at', name: 'invoice_master.created_at'},
            {"defaultContent": opthtm, className: 'hidden-print'},
          ],
        });

      $('.dataTables-teacher tbody').on( 'mouseenter', '.edit-option', function () {
        $(this).attr('href','{{ URL('fee/invoice/') }}/'+tbl.row( $(this).parents('tr') ).data().id);
        $(this).tooltip('show');
      });

        $("#tchr_rgstr").validate({
            rules: {
              gr_no: {
                required: true,
              },
              year: {
                required: true,
              },
              month: {
                required: true,
              },
            },
        });

        $('#select2').attr('style', 'width:100%').select2({
            placeholder: 'Search contacts',
            minimumInputLength: 3,
            Html: true,
            ajax: {
                url: '{{ URL('fee/findstu') }}',
/*                dataType: 'json',
                data: function (term, page) {
                    return {
                        contact_names_value: term
                    };
                },
                results: function (data, page) {
                    return {results: data.data};
                }*/
              processResults: function (data) {
                return {
                  results: data
                };
              }
            },
            tags: true,
            // templateResult: select2template,
        });

      @if(Session::get('invoice_created') !== null)
        window.open('{{ URL('fee/invoice/'.Session::get('invoice_created')) }}', '_new');
      @endif

      @if(Auth::user()->privileges->{$root['content']['id']}->create == 0)
        $('.make-fee').hide();
      @endif


      });
    </script>

    @endsection
