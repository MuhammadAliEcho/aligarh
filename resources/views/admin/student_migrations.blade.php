@extends('admin.layouts.master')
    @section('title', __('modules.pages_student_migrations_title').' |')

	@section('content')

	@include('admin.includes.side_navbar')

		<div id="page-wrapper" class="gray-bg">

			@include('admin.includes.top_navbar')

            <!-- Heading -->
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-8 col-md-6">
                    <h2>Student Migration</h2>
                    <ol class="breadcrumb">
                    <li>Home</li>
                    <li Class="active">
                        <a>Migration</a>
                    </li>
                    </ol>
                </div>
				@can('user-settings.change.session')
                <div class="col-lg-4 col-md-6">
                    @include('admin.includes.academic_session')
                </div>
				@endcan
            </div>

            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row ">
                    <div class="col-lg-12">

						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h2><span class="glyphicon glyphicon-transfer"> </span> Student Session Migrations</h2>
                            	<div class="hr-line-dashed"></div>
							</div>
							<div class="ibox-content">
								<div class="alert alert-info">
									<p>
										<strong>Student Promotion Notes</strong>
									</p>
									<p>
										Promoting student from the present class to the next class will create an enrollment of that student to
										the next session. Make sure to select correct class options from the select menu before promoting.If you don't want
										to promote a student to the next class, please select that option. That will not promote the student to the next class
										but it will create an enrollment to the next session but in the same class.
									</p>
								</div>

								<form id="form" method="get" action="{{ URL('student-migrations/students') }}" class="form-horizontal" v-if="students == false">

									<div class="form-group{{ ($errors->has('from_session'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">From Session</label>
                                        <div class="col-md-6">
                                        	<select name="from_session" v-model="from_session" class="form-control" required="true" :disabled="students" />
												<option></option>
												<option v-for="session in AcademicSession" :value="session.id">@{{ session.title }}</option>
											</select>
                                          @if ($errors->has('from_session'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('from_session') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                	</div>

									<div class="form-group{{ ($errors->has('from_class'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">From Class</label>
                                        <div class="col-md-6">
                                        	<select name="from_class" v-model="from_class" class="form-control" required="true" :disabled="students"/>
												<option></option>
												<option v-for="clase in Classes" :value="clase.id">@{{ clase.name }}</option>
											</select>
                                          @if ($errors->has('from_class'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('from_class') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                	</div>

									<div class="hr-line-dashed"></div>
									<div class="form-group{{ ($errors->has('to_session'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">To Session</label>
                                        <div class="col-md-6">
                                        	<select name="to_session" v-model="to_session" class="form-control" required="true" />
												<option></option>
												<option v-for="session in AcademicSession" v-if="session.id > from_session" :value="session.id">@{{ session.title }}</option>
											</select>
                                          @if ($errors->has('to_session'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('to_session') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                	</div>

									<div class="form-group{{ ($errors->has('to_class'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">To Class</label>
                                        <div class="col-md-6">
                                        	<select name="to_class" v-model="selected_to_class" class="form-control" required="true" :disabled="students"/>
												<option></option>
												<option v-for="clase in Classes" v-if="from_class != clase.id" :value="clase.id">@{{ clase.name }}</option>
											</select>
                                          @if ($errors->has('to_class'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('to_class') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                	</div>

									<div class="form-group">
										<div class="col-md-offset-2 col-md-4">
											<button class="btn btn-primary btn-block" type="submit"><span class="glyphicon glyphicon-search"></span> Search </button>
										</div>
                                    </div>

								</form>

								<form id="migrate_form" method="post" action="{{ URL('student-migrations/create') }}" class="form-horizontal" v-if="students">
									{{ csrf_field() }}

									<input type="hidden" name="from_session" :value="from_session" />
									<input type="hidden" name="to_session" :value="to_session" />
									<input type="hidden" name="from_class" :value="from_class" />

									<div class="form-group{{ ($errors->has('from_session'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">From Session</label>
                                        <div class="col-md-6">
                                        	<select name="from_session" v-model="from_session" class="form-control" required="true" :disabled="students" />
												<option></option>
												<option v-for="session in AcademicSession" :value="session.id">@{{ session.title }}</option>
											</select>
                                          @if ($errors->has('from_session'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('from_session') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                	</div>

									<div class="form-group{{ ($errors->has('from_class'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">From Class</label>
                                        <div class="col-md-6">
                                        	<select name="from_class" v-model="from_class" class="form-control" required="true" :disabled="students"/>
												<option></option>
												<option v-for="clase in Classes" :value="clase.id">@{{ clase.name }}</option>
											</select>
                                          @if ($errors->has('from_class'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('from_class') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                	</div>

									<div class="hr-line-dashed"></div>
									<div class="form-group{{ ($errors->has('to_session'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">To Session</label>
                                        <div class="col-md-6">
                                        	<select name="to_session" v-model="to_session" class="form-control" required="true" :disabled="students" />
												<option></option>
												<option v-for="session in AcademicSession" v-if="session.id > from_session" :value="session.id">@{{ session.title }}</option>
											</select>
                                          @if ($errors->has('to_session'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('to_session') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                	</div>

									<div class="form-group{{ ($errors->has('to_class'))? ' has-error' : '' }}">
                                        <label class="col-md-2 control-label">To Class</label>
                                        <div class="col-md-6">
                                        	<select v-model="selected_to_class" class="form-control" required="true" :disabled="students"/>
												<option></option>
												<option v-for="clase in Classes" v-if="from_class != clase.id" :value="clase.id">@{{ clase.name }}</option>
											</select>
                                          @if ($errors->has('to_class'))
                                              <span class="help-block">
                                                  <strong><span class="fa fa-exclamation-triangle"></span> {{ $errors->first('to_class') }}</strong>
                                              </span>
                                          @endif
                                        </div>
                                	</div>

									<table class="table table-bordered table-hover">
										<thead>
											<tr>
												<th>SNo</th>
												<th>GR No</th>
												<th>Name</th>
												<th>From Class of ( @{{ selected_from_session.title }} )</th>
												<th v-if="selected_to_session">To Class of ( @{{ selected_to_session.title }} )</th>
												<th v-else>To Class of ( Session Not Selected )</th>
												<th>Options</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(student, k) in students">
												<td>@{{ k+1 }}</td>
												<td>@{{ student.gr_no }}</td>
												<td>@{{ student.name }}</td>
												<td>@{{ selected_from_class.name }}</td>
												<td>
													<select class="form-control" :name="'to_class['+student.id+']'" :value="selected_to_class">
														<option v-for="clase in Classes" :value="clase.id">@{{ clase.name }}</option>
													</select>
												</td>
												<td><a class="btn btn-danger btn-sm" @click="remove_student(k)"><span class="fa fa-trash"></span></a></td>
											</tr>
										</tbody>
									</table>

									<div class="form-group">
										<div class="col-md-offset-2 col-md-4">
											<button class="btn btn-primary btn-block" type="submit"><span class="glyphicon glyphicon-transfer"></span> Migrate Selected Students </button>
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

		<script type="text/javascript">
			$("#form").validate();
		</script>

	@endsection

@section('vue')
	<script type="text/javascript">
		var app = new Vue({
			el: '#app',
			data: {
				AcademicSession: {!! ($academic_session->toJson()); !!},
				Classes:	{!! $classes->toJson() !!},

				from_session: {{ Request::input('from_session', 0) }},
				to_session: {{ Request::input('to_session', 0) }},
				from_class: {{ Request::input('from_class', 0) }},
				selected_to_class:	{{ Request::input('to_class', 0) }},

				to_class: {},

				students:	{!! $students ?? 'false' !!},

			},
			computed: {
				selected_from_session: function(){
					return _.find(this.AcademicSession, { 'id': this.from_session });
				},
				selected_to_session: function(){
					return _.find(this.AcademicSession, { 'id': this.to_session });
				},
				selected_from_class: function(){
					return _.find(this.Classes, { 'id': this.from_class });
				},
			},
			methods: {
				remove_student: function(k){
					this.students.splice(k, 1);
				}
			}
		});

	</script>
@endsection
