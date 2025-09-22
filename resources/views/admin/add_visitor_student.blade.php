@extends('admin.layouts.master')

@section('title', 'Students |')
@section('head')
    <link href="{{ asset('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <script type="text/javascript">
        var sections = {!! json_encode($sections ?? '') !!};
    </script>
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

    {{-- guardian-model --}}
    <style>

        .guardian-model-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: guardian-model-fadeInUp 0.5s ease-out;
        }

        .guardian-model-card-header {
            background: linear-gradient(135deg, #009486 0%, #1ab394 100%);
            display: flex;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            position: relative;
        }

        .guardian-model-profile-image-container {
            margin-bottom: 10px;
        }

        .guardian-model-profile-image {
            /* width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: 5px solid #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #fff; */
        }

        .guardian-model-title {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            text-transform: uppercase;
        }

        .guardian-model-card-body {
            padding: 20px;
        }

        .guardian-model-name {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }

        .guardian-model-status-select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .guardian-model-info-divider {
            border: none;
            height: 2px;
            background: #667eea;
            margin-bottom: 15px;
        }

        /* Form */
        .guardian-model-form-group {
            margin-bottom: 15px;
            flex: 1;
        }

        .guardian-model-form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .guardian-model-form-control {
            width: 100%;
            padding: 8px 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .guardian-model-form-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .guardian-model-submit-btn {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, #009486 0%, #1ab394 100%);
            color: #fff;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
        }

        .guardian-model-submit-btn:hover {
            opacity: 0.9;
        }

        @keyframes guardian-model-fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive inside modal */
        @media (max-width: 768px) {
            .guardian-model-card {
                font-size: 14px;
            }

            .guardian-model-profile-image {
                width: 80px;
                height: 80px;
                font-size: 30px;
            }
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
                <h2>Students</h2>
                <ol class="breadcrumb">
                    <li>Home</li>
                    <li Class="active">
                        <a>Students</a>
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
                            @can('students.add')
                                <li class="add-student">
                                    <a data-toggle="tab" href="#tab-11"><span class="fa fa-plus"></span> Admit Students</a>
                                </li>
                            @endcan
                        </ul>
                        <div class="tab-content">
                            @can('students.add')
                                <div id="tab-11" class="tab-pane fade add-student">
                                    <div class="panel-body">
                                        <h2> Admit Student </h2>
                                        <div class="hr-line-dashed"></div>

                                        <form v-if="admission_allow" id="tchr_rgstr" method="post"
                                            action="{{ route('students.create.visitor', $visitorStudents->id) }}" class="form-horizontal"
                                            enctype="multipart/form-data">
                                            {{ csrf_field() }}

                                            <input type="hidden" name="visitor_id" value="{{ $visitorStudents->id }}">

                                            <div class="form-group{{ $errors->has('guardian') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Guardian</label>
                                                <div class="col-md-6">
                                                    <select class="form-control" name="guardian" id="guardian-select">
                                                        <option value="" disabled selected>Guardian</option>
                                                            @foreach ($guardians as $guardian)
                                                                <option 
                                                                    value="{{ $guardian->id }}"
                                                                    data-address="{{ e($guardian->address ?? '') }}"
                                                                    data-phone="{{ e($guardian->phone ?? '') }}">
                                                                    {{ $guardian->name . ' | ' . $guardian->email }}
                                                                </option>
                                                            @endforeach
                                                    </select>
                                                    @if ($errors->has('guardian'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('guardian') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                @can('guardian.add')
                                                    <div style="padding-top: 5px; cursor: pointer;"
                                                        data-toggle="modal" data-target="#guardianModal">
                                                        <i data-placement="top" data-toggle="tooltip" title="Add Guardian" id="addGuardian" class="fa fa-plus"></i>
                                                    </div>
                                                @endcan
                                            </div>

                                            <div
                                                class="form-group{{ $errors->has('guardian_relation') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Guardian Relation</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="guardian_relation"
                                                        placeholder="Guardian Relation"
                                                        value="{{ old('guardian_relation', $visitorStudents->guardian_relation) }}" class="form-control" />
                                                    @if ($errors->has('guardian_relation'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('guardian_relation') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Student Name</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="name" placeholder="Name"
                                                        value="{{ $visitorStudents->name }}" class="form-control" />
                                                    @if ($errors->has('name'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('father_name') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Father Name</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="father_name" placeholder="Father Name"
                                                        value="{{ $visitorStudents->father_name }}" class="form-control" />
                                                    @if ($errors->has('father_name'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('father_name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Gender</label>
                                                <div class="col-md-6">
                                                    <select class="form-control" name="gender" placeholder="Gender">
                                                        <option {{ $visitorStudents->gender === 'male' ? 'selected' : '' }} >male</option>
                                                        <option {{ $visitorStudents->gender === 'female' ? 'selected' : '' }} >female</option>
                                                    </select>
                                                    @if ($errors->has('gender'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('gender') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('dob') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Date Of Birth</label>
                                                <div class="col-md-6">
                                                    <input type="text" id="datetimepicker4" name="dob"
                                                        placeholder="DOB" value="{{ $visitorStudents->date_of_birth }}"
                                                        class="form-control" />
                                                    @if ($errors->has('dob'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('dob') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('place_of_birth') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Place Of Birth</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="place_of_birth" placeholder="Place Of Birth"
                                                        value="{{ $visitorStudents->place_of_birth }}" class="form-control" />
                                                    @if ($errors->has('place_of_birth'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('place_of_birth') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('religion') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Religion</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="religion" placeholder="Religion"
                                                        value="{{ $visitorStudents->religion }}" class="form-control" />
                                                    @if ($errors->has('religion'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('religion') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('img') ? ' has-error' : '' }}">
                                                <div class="col-md-2">
                                                    <span class="btn btn-default btn-block btn-file">
                                                        <input type="file" name="img" accept="image/*"
                                                            id="imginp" />
                                                        <span class="fa fa-image"></span>
                                                        Upload Image
                                                    </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <img id="img" src="" alt="Item Image..."
                                                        class="img-responsive img-thumbnail" 
                                                        style="max-width:100px !important;min-width:105px !important;"/>
                                                    @if ($errors->has('img'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('img') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('last_school') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Last School</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="last_school"
                                                        placeholder="Last School Attendent" value="{{ $visitorStudents->last_school }}"
                                                        class="form-control" />
                                                    @if ($errors->has('last_school'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('last_school') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('seeking_class') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Seeking Class</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="seeking_class" placeholder="Seeking Class"
                                                        value="{{ $visitorStudents->seeking_class }}" class="form-control" />
                                                    @if ($errors->has('seeking_class'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('seeking_class') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="alert alert-warning ">
                                                <h4>Note! </h4>
                                                <p>
                                                    Once the class is set, it cannot be edited until the session ends.
                                                </p>

                                                <div class="form-group{{ $errors->has('class') ? ' has-error' : '' }}">
                                                    <label class="col-md-2 control-label">Class</label>
                                                    <div class="col-md-6 select2-div">
                                                        <select class="form-control select2" name="class">
                                                            <option value="" disabled selected>Class</option>
                                                            @foreach ($classes as $class)
                                                                <option {{ $visitorStudents->class_id === $class->id ? 'selected' : '' }}  value="{{ $class->id }}">{{ $class->name }}  </option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('class'))
                                                            <span class="help-block">
                                                                <strong><span class="fa fa-exclamation-triangle"></span>
                                                                    {{ $errors->first('class') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group{{ $errors->has('section') ? ' has-error' : '' }}">
                                                    <label class="col-md-2 control-label">Section</label>
                                                    <div class="col-md-6 select2-div">
                                                        <select class="form-control select2" name="section">
                                                            <option value="" disabled selected>Section</option>
                                                        </select>
                                                        @if ($errors->has('section'))
                                                            <span class="help-block">
                                                                <strong><span class="fa fa-exclamation-triangle"></span>
                                                                    {{ $errors->first('section') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-group{{ $errors->has('gr_no') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">GR No</label>
                                                <div class="col-md-6">
                                                    <input type="number" name="gr_no" placeholder="GR NO"
                                                        value="{{ old('gr_no') }}" class="form-control" />
                                                    @if ($errors->has('gr_no'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('gr_no') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('doa') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Date Of Admission</label>
                                                <div class="col-md-6">
                                                    <input type="text" id="datetimepicker5" name="doa"
                                                        placeholder="Date of Admission" value="{{ old('doa') }}"
                                                        class="form-control" required="true" />
                                                    @if ($errors->has('doa'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('doa') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('doe') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Date Of Enrolled</label>
                                                <div class="col-md-6">
                                                    <input type="text" id="datetimepicker6" name="doe"
                                                        placeholder="Date of Enrolled" value="{{ old('doe') }}"
                                                        class="form-control" required="true" />
                                                    @if ($errors->has('doe'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('doe') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('receipt_no') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Receipt No</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="receipt_no" placeholder="Receipt NO"
                                                        value="{{ old('receipt_no') }}" class="form-control" />
                                                    @if ($errors->has('receipt_no'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('receipt_no') }}</strong>
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

                                            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">Contact No</label>
                                                <div class="col-md-6">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon">+92</span>
                                                        <input type="text" name="phone" value="{{ old('phone') }}"
                                                            placeholder="Contact No" class="form-control"
                                                            data-mask="9999999999" />
                                                    </div>
                                                    @if ($errors->has('phone'))
                                                        <span class="help-block">
                                                            <strong><span class="fa fa-exclamation-triangle"></span>
                                                                {{ $errors->first('phone') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-lg-8">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading">
                                                        Additional Feeses <a href="#" id="addfee"
                                                            data-toggle="tooltip" title="Add Fee" @click="addAdditionalFee()"
                                                            style="color: #ffffff"><span class="fa fa-plus"></span></a>
                                                    </div>
                                                    <div class="panel-body">
                                                        <table id="additionalfeetbl"
                                                            class="table table-bordered table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th width="40%">Name</th>
                                                                    <th width="40%">Amount</th>
                                                                    <th width="20%">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Tuition Fee</td>
                                                                    <td>
                                                                        <div>
                                                                            <input type="number" name="tuition_fee"
                                                                                v-model.number="fee.tuition_fee"
                                                                                placeholder="Tuition Fee" min="1"
                                                                                class="form-control" />
                                                                            @if ($errors->has('tuition_fee'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('tuition_fee') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                    <td></td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Late Fee</td>
                                                                    <td>
                                                                        <div>
                                                                            <input type="number" name="late_fee"
                                                                                v-model.number="fee.late_fee"
                                                                                placeholder="Tuition Fee" min="1"
                                                                                class="form-control" />
                                                                            @if ($errors->has('late_fee'))
                                                                                <span class="help-block">
                                                                                    <strong><span
                                                                                            class="fa fa-exclamation-triangle"></span>
                                                                                        {{ $errors->first('late_fee') }}</strong>
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                    <td></td>
                                                                </tr>

                                                                <tr v-for="(fee, k) in fee.additionalfee">
                                                                    <td><input type="hidden" :name="'fee[' + k + '][id]'"
                                                                            value="0"><input type="text"
                                                                            :name="'fee[' + k + '][fee_name]'"
                                                                            class="form-control" required="true"
                                                                            v-model="fee.fee_name"></td>
                                                                    <td><input type="number" :name="'fee[' + k + '][amount]'"
                                                                            class="form-control additfeeamount"
                                                                            required="true" min="1"
                                                                            v-model.number="fee.amount"></td>
                                                                    <td>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"
                                                                                data-toggle="tooltip"
                                                                                title="select if onetime charge">
                                                                                <input type="checkbox"
                                                                                    :name="'fee[' + k + '][onetime]'"
                                                                                    value="1" :checked="fee.onetime">
                                                                            </span>
                                                                            <span class="input-group-addon"
                                                                                data-toggle="tooltip" title="Active">
                                                                                <input type="checkbox"
                                                                                    :name="'fee[' + k + '][active]'"
                                                                                    value="1" :checked="fee.active"
                                                                                    @click="fee.active = !fee.active">
                                                                            </span>
                                                                            <a href="javascript:void(0);"
                                                                                class="btn btn-default text-danger removefee"
                                                                                data-toggle="tooltip"
                                                                                @click="removeAdditionalFee(k)"
                                                                                title="Remove">
                                                                                <span class="fa fa-trash"></span>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>

                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th>Total</th>
                                                                    <th>@{{ total_amount }}</th>
                                                                    <th></th>
                                                                </tr>
                                                                <tr>
                                                                    <td>Discount</td>
                                                                    <td><input type="number" name="discount"
                                                                            class="form-control" placeholder="Discount"
                                                                            min="0" v-model.number="fee.discount"></td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Net Amount</th>
                                                                    <th>@{{ net_amount }}</th>
                                                                    <th></th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="net_amount" v-model="net_amount">
                                            <input type="hidden" name="total_amount" v-model="total_amount">
                                            <div class="form-group">
                                                <div class="col-md-offset-2 col-md-6">
                                                    <button class="btn btn-primary" type="submit"><span
                                                            class="glyphicon glyphicon-save"></span> Register </button>
                                                </div>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="guardianModal" tabindex="-1" role="dialog" aria-labelledby="guardianModalLabel">
                                              <div class="modal-dialog" role="document">
                                                  <div class="modal-content">
                                                      <div class="modal-header">
                                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                              <span aria-hidden="true">&times;</span>
                                                          </button>
                                                          <h4 class="modal-title" id="guardianModalLabel">Add Guardian</h4>
                                                      </div>
                                                    <div class="modal-body">
                                                        <div class="guardian-model-card">
                                                            <div class="guardian-model-card-header">
                                                                <div class="guardian-model-profile-image-container">
                                                                    <div class="guardian-model-profile-image">
                                                                        <img src="/img/avatar.jpg" alt="Guardian Photo" class="profile-image">
                                                                        {{-- <i class="fas fa-user-shield"></i> --}}
                                                                    </div>
                                                                </div>
                                                                <div class="guardian-model-title">Guardian Info</div>
                                                            </div>

                                                            <div class="guardian-model-card-body">
                                                                <h3 class="guardian-model-name">Guardian Information</h3>

                                                                <hr class="guardian-model-info-divider">

                                                                <form id="guardianModalForm" class="guardian-model-form">
                                                                    <div class="guardian-model-form-row">
                                                                        <div class="guardian-model-form-group">
                                                                            <label class="guardian-model-form-label">Name<span class="text-danger">*</span></label>
                                                                            <input id="guardian_name" required type="name" class="guardian-model-form-control" placeholder="Enter Name">
                                                                        </div>
                                                                        <div class="guardian-model-form-group">
                                                                            <label class="guardian-model-form-label">Email Address</label>
                                                                            <input id="guardian_email" required type="email" class="guardian-model-form-control" placeholder="Enter Email">
                                                                        </div>
                                                                    </div>

                                                                    <div class="guardian-model-form-row">
                                                                        <div class="guardian-model-form-group">
                                                                            <label class="guardian-model-form-label">Profession</label>
                                                                            <input id="guardian_profession" type="profession" class="guardian-model-form-control" placeholder="Enter Profession">
                                                                        </div>
                                                                        <div class="guardian-model-form-group">
                                                                            <label class="guardian-model-form-label">Phone No</label>
                                                                            <input id="guardian_phone" type="tel" class="guardian-model-form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="guardian-model-form-row">
                                                                        <div class="guardian-model-form-group">
                                                                            <label class="guardian-model-form-label">Address</label>
                                                                             <textarea id="guardian_address" style="height: 34px; width: 246px;" type="text" name="address" placeholder="Address" class="form-control"></textarea>
                                                                        </div>
                                                                        <div class="guardian-model-form-group">
                                                                            <label class="guardian-model-form-label">Income</label>
                                                                            <input id="guardian_income" type="number" value="0" name="income" class="guardian-model-form-control" placeholder="Enter Income">
                                                                        </div>
                                                                    </div>

                                                                    <div class="guardian-model-form-group" style="margin-top: 15px;">
                                                                        <button type="button" @click="addGuardian()" class="guardian-model-submit-btn">Add Guardian Information</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                  </div>
                                              </div>
                                            </div>
                                        </form>

                                        <div v-else class="alert alert-info">
                                            Student admission limit is over.
                                        </div>
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
    <script src="{{ asset('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('src/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).ready(function() {

            $('.nav-tabs a[href="#tab-11"]').tab('show');

            $('[data-toggle="tooltip"]').tooltip();

            $('#datetimepicker4').datetimepicker({
                format: 'DD/MM/YYYY',
            });
            $('#datetimepicker5').datetimepicker({
                format: 'DD/MM/YYYY',
                defaultDate: moment()
            });

            $('#datetimepicker6').datetimepicker({
                format: 'YYYY-MM-DD',
                defaultDate: moment()

            });

            $('#guardian-select').on('change', function() {
                var selected = $(this).find('option:selected');
                var address = selected.data('address');
                var phone = selected.data('phone');

                $('textarea[name="address"]').val(address);
                $('input[name="phone"]').val(phone);
            });


            $("#tchr_rgstr").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    email: {
                        email: true,
                    },
                    father_name: {
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
                    guardian: {
                        required: true,
                    },
                    guardian_relation: {
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
                    doe: {
                        required: true,
                    },
                    gr_no: {
                        required: true,
                        number: true,
                    },
                },
            });

            $('#tchr_rgstr [name="class"]').on('change', function() {
                clsid = $(this).val();
                $('#tchr_rgstr [name="section"]').html('');
                if (sections['class_' + clsid].length > 0) {
                    $.each(sections['class_' + clsid], function(k, v) {
                        $('#tchr_rgstr [name="section"]').append('<option value="' + v['id'] +
                            '">' + v['name'] + '</option>');
                    });
                }
            });

            @if (COUNT($errors) >= 1)
                $('#tchr_rgstr [name="gender"]').val('{{ old('gender') }}');
                $('#tchr_rgstr [name="guardian"]').val('{{ old('guardian') }}');
                $('#tchr_rgstr [name="class"]').val("{{ old('class') }}");
                $('#tchr_rgstr [name="class"]').change();
                $('#tchr_rgstr [name="section"]').val('{{ old('section') }}');
            @endif

            $('#tchr_rgstr [name="guardian"]').attr('style', 'width:100%').select2({
                placeholder: "Nothing Selected",
                allowClear: true,
            });

            @if (COUNT($errors) >= 1 && !$errors->has('toastrmsg'))
                $('.nav-tabs a[href="#tab-11"]').tab('show');
            @else
                $('.nav-tabs a[href="#tab-10"]').tab('show');
            @endif

            $("#imginp").change(function() {
                readURL(this);
            });
        });
    </script>

@endsection

@section('vue')
    <script src="{{ asset('src/js/plugins/axios-1.11.0/axios.min.js') }}"></script>
    <script type="text/javascript">
        var app = new Vue({
            el: '#app',
            data: {
                fee: {
                    additionalfee: {!! old('fee', config('feeses.additional_fee')) !!},
                    tuition_fee: {{ old('tuition_fee', config('feeses.compulsory.tuition_fee')) }},
                    late_fee: {{ old('late_fee', config('feeses.compulsory.late_fee')) }},
                    discount: {{ old('discount', 0) }},
                },
                no_of_active_students: {{ $no_of_active_students }},
                student_capacity: {{ tenancy()->tenant->system_info['general']['student_capacity'] }},
                layout: 'grid',
                options: [5, 10, 25, 50, 100],
                per_page: 10,
                current_page: 1,
                last_page: 1,
                total: 0,
                to: 0,
                from: 0,
                students: [],
                pagination_links: [],
                search_students: '',
            },

            methods: {
                addAdditionalFee: function() {
                    this.fee.additionalfee.push({
                        id: 0,
                        fee_name: '',
                        amount: 0,
                        active: 1,
                        onetime: 1
                    });
                },
                removeAdditionalFee: function(k) {
                    this.fee.additionalfee.splice(k, 1);
                },
                handleLayoutChange(page = 1) {
                    axios.get('/students/grid', {
                            params: {
                                per_page: this.per_page,
                                page: page,
                                search_students: this.search_students,
                            }
                        })
                        .then(response => {
                            const res = response.data;
                            this.students = res.data;
                            this.current_page = res.current_page;
                            this.last_page = res.last_page;
                            this.to = res.to;
                            this.from = res.from;
                            this.total = res.total;
                            this.pagination_links = res.links;
                        })
                        .catch(error => {
                            console.error('Failed to fetch students:', error);
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
                },
                addGuardian() {
                    axios.post('/guardians/add', {
                        name: $('#guardian_name').val(),
                        email: $('#guardian_email').val(),
                        phone: $('#guardian_phone').val(),
                        profession: $('#guardian_profession').val(),
                        address: $('#guardian_address').val(),
                        income: $('#guardian_income').val(),
                    }, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        toastr.success("Guardian added successfully");
                        $('#guardianModal').modal('hide');
                        axios.get('students/guardians/list')
                            .then(res => {
                                const dropdown = $('select[name="guardian"]');
                                dropdown.empty();
                                dropdown.append('<option value="" disabled selected>Select Guardian</option>');
                                res.data.forEach(guardian => {
                                    dropdown.append(
                                        `<option value="${guardian.id}">${guardian.name} | ${guardian.email}</option>`
                                    );
                                });
                            })
                            .catch(err => {
                                toastr.error("Could not refresh guardian list.");
                                console.error(err);
                            });
                    })
                    .catch(error => {
                        if (error.response && error.response.status === 422) {
                            const errors = error.response.data.errors;

                            Object.keys(errors).forEach(field => {
                                errors[field].forEach(msg => {
                                    toastr.error(msg, "Validation Error");
                                });
                            });
                        } else {
                            toastr.error("Something went wrong. Please try again.", "Error");
                            console.error('Error:', error);
                        }
                    });
                },
            },

            computed: {
                total_amount: function() {
                    tot_amount = Number(this.fee.tuition_fee);
                    for (k in this.fee.additionalfee) {
                        if (this.fee.additionalfee[k].active) {
                            tot_amount += Number(this.fee.additionalfee[k].amount);
                        }
                    }
                    return tot_amount;
                },
                net_amount: function() {
                    return Number(this.total_amount) - Number(this.fee.discount);
                },
                admission_allow: function() {
                    return this.no_of_active_students < this.student_capacity
                }
            },
            mounted: function() {
                this.handleLayoutChange();
            }
        });
    </script>
@endsection
