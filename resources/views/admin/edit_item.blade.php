@extends('admin.layouts.master')

  @section('title', __('modules.pages_edit_item_title').' |')

  @section('head')
  <link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
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
                      <li>
                          <a>Items</a>
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
                        <h2>{{ __('modules.forms_edit_item') }}</h2>
                        <div class="hr-line-dashed"></div>
                    </div>

                    <div class="ibox-content">

                                    <form id="tchr_rgstr" method="post" action="{{ URL('items/edit/'.$item['id']) }}" class="form-horizontal" >
                                      {{ csrf_field() }}

                                      <div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">{{ __('modules.labels_item_name') }}</label>
                                        <div class="col-md-6">
                                          <input type="text" name="name" placeholder="{{ __('modules.labels_item_name') }}" value="{{ old('name', $item['name']) }}" class="form-control"/>
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
                                          <input type="text" name="category" placeholder="Category" value="{{ old('category', $item['category']) }}" class="form-control"/>
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
                                          <input type="text" name="qty" placeholder="Qty" value="{{ old('qty', $item['qty']) }}" class="form-control"/>
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
                                          <input type="text" name="qty_level" placeholder="Qty level" value="{{ old('qty_level', $item['qty_level']) }}" class="form-control"/>
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
                                          <input type="text" name="location" placeholder="Location" value="{{ old('location', $item['location']) }}" class="form-control"/>
                                          @if ($errors->has('location'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('location') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group">
                                          <div class="col-md-offset-2 col-md-6">
                                              <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> {{ __('modules.buttons_save') }} </button>
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

    <script type="text/javascript">

      $(document).ready(function(){

        $("#tchr_rgstr").validate({
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

      });
    </script>

    @endsection
