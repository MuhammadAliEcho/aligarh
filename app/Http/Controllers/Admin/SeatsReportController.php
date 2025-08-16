<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classe;
use App\AcademicSession;
use Auth;

class SeatsReportController extends Controller
{



	public function GetSeatsStatus(){
		$data['classes']	=	Classe::orderBy('numeric_name')
									->with('Section')
									->get();
		$data['academic_session']	=	AcademicSession::find(Auth::user()->academic_session);
		return view('admin.printable.seats_status_report', $data);
	}

}
