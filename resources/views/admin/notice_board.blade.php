@extends('admin.layouts.master')

  @section('title', 'Notice |')

  @section('head')

    <link href="{{ asset('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="{{ asset('src/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('admin.includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Notice Boards</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li Class="active">
                          <a>Notice</a>
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
                            <li>
                              <a data-toggle="tab" href="#tab-10"><span class="fa fa-clipboard"></span> Notices</a>
                            </li>
                            @can('noticeboard.create')
                              <li class="make-notice">
                                <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Create Notice</a>
                              </li>
                            @endcan
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade">
                                <div class="panel-body">
                                  <div class="jumbotron">
                                    <div class="container-fluid">

                                      <div class="wrapper wrapper-content animated fadeInUp">
                                          <ul class="notes">
                                          @foreach($notices As $notice)
                                              <li li-notice="{{ $notice->id }}">
                                                  <div>
                                                      <small>Till: {{ $notice->till_date }}</small>
                                                      <h4>{{ $notice->title }}</h4>
                                                      <p>{{ $notice->notice }}</p>
                                                      @can('noticeboard.delete')
                                                      <a class="delete-notice-btn delete-notice" notice-id="{{ $notice->id }}" href="#"><i class="fa fa-trash-o "></i></a>
                                                      @endcan
                                                  </div>
                                              </li>
                                          @endforeach
                                          </ul>
                                      </div>

                                    </div>
                                  </div>
                                </div>
                            </div>
                            @can('noticeboard.create')
                              <div id="tab-11" class="tab-pane fade make-notice">
                                  <div class="panel-body">
                                    <h2> Create Notice </h2>
                                    <div class="hr-line-dashed"></div>

                                      <form id="tchr_rgstr" method="POST" action="{{ URL('noticeboard/create') }}" class="form-horizontal" >
                                        {{ csrf_field() }}

                                        <div class="form-group{{ ($errors->has('title'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Title</label>
                                          <div class="col-md-6">
                                            <input type="text" name="title" placeholder="Title" value="{{ old('title') }}" class="form-control"/>
                                            @if ($errors->has('title'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('title') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group{{ ($errors->has('notice'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Notice</label>
                                          <div class="col-md-6">
                                            <textarea type="text" name="notice" placeholder="Notice" class="form-control">{{ old('notice') }}</textarea>
                                            @if ($errors->has('notice'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('notice') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group{{ ($errors->has('till_date'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label"> Till Time </label>
                                          <div class="col-md-6">
                                          <input id="datetimepicker4" type="text" name="till_date" class="form-control" placeholder="Date" value="{{ old('till_date') }}" required="true">
                                            @if ($errors->has('till_date'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('till_date') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-offset-2 col-md-6">
                                                <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Create </button>
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


    <script src="{{ asset('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

    <!-- require with bootstrap-datetimepicker -->
    <script src="{{ asset('src/js/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

    <!-- Sweet alert -->
    <script src="{{ asset('src/js/plugins/sweetalert/sweetalert.min.js') }}"></script>


    <script type="text/javascript">

        function alert_remove(){
          if ($('.alert').length > 0) {
            $(".alert").delay(5000).addClass("in").fadeOut(3500, function() {   
              $(this).remove();
            });
          }
        }

      $(document).ready(function(){

      @if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
        $('a[href="#tab-11"]').tab('show');
      @else
        $('a[href="#tab-10"]').tab('show');
      @endif

        $("#tchr_rgstr").validate({
            rules: {
              title: {
                required: true,
              },
              notice: {
                required: true,
              },
              till_date: {
                required: true,
              },
            },
        });

      $('#datetimepicker4').datetimepicker({
        format: 'DD/MM/YYYY'
      });

        $('.delete-notice-btn').click(function(){
          var noticeid = $(this).attr('notice-id');
          swal({
                title: "Are you sure?",
                text: "Sure You Want to Delete This Notice",
                type: "warning",
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: false,
                closeOnCancel: false,
              },
            function (isConfirm) {
                if (isConfirm) {
                      swal({ title: "Wait....",   text: "<i class='fa fa-spinner fa-pulse fa-4x' ></i>",   html: true, showConfirmButton: false });
                      $.post( "{{ URL('noticeboard/delete') }}", { id: noticeid, _token: "{{ csrf_token() }}" })
                        .done(function( data ) {
                          $('[li-notice="'+noticeid+'"]').remove();
                          swal("Deleted!", "Notice has been deleted.", "success");
                      })
                        .fail(function(){
                          swal("Error!", "Notice notice is safe :)", "error");
                        });
//                    swal("Deleted!", "Your imaginary file has been deleted.", "success");
                } else {
                  swal("Cancelled!", "Notice notice is safe :)", "error");
                }
            });
        });

        $('#sms_frm').submit(function(e){
            e.preventDefault();
            var $btn = $("#sms_frm button:submit").button('loading');
            $.ajax({
            type: $(this).attr('method'),
            url:  $(this).attr('action'),
            data: $(this).serialize(),
              success: function(data){
                $('#alert').append(data);
                alert_remove();
                $btn.button('reset');
              },
            error: function(){
              alert("Request failure");
              $btn.button('reset');
              }
            });

          });
      });
    </script>

    @endsection
