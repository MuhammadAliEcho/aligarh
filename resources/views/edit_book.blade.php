@extends('layouts.master')

  @section('title', 'Edit Book |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
  @endsection

  @section('content')

  @include('includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Library</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li>
                          <a>Books</a>
                      </li>
                      <li Class="active">
                          <a>Edit</a>
                      </li>
                  </ol>
              </div>
              <div class="col-lg-4 col-md-6">
                @include('includes.academic_session')
              </div>
          </div>

          <!-- main Section -->

          <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row ">
               <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>Edit Book</h2>
                        <div class="hr-line-dashed"></div>
                    </div>

                    <div class="ibox-content">

                                    <form id="tchr_rgstr" method="post" action="{{ URL('library/edit/'.$book['id']) }}" class="form-horizontal" >
                                      {{ csrf_field() }}

                                      <div class="form-group{{ ($errors->has('title'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Title</label>
                                        <div class="col-md-6">
                                          <input type="text" name="title" placeholder="Book Title" value="{{ old('title', $book['title']) }}" class="form-control"/>
                                          @if ($errors->has('title'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('title') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('author'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Author</label>
                                        <div class="col-md-6">
                                          <input type="text" name="author" placeholder="author" value="{{ old('author', $book['author']) }}" class="form-control"/>
                                          @if ($errors->has('author'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('author') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('edition'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Edition</label>
                                        <div class="col-md-6">
                                          <input type="text" name="edition" placeholder="edition" value="{{ old('edition', $book['edition']) }}" class="form-control"/>
                                          @if ($errors->has('edition'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('edition') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('publisher'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Publisher</label>
                                        <div class="col-md-6">
                                          <input type="text" name="publisher" placeholder="publisher" value="{{ old('publisher', $book['publisher']) }}" class="form-control"/>
                                          @if ($errors->has('publisher'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('publisher') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('qty'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Qty</label>
                                        <div class="col-md-6">
                                          <input type="number" name="qty" placeholder="Qty" value="{{ old('qty', $book['qty']) }}" class="form-control"/>
                                          @if ($errors->has('qty'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('qty') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('rate'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Rate</label>
                                        <div class="col-md-6">
                                          <input type="number" name="rate" placeholder="Rate" value="{{ old('rate', $book['rate']) }}" class="form-control"/>
                                          @if ($errors->has('rate'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('rate') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('description'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Description</label>
                                        <div class="col-md-6">
                                          <textarea name="description" placeholder="description" class="form-control">{{ old('description', $book['description']) }}</textarea>
                                          @if ($errors->has('description'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('description') }}</strong>
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


          @include('includes.footercopyright')


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
              title: {
                required: true,
              },
              qty: {
                required: true,
              },
            },
        });

      });
    </script>

    @endsection
