<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\QuizResult;
use App\Quiz;
use App\Student;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class QuizResultController extends Controller
{
    public function Index($id)
    {
        $quiz = Quiz::select('title', 'section_id', 'class_id')->findOrFail($id);

        $studentsQuery = Student::SessionCurrent();
        if ($quiz->section_id === null) {
            $studentsQuery->where('class_id', $quiz->class_id);
        } else {
            $studentsQuery->where('section_id', $quiz->section_id);
        }

        $students = $studentsQuery->get();

        return response()->json($students);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_id'                   => 'required|uuid|exists:quizzes,id',
            'results'                   => 'required|array|min:1',
            'results.*.student_id'      => 'required|exists:students,id',
            'results.*.obtain_marks'    => 'required|numeric|between:0,200',
            'results.*.present'         => 'required|boolean|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $quizId = $request->quiz_id;
        $results = collect($request->results)->map(function ($result) use ($quizId) {
            return [
                'id'           => (string) Str::uuid(),
                'quiz_id'      => $quizId,
                'student_id'   => $result['student_id'],
                'obtain_marks' => $result['obtain_marks'],
                'present'      => $result['present'],
                'created_at'   => now(),
                // 'updated_at'   => now(),
            ];
        });

        $chunks = $results->chunk(200);
        foreach ($chunks as $chunk) {
            QuizResult::insert($chunk->toArray());
        }

        return response()->json(['message' => 'Quiz results saved'], 201);
    }

    // public function show($id)
    // {
    //     $quizResult = QuizResult::findOrFail($id);
    //     return response()->json($quizResult);
    // }

    // public function update(Request $request, $id)
    // {
    //     $quizResult = QuizResult::findOrFail($id);

    //     $request->validate([
    //         'obtain_marks'  => 'sometimes|string|max:5',
    //         'present'       => 'sometimes|boolean',
    //     ]);

    //     $quizResult->update($request->only(['obtain_marks', 'present']));

    //     return response()->json($quizResult);
    // }

    // public function delete($id)
    // {
    //     $quizResult = QuizResult::findOrFail($id);
    //     $quizResult->delete();

    //     return response()->json(['message' => 'Quiz result deleted']);
    // }
}
