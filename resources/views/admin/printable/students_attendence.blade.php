@extends('admin.layouts.printable')
@section('title', 'Student Attendance | ')

@section('head')

	<style type="text/css">
		.invoice-title h2, .invoice-title h3 {
			display: inline-block;
		}

		.table > tbody > tr > .no-line {
			border-top: none;
		}

		.table > thead > tr > .no-line {
			border-bottom: none;
		}

		.table > tbody > tr > .thick-line {
			border-top: 1px solid;
		}


    body {
      padding: 0px 10px;
      margin: 0px;
      font-size: 12px;
      }
    .table-bordered th,
    .table-bordered td {
      border: 1px solid black !important;
      padding: 0px;
    }   

  .table > thead > tr > th,
  .table > tbody > tr > th
   {
      padding: 2px;
    }
  .table > tbody > tr > td {
      padding: 2px;
    }
    a[href]:after {
      content: none;
/*      content: " (" attr(href) ")";*/
    }



	</style>

@endsection

@section('content')
<div class="container-fluid">

	<div class="row">
	<h3 class="text-center">{{ config('systemInfo.title') }}</h3>
	<h4>Class: {{ $selected_class->name.' '.$section_nick }} ({{ $input['date'] }})</h4>
	<h4>No Of Students: {{ COUNT($students) }}</h3>
	<h4>Teacher: {{ $selected_class->Teacher->name or '' }}</h3>
		<table id="rpt-att" class="table table-bordered">
			<thead>
			  <tr>
				<th>Students</th>
				<th width="50px">Gr No</th>
				@for($i=1; $i <= $noofdays; $i++)
				<th class="{{ in_array($i, $weekends)? 'h' : '' }}" >{{ $i }}</th>
				@endfor
				<th>H*</th>
				<th>A*</th>
				<th>P*</th>
				<th>R*</th>
				<th>%P*</th>
			  </tr>
			</thead>
			<tbody>
				@foreach($students as $student)
				<tr>
					<td>{{ $student->name }}</td>
					<td>{{ $student->gr_no }}</td>
					@for($i=1; $i <= $noofdays; $i++)
					<td  class="std_{{ $student->id }}_dat_{{ $i }} col_dat_{{ $i }} {{ in_array($i, $weekends)? 'h' : '' }}"></td>
					@endfor
					<td>{{ $noofweekends }}</td>
					<td class="std_{{ $student->id }}_a"></td>
					<td class="std_{{ $student->id }}_p"></td>
					<td>{{ $noofdays-$noofweekends }}</td>
					<td class="std_{{ $student->id }}_percent"></td>
				</tr>
				@endforeach
				<tr>
					<th colspan="2">Present</th>
					@for($i=1; $i <= $noofdays; $i++)
					<td  class="p_std_dat_{{ $i }} {{ in_array($i, $weekends)? 'h' : '' }}"></td>
					@endfor
				</tr>
				<tr>
					<th colspan="2">Absent</th>
					@for($i=1; $i <= $noofdays; $i++)
					<td  class="a_std_dat_{{ $i }} {{ in_array($i, $weekends)? 'h' : '' }}"></td>
					@endfor
				</tr>
				<tr>
					<th colspan="2">% Attendance</th>
					@for($i=1; $i <= $noofdays; $i++)
					<td  class="percent_std_dat_{{ $i }} {{ in_array($i, $weekends)? 'h' : '' }}"></td>
					@endfor
				</tr>
			</tbody>
		</table>
	</div>

</div>

@include('admin.includes.footercopyright')

@endsection


@section('script')

@endsection

@section('vue')

<script type="text/javascript">

	var app = new Vue({
		el: '#app',
		data: { 
			students: {!! json_encode($students, JSON_NUMERIC_CHECK) !!},
			attendancerpt: {!! json_encode($attendence, JSON_NUMERIC_CHECK) !!},
			noofdays: {!! json_encode($noofdays, JSON_NUMERIC_CHECK) !!},
		},

		mounted: function(){
			this.checkattendance(this.noofdays);
			this.calcattendence();
			$('tbody .h').html('H');
//			$('.h').css('background', 'yellow');
			window.print();
		},

		methods: {
			checkattendance: function(noofdays){
				$.each(this.attendancerpt, function(k, v){
					totp = 0;
					tota = 0;
					$.each(v, function(i, d){
						date = new Date(d.date);
						day = date.getDate();
						/*            console.log(d);
						alert(day);
						*/
						if(d.status){
							prefix = 'P';
							totp++;
						} else {
							prefix =	'<span class="text-danger">A</span>';
						}
						$('.std_'+k+'_dat_'+day).html(prefix);
					});
//					this.attendancerpt.k.noofpresent = totp;
					tota = noofdays-({{ COUNT($weekends) }}+totp);
					$('.std_'+k+'_p').html(totp);
					$('.std_'+k+'_a').html(tota);
					$('.std_'+k+'_percent').html(((totp/noofdays)*100).toFixed(1)+'%');

				});
			},
			calcattendence: function(){

				for (var i = this.noofdays; i >= 0; i--) {
					p = 0;
					a = 0;
					percent = 0;
					$('.col_dat_'+i).each(function(v){
						if ($(this).text() == 'P') {
							p++;
						} else {
							a++;
						}
					});
					$('.p_std_dat_'+i).html(p);
					$('.a_std_dat_'+i).html(a);
					$('.percent_std_dat_'+i).html(((p/{{ COUNT($students) }})*100).toFixed(1)+'%');
				}

			}
		},
	});
</script>

@endsection