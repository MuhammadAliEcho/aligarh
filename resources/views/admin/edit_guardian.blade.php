@extends('admin.layouts.master')

  @section('title', 'Edit Guardian |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
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
                      <li>
                          <a>Guardians</a>
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
                        <h2>Edit Guardian</h2>
                        <div class="hr-line-dashed"></div>
                    </div>

                    <div class="ibox-content">

                                    <form id="tchr_rgstr" method="post" action="{{ URL('guardians/edit/'.$guardian['id']) }}" class="form-horizontal" >
                                      {{ csrf_field() }}

                                      <div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Name</label>
                                        <div class="col-md-6">
                                          <input type="text" name="name" placeholder="Name" value="{{ old('name', $guardian['name']) }}" class="form-control"/>
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
                                          <input type="text" name="email" placeholder="E-Mail" value="{{ old('email', $guardian['email']) }}" class="form-control"/>
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
                                          <input type="text" name="profession" placeholder="Profession" value="{{ old('profession', $guardian['profession']) }}" class="form-control"/>
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
                                          <textarea type="text" name="address" placeholder="Address" class="form-control">{{ old('address', $guardian['address']) }}</textarea>
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('phone'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Contact No</label>
                                        <div class="col-md-6">
                                          <div class="input-group m-b">
                                            <span class="input-group-addon">+92</span>
                                            <input type="text" name="phone" value="{{ old('phone', $guardian['phone']) }}" placeholder="Contact No" class="form-control" data-mask="9999999999"/>
                                          </div>
                                          @if ($errors->has('phone'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('phone') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('income'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">income</label>
                                        <div class="col-md-6">
                                          <input type="text" name="income" value="{{ old('income', $guardian['income']) }}" placeholder="Income" class="form-control"/>
                                          @if ($errors->has('income'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('income') }}</strong>
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


    <script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- Input Mask-->
     <script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

    <script type="text/javascript">

      $(document).ready(function(){

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

      });
    </script>

    @endsection
