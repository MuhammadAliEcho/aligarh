<?php

namespace App\Http\Controllers\Admin;

use App\Classe;
use App\Section;
use App\Quiz;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Teacher;
use Illuminate\Support\Facades\Validator;


class QuizController extends Controller
{
    public function index()
    {
        $data['teachers'] = Teacher::select('id', 'name')->get();
        $getClassWithSections = $this->getClassWithSections();
        $data['classes'] = $getClassWithSections['classes'];
        $data['sections'] = $getClassWithSections['sections'];

        return view('admin.quiz', $data);
    }

    public function GetData(Request $request)
    {
        $quizzes = Quiz::with('class:id,name', 'section:id,name', 'teacher:id,name', 'quizResults')->SelfSession();

        if ($request->filled('search_quiz')) {
            $search = $request->input('search_quiz');

            $quizzes->where(
                fn($query) =>
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('date', 'like', "%{$search}%")
                    ->orWhere('total_marks', 'like', "%{$search}%")
                    ->orWhereHas(
                        'class',
                        fn($q) =>
                        $q->where('name', 'like', "%{$search}%")
                    )
                    ->orWhereHas(
                        'section',
                        fn($q) =>
                        $q->where('name', 'like', "%{$search}%")
                    )
                    ->orWhereHas(
                        'teacher',
                        fn($q) =>
                        $q->where('name', 'like', "%{$search}%")
                    ),
            );
        }

        $quizzes = $request->filled('per_page') ? $quizzes->paginate($request->input('per_page')) : $quizzes->get();

        return response()->json($quizzes);
    }
    public function create(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'title'         => 'required|string|max:255',
                'date'          => 'required|date|date_format:Y-m-d',
                'class'         => 'required|exists:classes,id',
                'section'       => 'nullable|exists:sections,id',
                'teacher'       => 'nullable|exists:teachers,id',
                'total_marks'   => 'required|numeric|between:0,200',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'toastrmsg' => [
                        'type' => 'Quiz',
                        'title' => 'Quiz',
                        'msg' => 'There was an issue while Creating Quiz',
                    ],
                ]);
        }

        Quiz::create([
            'title'                => $request->input('title'),
            'date'                 => $request->input('date'),
            'class_id'             => $request->input('class'),
            'section_id'           => $request->input('section'),
            'teacher_id'           => $request->input('teacher'),
            'total_marks'          => $request->input('total_marks'),
        ]);

        return redirect('quizzes')->with([
            'toastrmsg' => [
                'type' => 'success',
                'title' => 'Quiz',
                'msg' => 'Quiz created successfully',
            ],
        ]);
    }

    public function edit($id)
    {
        $data['quiz'] = Quiz::with('class:id,name', 'section:id,name', 'teacher:id,name', 'quizResults:id,quiz_id')->findOrFail($id);
        $getClassWithSections = $this->getClassWithSections();
        $data['classes'] = $getClassWithSections['classes'];
        $data['sections'] = $getClassWithSections['sections'];
        $data['teachers'] = Teacher::select('id', 'name')->get();

        return view('admin.edit_quiz', $data);
    }

    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $request->validate([
            'title'         => 'required|string|max:255',
            'section'       => 'nullable|exists:sections,id',
            'teacher'       => 'nullable|exists:teachers,id',
            'class'         => 'sometimes|required|exists:classes,id',
            'date'          => 'required|date|date_format:Y-m-d',
            'total_marks'   => 'required|numeric|between:0,200',
        ]);

        $quiz->update([
            'title'       => $request->title,
            'date'        => $request->date,
            'section_id'  => $request->section,
            // 'section_id'  => $request->section?? $quiz->section_id,
            'class_id'    => $request->class ?? $quiz->class_id,
            'teacher_id'  => $request->teacher,
            'total_marks' => $request->total_marks
        ]);

        return redirect('quizzes')->with([
            'toastrmsg' => [
                'type' => 'success',
                'title' => 'Quiz',
                'msg' => 'Quiz Updated successfully',
            ],
        ]);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:quizzes,id',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'toastrmsg' => [
                        'type' => 'Error',
                        'title' => 'Quiz',
                        'msg' => 'There was an issue while Deleting Quiz',
                    ],
                ]);
        }

        $Quiz = Quiz::with('quizResults')->find($request->input('id'));
        $Quiz->quizResults()->delete();
        $Quiz->delete();


        return redirect('attendance-leave')->with([
            'toastrmsg' => [
                'type' => 'success',
                'title' => 'Quiz',
                'msg' => 'Deleted Successfully',
            ],
        ]);
    }

    private function getClassWithSections()
    {
        $data['classes'] = Classe::select('id', 'name')->get();
        foreach ($data['classes'] as $class) {
            $data['sections']['class_' . $class->id] = Section::select('name', 'id')->where(['class_id' => $class->id])->get();
        }

        return $data;
    }
}
