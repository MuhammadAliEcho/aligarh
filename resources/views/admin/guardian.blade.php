@extends('admin.layouts.master')

  @section('title', 'Guardians |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
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
                      <li Class="active">
                          <a>Guardians</a>
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
                            <li class="">
                              <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Guardians</a>
                            </li>
                            @can('guardian.add')
                              <li class="add-guardian">
                                <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Add Guardians</a>
                              </li>
                            @endcan
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade">
                                <div class="panel-body">
                                  <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover dataTables-teacher" width="100%">
                                      <thead>
                                        <tr>
                                          <th>Name</th>
                                          <th>E-Mail</th>
                                          <th>Contact</th>
                                          <th>Address</th>
                                          <th>Options</th>
                                        </tr>
                                      </thead>
                                      <tfoot>
                                        <tr>
                                          <th><input type="text" placeholder="Name..."></th>
                                          <th><input type="text" placeholder="E-mail..."></th>
                                          <th><input type="text" placeholder="Contact..."></th>
                                          <th><input type="text" placeholder="Address..."></th>
                                          <th>Options</th>
                                        </tr>
                                      </tfoot>
                                    </table>
                                  </div>

                                </div>
                            </div>
                            @can('guardian.add')
                              <div id="tab-11" class="tab-pane fade add-guardian">
                                  <div class="panel-body">
                                    <h2> Guardian Registration </h2>
                                    <div class="hr-line-dashed"></div>

                                      <form id="tchr_rgstr" method="post" action="{{ URL('guardians/add') }}" class="form-horizontal" >
                                        {{ csrf_field() }}

                                        <div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Name</label>
                                          <div class="col-md-6">
                                            <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" class="form-control"/>
                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group{{ ($errors->has('email'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">E-Mail</label>
                                          <div class="col-md-6">
                                            <input type="text" name="email" placeholder="E-Mail" value="{{ old('email') }}" class="form-control"/>
                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group{{ ($errors->has('profession'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Profession</label>
                                          <div class="col-md-6">
                                            <input type="text" name="profession" placeholder="Profession" value="{{ old('profession') }}" class="form-control"/>
                                            @if ($errors->has('profession'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('profession') }}</strong>
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

                                        <div class="form-group{{ ($errors->has('phone'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Contact No</label>
                                          <div class="col-md-6">
                                            <div class="input-group m-b">
                                              <span class="input-group-addon">+92</span>
                                              <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contact No" class="form-control" data-mask="9999999999"/>
                                            </div>
                                            @if ($errors->has('phone'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('phone') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group{{ ($errors->has('income'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Imcome</label>
                                          <div class="col-md-6">
                                            <input type="text" name="income" value="{{ old('income') }}" placeholder="Income" class="form-control"/>
                                            @if ($errors->has('income'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('income') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-offset-2 col-md-6">
                                                <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Register </button>
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


          @include('admin.includes.footercopyright')


        </div>

    @endsection

    @section('script')

    <!-- Mainly scripts -->
    <script src="{{ URL::to('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>

    <script src="{{ URL::to('src/js/plugins/dataTables/datatables.min.js') }}"></script>

    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- Input Mask-->
     <script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

    <script type="text/javascript">
    var tbl;

    function loadOptions(data, type, full, meta) {
        opthtm = '';
        @can('guardian.profile')
        opthtm = '<a href="{{ URL('guardians/profile') }}/'+full.id+'" data-toggle="tooltip" title="Profile" class="btn btn-default btn-circle btn-xs profile"><span class="fa fa-user"></span></a>';
        @endcan
        @can('guardian.edit.post')
        opthtm += '<a href="{{ URL('guardians/edit') }}/'+full.id+'" data-toggle="tooltip" title="Edit Profile" class="btn btn-default btn-circle btn-xs"><span class="fa fa-edit"></span></a>';
        @endcan

        return opthtm;
    }

      $(document).ready(function(){

        opthtm = '<a data-toggle="tooltip" title="Profile" class="btn btn-default btn-circle btn-xs profile"><span class="fa fa-user"></span></a>';

        // "(Auth::user()->getprivileges->privileges->{$root['content']['id']}->edit)"
          opthtm += '<a data-toggle="tooltip" title="Edit" class="btn btn-default btn-circle btn-xs edit-option eidt-guardian"><span class="fa fa-edit"></span></a>';
        // "endif"

        tbl = $('.dataTables-teacher').DataTable({
          dom: '<"html5buttons"B>lTfgitp',
          buttons: [
//            { extend: 'copy'},
//            {extend: 'csv'},
//            {extend: 'excel', title: 'ExampleFile'},
//            {extend: 'pdf', title: 'ExampleFile'},

            {extend: 'print',
              customize: function (win){
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
                columns: [ 0, 1, 2, 3]
              },
              title: "Guardians | {{ config('systemInfo.title') }}",
            }
          ],
          Processing: true,
          serverSide: true,
          ajax: '{{ URL('guardians') }}',
          columns: [
            {data: 'name'},
            {data: 'email'},
            {data: 'phone'},
            {data: 'address'},
//            {"defaultContent": '<div class="btn-group"><button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle option" aria-expanded="true">Action <span class="caret"></span></button><ul class="dropdown-menu"><li><a href="#"><span class="fa fa-user"></span> Profile</a></li><li class="divider"></li><li><a data-original-title="Edit" class="edit-option"><span class="fa fa-edit"></span> Edit</a></li><li><a href="#"><span class="fa fa-trash"></span> Delete</a></li></ul></div>', className: 'hidden-print'},
//            {"defaultContent": opthtm, className: 'hidden-print', "orderable": false},
            {render: loadOptions, className: 'hidden-print', "orderable": false},
          ],
          "order": [[0, "asc"]],
          "scrollY": "450px",
          "scrollX": true,
          "scrollCollapse": true,
          "paging": true,
        });

    var search = $.fn.dataTable.util.throttle(
      function (colIdx, val ) {
        tbl
        .column( colIdx )
        .search( val )
        .draw();
      },
      1000
    );

//    for Column search
        tbl.columns().eq( 0 ).each( function ( colIdx ) {
            $( 'input', tbl.column( colIdx ).footer() ).on( 'keyup change', function () {
                search(colIdx, this.value);
            });
        });

      $('.dataTables-teacher tbody').on( 'mouseenter', '[data-toggle="tooltip"]', function () {
        $(this).tooltip('show');
      });

        $("#tchr_rgstr").validate({
            rules: {
              name: {
                required: true,
              },
/*              profession: {
                required: true,
              },
              email: {
                required: true,
                email: true
              },
*/              income:{
                number:true,
              },
            },
            messages:{
              income:{
                number:'Enter valid amount'
             },
           }
        });

      @if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
        $('.nav-tabs a[href="#tab-11"]').tab('show');
      @else
        $('.nav-tabs a[href="#tab-10"]').tab('show');
      @endif
      });
    </script>

    @endsection
