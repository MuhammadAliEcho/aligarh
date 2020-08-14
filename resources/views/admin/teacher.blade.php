@extends('admin.layouts.master')

  @section('title', 'Teacher |')

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
                  <h2>Teachers</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li Class="active">
                          <a>Teachers</a>
                      </li>
                  </ol>
              </div>
              <div class="col-lg-4 col-md-6">
                @include('admin.includes.academic_session')
              </div>
          </div>

          <!-- main Section -->

          <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row ">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class="">
                              <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Teachers</a>
                            </li>
                            <li class="add-teacher">
                              <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Add Teachers</a>
                            </li>
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
                                          <th width="60px">Options</th>
                                        </tr>
                                      </thead>
                                      <tfoot>
                                        <tr>
                                          <th><input type="text" placeholder="Name..."></th>
                                          <th><input type="text" placeholder="E-Mail..."></th>
                                          <th><input type="text" placeholder="Contact..."></th>
                                          <th><input type="text" placeholder="Address..."></th>
                                          <th>Options</th>
                                        </tr>
                                      </tfoot>
                                    </table>
                                  </div>

                                </div>
                            </div>
                            <div id="tab-11" class="tab-pane fade add-teacher">
                                <div class="panel-body">
                                  <h2> Teacher Registration </h2>
                                  <div class="hr-line-dashed"></div>

                                    <form id="tchr_rgstr" method="post" action="{{ URL('teacher/add') }}" class="form-horizontal" enctype="multipart/form-data">
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

                                      <div class="form-group{{ ($errors->has('relegion'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Relegion</label>
                                        <div class="col-md-6">
                                          <input type="text" name="relegion" placeholder="Relegion" value="{{ old('relegion') }}" class="form-control"/>
                                          @if ($errors->has('relegion'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('relegion') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('f_name'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Father Name</label>
                                        <div class="col-md-6">
                                          <input type="text" name="f_name" placeholder="Father Name" value="{{ old('f_name') }}" class="form-control"/>
                                          @if ($errors->has('f_name'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('f_name') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('img'))? ' has-error' : '' }}">
                                        <div class="col-md-2">
                                          <span class="btn btn-default btn-block btn-file">
                                            <input type="file" name="img" accept="image/*" id="imginp" />
                                              <span class="fa fa-image"></span>
                                              Upload Image
                                          </span>
                                        </div>
                                        <div class="col-md-6">
                                          <img id="img" src=""  alt="Item Image..." class="img-responsive img-thumbnail" />
                                          @if ($errors->has('img'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('img') }}</strong>
                                              </span>
                                          @endif
                                        </div>
									  </div>
									  
                                      <div class="form-group{{ ($errors->has('husband_name'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Husband Name</label>
                                        <div class="col-md-6">
                                          <input type="text" name="husband_name" placeholder="Husband Name" value="{{ old('husband_name') }}" class="form-control"/>
                                          @if ($errors->has('husband_name'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('husband_name') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('subject'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Subject</label>
                                        <div class="col-md-6">
                                          <input type="text" name="subject" placeholder="Subject" value="{{ old('subject') }}" class="form-control"/>
                                          @if ($errors->has('subject'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('subject') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('gender'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Gender</label>
                                        <div class="col-md-6">
                                          <select class="form-control" name="gender">
                                            <option></option>
                                            <option>Male</option>
                                            <option>Female</option>
                                          </select>
                                          @if ($errors->has('gender'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('gender') }}</strong>
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

                                      <div class="form-group{{ ($errors->has('qualification'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Qualification</label>
                                        <div class="col-md-6">
                                          <input type="text" name="qualification" placeholder="Qualification" value="{{ old('qualification') }}" class="form-control"/>
                                          @if ($errors->has('qualification'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('qualification') }}</strong>
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

                                      <div class="form-group{{ ($errors->has('salary'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Salary</label>
                                        <div class="col-md-6">
                                          <input type="text" name="salary" value="{{ old('salary') }}" placeholder="Salary" class="form-control"/>
                                          @if ($errors->has('salary'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('salary') }}</strong>
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

            <div class="row">

            </div>
          </div>


          @include('admin.includes.footercopyright')


        </div>

    @endsection

    @section('script')

    <!-- Mainly scripts 
    <script src="{{ URL::to('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>
    -->

    <script src="{{ URL::to('src/js/plugins/dataTables/datatables.min.js') }}"></script>

    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- Input Mask-->
     <script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

    <script type="text/javascript">

    function readURL(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
              $('#img').attr('src', e.target.result);
          }
          reader.readAsDataURL(input.files[0]);
      }
    }

    function loadOptions(data, type, full, meta) {
        opthtm = '<a href="{{ URL('teacher/profile') }}/'+full.id+'" data-toggle="tooltip" title="Profile" class="btn '+ ((full.user_id != null)? ((full.active)? 'btn-info' : 'btn-primary') : 'btn-default') +' btn-circle btn-xs profile"><span class="fa fa-user"></span></a>';
        
        @if(Auth::user()->getprivileges->privileges->{$root['content']['id']}->edit)
          opthtm += '<a href="{{ URL('teacher/edit') }}/'+full.id+'" data-toggle="tooltip" title="Edit Profile" class="btn btn-default btn-circle btn-xs"><span class="fa fa-edit"></span></a>';
        @endif
        if(full.user_id != null){
          opthtm += '<a href="{{ URL('users/edit') }}/'+full.user_id+'" data-toggle="tooltip" title="Edit User" class="btn btn-default btn-circle btn-xs"><span class="fa fa-edit"></span></a>';
        }

        return opthtm;
    }

    var tbl;
      $(document).ready(function(){

/*    For Column Search
        $('.dataTables-teacher thead th').each( function () {
            var title = $('.dataTables-teacher thead th').eq( $(this).index() ).text();
          if (title !== 'Options') {
            $(this).html( '<input class="" type="text" placeholder="'+title+'" />' );
          }
        });
*/

    tbl =   $('.dataTables-teacher').DataTable({
          dom: '<"html5buttons"B>lTfgitp',
          buttons: [
//            {extend: 'copy'},
//            {extend: 'csv'},
//            {extend: 'excel', title: 'Teacher List'},
//            {extend: 'pdf', title: 'Teacher List'},

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
              title: "Teachers | {{ config('systemInfo.title') }}",
            }
          ],
          Processing: true,
          serverSide: true,
          ajax: '{{ URL('teacher') }}',
          columns: [
            {data: "name", name: "teachers.name"},
            {data: "email", name: "teachers.email"},
            {data: "phone", name: "teachers.phone"},
            {data: "address", name: "teachers.address"},
//            {"defaultContent": '<div class="btn-group"><button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle option" aria-expanded="true">Action <span class="caret"></span></button><ul class="dropdown-menu"><li><a href="#"><span class="fa fa-user"></span> Profile</a></li><li class="divider"></li><li><a data-original-title="Edit" class="edit-option"><span class="fa fa-edit"></span> Edit</a></li><li><a href="#"><span class="fa fa-trash"></span> Delete</a></li></ul></div>', className: 'hidden-print'},
//            {"defaultContent": opthtm, className: 'hidden-print'},
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


/*
    for Column search
        tbl.columns().eq( 0 ).each( function ( colIdx ) {
            $( 'input', tbl.column( colIdx ).header() ).on( 'keyup change', function () {
                tbl
                    .column( colIdx )
                    .search( this.value )
                    .draw();
            });
        });
*/


      $(".dataTables-teacher tbody").on('mouseenter', "[data-toggle='tooltip']", function(){
          $(this).tooltip('show');
      });

        $("#tchr_rgstr").validate({
            rules: {
              name: {
                required: true,
              },
/*              subject: {
                required: true,
              },
*/              gender: {
                required: true,
              },
              qualification: {
                required: true,
              },
/*              email: {
                required: true,
                email: true
              },
*/              salary:{
                required:true,
                number:true,
              },
            },
            messages:{
              salary:{
                number:'Enter valid amount'
             },
           }
        });

      $('#tchr_rgstr [name="gender"]').val('{{ old('gender') }}');
      
      @if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
        $('a[href="#tab-11"]').tab('show');
      @else
        $('a[href="#tab-10"]').tab('show');
      @endif

      $("#imginp").change(function(){
          readURL(this);
      });

      @if(Auth::user()->getprivileges->privileges->{$root['content']['id']}->add == 0)
        $('.add-teacher').hide();
      @endif

      @if(Auth::user()->getprivileges->privileges->{$root['content']['id']}->edit == 0)
        $('.edit-teacher').hide();
      @endif


      });
    </script>

    @endsection
