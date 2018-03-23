@extends('admin.layouts.master')

	@section('title', 'Fee Scenario |')

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
									<h2>Settings</h2>
									<ol class="breadcrumb">
										<li>Home</li>
											<li Class="active">
													<a>Fee Scenario</a>
											</li>
									</ol>
							</div>
							<div class="col-lg-4 col-md-6">
								@include('admin.includes.academic_session')
							</div>
					</div>

					<!-- main Section -->

					<div class="wrapper wrapper-content animated fadeInRight">

						<div class="row ">
								<div class="col-lg-12">
										<div class="tabs-container">
												<ul class="nav nav-tabs">
														<li class="active">
															<a data-toggle="tab" href="#tab-10"><span class="fa fa-list"></span> Fee Scenario</a>
														</li>
												</ul>
												<div class="tab-content">
														<div id="tab-11" class="tab-pane fade fade in active add-guardian">
																<div class="panel-body">
																	
																	<div class="alert alert-info ">
																		<h4>Note! </h4>Fee Scenario Update, will be applicable only for new student registrations.
																	</div>

																	<form id="tchr_rgstr" method="POST" action="{{ URL('fee-scenario/update') }}" class="form-horizontal" >
																		{{ csrf_field() }}

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
																										<input type="number" name="tuition_fee" v-model.number="fee.tuition_fee" placeholder="Tuition Fee" min="1" required="true" class="form-control"/>
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
																								<td><input type="number" :name="'fee['+ k +'][amount]'" class="form-control additfeeamount" required="true" min="1" v-model.number="fee.amount" required="true"></td>
																								<td><a href="javascript:void(0);" class="btn btn-default text-danger removefee" data-toggle="tooltip" @click="removeAdditionalFee(k)" title="Remove" ><span class="fa fa-trash"></span></a></td>
																							</tr>
																						</tbody>
																						<tfoot>
																							<tr>
																								<th>Total</th>
																								<th>@{{ total_amount }}</th>
																								<th></th>
																							</tr>
																						</tfoot>
																					</table>
																				</div>
																			</div>
																		</div>

																		<div class="form-group">
																				<div class="col-md-offset-2 col-md-6">
																						<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> Update </button>
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


					@include('admin.includes.footercopyright')


				</div>

		@endsection

		@section('script')

		<!-- Mainly scripts -->
		<script src="{{ URL::to('src/js/plugins/jeditable/jquery.jeditable.js') }}"></script>

		<script src="{{ URL::to('src/js/plugins/validate/jquery.validate.min.js') }}"></script>

		<!-- Input Mask-->
		 <script src="{{ URL::to('src/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>

		<script type="text/javascript">
		var tbl;


			$(document).ready(function(){


				$("#tchr_rgstr").validate({
					rules: {
						tuition_fee: {
							required: true,
						},
					}
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
			tuition_fee: {{ old('tuition_fee', config('feeses.compulsory.tuition_fee')) }},
			additionalfee: {!! old('fee', config('feeses.additional_fee')) !!},
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
		}
	  });
	</script>
	@endsection
