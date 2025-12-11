<?php

namespace App\Http\Controllers\Admin;

use App\Model\Classe;
use App\Model\Section;
use Illuminate\Http\Request;
use App\Model\VisitorStudent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class VistorStudentController extends Controller
{
    public function index(Request $request)
    {
        $data['classes'] = Classe::select('id', 'name')->get();
        return view('admin.visitor_students', $data);
    }

    public function grid(Request $request)
    {
        $visitorStudents = VisitorStudent::with([
            'StdClass:id,name',
        ]);

        if ($request->filled('search_visitors')) {
            $search = $request->input('search_visitors');

            $visitorStudents->where(
                fn($query) =>
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('gender', 'like', "%{$search}%")
                    ->orWhere('father_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('last_school', 'like', "%{$search}%")
                    ->orWhere('seeking_class', 'like', "%{$search}%")
                    ->orWhere('religion', 'like', "%{$search}%")
                    ->orWhere('place_of_birth', 'like', "%{$search}%")
                    ->orWhere('date_of_birth', 'like', "%{$search}%")
                    ->orWhereHas(
                        'StdClass',
                        fn($q) =>
                        $q->where('name', 'like', "%{$search}%")
                    )
            );
        }

        $visitorStudents = $request->filled('per_page') ? $visitorStudents->paginate($request->input('per_page')) : $visitorStudents->get();

        return response()->json($visitorStudents);
    }


    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'              => 'required|string|max:255',
                'father_name'       => 'required|string',
                'class'             => 'required|exists:classes,id',
                'email'             => 'nullable|unique:visitor_students,email',
                'religion'           => 'required|string',
                'phone'             => 'required|string',
                'gender'             => 'required|string|in:Male,Female',
                'address'           => 'required|string',
                'seeking_class'     => 'required|string',
                'place_of_birth'    => 'required|string',
                'last_school'       => 'required|string',
                'date_of_birth'     => 'required|date|date_format:Y-m-d',
                'date_of_visiting'  => 'required|date|date_format:Y-m-d',
                'remarks'           => 'nullable|string|max:1000',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'toastrmsg' => [
                        'type' => 'Visitor Student',
                        'title' => 'Visitor Student',
                        'msg' => __('modules.visitor_student_create_error'),
                    ],
                ]);
        }

        VisitorStudent::create([
            'name'                  => $request->input('name'),
            'father_name'           => $request->input('father_name'),
            'class_id'              => $request->input('class'),
            'religion'              => $request->input('religion'),
            'phone'                 => $request->input('phone'),
            'email'                 => $request->input('email'),
            'gender'                => $request->input('gender'),
            'seeking_class'         => $request->input('seeking_class'),
            'address'               => $request->input('address'),
            'place_of_birth'        => $request->input('place_of_birth'),
            'date_of_birth'         => $request->input('date_of_birth'),
            'last_school'           => $request->input('last_school'),
            'date_of_visiting'      => $request->input('date_of_visiting'),
            'remarks'               => $request->input('remarks'),
        ]);

        return redirect('visitors')->with([
            'toastrmsg' => [
                'type' => 'success',
                'title' => 'Visitor Student',
                'msg' => __('modules.visitor_student_create_success'),
            ],
        ]);
    }
    public function edit(Request $request, $id)
    {
        $data['classes'] = Classe::select('id', 'name')->get();
        $data['visitorStudents'] = VisitorStudent::findorFail($id);

        return view('admin.edit_visitor_student', $data);
    }

    public function GetProfile(Request $request, $id)
    {
        $data['visitorStudents'] = VisitorStudent::with('StdClass:id,name')->findorFail($id);
        return view('admin.visitor_student_profile', $data);
    }

    public function update(Request $request, $id)
    {
        $visitorStudents = VisitorStudent::findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            [
                'name'              => 'sometimes|required|string|max:255',
                'father_name'       => 'sometimes|required|string',
                'class'             => 'sometimes|required|exists:classes,id',
                'email'             => 'nullable|email|unique:visitor_students,email,' . $id,
                'religion'          => 'sometimes|required|string',
                'phone'             => 'sometimes|required|string',
                'gender'            => 'sometimes|required|string|in:Male,Female',
                'address'           => 'sometimes|required|string',
                'seeking_class'     => 'sometimes|required|string',
                'place_of_birth'    => 'sometimes|required|string',
                'last_school'       => 'sometimes|required|string',
                'date_of_birth'     => 'sometimes|required|date|date_format:Y-m-d',
                // 'date_of_visiting'  => 'required|date|date_format:Y-m-d',
                'remarks'           => 'nullable|string|max:1000',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'toastrmsg' => [
                        'type' => 'Visitor Student',
                        'title' => 'Visitor Student',
                        'msg' => __('modules.visitor_student_update_error'),
                    ],
                ]);
        }

        $visitorStudents->update([
            'name'              => $request->input('name'),
            'father_name'       => $request->input('father_name'),
            'class_id'          => $request->input('class'),
            'religion'          => $request->input('religion'),
            'phone'             => $request->input('phone'),
            'email'             => $request->input('email'),
            'gender'            => $request->input('gender'),
            'seeking_class'     => $request->input('seeking_class'),
            'address'           => $request->input('address'),
            'place_of_birth'    => $request->input('place_of_birth'),
            'date_of_birth'     => $request->input('date_of_birth'),
            'last_school'       => $request->input('last_school'),
            'remarks'           => $request->input('remarks'),
            // 'date_of_visiting'  => $request->input('date_of_visiting'),
        ]);

        return redirect('visitors')->with([
            'toastrmsg' => [
                'type' => 'success',
                'title' => 'Visitor Student',
                'msg' => __('modules.visitor_student_update_success'),
            ],
        ]);
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        VisitorStudent::findorFail($id)->delete();
        return redirect('visitors')->with([
            'toastrmsg' => [
                'type' => 'success',
                'title' => 'Visitor Student',
                'msg' => __('modules.visitor_student_delete_success'),
            ],
        ]);
    }
}
