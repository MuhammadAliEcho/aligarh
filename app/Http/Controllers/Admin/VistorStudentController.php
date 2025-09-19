<?php

namespace App\Http\Controllers\Admin;

use App\Classe;
use App\Section;
use Illuminate\Http\Request;
use App\Model\VisitorStudent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class VistorStudentController extends Controller
{
    public function index(Request $request)
    {
        $data['classes'] = Classe::select('id', 'name')->get();
        foreach ($data['classes'] as $key => $class) {
            $data['sections']['class_' . $class->id] = Section::select('name', 'id')->where(['class_id' => $class->id])->get();
        }
        return view('admin.vistor_students', $data);
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
                    ->orWhere('guardian_relation', 'like', "%{$search}%")
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


    public function create(Request $request )
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'              => 'required|string|max:255',
                'father_name'       => 'required|string',
                'class'             => 'required|exists:classes,id',
                'email'             => 'required|unique:visitor_students,email',
                'section'           => 'nullable|exists:sections,id',
                'phone'             => 'required|string',
                'gender'             => 'required|string|in:Male,Female',
                'address'           => 'required|string',
                'seeking_class'     => 'required|string',
                'place_of_birth'    => 'required|string',
                'guardian_relation' => 'required|string',
                'last_school'       => 'required|string',
                'date_of_birth'     => 'required|date|date_format:Y-m-d',
                'date_of_visiting'  => 'required|date|date_format:Y-m-d',
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
                        'msg' => 'There was an issue while Creating Visitor Student',
                    ],
                ]);
        }

        VisitorStudent::create([
            'name'                  => $request->input('name'),
            'father_name'           => $request->input('father_name'),
            'class_id'              => $request->input('class'),
            'section_id'            => $request->input('section'),
            'phone'                 => $request->input('phone'),
            'email'                 => $request->input('email'),
            'gender'                => $request->input('gender'),
            'seeking_class'         => $request->input('seeking_class'),
            'address'               => $request->input('address'),
            'place_of_birth'        => $request->input('place_of_birth'),
            'guardian_relation'     => $request->input('guardian_relation'),
            'date_of_birth'         => $request->input('date_of_birth'),
            'last_school'           => $request->input('last_school'),
            'date_of_visiting'      => $request->input('date_of_visiting'),
        ]);

        return redirect('visitors')->with([
            'toastrmsg' => [
                'type' => 'success',
                'title' => 'Visitor Student',
                'msg' => 'Visitor Student created successfully',
            ],
        ]);

    }
}
