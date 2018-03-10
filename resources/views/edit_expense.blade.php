@extends('layouts.master')

  @section('title', 'Edit Expense |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
  @endsection

  @section('content')

  @include('includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Expense</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li>
                          <a>Expense</a>
                      </li>
                      <li Class="active">
                          <a>Edit</a>
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
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>Edit Expense</h2>
                        <div class="hr-line-dashed"></div>
                    </div>

                    <div class="ibox-content">

                                    <form id="tchr_rgstr" method="post" action="{{ URL('expense/edit/'.$expense['id']) }}" class="form-horizontal" >
                                      {{ csrf_field() }}

                                      <div class="form-group{{ ($errors->has('type'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Type</label>
                                        <div class="col-md-6">
                                        <select class="form-control" name="type" >
                                          <option></option>
                                          <option>Salary</option>
                                          <option>Bills</option>
                                          <option>Maintenance</option>
                                          <option>Others</option>
                                        </select>
                                          @if ($errors->has('type'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('type') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('description'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Description</label>
                                        <div class="col-md-6">
                                          <textarea type="text" name="description" placeholder="Description" class="form-control" required="true">{{ old('description', $expense->description) }}</textarea>
                                          @if ($errors->has('description'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('description') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('amount'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Amount</label>
                                        <div class="col-md-6">
                                          <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}" placeholder="Amount" class="form-control"/>
                                          @if ($errors->has('amount'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('amount') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('date'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Date</label>
                                        <div class="col-md-6">
                                          <input id="datetimepicker4" type="text" name="date" value="{{ old('date', $expense->date) }}" placeholder="Date" class="form-control"/>
                                          @if ($errors->has('date'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('date') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group">
                                          <div class="col-md-offset-2 col-md-6">
                                              <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Save Changes </button>
                                          </div>
                                      </div>
                                    </form>

                        </div>
                    </div>
                </div>
            </div>

          </div>


          @include('includes.footercopyright')


        </div>

    @endsection

    @section('script')


    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- require with bootstrap-datetimepicker -->
    <script src="{{ URL::to('src/js/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

    <script type="text/javascript">

      $(document).ready(function(){

        $('#datetimepicker4').datetimepicker({
          format: 'DD/MM/YYYY'
        });

        $("#tchr_rgstr").validate({
            rules: {
              type: {
                required: true,
              },
              description: {
                required: true,
              },
              amount: {
                required: true,
              },
              date:{
                required:true,
              },
            },
        });

      $('#tchr_rgstr [name="type"]').val('{{ old('type', $expense->type) }}');

      });
    </script>

    @endsection