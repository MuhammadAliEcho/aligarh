@extends('admin.layouts.master')

  @section('title', 'Edit Employee |')

  @section('head')
  <!-- Input Mask-->
  <link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">

  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('admin.includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Employee</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li>
                          <a>Employees</a>
                      </li>
                      <li Class="active">
                          <a>Edit</a>
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
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>Edit Employee</h2>
                        <div class="hr-line-dashed"></div>
                    </div>

                    <div class="ibox-content">

                                    <form id="tchr_rgstr" method="post" action="{{ URL('employee/edit/'.$employee['id']) }}" class="form-horizontal" enctype="multipart/form-data" >
                                      {{ csrf_field() }}

                                      <div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Name</label>
                                        <div class="col-md-6">
                                          <input type="text" name="name" placeholder="Name" value="{{ old('name', $employee['name']) }}" class="form-control"/>
                                          @if ($errors->has('name'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('name') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Religion</label>
                                        <div class="col-md-6">
                                          <input type="text" name="religion" placeholder="religion" value="{{ old('religion', $employee['religion']) }}" class="form-control"/>
                                          @if ($errors->has('religion'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('religion') }}</strong>
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
                                          <input type="hidden" name="removeImage" v-model="removeImage" />
                                          <template v-if="removeImage == 0">
                                            <button type="button" class="close" @click="removeImage = 1">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                            <img id="img" src="{{ ($employee->img_url == '')? '#' : URL($employee->img_url) }}"  alt="Item Image... 454" class="img-responsive img-thumbnail" style="max-width:100px !important;min-width:105px !important;"/>
                                          </template>
                                          <template v-if="removeImage">
                                            <img id="img" src=""  alt="Item Image..." class="img-responsive img-thumbnail" :style="{ maxWidth: '100px', minWidth: '105px' }"/>
                                          </template>
                                          @if ($errors->has('img'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('img') }}</strong>
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
                                          <input type="text" name="email" placeholder="E-Mail" value="{{ old('email', $employee['email']) }}" class="form-control"/>
                                          @if ($errors->has('email'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('email') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('role'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Employee Role</label>
                                        <div class="col-md-6">
                                          <input type="text" name="role" placeholder="Employee Role" value="{{ old('role', $employee['role']) }}" class="form-control"/>
                                          @if ($errors->has('role'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('role') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('qualification'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Qualification</label>
                                        <div class="col-md-6">
                                          <input type="text" name="qualification" placeholder="Qualification" value="{{ old('qualification', $employee['qualification']) }}" class="form-control"/>
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
                                          <textarea type="text" name="address" placeholder="Address" class="form-control">{{ old('address', $employee['address']) }}</textarea>
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('phone'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Contact No</label>
                                        <div class="col-md-6">
                                          <div class="input-group m-b">
                                            <span class="input-group-addon">+92</span>
                                            <input type="text" name="phone" value="{{ old('phone', $employee['phone']) }}" placeholder="Contact No" class="form-control" data-mask="9999999999"/>
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
                                          <input type="text" name="salary" value="{{ old('salary', $employee['salary']) }}" placeholder="Salary" class="form-control"/>
                                          @if ($errors->has('salary'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('salary') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('date_of_birth'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">DOB</label>
                                        <div class="col-md-6">
                                          <input id="date_of_birth" type="text" name="date_of_birth" value="{{ old('date_of_birth', $teacher['date_of_birth']) }}" placeholder="Date Of Birth" class="form-control"/>
                                          @if ($errors->has('date_of_birth'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('date_of_birth') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('id_card'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">ID:</label>
                                        <div class="col-md-6">
                                          <input type="text" name="id_card" value="{{ old('id_card', $teacher['id_card']) }}" placeholder="Enter ID CNIC/Passport etc..." class="form-control"/>
                                          @if ($errors->has('id_card'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('id_card') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('date_of_joining'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Date Of Joining</label>
                                        <div class="col-md-6">
                                          <input id="date_of_joining" type="text" name="date_of_joining" value="{{ old('date_of_joining', $teacher['date_of_joining']) }}" placeholder="Date Of Joining" class="form-control"/>
                                          @if ($errors->has('date_of_joining'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('date_of_joining') }}</strong>
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


          


        </div>

    @endsection

    @section('script')


    <script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- Input Mask-->
    <script src="{{ asset('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/moment/moment.min.js') }}"></script>

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}", "Validation Error");
            @endforeach
        </script>
    @endiript>

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

      $(document).ready(function(){

        $('#date_of_birth').datetimepicker({
            format: 'YYYY-MM-DD',
        });

        $('#date_of_joining').datetimepicker({
            format: 'YYYY-MM-DD',
            defaultDate: moment(),
        });

        $("#tchr_rgstr").validate({
            rules: {
              name: {
                required: true,
              },
              role: {
                required: true,
              },
              gender: {
                required: true,
              },
/*              qualification: {
                required: true,
              },
              email: {
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

        $('#tchr_rgstr [name="gender"]').val('{{ old('gender', $employee['gender']) }}');

      $("#imginp").change(function(){
          readURL(this);
      });

      });
    </script>

    @endsection
    @section('vue')
			<script type="text/javascript">
				var app = new Vue({
					el: '#app',
					data: {
						removeImage: {{ $employee->img_url? 0 : 1 }},
					},
				});
			</script>
		@endsection
