<?php

namespace App;

//use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use App\AcademicSession;

class Student extends Model {

/*
	protected static function boot() {
		parent::boot();

		static::addGlobalScope('session_id', function (Builder $builder) {
			$builder->where('session_id', '=', Auth::user()->academic_session);
		});
	}
*/

	public function scopeHaveCellNo($query){
		return $query->where('phone', 'NOT LIKE', '21%')->whereRaw('LENGTH(phone) = 10');
	}

	public function scopeCurrentSession($query){
		return $query->where('session_id', Auth::user()->academic_session);
	}

	public function scopeNewAdmission($query, $academic_session){
//		return $query->where('date_of_admission', '>=', Carbon::now()->toDateString());
//		return $query->where('date_of_admission', '>=', AcademicSession::find(Auth::user()->academic_session)->getOriginal('from'));
		return $query->where('date_of_admission', '>=', $academic_session->getOriginal('from'));
//		return $query->where('date_of_admission', '>=', '2018-04-01');
	}

	public function scopeOldAdmission($query, $academic_session){
//		return $query->where('date_of_admission', '<=', Carbon::now()->toDateString());
//		return $query->where('date_of_admission', '<=', AcademicSession::find(Auth::user()->academic_session)->getOriginal('from'));
		return $query->where('date_of_admission', '<=', $academic_session->getOriginal('from'));
//		return $query->where('date_of_admission', '<=', '2018-04-01');
	}

	public function scopeActive($query){
		return $query->where('active', 1);
	}

	public function scopeInActive($query){
		return $query->where('active', 0);
	}

	public function scopeWithOutDiscount($query){
		return $query->where('discount', 0);
	}

	public function scopeWithDiscount($query){
		return $query->where('discount', '>', 0);
	}

	public function scopeWithFullDiscount($query){
		return $query->where('net_amount', '>', 0);
	}

	public function Guardian() {
		return $this->belongsTo('App\Guardian');
	}

	public function Std_Class() {
		return $this->hasOne('App\Classe', 'id', 'class_id');
	}

	public function StdClass() {
		return $this->hasOne('App\Classe', 'id', 'class_id');
	}

	public function Section() {
		return $this->hasOne('App\Section', 'id', 'section_id');
	}

	public function StudentResult(){
		return $this->hasMany('App\StudentResult');
	}

	public function StudentSubjectResult(){
		return $this->hasOne('App\StudentResult');
	}

	public function ParentInterview(){
		return $this->hasOne('App\ParentInterview');
	}

	public function getDateOfBirthAttribute($date){
		return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
	}

	public function getDateOfAdmissionAttribute($date){
		return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
	}

	public function setDateOfBirthInwordsAttribute($date){
		$this->attributes['date_of_birth_inwords']	=	Carbon::createFromFormat('Y-m-d', $date)->format('l jS \\of F Y');
	}

	public function AdditionalFee(){
		return $this->hasMany('App\AdditionalFee');
	}

	public function StudentAttendance(){
		return $this->hasMany('App\StudentAttendance');
	}

	public function StudentAttendanceByDate(){
		return $this->hasOne('App\StudentAttendance');
	}

	public function Invoices(){
		return $this->hasMany('App\InvoiceMaster');
	}

	public function Certificates(){
		return $this->hasMany('App\Certificate');
	}

}