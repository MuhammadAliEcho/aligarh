<?php

namespace App\Http\Controllers\Api\Guardian;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Model\Routine;
use App\Model\Classe;

class RoutineController extends Controller
{
	protected $Routines, $Classes;

	public function GetRoutines(Request $request){

        $this->Classes = Classe::select('id', 'name')->orderBy('id')->orderby('numeric_name')->get();

		$this->Routines		=	Routine::select('id', 'class_id', 'subject_id', 'day', 'from_time', 'to_time')->with(['Subject' => function($qry){
            $qry->select('id', 'name');
        }])->orderBy('id')->get();

		return response()->json(['Routines' =>  $this->Routines, 'Classes' => $this->Classes], 200, ['Content-Type' => 'application/json'], JSON_NUMERIC_CHECK);
	}

}
