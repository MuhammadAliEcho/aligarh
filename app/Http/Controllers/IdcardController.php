<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use Auth;

class IdcardController extends Controller {

    public function StudentIdcard(Request $request){
        
        $student = Student::paginate(4);

        return view('admin.printable.idcard_student', ['student' => $student]);
    }
}
