@extends('admin.layouts.master')

  @section('title', 'Items |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('admin.includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Items</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li Class="active">
                          <a>Items</a>
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
                              <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Add Items</a>
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
                                          <th>Name</th>
                                          <th>Category</th>
                                          <th>Qty</th>
                                          <th>Qty Level</th>
                                          <th>location</th>
                                          <th>Options</th>
                                        </tr>
                                      </thead>
                                    </table>
                                  </div>

                                </div>
                            </div>
                            <div id="tab-11" class="tab-pane fade add-item">
                                <div class="panel-body">
                                  <h2> Item Registration </h2>
                                  <div class="hr-line-dashed"></div>

                                    <form id="vdr_rgstr" method="post" action="{{ URL('items/add') }}" class="form-horizontal" >
                                      {{ csrf_field() }}

                                      <div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Item Name</label>
                                        <div class="col-md-6">
                                          <input type="text" name="name" placeholder="Item Name" value="{{ old('name') }}" class="form-control"/>
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
                                          <input type="text" name="category" placeholder="Category" value="{{ old('category') }}" class="form-control"/>
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

      @if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
        $('a[href="#tab-11"]').tab('show');
      @else
        $('a[href="#tab-10"]').tab('show');
      @endif

      //Permission will be applied later
      // "if(Auth::user()->getprivileges->privileges->{$root['content']['id']}->add == 0)"
      //   $('.add-item').hide();
      // "endif"


      });
    </script>

    @endsection
