<?php

namespace App;

//use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\AcademicSession;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\HasLeave;

class Student extends Model {
	use HasLeave;

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
//		return $query->where('date_of_admission', '>=', AcademicSession::find(Auth::user()->academic_session)->getRawOriginal('start'));
		return $query->where('date_of_admission', '>=', $academic_session->getRawOriginal('start'));
//		return $query->where('date_of_admission', '>=', '2018-04-01');
	}

	public function scopeOldAdmission($query, $academic_session){
//		return $query->where('date_of_admission', '<=', Carbon::now()->toDateString());
//		return $query->where('date_of_admission', '<=', AcademicSession::find(Auth::user()->academic_session)->getRawOriginal('start'));
		return $query->where('date_of_admission', '<=', $academic_session->getRawOriginal('start'));
//		return $query->where('date_of_admission', '<=', '2018-04-01');
	}

	public function scopeInActiveOnSelectedSession($query, $academic_session){
		return $query->InActive()->whereBetween('date_of_leaving', [$academic_session->getRawOriginal('start'), $academic_session->getRawOriginal('end')]);
	}

	public function scopeActive($query){
		return $query->where('students.active', 1);
	}

	public function scopeInActive($query){
		return $query->where('students.active', 0);
	}

	public function scopeWithOutDiscount($query){
		return $query->where('discount', 0);
	}

	public function scopeWithDiscount($query){
		return $query->where('discount', '>', 0);
	}

	public function scopeWithOutFullDiscount($query){
		return $query->where('students.net_amount', '>', 0);
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

	public function setDateOfBirthInwordsAttribute($date)
	{
		try {
			// Try Y-m-d (raw DB format)
			$parsed = Carbon::createFromFormat('Y-m-d', $date);
		} catch (\Exception $e1) {
			try {
				// Try d/m/Y (formatted version)
				$parsed = Carbon::createFromFormat('d/m/Y', $date);
			} catch (\Exception $e2) {
				// If both fail, fallback or throw
				$parsed = null;
			}
		}

		$this->attributes['date_of_birth_inwords'] = $parsed
			? $parsed->format('l jS \\of F Y')
			: null;
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

	public function InvoiceMonths(){
		return $this->hasMany('App\InvoiceMonth');
	}

	public function Certificates(){
		return $this->hasMany('App\Certificate');
	}

	public function scopeSessionCurrent($query)
	{
		return $query->where('session_id', Auth::user()->academic_session);
	}

	public function lastInvoice()
	{
		return $this->hasOne('App\InvoiceMaster')->orderBy('id', 'desc');
	}

	public function attendances()
	{
		return $this->hasMany(StudentAttendance::class);
	}
}