<div class="container-fluid">

	<div class="row">
		<h2 class="text-center">{{ config('systemInfo.title') }}</h3>
		<h4 class="text-center">{{ config('systemInfo.address') }}</h4>
		<h3 class="text-center"><u>ADMISSION FORM</u></h4>
			<hr>
		<div class="col-xs-9" style="padding-left: 0px">
			<table class="table">
				<tbody>				
					<tr>
						<th>Name of Student</th>
						<td class="bottom-border text-uppercase">@{{student.name}}</td>
					</tr>
					<tr>
						<th>Name of Father</th>
						<td class="bottom-border text-uppercase">@{{student.father_name}}</td>
					</tr>
					<tr>
						<th>Address</th>
						<td class="bottom-border">@{{student.address}}</td>
					</tr>
					<tr>
						<th>Phone No</th>
						<td class="bottom-border">+92 @{{student.phone}}</td>
					</tr>
					<tr>
						<th>Date of Birth (in figure)</th>
						<td class="bottom-border">@{{student.date_of_birth}}</td>
					</tr>
					<tr>
						<th>Date of Birth (in words)</th>
						<td class="bottom-border">@{{student.date_of_birth_inwords}}</td>
					</tr>
					<tr>
						<th>Place of Birth</th>
						<td class="bottom-border">@{{student.place_of_birth}}</td>
					</tr>
					<tr>
						<th>Religion</th>
						<td class="bottom-border">@{{student.religion}}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="pull-right col-xs-3" style="border: 1px solid black; height: 200px">
			<img v-if="student.image_url" alt="image" class="img-responsive" height="200px" :src="URL + '/'+student.image_url">
			<p v-else style="font-size: 15px; width: 100%; margin-top: 80px" class="text-center">Photo</p>
		</div>
		<div style="min-height: 550px; margin-top: 10px">
			<table class="table">
				<tbody>
						<tr>
							<th colspan="2">Name of Guardian</th>
							<td colspan="2" class="bottom-border" width="60%">@{{student.guardian.name}} @{{"("+student.guardian_relation+")"}}</td>
						</tr>
					<tr>
						<th colspan="2">Father's/Guardian's Occupation</th>
						<td colspan="2" class="bottom-border">@{{student.guardian.profession}}</td>
					</tr>
					<tr>
						<th colspan="2">Name of the School Last Attended</th>
						<td colspan="2" class="bottom-border">@{{student.last_school}}</td>
					</tr>
					<tr>
						<th colspan="2">Seeking Admission in Class</th>
						<td colspan="2" class="bottom-border">@{{student.seeking_Class}}</td>
					</tr>
				</tbody>
			</table>
			<table v-if="siblings.length" class="table table-bordered sibling-table">
				<thead>
					<tr>
						<th colspan="3">Sibling study in this school</th>
					</tr>
					<tr>
						<th>S.No</th>
						<th>Name</th>
						<th>Gr No</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(std, k) in siblings">
						<td>@{{k+1}}</td>
						<td>@{{std.name}}</td>
						<td>@{{std.gr_no}}</td>
					</tr>
				</tbody>
			</table>
			<h4 v-else>No sibling study in this school</h4>
		</div>
		<table width="100%" style="margin-top: 100px">
			<tbody>
				<tr>
					<th width="35%" style="border-bottom: 1px solid; padding-top: 2px">Date:</th>
					<th width="30%"></th>
					<th width="35%" class="text-center" style="border-top: 1px solid; padding-top: 2px">Signature of Parent/Guardian</th>
				</tr>		
			</tbody>
		</table>
		<h3 class="text-center" style="border-top: 1px solid; padding-top: 8px">FOR OFFICE USE</h3>
		<table class="table">
			<tbody>
				<tr>
					<th>Name</th>
					<td class="bottom-border">@{{student.name}}</td>
					<th v-if="student.gender == 'Male'">S/O</th>
					<th v-else>D/O</th>
					<td class="bottom-border">@{{student.father_name}}</td>
				</tr>
				<tr>
					<th>Class</th>
					<td class="bottom-border">@{{student.std_class.name}}</td>
					<th>Section</th>
					<td class="bottom-border">@{{student.section.name}}</td>
				</tr>
				<tr>
					<th>Receipt No</th>
					<td class="bottom-border">@{{student.receipt_no}}</td>
					<th>Date</th>
					<td class="bottom-border">@{{student.date_of_admission}}</td>
				</tr>
			</tbody>
		</table>
	</div>

</div>
<div class="footer" style="border: none;">

	<table width="100%">
		<tbody>
			<tr>
				<th width="35%" class="text-center" style="border-top: 1px solid; padding-top: 5px">Signature of Principal</th>
				<th width="30%"></th>
				<th width="35%" class="text-center" style="border-top: 1px solid; padding-top: 5px">Signature of Accountant</th>
			</tr>		
		</tbody>
	</table>

	<hr>

	<div class="row">
	<div class="pull-right">
		<strong>All rights reserved</strong>
	</div>
	<div>
		Software Developed By <strong> HASHMANAGEMENT.COM © 2018 </strong>
	</div>
	</div>
</div>
