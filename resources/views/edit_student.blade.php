@extends('layouts.master')

  @section('title', 'Students |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
  <script type="text/javascript">
      var sections = {!! json_encode($sections) !!};
  </script>
  @endsection

  @section('content')

  @include('includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('includes.top_navbar')

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
                @include('includes.academic_session')
              </div>
          </div>

          <!-- main Section -->

          <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row">
               <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>Edit Student</h2>
                        <div class="hr-line-dashed"></div>
                    </div>

                    <div class="ibox-content">

                                    <form id="tchr_rgstr" method="post" action="{{ URL('students/edit/'.$student->id) }}" class="form-horizontal" enctype="multipart/form-data">
                                      {{ csrf_field() }}

                                      <div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Name</label>
                                        <div class="col-md-6">
                                          <input type="text" name="name" placeholder="Name" value="{{ old('name', $student->name) }}" class="form-control"/>
                                          @if ($errors->has('name'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('name') }}</strong>
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
                                          <input type="text" id="datetimepicker4" name="dob" placeholder="DOB" value="{{ old('dob', $student->date_of_birth) }}" class="form-control"/>
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
                                          <input type="text" name="place_of_birth" placeholder="Place Of Birth" value="{{ old('place_of_birth', $student->place_of_birth) }}" class="form-control"/>
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
                                          <input type="text" name="relegion" placeholder="Relegion" value="{{ old('relegion', $student->relegion) }}" class="form-control"/>
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
                                          <img id="img" src="{{ URL($student->image_url) }}"  alt="Item Image..." class="img-responsive img-thumbnail" />
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
                                          <input type="text" name="last_school" placeholder="Last School Attendent" value="{{ old('last_school', $student->last_school) }}" class="form-control"/>
                                          @if ($errors->has('last_school'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('last_school') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

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
                                          </select>
                                          @if ($errors->has('section'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('section') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('gr_no'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">GR No</label>
                                        <div class="col-md-6">
                                          <input type="text" name="gr_no" placeholder="GR NO" value="{{ old('gr_no', substr($student->gr_no, 3)) }}" class="form-control" />
                                          @if ($errors->has('gr_no'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('gr_no') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('parent'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Parent</label>
                                        <div class="col-md-6">
                                          <select class="form-control" name="parent">
                                            <option></option>
                                            @foreach($parents as $parent)
                                              <option value="{{ $parent->id }}">{{ $parent->name.' | '.$parent->email }}</option>
                                            @endforeach
                                          </select>
                                          @if ($errors->has('parent'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('parent') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('parent_relation'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Parent Relation</label>
                                        <div class="col-md-6">
                                          <input type="text" name="parent_relation" placeholder="Parent Relation" value="{{ old('parent_relation', $student->parent_relation) }}" class="form-control"/>
                                          @if ($errors->has('parent_relation'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('parent_relation') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('email'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">E-Mail</label>
                                        <div class="col-md-6">
                                          <input type="text" name="email" placeholder="E-Mail" value="{{ old('email', $student->email) }}" class="form-control"/>
                                          @if ($errors->has('email'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('email') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group">
                                        <label class="col-md-2 control-label">Address</label>
                                        <div class="col-md-6">
                                          <textarea type="text" name="address" placeholder="Address" class="form-control">{{ old('address', $student->address) }}</textarea>
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('phone'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Contact No</label>
                                        <div class="col-md-6">
                                          <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" placeholder="Contact No" class="form-control" data-mask="(999) 999-9999"/>
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
                                          <input type="text" id="datetimepicker5" name="doa" placeholder="Date Of Admission" value="{{ old('doa', $student->date_of_admission) }}" class="form-control"/>
                                          @if ($errors->has('doa'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('doa') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="col-lg-8">
                                      <div class="panel panel-info">
                                      <div class="panel-heading">
                                        Additional Feeses <a href="#" id="addfee" data-toggle="tooltip" title="Add Fee" style="color: #ffffff"><span class="fa fa-plus"></span></a>
                                      </div>
                                      <div class="panel-body">
                                      <table id="additionalfeetbl" class="table table-bordered table-hover table-striped">
                                        <thead>
                                          <tr>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Remove</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>Tuition Fee</td>
                                            <td>
                                              <div>
                                                <input type="number" name="tuition_fee" value="{{ old('tuition_fee', $student->tuition_fee) }}" placeholder="Tuition Fee" class="form-control"/>
                                                @if ($errors->has('tuition_fee'))
                                                    <span class="help-block">
                                                        <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('tuition_fee') }}</strong>
                                                    </span>
                                                @endif
                                              </div>
                                            </td>
                                            <td></td>
                                          </tr>
                                          @if(COUNT(old('fee')) >= 1)
                                          @foreach(old('fee') AS $k=>$v)
                                            <tr>
                                              <td><input type="text" name="fee[{{ $k }}][feename]" class="form-control" required="true" value="{{ $v['feename'] }}"></td>
                                              <td><input type="number" name="fee[{{ $k }}][feeamount]" class="form-control additfeeamount" required="true" value="{{ $v['feeamount'] }}"></td>
                                              <td><a href="javascript:void(0);" class="btn btn-default text-danger removefee" data-toggle="tooltip" title="Remove" ><span class="fa fa-trash"></span></a></td>
                                            </tr>
                                          @endforeach
                                          @else
                                            @foreach($additional_fee AS $fee)
                                            <tr>
                                              <td><input type="text" name="fee[{{ $fee->id }}][feename]" class="form-control" required="true" value="{{ $fee->fee_name }}"></td>
                                              <td><input type="number" name="fee[{{ $fee->id }}][feeamount]" class="form-control additfeeamount" required="true" value="{{ $fee->amount }}"></td>
                                              <td><a href="javascript:void(0);" class="btn btn-default text-danger removefee" data-toggle="tooltip" title="Remove" ><span class="fa fa-trash"></span></a></td>
                                            </tr>
                                            @endforeach                                          
                                          @endif
                                        </tbody>
                                        <tfoot>
                                          <tr>
                                            <td>Discount</td>
                                            <td><input type="number" name="discount" class="form-control" value="{{ old('discount', $student->discount) }}" placeholder="Discount" ></td>
                                            <td></td>
                                          </tr>
                                          <tr>
                                            <th>Total</th>
                                            <th id="total">{{ old('net_amount', $student->net_amount) }}</th>
                                            <th></th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                      </div>
                                      </div>
                                      </div>
                                      <input type="hidden" name="total_amount" value="{{ old('total_amount', $student->total_amount) }}">
                                      <input type="hidden" name="net_amount" value="{{ old('net_amount', $student->net_amount) }}">


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


          @include('includes.footercopyright')


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

    var tr;
    no = 1;

    function Calc(){
//      alert();
      tuition_fee =  Number($('[name="tuition_fee"]').val());
      discount =  Number($('[name="discount"]').val());
      additfeeamount = 0;
      $('.additfeeamount').each(function(){
        additfeeamount = additfeeamount + Number($(this).val());
      });
      total = tuition_fee + additfeeamount;
      net_amount = total - discount;
      $('#total').text(net_amount);
      $('[name="net_amount"]').val(net_amount);
      $('[name="total_amount"]').val(total);
    }

    function readURL(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
              $('#img').attr('src', e.target.result);
          }
          reader.readAsDataURL(input.files[0]);
      }
    }

      $(document).ready(function(){

        $('[data-toggle="tooltip"]').tooltip();

        $('#datetimepicker4').datetimepicker({
                 format: 'DD/MM/YYYY'
           });
        $('#datetimepicker5').datetimepicker({
                 format: 'DD/MM/YYYY'
           });

        $("#tchr_rgstr").validate({
            rules: {
              name: {
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
              parent: {
                required: true,
              },
              parent_relation: {
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

      $('#addfee').click(function(){

        tr = '<tr>';
        tr  += '<td><input type="text" name="fee['+no+'][feename]" class="form-control" required="true"></td>';
        tr  += '<td><input type="number" name="fee['+no+'][feeamount]" class="form-control additfeeamount" required="true"></td>';
        tr  += '<td><a href="javascript:void(0);" class="btn btn-default text-danger removefee" data-toggle="tooltip" title="Remove" ><span class="fa fa-trash"></span></a></td>';
        tr += '</tr>';

        $('#additionalfeetbl tbody').append(tr);
        $('.removefee').click(function(){
          $(this).parents('tr').remove();
        });
        $('[data-toggle="tooltip"]').tooltip();
        $('.additfeeamount').on("keyup change", function(){
          Calc();
        });
        no = no+1;
      });

      $('.removefee').click(function(){
        $(this).parents('tr').remove();
        Calc();
      });

      $('[name="discount"]').on("keyup change", function(){
        Calc();
      });

      $('[name="tuition_fee"]').on("keyup change", function(){
        Calc();
      });
      
      $('.additfeeamount').on("keyup change", function(){
        Calc();
      });

      $('#tchr_rgstr [name="gender"]').val('{{ old('gender', $student->gender) }}');
      $('#tchr_rgstr [name="parent"]').val('{{ old('parent', $student->parent_id) }}');
      $('#tchr_rgstr [name="class"]').val("{{ old('class', $student->class_id) }}");
      $('#tchr_rgstr [name="class"]').change();
      $('#tchr_rgstr [name="section"]').val('{{ old('section', $student->section_id) }}');

      $('#tchr_rgstr [name="parent"]').attr('style', 'width:100%').select2({
                placeholder: "Nothing Selected",
                allowClear: true,
            });


      $("#imginp").change(function(){
          readURL(this);
      });



      });

    </script>

    @endsection