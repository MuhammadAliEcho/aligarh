@extends('admin.layouts.master')

  @section('title', 'Edit Teacher |')

  @section('head')
  <!-- Input Mask-->
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
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
                      <li>
                          <a>Teachers</a>
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
                        <h2>Edit Teacher</h2>
                        <div class="hr-line-dashed"></div>
                    </div>

                    <div class="ibox-content">

                                    <form id="tchr_rgstr" method="post" action="{{ URL('teacher/edit/'.$teacher['id']) }}" class="form-horizontal" enctype="multipart/form-data" >
                                      {{ csrf_field() }}

                                      <div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Name</label>
                                        <div class="col-md-6">
                                          <input type="text" name="name" placeholder="Name" value="{{ old('name', $teacher['name']) }}" class="form-control"/>
                                          @if ($errors->has('name'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('name') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Relegion</label>
                                        <div class="col-md-6">
                                          <input type="text" name="relegion" placeholder="Relegion" value="{{ old('relegion', $teacher['relegion']) }}" class="form-control"/>
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
                                          <input type="text" name="f_name" placeholder="Father Name" value="{{ old('f_name', $teacher->f_name) }}" class="form-control"/>
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
                                          <img id="img" src="{{ ($teacher->image_url == '')? '#' : URL($teacher->image_url) }}"  alt="Item Image..." class="img-responsive img-thumbnail" />
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
                                          <input type="text" name="husband_name" placeholder="Husband Name" value="{{ old('husband_name', $teacher->husband_name) }}" class="form-control"/>
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
                                          <input type="text" name="subject" placeholder="Subject" value="{{ old('subject', $teacher->subject) }}" class="form-control"/>
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
                                          <input type="text" name="email" placeholder="E-Mail" value="{{ old('email', $teacher['email']) }}" class="form-control"/>
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
                                          <input type="text" name="qualification" placeholder="Qualification" value="{{ old('qualification', $teacher['qualification']) }}" class="form-control"/>
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
                                          <textarea type="text" name="address" placeholder="Address" class="form-control">{{ old('address', $teacher['address']) }}</textarea>
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('phone'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Contact No</label>
                                        <div class="col-md-6">
                                          <div class="input-group m-b">
                                            <span class="input-group-addon">+92</span>
                                            <input type="text" name="phone" value="{{ old('phone', $teacher['phone']) }}" placeholder="Contact No" class="form-control" data-mask="9999999999"/>
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
                                          <input type="text" name="salary" value="{{ old('salary', $teacher['salary']) }}" placeholder="Salary" class="form-control"/>
                                          @if ($errors->has('salary'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('salary') }}</strong>
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


          @include('admin.includes.footercopyright')


        </div>

    @endsection

    @section('script')


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

      $(document).ready(function(){

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

        $('#tchr_rgstr [name="gender"]').val('{{ old('gender', $teacher['gender']) }}');

      $("#imginp").change(function(){
          readURL(this);
      });

      });
    </script>

    @endsection
