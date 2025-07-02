@extends('admin.layouts.master')

  @section('title', 'Vouchers |')

  @section('head')
  <!-- HEAD -->
  @endsection

  @section('content')

  @include('admin.includes.side_navbar')

        <div id="page-wrapper" class="gray-bg">

          @include('admin.includes.top_navbar')

          <!-- Heading -->
          <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-8 col-md-6">
                  <h2>Vouchers</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                    <li><a href="{{ URL('vouchers') }}"> Voucher </a></li>
                      <li Class="active">
                          <a>Detail</a>
                      </li>
                      <li Class="active">
                        <strong>
                          {{ $voucher->id }}
                        </strong>
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
                        <h2>Voucher Details</h2>
                        <div class="hr-line-dashed"></div>
                    </div>

                    <div class="ibox-content">

                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <th>Vendor :</th>
                                        <td>{{ $voucher->Vendor->v_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Voucher Date :</th>
                                        <td>{{ $voucher->voucher_date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Voucher No :</th>
                                        <td>{{ $voucher->voucher_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Net Amount :</th>
                                        <td>{{ $voucher->net_amount }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <h3>DETAILS</h3>
                            <table class="table table-hover">
                                <thead>
                                  <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Qty</th>
                                    <th>Rate</th>
                                  </tr>
                                </thead>

                                <tbody>
                                  @foreach($voucher->Details AS $detail)
                                    <tr>
                                        <th>{{ $detail->Item->name }}</th>
                                        <th>{{ $detail->Item->category }}</th>
                                        <th>{{ $detail->rate }}</th>
                                        <th>{{ $detail->qty }}</th>
                                    </tr>
                                  @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

          </div>

          @include('admin.includes.footercopyright')


        </div>

    @endsection

