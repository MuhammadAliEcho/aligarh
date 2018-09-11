@extends('admin.layouts.master')

  @section('title', 'Students |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
  <script type="text/javascript">
      var sections = {!! json_encode($sections) !!};
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
                  <h2>Students</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                    <li Class="active">
                        <a>Students</a>
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
                              <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Students</a>
                            </li>

                            <li class="add-student">
                              <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Admit Students</a>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade">
                                <div class="panel-body">
                                  <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover dataTables-teacher" width="100%">
                                      <thead>
                                        <tr>
                                          <th>GR No</th>
                                          <th>Name</th>
                                          <th>Contact</th>
                                          <th>Address</th>
                                          <th>Active</th>
                                          <th>Options</th>
                                        </tr>
                                      </thead>
                                      <tfoot>
                                        <tr>
                                          <th><input type="text" placeholder="Gr No..." autocomplete="off"></th>
                                          <th><input type="text" placeholder="Name..." autocomplete="off"></th>
                                          <th><input type="text" placeholder="Contact..." autocomplete="off"></th>
                                          <th><input type="text" placeholder="Address..." autocomplete="off"></th>
                                          <th></th>
                                          <th>
                                            <select id="filterActive">
                                              <option value="">All</option>
                                              <option value="1">Active</option>
                                              <option value="0">InActive</option>
                                            </select>
                                          </th>
                                        </tr>
                                      </tfoot>
                                    </table>
                                  </div>

                                </div>
                            </div>
                            <div id="tab-11" class="tab-pane fade add-student">
                                <div class="panel-body">
                                  <h2> Admit Student </h2>
                                  <div class="hr-line-dashed"></div>

                                    <form id="tchr_rgstr" method="post" action="{{ URL('students/add') }}" class="form-horizontal" enctype="multipart/form-data">
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

                                      <div class="form-group{{ ($errors->has('father_name'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Father Name</label>
                                        <div class="col-md-6">
                                          <input type="text" name="father_name" placeholder="Father Name" value="{{ old('father_name') }}" class="form-control"/>
                                          @if ($errors->has('father_name'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('father_name') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('gender'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Gender</label>
                                        <div class="col-md-6">
                                          <select class="form-control" name="gender" placeholder="Gender">
                                            <option value="" disabled selected>Gender</option>
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

                                      <div class="form-group{{ ($errors->has('dob'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Date Of Birth</label>
                                        <div class="col-md-6">
                                          <input type="text" id="datetimepicker4" name="dob" placeholder="DOB" value="{{ old('dob') }}" class="form-control"/>
                                          @if ($errors->has('dob'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('dob') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('place_of_birth'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Place Of Birth</label>
                                        <div class="col-md-6">
                                          <input type="text" name="place_of_birth" placeholder="Place Of Birth" value="{{ old('place_of_birth') }}" class="form-control"/>
                                          @if ($errors->has('place_of_birth'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('place_of_birth') }}</strong>
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

                                      <div class="form-group{{ ($errors->has('last_school'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Last School</label>
                                        <div class="col-md-6">
                                          <input type="text" name="last_school" placeholder="Last School Attendent" value="{{ old('last_school') }}" class="form-control"/>
                                          @if ($errors->has('last_school'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('last_school') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('seeking_class'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Seeking Class</label>
                                        <div class="col-md-6">
                                          <input type="text" name="seeking_class" placeholder="Seeking Class" value="{{ old('seeking_class') }}" class="form-control"/>
                                          @if ($errors->has('seeking_class'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('seeking_class') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="alert alert-warning ">
                                        <p>
                                          <h4>Carefully! </h4>Once set class it will not be editable until session end.
                                        </p>

                                      <div class="form-group{{ ($errors->has('class'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Class</label>
                                        <div class="col-md-6 select2-div">
                                          <select class="form-control select2" name="class">
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

                                      <div class="form-group{{ ($errors->has('section'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Section</label>
                                        <div class="col-md-6 select2-div">
                                          <select class="form-control select2" name="section">
                                          <option value="" disabled selected>Section</option>
                                          </select>
                                          @if ($errors->has('section'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('section') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      </div>

                                      <div class="form-group{{ ($errors->has('gr_no'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">GR No</label>
                                        <div class="col-md-6">
                                          <input type="number" name="gr_no" placeholder="GR NO" value="{{ old('gr_no') }}" class="form-control" />
                                          @if ($errors->has('gr_no'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('gr_no') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('guardian'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">guardian</label>
                                        <div class="col-md-6">
                                          <select class="form-control" name="guardian">
                                            <option value="" disabled selected>Guardian</option>
                                            @foreach($guardians as $guardian)
                                              <option value="{{ $guardian->id }}">{{ $guardian->name.' | '.$guardian->email }}</option>
                                            @endforeach
                                          </select>
                                          @if ($errors->has('guardian'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('guardian') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('guardian_relation'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Guardian Relation</label>
                                        <div class="col-md-6">
                                          <input type="text" name="guardian_relation" placeholder="Guardian Relation" value="{{ old('guardian_relation') }}" class="form-control"/>
                                          @if ($errors->has('guardian_relation'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('guardian_relation') }}</strong>
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

                                      <div class="form-group{{ ($errors->has('doa'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Date Of Admission</label>
                                        <div class="col-md-6">
                                          <input type="text" id="datetimepicker5" name="doa" placeholder="Date of Admission" value="{{ old('doa') }}" class="form-control" required="true" />
                                          @if ($errors->has('doa'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('doa') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('receipt_no'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Receipt No</label>
                                        <div class="col-md-6">
                                          <input type="text" name="receipt_no" placeholder="Receipt NO" value="{{ old('receipt_no') }}" class="form-control" />
                                          @if ($errors->has('receipt_no'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('receipt_no') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="col-lg-8">
                                      <div class="panel panel-info">
                                      <div class="panel-heading">
                                        Additional Feeses <a href="#" id="addfee" data-toggle="tooltip" title="Add Fee" @click="addAdditionalFee()" style="color: #ffffff"><span class="fa fa-plus"></span></a>
                                      </div>
                                      <div class="panel-body">
                                      <table id="additionalfeetbl" class="table table-bordered table-hover table-striped">
                                        <thead>
                                          <tr>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Actions</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>Tuition Fee</td>
                                            <td>
                                              <div>
                                                <input type="number" name="tuition_fee" v-model.number="fee.tuition_fee" placeholder="Tuition Fee" min="1" class="form-control"/>
                                                @if ($errors->has('tuition_fee'))
                                                    <span class="help-block">
                                                        <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('tuition_fee') }}</strong>
                                                    </span>
                                                @endif
                                              </div>
                                            </td>
                                            <td></td>
                                          </tr>

                                            <tr v-for="(fee, k) in fee.additionalfee">
                                              <td><input type="hidden" :name="'fee['+k+'][id]'" value="0" ><input type="text" :name="'fee['+ k +'][fee_name]'" class="form-control" required="true" v-model="fee.fee_name"></td>
                                              <td><input type="number" :name="'fee['+ k +'][amount]'" class="form-control additfeeamount" required="true" min="1" v-model.number="fee.amount"></td>
                                              <td>
                                                <div class="input-group">
                                                  <span class="input-group-addon" data-toggle="tooltip" title="select if onetime charge">
                                                    <input type="checkbox" :name="'fee['+ k +'][onetime]'" value="1" :checked="fee.onetime">
                                                  </span>
                                                  <span class="input-group-addon" data-toggle="tooltip" title="Active">
                                                    <input type="checkbox" :name="'fee['+ k +'][active]'" value="1" :checked="fee.active" @click="fee.active = !fee.active">
                                                  </span>
                                                  <a href="javascript:void(0);" class="btn btn-default text-danger removefee" data-toggle="tooltip" @click="removeAdditionalFee(k)" title="Remove">
                                                    <span class="fa fa-trash"></span>
                                                  </a>
                                                </div>
                                              </td>
                                            </tr>

                                        </tbody>
                                        <tfoot>
                                          <tr>
                                            <th>Total</th>
                                            <th>@{{ total_amount }}</th>
                                            <th></th>
                                          </tr>
                                          <tr>
                                            <td>Discount</td>
                                            <td><input type="number" name="discount" class="form-control" placeholder="Discount" min="0" v-model.number="fee.discount"></td>
                                            <td></td>
                                          </tr>
                                          <tr>
                                            <th>Net Amount</th>
                                            <th>@{{ net_amount }}</th>
                                            <th></th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                      </div>
                                      </div>
                                      </div>
                                      <input type="hidden" name="net_amount" v-model="net_amount">
                                      <input type="hidden" name="total_amount" v-model="total_amount">

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

    <!-- Select2 -->
    <script src="{{ URL::to('src/js/plugins/select2/select2.full.min.js') }}"></script>

    <!-- require with bootstrap-datetimepicker -->
    <script src="{{ URL::to('src/js/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ URL::to('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>



    <script type="text/javascript">

    var tbl;
    var tr;

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

       opthtm = '<a href="{{ URL('students/profile') }}/'+full.id+'" data-toggle="tooltip" title="Profile" class="btn btn-'+((full.active == 1)? 'default' : 'danger') +' btn-circle btn-xs profile"><span class="fa fa-user"></span></a>';

        @if(Auth::user()->getprivileges->privileges->{$root['content']['id']}->edit)
          opthtm += '<a href="{{ URL('students/edit') }}/'+full.id+'" data-toggle="tooltip" title="Edit Student" class="btn btn-default btn-circle btn-xs"><span class="fa fa-edit"></span></a>';
        @endif

        return opthtm;
    }

      $(document).ready(function(){

        $('[data-toggle="tooltip"]').tooltip();

        $('#datetimepicker4').datetimepicker({
                 format: 'DD/MM/YYYY'
           });
        $('#datetimepicker5').datetimepicker({
                 format: 'DD/MM/YYYY'
           });

/*    For Column Search  */ 
/*        $('.dataTables-teacher tfoot th').each( function () {
            var title = $('.dataTables-teacher tfoot th').eq( $(this).index() ).text();
          if (title !== 'Options') {
            $(this).html( '<input type="text" placeholder="'+title+'" />' );
          }
        });
*/


    tbl =   $('.dataTables-teacher').DataTable({
          dom: '<"html5buttons"B>lTfgitp',
          buttons: [
          //  {extend: 'copy'},
          //  {extend: 'csv'},
          //  {extend: 'excel', title: 'Students List'},
          //  {extend: 'pdf', title: 'Students List'},

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
              title: "Students | {{ config('systemInfo.title') }}",
            }
          ],
          Processing: true,
          serverSide: true,
          ajax: '{{ URL('students') }}',
          columns: [
            {data: 'gr_no', name: 'students.gr_no'},
            {data: 'name', name: 'students.name'},
            {data: 'phone', name: 'students.phone'},
            {data: 'address', name: 'students.address'},
            {data: 'active', name: 'students.active', visible: false},
//            {"defaultContent": opthtm, className: 'hidden-print'},
            {render: loadOptions, className: 'hidden-print', "orderable": false},

          ],
          "order": [[1, "asc"]],
          "scrollY": "450px",
          "scrollX": true,
          "scrollCollapse": true,
          "paging": true,
/*          "columnDefs": [
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    return data +' ('+ row.section_nick +')';
                },
                "targets": 0
            },
            { "visible": false,  "targets": [ 1 ] }
          ]*/
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
        $("#filterActive").on('change', function(){
          search(4, this.value);
        });


/*    tbl.columns().every( function () {
        var that = this;
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        });
    });*/

      $('.dataTables-teacher tbody').on('mouseenter', '[data-toggle="tooltip"]', function() {
        $(this).tooltip('show');
      });

        $("#tchr_rgstr").validate({
            rules: {
              name: {
                required: true,
              },
              email: {
                email: true,
              },
              father_name: {
                required: true,
              },
              gender: {
                required: true,
              },
              class: {
                required: true,
              },
              section: {
                required: true,
              },
              guardian: {
                required: true,
              },
              guardian_relation: {
                required: true,
              },
              tuition_fee: {
                required: true,
              },
              dob: {
                required: true,
              },
              doa: {
                required: true,
              },
              gr_no: {
                required: true,
                number: true,
              },
            },
        });

      
      $('#tchr_rgstr [name="class"]').on('change', function(){
        clsid = $(this).val();
          $('#tchr_rgstr [name="section"]').html('');
          if(sections['class_'+clsid].length > 0){          
            $.each(sections['class_'+clsid], function(k, v){
              $('#tchr_rgstr [name="section"]').append('<option value="'+v['id']+'">'+v['name']+'</option>');
            });
          }
      });

      @if(COUNT($errors) >= 1)
      $('#tchr_rgstr [name="gender"]').val('{{ old('gender') }}');
      $('#tchr_rgstr [name="guardian"]').val('{{ old('guardian') }}');
      $('#tchr_rgstr [name="class"]').val("{{ old('class') }}");
      $('#tchr_rgstr [name="class"]').change();
      $('#tchr_rgstr [name="section"]').val('{{ old('section') }}');
      @endif

      $('#tchr_rgstr [name="guardian"]').attr('style', 'width:100%').select2({
                placeholder: "Nothing Selected",
                allowClear: true,
            });

      @if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
        $('.nav-tabs a[href="#tab-11"]').tab('show');
      @else
        $('.nav-tabs a[href="#tab-10"]').tab('show');
      @endif

      $("#imginp").change(function(){
          readURL(this);
      });

      @if(Auth::user()->getprivileges->privileges->{$root['content']['id']}->add == 0)
        $('.add-student').hide();
      @endif

      @if(Auth::user()->getprivileges->privileges->{$root['content']['id']}->edit == 0)
        $('.edit-student').hide();
      @endif
       
      });
    </script>

    @endsection

    @section('vue')
    <script type="text/javascript">
      var app = new Vue({
        el: '#app',
        data: { 
          fee: {
            additionalfee: {!! old('fee', config('feeses.additional_fee')) !!},
            tuition_fee: {{ old('tuition_fee', config('feeses.compulsory.tuition_fee')) }},
            discount:  {{ old('discount', 0) }},
          },
        },

        methods: {
          addAdditionalFee: function (){
            this.fee.additionalfee.push({
              id: 0,
              fee_name: '',
              amount: 0,
              active: 1,
              onetime: 1
            });
          },
          removeAdditionalFee: function(k){
            this.fee.additionalfee.splice(k, 1);
          }
        },

        computed: {
          total_amount: function(){
            tot_amount = Number(this.fee.tuition_fee);
            for(k in this.fee.additionalfee) { 
              if(this.fee.additionalfee[k].active){
                tot_amount += Number(this.fee.additionalfee[k].amount);
              }
            }
            return  tot_amount;
          },
          net_amount: function(){
            return Number(this.total_amount) - Number(this.fee.discount);
          }
        }
      });
    </script>
    @endsection
