@extends('admin.layouts.master')

  @section('title', __('modules.pages_vendors_title').' |')

  @section('head')
  <link href="{{ asset('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
  <link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('admin.includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Vendors</h2>
                  <ol class="breadcrumb">
                    <li>{{ __("common.home") }}</li>
                      <li Class="active">
                          <a>Vendors</a>
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
                              <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> {{ __('modules.tabs_vendors') }}</a>
                            </li>
                            @can('vendors.add')
                              <li class="add-vendor">
                                <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> {{ __('modules.tabs_add_vendor') }}</a>
                              </li>
                            @endcan
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade ">
                                <div class="panel-body">
                                  <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover dataTables-teacher" >
                                      <thead>
                                        <tr>
                                          <th>V Name</th>
                                          <th>C Name</th>
                                          <th>{{ __("labels.email_label") }}</th>
                                          <th>{{ __("labels.contact") }}</th>
                                          <th>{{ __("labels.address") }}</th>
                                          <th>{{ __("labels.options") }}</th>
                                        </tr>
                                      </thead>
                                    </table>
                                  </div>

                                </div>
                            </div>
                            @can('vendors.add')
                              <div id="tab-11" class="tab-pane fade add-vendor">
                                  <div class="panel-body">
                                    <h2> Vendor Registration </h2>
                                    <div class="hr-line-dashed"></div>

                                      <form id="vdr_rgstr" method="post" action="{{ URL('vendors/add') }}" class="form-horizontal" >
                                        {{ csrf_field() }}

                                        <div class="form-group{{ ($errors->has('v_name'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Vendor Name</label>
                                          <div class="col-md-6">
                                            <input type="text" name="v_name" placeholder="V Name" value="{{ old('v_name') }}" class="form-control"/>
                                            @if ($errors->has('v_name'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('v_name') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group{{ ($errors->has('c_name'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Contact Name</label>
                                          <div class="col-md-6">
                                            <input type="text" name="c_name" placeholder="C Name" value="{{ old('c_name') }}" class="form-control"/>
                                            @if ($errors->has('c_name'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('c_name') }}</strong>
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


                                        <div class="form-group{{ ($errors->has('phone'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Contact No</label>
                                          <div class="col-md-6">
                                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="{{ __('labels.contact_no_placeholder') }}" class="form-control" data-mask="(999) 999-9999"/>
                                            @if ($errors->has('phone'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('phone') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group">
                                          <label class="col-md-2 control-label">Address</label>
                                          <div class="col-md-6">
                                            <textarea type="text" name="address" placeholder="{{ __("labels.address_placeholder_ellipsis") }}" class="form-control">{{ old('address') }}</textarea>
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


          


        </div>

    @endsection

    @section('script')

    <!-- Mainly scripts -->
    <script src="{{ asset('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>

    <script src="{{ asset('src/js/plugins/dataTables/datatables.min.js') }}"></script>

    <script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- Input Mask-->
     <script src="{{ asset('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

    <script type="text/javascript">
    var tbl;

    function loadOptions(data, type, full, meta) {

      //  opthtm = '<a href="{{ URL('vendors/profile') }}/'+full.id+'" data-toggle="tooltip" title="Profile" class="btn btn-default btn-circle btn-xs profile"><span class="fa fa-user"></span></a>';
      opthtm = '';
      @can('vendors.edit.post')
      opthtm += '<a href="{{ URL('vendors/edit') }}/'+full.id+'" data-toggle="tooltip" title="Edit Vendor" class="btn btn-default btn-circle btn-xs"><span class="fa fa-edit"></span></a>';
      @endcan
      return opthtm;
    }


      $(document).ready(function(){

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
          ajax: '{{ URL('vendors') }}',
          columns: [
            {data: 'v_name'},
            {data: 'c_name'},
            {data: 'email'},
            {data: 'phone'},
            {data: 'address'},
//            {"defaultContent": '<div class="btn-group"><button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle option" aria-expanded="true">Action <span class="caret"></span></button><ul class="dropdown-menu"><li><a href="#"><span class="fa fa-user"></span> Profile</a></li><li class="divider"></li><li><a data-original-title="Edit" class="edit-option"><span class="fa fa-edit"></span> Edit</a></li><li><a href="#"><span class="fa fa-trash"></span> Delete</a></li></ul></div>', className: 'hidden-print'},
//            {"defaultContent": opthtm, className: 'hidden-print'},
            {render: loadOptions, className: 'hidden-print', "orderable": false},
          ],
        });


      $('.dataTables-teacher tbody').on( 'mouseenter', '[data-toggle="tooltip"]', function () {
        $(this).tooltip('show');
      });

        $("#vdr_rgstr").validate({
            rules: {
              v_name: {
                required: true,
              },
              c_name: {
                required: true,
              },
              email: {
                email: true
              },
            },
        });

      @if(count($errors) >= 1 && !$errors->has('toastrmsg'))
        $('a[href="#tab-11"]').tab('show');
      @else
        $('a[href="#tab-10"]').tab('show');
      @endif
      });
    </script>

    @endsection
