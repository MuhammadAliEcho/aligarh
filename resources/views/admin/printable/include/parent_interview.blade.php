<div class="container-fluid">

	<div class="row">
		<h2 class="text-center">{{ config('systemInfo.title') }}</h3>
		<h4 class="text-center">{{ config('systemInfo.address') }}</h4>
		<h3 class="text-center"><u>Parent Interview Report</u></h4>
		<hr>
		<table class="table">
			<tbody>				
				<tr>
					<th>Date</th>
					<td class="bottom-border text-uppercase">@{{student.date_of_admission}}</td>
				</tr>
				<tr>
					<th>Class</th>
					<td class="bottom-border text-uppercase">@{{student.std_class.name}}</td>
					<th>Name</th>
					<td class="bottom-border text-uppercase">@{{student.name}}</td>
				</tr>
				<tr>
					<th>Registration No</th>
					<td class="bottom-border text-uppercase">@{{student.receipt_no}}</td>
					<th>Father's/Guardian's Name</th>
					<td class="bottom-border text-uppercase">@{{student.father_name}}</td>
				</tr>
			</tbody>
		</table>
		<h4 class="text-center"><u>GENERAL INFORMATION</u></h4>
		<div style="margin-top: 5px">
			<table class="table">
				<tbody>
					<tr>
						<th width="20%">Father's Qualification</th>
						<td class="bottom-border" width="30%">@{{interview.father_qualification}}</td>
						<th width="20%">Mother's Qualification</th>
						<td class="bottom-border">@{{interview.mother_qualification}}</td>
					</tr>
					<tr>
						<th>Father's Occupation</th>
						<td class="bottom-border">@{{interview.father_occupation}}</td>
						<th>Mother's Occupation</th>
						<td class="bottom-border">@{{interview.mother_occupation}}</td>
					</tr>
					<tr>
						<th>Monthly Income</th>
						<td class="bottom-border">@{{interview.mothly_income}}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<h4 class="text-center"><u>COMMITMENT OTHER THAN JOB</u></h4>
		<div style="margin-top: 5px">
			<table class="table">
				<tbody>
					<tr>
						<th width="20%">Father's</th>
						<td class="bottom-border" width="30%">@{{interview.other_job_father}}</td>
						<th width="20%">Mother's</th>
						<td class="bottom-border">@{{interview.other_job_mother}}</td>
					</tr>
				</tbody>
			</table>
		</div>

		<h4 class="text-center"><u>Family Info</u></h4>
		<div style="margin-top: 5px">
			<table class="table">
				<tbody>
					<tr>
						<th width="20%">Family Structure</th>
						<td class="bottom-border" width="30%">@{{interview.family_structure}}</td>
						<th width="20%">Parents</th>
						<td class="bottom-border">@{{interview.parents_living}}</td>
					</tr>
					<tr>
						<th width="20%">No Of Children</th>
						<td class="bottom-border">@{{ no_of_children() }}</td>
					</tr>
				</tbody>
			</table>
			<table class="table">
				<tbody>
					<tr v-for="question in interview.questions">
						<td width="40%">@{{ question.q }}</td>
						<td class="bottom-border">@{{ question.a }}</td>
					</tr>
				</tbody>
			</table>	
		</div>

		<h4 class="text-center"><u>For Montessori section only</u></h4>
		<div style="margin-top: 5px">
			<table class="table">
				<tbody>
					<tr v-for="question in interview.questions_montessori">
						<td width="40%">@{{ question.q }}</td>
						<td class="bottom-border">@{{ question.a }}</td>
					</tr>
				</tbody>
			</table>
			<table>
				<tbody>
					<tr>
						<th width="20%">Remarks: &nbsp;&nbsp;&nbsp;</th>
						<td>@{{ interview.remarks }}</td>
					</tr>
				</tbody>
			</table>
		</div>

	</div>

</div>
<div class="footer" style="border: none;">

	<table width="100%">
		<tbody>
			<tr>
				<th width="35%" class="text-center" style="border-top: 1px solid; padding-top: 5px">Signature of Vice Principal</th>
				<th width="30%"></th>
				<th width="35%" class="text-center" style="border-top: 1px solid; padding-top: 5px">Signature of Principal</th>
			</tr>		
		</tbody>
	</table>
	<br>
	<div class="row">
	<div class="pull-right">
		<strong>All rights reserved</strong>
	</div>
	<div>
		Software Developed By <strong> HASHMANAGEMENT.COM © 2018 </strong>
	</div>
	</div>
</div>
