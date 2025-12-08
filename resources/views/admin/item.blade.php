@extends('admin.layouts.master')

  @section('title', 'Items |')

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
                  <h2>{{ __('modules.pages_items_title') }}</h2>
                  <ol class="breadcrumb">
                    <li>{{ __('common.home') }}</li>
                      <li Class="active">
                          <a>{{ __('modules.pages_items_title') }}</a>
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
                              <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Items</a>
                            </li>
                            @can('items.add')
                              <li class="add-item">
                                <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> {{ __('modules.tabs_add_items') }}</a>
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
                                          <th>{{ __("labels.name") }}</th>
                                          <th>Category</th>
                                          <th>Qty</th>
                                          <th>Qty Level</th>
                                          <th>location</th>
                                          <th>{{ __("labels.options") }}</th>
                                        </tr>
                                      </thead>
                                    </table>
                                  </div>

                                </div>
                            </div>
                            @can('items.add')
                              <div id="tab-11" class="tab-pane fade add-item">
                                  <div class="panel-body">
                                    <h2> Item Registration </h2>
                                    <div class="hr-line-dashed"></div>

                                      <form id="vdr_rgstr" method="post" action="{{ URL('items/add') }}" class="form-horizontal" >
                                        {{ csrf_field() }}

                                        <div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
                                            <label class="col-md-2 control-label">{{ __('modules.labels_item_name') }}</label>
                                          <div class="col-md-6">
                                            <input type="text" name="name" placeholder="{{ __('modules.labels_item_name') }}" value="{{ old('name') }}" class="form-control"/>
                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group{{ ($errors->has('category'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Category</label>
                                          <div class="col-md-6">
                                            <input type="text" name="category" placeholder="{{ __('labels.category_placeholder') }}" value="{{ old('category') }}" class="form-control"/>
                                            @if ($errors->has('category'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('category') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group{{ ($errors->has('qty'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Qty</label>
                                          <div class="col-md-6">
                                            <input type="number" name="qty" placeholder="Qty" value="{{ old('qty') }}" class="form-control"/>
                                            @if ($errors->has('qty'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('qty') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group{{ ($errors->has('qty_level'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Qty Level</label>
                                          <div class="col-md-6">
                                            <input type="number" name="qty_level" placeholder="Qty Level" value="{{ old('qty_level') }}" class="form-control"/>
                                            @if ($errors->has('qty_level'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('qty_level') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group{{ ($errors->has('location'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Location</label>
                                          <div class="col-md-6">
                                            <input type="text" name="location" placeholder="Location" value="{{ old('location') }}" class="form-control"/>
                                            @if ($errors->has('location'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('location') }}</strong>
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

      opthtm = '';
      //  opthtm = '<a href="{{ URL('items/profile') }}/'+full.id+'" data-toggle="tooltip" title="Profile" class="btn btn-default btn-circle btn-xs profile"><span class="fa fa-user"></span></a>';
      @can('items.edit.post')
      opthtm += '<a href="{{ URL('items/edit') }}/'+full.id+'" data-toggle="tooltip" title="Edit Profile" class="btn btn-default btn-circle btn-xs"><span class="fa fa-edit"></span></a>';
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
          ajax: '{{ URL('items') }}',
          columns: [
            {data: 'name'},
            {data: 'category'},
            {data: 'qty'},
            {data: 'qty_level'},
            {data: 'location'},
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
              name: {
                required: true,
              },
              category: {
                required: true,
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
