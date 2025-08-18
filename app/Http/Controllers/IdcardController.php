<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;

class IdcardController extends Controller {

    public function StudentIdcard(Request $request, $id){
        
        $student = Student::findOrFail($id);

        return view('admin.printable.idcard_student', ['student' => $student]);
    }
}
