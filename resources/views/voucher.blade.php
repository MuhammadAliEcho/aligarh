@extends('layouts.master')

  @section('title', 'Vouchers |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
  @endsection

  @section('content')

  @include('includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Vouchers</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li Class="active">
                          <a>Vouchers</a>
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
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class="">
                              <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Vouchers</a>
                            </li>

                            <li class="add-voucher">
                              <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Add Vouchers</a>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade">
                                <div class="panel-body">
                                  <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover dataTables-Voucher" >
                                      <thead>
                                        <tr>
                                          <th>ID</th>
                                          <th>Voucher No</th>
                                          <th>Vendor</th>
                                          <th>Net Amount</th>
                                          <th>Options</th>
                                        </tr>
                                      </thead>
                                      <tfoot>
                                        <tr>
                                          <th>ID</th>
                                          <th>Voucher No</th>
                                          <th>Vendor</th>
                                          <th>Net Amount</th>
                                          <th>Options</th>
                                        </tr>
                                      </tfoot>
                                    </table>
                                  </div>

                                </div>
                            </div>
                            <div id="tab-11" class="tab-pane fade add-voucher">
                                <div class="panel-body">
                                  <h2> Add Voucher </h2>
                                  <div class="hr-line-dashed"></div>

                                    <form id="vchr_rgstr" method="post" action="{{ URL('vouchers/add') }}" class="form-horizontal" >
                                      {{ csrf_field() }}
                                      
                                      <input type="hidden" name="net_amount">

                                      <div class="form-group{{ ($errors->has('vendor'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Vendor</label>
                                        <div class="col-md-6">
                                          <select class="form-control" name="vendor">
                                            <option value="" disabled selected>Vendor</option>
                                            @foreach($vendors as $vendor)
                                              <option value="{{ $vendor->id }}">{{ $vendor->v_name.' | '.$vendor->email }}</option>
                                            @endforeach
                                          </select>
                                          @if ($errors->has('vendor'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('vendor') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('voucher_date'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Voucher Date</label>
                                        <div class="col-md-6">
                                          <input type="text" id="datetimepicker4" name="voucher_date" placeholder="Voucher Date" value="{{ old('voucher_date') }}" class="form-control"/>
                                          @if ($errors->has('voucher_date'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('voucher_date') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="form-group{{ ($errors->has('voucher_no'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">Voucher No</label>
                                        <div class="col-md-6">
                                          <input type="text" name="voucher_no" placeholder="Voucher No" value="{{ old('voucher_no') }}" class="form-control" />
                                          @if ($errors->has('voucher_no'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('voucher_no') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                      </div>

                                      <div class="row">
                                      <div class="panel panel-info">
                                      <div class="panel-heading">
                                        Items <a href="#" id="additemrow" data-toggle="tooltip" title="Add Items" style="color: #ffffff"><span class="fa fa-plus"></span></a>
                                      </div>
                                      <div class="panel-body">
                                      <table id="additionalfeetbl" class="table table-bordered table-hover table-striped">
                                        <thead>
                                          <tr>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Qty</th>
                                            <th>Rate</th>
                                            <th>Remove</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                              <td>
                                                <select class="form-control item" onchange="ItemChanged(this)" name="items[1][id]" required="true">
                                                  <option value="" disabled selected>Items</option>
                                                  @foreach($items as $item)
                                                    <option value="{{ $item->id }}" category="{{ $item->category }}">{{ $item->name.' | '.$item->category }}</option>
                                                  @endforeach
                                                </select>
                                              </td>
                                              <td>
                                                <input type="text" placeholder="Category" class="form-control category" disabled="true">
                                              </td>
                                              <td>
                                                <input type="number" name="items[1][qty]" placeholder="Qty" onchange="Calc()" class="form-control qty" required="true">
                                              </td>
                                              <td>
                                                <input type="number" name="items[1][rate]" placeholder="Rate" onchange="Calc()" class="form-control rate" required="true">
                                              </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                          <tr>
                                            <th>Total</th>
                                            <th></th>
                                            <th></th>
                                            <th id="net_amount">{{ old('net_amount') }}</th>
                                            <th></th>
                                          </tr>
                                        </tfoot>
                                      </table>
                                      </div>
                                      </div>
                                      </div>

                                      <div class="form-group">
                                          <div class="col-md-offset-2 col-md-4">
                                              <button class="btn btn-primary btn-block" type="submit"><span class="glyphicon glyphicon-save"></span> Save </button>
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

    var tbl;
    var tr;
    no = 1;

    function ItemChanged(d){
      $(d).parents('tr').find('.category').val($(d).find(':selected').attr('category'));
    }

    function Calc(){
      net_amount   = 0;
      $('#additionalfeetbl tbody tr').each(function(k, v){
        qty =  Number($(this).find('.qty').val());
        rate =  Number($(this).find('.rate').val());
        net_amount = net_amount+(qty*rate);
      });
      $('#net_amount').text(net_amount);
      $('[name="net_amount"]').val(net_amount);
    }

    function loadOptions(data, type, full, meta) {

       opthtm = '<a href="{{ URL('vouchers/details') }}/'+full.id+'" data-toggle="tooltip" title="Detail" class="btn btn-default btn-circle btn-xs profile"><span class="fa fa-eye"></span></a>';

        @if(Auth::user()->privileges->{$root['content']['id']}->edit)
          opthtm += '<a href="{{ URL('vouchers/edit') }}/'+full.id+'" data-toggle="tooltip" title="Edit Voucher" class="btn btn-default btn-circle btn-xs"><span class="fa fa-edit"></span></a>';
        @endif

        return opthtm;
    }

      $(document).ready(function(){

        $('[data-toggle="tooltip"]').tooltip();

        $('#datetimepicker4').datetimepicker({
                 format: 'DD/MM/YYYY'
           });

/*    For Column Search  */ 
/*        $('.dataTables-Voucher tfoot th').each( function () {
            var title = $('.dataTables-Voucher tfoot th').eq( $(this).index() ).text();
          if (title !== 'Options') {
            $(this).html( '<input type="text" placeholder="'+title+'" />' );
          }
        });
*/

    tbl =   $('.dataTables-Voucher').DataTable({
          dom: '<"html5buttons"B>lTfgitp',
          buttons: [
            {extend: 'copy'},
            {extend: 'csv'},
            {extend: 'excel', title: 'Students List'},
            {extend: 'pdf', title: 'Students List'},

            {extend: 'print',
              customize: function (win){
                $(win.document.body).addClass('white-bg');
                $(win.document.body).css('font-size', '10px');

                $(win.document.body).find('table')
                .addClass('compact')
                .css('font-size', 'inherit');
              },
              exportOptions: {
                columns: [ 0, 1, 2, 3]
              }
            }
          ],
          Processing: true,
          serverSide: true,
          ajax: '{{ URL('ajax/vouchers') }}',
          columns: [
            {data: 'id', name: 'vouchers.id'},
            {data: 'voucher_no', name: 'vouchers.voucher_no'},
            {data: 'v_name', name: 'vendors.v_name'},
            {data: 'net_amount', name: 'vouchers.net_amount'},
//            {"defaultContent": opthtm, className: 'hidden-print'},
            {render: loadOptions, className: 'hidden-print', "orderable": false},

          ],
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

/*    for Column search
        tbl.columns().eq( 0 ).each( function ( colIdx ) {
            $( 'input', tbl.column( colIdx ).header() ).on( 'keyup change', function () {
                tbl
                    .column( colIdx )
                    .search( this.value )
                    .draw();
            });
        });*/

      $('.dataTables-Voucher tbody').on( 'mouseenter', '[data-toggle="tooltip"]', function () {
        $(this).tooltip('show');
      });

        $("#vchr_rgstr").validate({
            rules: {
              vendor: {
                required: true,
              },
              voucher_no: {
                required: true,
              },
              voucher_date: {
                required: true,
              },
            },
        });


      $('#additemrow').click(function(){
        var $row = $('#additionalfeetbl tbody').children('tr:first').html();
        $row  += '<td><a href="javascript:void(0);" class="btn btn-default text-danger remove" data-toggle="tooltip" title="Remove" ><span class="fa fa-trash"></span></a></td>';
//        console.log($row);

        $('#additionalfeetbl tbody').append('<tr>'+$row+'</tr>');
        $('.remove').click(function(){
          $(this).parents('tr').remove();
          Calc();
        });
        $('[data-toggle="tooltip"]').tooltip();

        $("input.qty").each(function(k, v){
          $("select.item").eq(k).attr('name', 'items['+(k+1)+'][id]');
          $(this).attr('name', 'items['+(k+1)+'][qty]');
          $("input.rate").eq(k).attr('name', 'items['+(k+1)+'][rate]');
        });

        no = no+1;
      });

      $('.remove').click(function(){
        $(this).parents('tr').remove();
        Calc();
      });

      @if(COUNT($errors) >= 1)
        $('#vchr_rgstr [name="vendor"]').val('{{ old('vendor') }}');
      @endif

      $('#vchr_rgstr [name="vendor"]').attr('style', 'width:100%').select2({
                placeholder: "Nothing Selected",
                allowClear: true,
            });

      @if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
        $('.nav-tabs a[href="#tab-11"]').tab('show');
      @else
        $('.nav-tabs a[href="#tab-10"]').tab('show');
      @endif


      @if(Auth::user()->privileges->{$root['content']['id']}->add == 0)
        $('.add-voucher').hide();
      @endif


      });
    </script>

    @endsection
