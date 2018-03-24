@extends('admin.layouts.master')

	@section('title', 'Students |')

	@section('head')
	<link href="{{ URL::to('src/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
	<link href="{{ URL::to('src/css/plugins/jasny/jasny-bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ URL::to('src/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
	<link href="{{ URL::to('src/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
	<script type="text/javascript">
			var sections = {!! json_encode($sections) !!};
	</script>
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
							<div class="col-lg-4 col-md-6">
								@include('admin.includes.academic_session')
							</div>
					</div>

					<!-- main Section -->

					<div class="wrapper wrapper-content animated fadeInRight">

						<div class="row">
							 <div class="col-lg-12">
								<div class="ibox float-e-margins">
										<div class="ibox-title">
												<h2>Edit Student</h2>
												<div class="hr-line-dashed"></div>
										</div>

										<div class="ibox-content">
																		<form id="tchr_rgstr" method="post" action="{{ URL('students/edit/'.$student->id) }}" class="form-horizontal" enctype="multipart/form-data">
																			{{ csrf_field() }}

																			<div class="form-group{{ ($errors->has('name'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Name</label>
																				<div class="col-md-6">
																					<input type="text" name="name" placeholder="Name" value="{{ old('name', $student->name) }}" class="form-control"/>
																					@if ($errors->has('name'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('name') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('father_name'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Father Name</label>
																				<div class="col-md-6">
																					<input type="text" name="father_name" placeholder="Father Name" value="{{ old('father_name', $student->father_name) }}" class="form-control"/>
																					@if ($errors->has('father_name'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('father_name') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('gender'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Gender</label>
																				<div class="col-md-6">
																					<select class="form-control" name="gender" placeholder="Gender">
																						<option value="" disabled selected>Gender</option>
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

																			<div class="form-group{{ ($errors->has('dob'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Date Of Birth</label>
																				<div class="col-md-6">
																					<input type="text" id="datetimepicker4" name="dob" placeholder="DOB" value="{{ old('dob', $student->date_of_birth) }}" class="form-control"/>
																					@if ($errors->has('dob'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('dob') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('place_of_birth'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Place Of Birth</label>
																				<div class="col-md-6">
																					<input type="text" name="place_of_birth" placeholder="Place Of Birth" value="{{ old('place_of_birth', $student->place_of_birth) }}" class="form-control"/>
																					@if ($errors->has('place_of_birth'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('place_of_birth') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('relegion'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Relegion</label>
																				<div class="col-md-6">
																					<input type="text" name="relegion" placeholder="Relegion" value="{{ old('relegion', $student->relegion) }}" class="form-control"/>
																					@if ($errors->has('relegion'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('relegion') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group {{ ($errors->has('img'))? ' has-error' : '' }}">
																				<div class="col-md-2">
																					<span class="btn btn-default btn-block btn-file">
																						<input type="file" name="img" accept="image/*" id="imginp" />
																							<span class="fa fa-image"></span>
																							Upload Image
																					</span>
																				</div>
																				<div class="col-md-6">
																					<img id="img" src="{{ URL($student->image_url) or '' }}"  alt="Item Image..." class="img-responsive img-thumbnail" />
																					@if ($errors->has('img'))
																						<span class="help-block">
																								<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('img') }}</strong>
																						</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('last_school'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Last School</label>
																				<div class="col-md-6">
																					<input type="text" name="last_school" placeholder="Last School Attendent" value="{{ old('last_school', $student->last_school) }}" class="form-control"/>
																					@if ($errors->has('last_school'))
																						<span class="help-block">
																								<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('last_school') }}</strong>
																						</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('class'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Class</label>
																				<div class="col-md-6 select2-div">
																					<select class="form-control select2" name="class">
																						<option value="" disabled selected>Class</option>
																						@foreach($classes AS $class)
																							<option value="{{ $class->id }}">{{ $class->name }}</option>
																						@endforeach
																					</select>
																					@if ($errors->has('class'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('class') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('section'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Section</label>
																				<div class="col-md-6 select2-div">
																					<select class="form-control select2" name="section">
																					</select>
																					@if ($errors->has('section'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('section') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('gr_no'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">GR No</label>
																				<div class="col-md-6">
																					<input type="text" name="gr_no" placeholder="GR NO" value="{{ old('gr_no', $student->gr_no) }}" class="form-control" />
																					@if ($errors->has('gr_no'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('gr_no') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('guardian'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Guardian</label>
																				<div class="col-md-6">
																					<select class="form-control" name="guardian">
																						<option></option>
																						@foreach($guardians as $guardian)
																							<option value="{{ $guardian->id }}">{{ $guardian->name.' | '.$guardian->email }}</option>
																						@endforeach
																					</select>
																					@if ($errors->has('guardian'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('guardian') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('guardian_relation'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Guardian Relation</label>
																				<div class="col-md-6">
																					<input type="text" name="guardian_relation" placeholder="guardian Relation" value="{{ old('guardian_relation', $student->guardian_relation) }}" class="form-control"/>
																					@if ($errors->has('guardian_relation'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('guardian_relation') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group">
																				<label class="col-md-2 control-label">Address</label>
																				<div class="col-md-6">
																					<textarea type="text" name="address" placeholder="Address" class="form-control">{{ old('address', $student->address) }}</textarea>
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('phone'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Contact No</label>
																				<div class="col-md-6">
																					<input type="text" name="phone" value="{{ old('phone', $student->phone) }}" placeholder="Contact No" class="form-control" data-mask="(999) 999-9999"/>
																					@if ($errors->has('phone'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('phone') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('doa'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Date Of Admission</label>
																				<div class="col-md-6">
																					<input type="text" id="datetimepicker5" name="doa" placeholder="Date Of Admission" value="{{ old('doa', $student->date_of_admission) }}" class="form-control"/>
																					@if ($errors->has('doa'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('doa') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>

																			<div class="form-group{{ ($errors->has('receipt_no'))? ' has-error' : '' }}">
																				<label class="col-md-2 control-label">Receipt No</label>
																				<div class="col-md-6">
																					<input type="text" name="receipt_no" placeholder="Receipt NO" value="{{ old('receipt_no', $student->receipt_no) }}" class="form-control" />
																					@if ($errors->has('receipt_no'))
																							<span class="help-block">
																									<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('receipt_no') }}</strong>
																							</span>
																					@endif
																				</div>
																			</div>
																			<div class="col-lg-8">
																			<div class="panel panel-info">
																			<div class="panel-heading">
																				Additional Feeses <a href="#" id="addfee" data-toggle="tooltip" title="Add Fee" @click="addAdditionalFee()" style="color: #ffffff"><span class="fa fa-plus"></span></a>
																			</div>
																			<div class="panel-body">
																			<table id="additionalfeetbl" class="table table-bordered table-hover table-striped">
																				<thead>
																					<tr>
																						<th>Name</th>
																						<th>Amount</th>
																						<th>Remove</th>
																					</tr>
																				</thead>
																				<tbody>
																					<tr>
																						<td>Tuition Fee</td>
																						<td>
																							<div>
																								<input type="number" name="tuition_fee" v-model.number="fee.tuition_fee" placeholder="Tuition Fee" class="form-control"/>
																								@if ($errors->has('tuition_fee'))
																										<span class="help-block">
																												<strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('tuition_fee') }}</strong>
																										</span>
																								@endif
																							</div>
																						</td>
																						<td></td>
																					</tr>

																						<tr v-for="(fee, k) in fee.additionalfee">
																							<td><input type="text" :name="'fee['+ k +'][fee_name]'" class="form-control" required="true" v-model="fee.fee_name"></td>
																							<td><input type="number" :name="'fee['+ k +'][amount]'" class="form-control additfeeamount" required="true" min="0" v-model.number="fee.amount"></td>
																							<td><a href="javascript:void(0);" class="btn btn-default text-danger removefee" data-toggle="tooltip" @click="removeAdditionalFee(k)" title="Remove" ><span class="fa fa-trash"></span></a></td>
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
																						<td><input type="number" name="discount" class="form-control" placeholder="Discount" min="0" v-model.number="fee.discount"></td>
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
																							<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Register </button>
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

		var tr;
		no = 1;

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

				$('[data-toggle="tooltip"]').tooltip();

				$('#datetimepicker4').datetimepicker({
								 format: 'DD/MM/YYYY'
					 });
				$('#datetimepicker5').datetimepicker({
								 format: 'DD/MM/YYYY'
					 });

				$("#tchr_rgstr").validate({
						rules: {
							name: {
								required: true,
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
							gr_no: {
								required: true,
							},
						},
				});

			
			$('#tchr_rgstr [name="class"]').on('change', function(){
				clsid = $(this).val();
					$('#tchr_rgstr [name="section"]').html('');
					if(sections['class_'+clsid].length > 0){          
						$.each(sections['class_'+clsid], function(k, v){
							$('#tchr_rgstr [name="section"]').append('<option value="'+v['id']+'">'+v['name']+'</option>');
						});
					}
			});

			$('#tchr_rgstr [name="gender"]').val('{{ old('gender', $student->gender) }}');
			$('#tchr_rgstr [name="guardian"]').val('{{ old('guardian', $student->guardian_id) }}');
			$('#tchr_rgstr [name="class"]').val("{{ old('class', $student->class_id) }}");
			$('#tchr_rgstr [name="class"]').change();
			$('#tchr_rgstr [name="section"]').val('{{ old('section', $student->section_id) }}');

			$('#tchr_rgstr [name="guardian"]').attr('style', 'width:100%').select2({
								placeholder: "Nothing Selected",
								allowClear: true,
						});


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
					fee: {
						additionalfee: {!! json_encode(old('fee', $additional_fee)) !!},
						tuition_fee: {{ old('tuition_fee', $student->tuition_fee) }},
						discount:  {{ old('discount', $student->discount) }},
					},
				},

				methods: {
					addAdditionalFee: function (){
						this.fee.additionalfee.push({
							fee_name: '',
							amount: 0
						});
					},
					removeAdditionalFee: function(k){
						this.fee.additionalfee.splice(k, 1);
					}
				},

				computed: {
					total_amount: function(){
						tot_amount = (this.fee.tuition_fee);
						for(k in this.fee.additionalfee) { 
							tot_amount += (this.fee.additionalfee[k].amount);
						}
						return  tot_amount;
					},
					net_amount: function(){
						return this.total_amount - this.fee.discount;
					}
				}
			});
		</script>
		@endsection



