<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Student;

class IdcardController extends Controller {

    public function StudentIdcard(Request $request, $id){
        
        $student = Student::with('Guardian:id,phone', 'AcademicSession:id,title')->findOrFail($id);
        return view('admin.printable.idcard_student', ['student' => $student]);
    }
}
