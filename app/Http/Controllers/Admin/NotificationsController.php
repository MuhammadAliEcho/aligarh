<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classe;
use App\Teacher;
use App\Employee;
use App\Guardian;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {


        return view('admin.notifications');
    }

    public function getData(Request $request)
    {
        switch ($request->input('type')) {
            case 'students':
                $data = Classe::with('Students')->get();
                break;
            case 'teachers':
                $data = Teacher::all();
                break;
            case 'guardians':
                $data = Guardian::all();
                break;
            case 'employees':
                $data = Employee::all();
                break;
            default:
                return response()->json(['error' => 'Invalid type provided.'], 400);
        }

        return response()->json($data);
    }
}
