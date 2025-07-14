@extends('admin.layouts.master')

  @section('title', 'Employee |')

  @section('head')
  <link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
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
  <style>
    .employee-card {
        background: #ffffff;
        border: none;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        margin-bottom: 30px;
    }

    .employee-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15), 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background: linear-gradient(135deg, #009486 0%, #1ab394 100%);
        height: 70px;
        position: relative;
        /* overflow: hidden; */
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.15"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    }

    .profile-image-container {
        position: absolute;
        bottom: -20px;
        left: 50%;
        transform: translateX(-50%);
        /* z-index: 10; */
    }

    .profile-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 5px solid #ffffff;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        object-fit: cover;
    }

    .employee-card:hover .profile-image {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
    }

    .card-body {
        padding: 60px 25px 25px;
        text-align: center;
    }

    .employee-name {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 8px;
        letter-spacing: -0.5px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 25px;
    }

    .status-active {
        background: linear-gradient(135deg, #00b894, #00cec9);
        color: white;
    }

    .status-inactive {
        background: linear-gradient(135deg, #e17055, #fdcb6e);
        color: white;
    }

    .info-divider {
        border: none;
        height: 2px;
        background: linear-gradient(90deg, transparent, #667eea, transparent);
        margin: 0px 0;
    }

    .info-list {
        text-align: left;
        margin: 0;
        padding: 0;
    }

    .info-item {
        display: flex;
        align-items: center;
        padding: 7px 0;
        border-bottom: 1px solid #f8f9fa;
        transition: all 0.3s ease;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-item:hover {
        background: rgba(102, 126, 234, 0.05);
        padding-left: 10px;
        border-radius: 8px;
    }

    .info-icon {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 16px;
        color: white;
        flex-shrink: 0;
    }

    .icon-education { background: linear-gradient(135deg, #667eea, #764ba2); }
    .icon-id { background: linear-gradient(135deg, #00cec9, #55a3ff); }
    .icon-gender { background: linear-gradient(135deg, #fd79a8, #fdcb6e); }
    .icon-fee { background: linear-gradient(135deg, #00b894, #55efc4); }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 12px;
        color: #74b9ff;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .info-value {
        font-size: 15px;
        color: #2d3436;
        font-weight: 600;
    }

    .fee-amount {
        color: #00b894;
        font-weight: 700;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .employee-card {
            margin: 0 10px 20px;
        }
        
        .card-body {
            padding: 50px 20px 20px;
        }
    }

    .employee-card {
        animation: fadeInUp 0.6s ease-out;
    }

    .employee-card {
      width: 250px;
    }

    .profile-image {
      width: 80px;
      height: 80px;
    }

    .card-body {
      padding: 25px 20px 10px;
    }

    .employee-name {
      font-size: 18px;
    }

    .info-value {
      font-size: 10px;
    }

    .status-badge {
      font-size: 10px;
      padding: 4px 12px;
    }

    .m-2{
      margin: 2rem;
    }
    .pagination nav {
        width: 100%;
        display: flex;
        justify-content: center;
    }

    .pagination{
        display: inline !important;
        padding-left: 0;
        margin: 20px 0;
        border-radius: 4px;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ribbon */
    .ribbon {
      position: absolute;
      right: -5px;
      top: -5px;
      z-index: 1;
      overflow: hidden;
      width: 93px;
      height: 93px;
      text-align: right;
    }

    .ribbon span {
      font-size: 0.8rem;
      color: #fff;
      text-transform: uppercase;
      text-align: center;
      font-weight: bold;
      line-height: 32px;
      transform: rotate(45deg);
      width: 125px;
      display: block;
      background: linear-gradient(#f8ac59 0%, #f39229c7 100%);
      box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 1);
      position: absolute;
      top: 17px;
      right: -29px;
    }

    .ribbon span::before {
      content: '';
      position: absolute;
      left: 0px;
      top: 100%;
      z-index: -1;
      border-left: 3px solid #f39229c7;
      border-right: 3px solid transparent;
      border-bottom: 3px solid transparent;
      border-top: 3px solid #f39229c7;
    }

    .ribbon span::after {
      content: '';
      position: absolute;
      right: 0%;
      top: 100%;
      z-index: -1;
      border-right: 3px solid #f39229c7;
      border-left: 3px solid transparent;
      border-bottom: 3px solid transparent;
      border-top: 3px solid #f39229c7;
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
                  <h2>Employees</h2>
                  <ol class="breadcrumb">
                    <li>Home</li>
                      <li Class="active">
                          <a>Employee</a>
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
                            <li class="">
                              <a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Employees</a>
                            </li>
                            @can('employee.add')
                              <li class="add-employee">
                                <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Add Employee</a>
                              </li>
                            @endcan
                        </ul>
                        <div class="tab-content">
                            <div id="tab-10" class="tab-pane fade">
                                <div class="panel-body">
                                  <div class="row" id="app">
                                    <div class="col-md-2 col-md-offset-10">
                                      <div class="form-group pull-right">
                                        <div class="clearfix">
                                          <label data-placement="top" data-toggle="tooltip" title="Grid Layout" class="control-label pull-left" style="margin-right: 10px; line-height: 34px;">
                                            <span class="fa fa-th"></span>
                                          </label>
                                          <label class="switch pull-left" style="margin-top: 5px;">
                                            <input type="checkbox" v-model="isGrid" @change="handleLayoutChange">
                                            <span class="slider round"></span>
                                          </label>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="grid" id="gridLayout" v-show="layout === 'grid'">
                                    <div class="row" style="margin-bottom: 20px;" id="app">
                                      <div class="col-md-6">
                                        <label>
                                          Show
                                          <select v-model="per_page" class="form-control input-sm" style="width: auto; display: inline-block;" @change="handleLayoutChange">
                                            <option v-for="option in options" :key="option" :value="option">
                                              @{{ option }}
                                            </option>
                                          </select>
                                          entries
                                        </label>
                                      </div>
                                      <div class="col-md-6 text-right">
                                        <input type="text" v-model="search_employees" @input="debouncedSearch" class="form-control input-sm" style="width: 200px; display: inline-block;" placeholder="Search...">
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-6">
                                        <label>
                                          Showing @{{from}} to @{{to}} of @{{total}} entries
                                        </label>
                                      </div>
                                    </div>
                                    <div class="" style="display: ruby">
                                      <div class="m-2" v-for="employee in employees" :key="employee.id">
                                        <a :href="'{{ url('employee/profile') }}/' + employee.id" class="text-decoration-none">
                                          <div class="panel employee-card">
                                              <div class="card-header">
                                                  <div class="ribbon"><span>employee</span></div>
                                                  <div class="profile-image-container">
                                                      <img :src="employee.img_url || 'img/avatar.jpg'" alt="employee Photo" class="profile-image">
                                                  </div>
                                              </div>
                                              <div class="card-body">
                                                  <h4 class="employee-name">@{{ employee.name }}</h4>
                                                  <hr class="info-divider">
                                                  <ul class="list-unstyled info-list">
                                                      <li class="info-item">
                                                          <div class="info-icon icon-education">
                                                              <i class="fa fa-graduation-cap"></i>
                                                          </div>
                                                          <div class="info-content">
                                                              <div class="info-label">Qualification</div>
                                                              <div class="info-value">@{{ employee.qualification }}</div>
                                                          </div>
                                                      </li>
                                                      <li class="info-item">
                                                          <div class="info-icon icon-id">
                                                              <i class="fa fa-envelope-o"></i>
                                                          </div>
                                                          <div class="info-content">
                                                              <div class="info-label">Email</div>
                                                              <div class="info-value">@{{ employee.email }}</div>
                                                          </div>
                                                      </li>
                                                      <li class="info-item">
                                                          <div class="info-icon icon-gender">
                                                              <i class="fa fa-user"></i>
                                                          </div>
                                                          <div class="info-content">
                                                              <div class="info-label">Gender</div>
                                                              <div class="info-value">@{{ employee.gender }}</div>
                                                          </div>
                                                      </li>
                                                      <li class="info-item">
                                                          <div class="info-icon icon-fee">
                                                              <i class="fa fa-money"></i>
                                                          </div>
                                                          <div class="info-content">
                                                              <div class="info-label">Monthly Salary</div>
                                                              <div class="info-value fee-amount">PKR @{{ employee.salary }}</div>
                                                          </div>
                                                      </li>
                                                  </ul>
                                                  <div class="text-end mt-3">
                                                      <a :href="'{{ url('employee/edit') }}/' + employee.id" class="btn btn-sm btn-outline-primary">
                                                          <i class="fa fa-pencil"></i> Edit
                                                      </a>
                                                  </div>
                                              </div>
                                          </div>
                                        </a>  
                                      </div>
                                    </div>
                                    <div class="pagination" id="app">
                                      <nav class="text-center">
                                        <ul class="pagination">
                                          <li
                                            v-for="(link, index) in pagination_links"
                                            :key="index" :class="['page-item', { active: link.active, disabled: !link.url }]"
                                          >
                                            <a
                                              class="page-link"
                                              href="#"
                                              @click.prevent="goToPage(link)"
                                              v-html="link.label"
                                            ></a>
                                          </li>
                                        </ul>
                                      </nav>
                                    </div>
                                  </div>
                                  <div class="table-responsive" v-show="layout === 'list'">
                                    <table class="table table-striped table-bordered table-hover dataTables-employee" >
                                      <thead>
                                        <tr>
                                          <th>Name</th>
                                          <th>E-Mail</th>
                                          <th>Contact</th>
                                          <th>Role</th>
                                          <th>Options</th>
                                        </tr>
                                      </thead>

                                      <tfoot>
                                        <tr>
                                          <th>Name</th>
                                          <th>E-Mail</th>
                                          <th>Contact</th>
                                          <th>Role</th>
                                          <th>Options</th>
                                        </tr>
                                      </tfoot>
                                    </table>
                                  </div>

                                </div>
                            </div>
                            @can('employee.add')
                              <div id="tab-11" class="tab-pane fade add-employee">
                                  <div class="panel-body">
                                    <h2> Employee Registration </h2>
                                    <div class="hr-line-dashed"></div>
                                      <form id="tchr_rgstr" method="post" action="{{ URL('employee/add') }}" class="form-horizontal" enctype="multipart/form-data">
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
                                            <input type="text" name="email" placeholder="E-Mail" value="{{ old('email') }}" class="form-control"/>
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
                                            <input type="text" name="role" placeholder="Employee Role" value="{{ old('role') }}" class="form-control"/>
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
                                            <input type="text" name="qualification" placeholder="Qualification" value="{{ old('qualification') }}" class="form-control"/>
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
                                        <div class="form-group{{ ($errors->has('salary'))? ' has-error' : '' }}">
                                          <label class="col-md-2 control-label">Salary</label>
                                          <div class="col-md-6">
                                            <input type="text" name="salary" value="{{ old('salary') }}" placeholder="Salary" class="form-control"/>
                                            @if ($errors->has('salary'))
                                                <span class="help-block">
                                                    <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('salary') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-offset-2 col-md-6">
                                                <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Register </button>
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

    function loadOptions(data, type, full, meta) {
        opthtm = '';
        @can('employee.profile')
        opthtm = '<a href="{{ URL('employee/profile') }}/'+full.id+'" data-toggle="tooltip" title="Profile" class="btn '+ ((full.user_id != null)? ((full.active == 1)? 'btn-info' : 'btn-primary') : 'btn-default') +' btn-circle btn-xs profile"><span class="fa fa-user"></span></a>';
        @endcan
        @can('employee.edit.post')
        opthtm += '<a href="{{ URL('employee/edit') }}/'+full.id+'" data-toggle="tooltip" title="Edit Profile" class="btn btn-default btn-circle btn-xs"><span class="fa fa-edit"></span></a>';
        @endcan
        @can('users.update')
        opthtm += '<a href="{{ URL('users/edit') }}/'+full.user_id+'" data-toggle="tooltip" title="Edit User" class="btn btn-default btn-circle btn-xs"><span class="fa fa-edit"></span></a>';
        @endcan

        return opthtm;
    }

    var tbl;
      $(document).ready(function(){

/*    For Column Search
        $('.dataTables-teacher thead th').each( function () {
            var title = $('.dataTables-teacher thead th').eq( $(this).index() ).text();
          if (title !== 'Options') {
            $(this).html( '<input class="" type="text" placeholder="'+title+'" />' );
          }
        });
*/

    tbl =   $('.dataTables-employee').DataTable({
          dom: '<"html5buttons"B>lTfgitp',
          buttons: [
//            {extend: 'copy'},
//            {extend: 'csv'},
//            {extend: 'excel', title: 'Employee List'},
//           {extend: 'pdf', title: 'Employee List'},

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
              title: "Employees | {{ config('systemInfo.title') }}",
            }
          ],
          Processing: true,
          serverSide: true,
          ajax: '{{ URL('employee') }}',
          columns: [
            {data: 'name', name: 'employees.name'},
            {data: 'email', name: 'employees.email'},
            {data: 'phone', name: 'employees.phone'},
            {data: 'role', name: 'employees.role'},
//            {"defaultContent": '<div class="btn-group"><button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle option" aria-expanded="true">Action <span class="caret"></span></button><ul class="dropdown-menu"><li><a href="#"><span class="fa fa-user"></span> Profile</a></li><li class="divider"></li><li><a data-original-title="Edit" class="edit-option"><span class="fa fa-edit"></span> Edit</a></li><li><a href="#"><span class="fa fa-trash"></span> Delete</a></li></ul></div>', className: 'hidden-print'},
            {render: loadOptions, className: 'hidden-print', "orderable": false},
          ],
        });

/*
    for Column search
        tbl.columns().eq( 0 ).each( function ( colIdx ) {
            $( 'input', tbl.column( colIdx ).header() ).on( 'keyup change', function () {
                tbl
                    .column( colIdx )
                    .search( this.value )
                    .draw();
            });
        });
*/

      $(".dataTables-employee tbody").on('mouseenter', "[data-toggle='tooltip']", function(){
          $(this).tooltip('show');
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

      $('#tchr_rgstr [name="gender"]').val('{{ old('gender') }}');
      
      @if(COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
        $('a[href="#tab-11"]').tab('show');
      @else
        $('a[href="#tab-10"]').tab('show');
      @endif

      $("#imginp").change(function(){
          readURL(this);
      });
      });
    </script>


<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script type="text/javascript">
      var app = new Vue({
        el: '#app',
        data: { 
          layout: 'grid',
          options: [5,10, 25, 50, 100],
          per_page: 10,
          current_page: 1,
          last_page: 1,
          total: 0,
          to: 0,
          from: 0,
          employees: [],
          pagination_links: [],
          search_employees: '',
        },

        methods: {
          handleLayoutChange(page = 1) {
            axios.get('/employee/grid', {
              params: {
                per_page: this.per_page,
                page: page,
                search_employees: this.search_employees,
              }
            })
            .then(response => {
              const res = response.data;
              this.employees = res.data;
              this.current_page = res.current_page;
              this.last_page = res.last_page;
              this.to = res.to;
              this.from = res.from;
              this.total = res.total;
              this.pagination_links = res.links;
            })
            .catch(error => {
              console.error('Failed to fetch employees:', error);
            });
          },
          debouncedSearch(page = 1) {
            clearTimeout(this.debounceTimeout);
            this.debounceTimeout = setTimeout(() => {
              this.handleLayoutChange(page);
            }, 300);
          },
          goToPage(link) {
            if (!link.url) return;
            const url = new URL(link.url);
            const page = url.searchParams.get('page');
            this.handleLayoutChange(page);
          }
        },

        computed: {
          isGrid: {
            get() {
              return this.layout === 'grid';
            },
            set(val) {
              this.layout = val ? 'grid' : 'list';
            }
          }
        },
        mounted: function() {
          this.handleLayoutChange();
        }
      });
    </script>
    @endsection
