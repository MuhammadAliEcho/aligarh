<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\QuizResult;
use App\Quiz;
use App\Student;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class QuizResultController extends Controller
{
    public function Index($id)
    {
        $quiz = Quiz::with('teacher:id,name', 'class:id,name', 'section:id,name')
            ->select('id', 'title', 'section_id', 'class_id', 'teacher_id', 'total_marks')
            ->findOrFail($id);

        $studentsQuery = Student::SessionCurrent();
        if ($quiz->section_id === null) {
            $studentsQuery->where('class_id', $quiz->class_id);
        } else {
            $studentsQuery->where('section_id', $quiz->section_id);
        }

        $students = $studentsQuery->select('id', 'name', 'gr_no')->get();
        $results = QuizResult::where('quiz_id', $quiz->id)->get()->keyBy('student_id');

        $studentsData = $students->map(function ($student) use ($results) {
            $result = $results->get($student->id);

            return [
                'student_id'   => $student->id,
                'gr_no'        => $student->gr_no,
                'name'         => $student->name,
                'obtain_marks' => optional($result)->obtain_marks,
                'present'      => optional($result)->present,
            ];
        });

        return response()->json([
            'quiz_id'   => $quiz->id,
            'title'     => $quiz->title,
            'teacher'   => optional($quiz->teacher)->name ?? 'N/A',
            'class'     => optional($quiz->class)->name ?? 'N/A',
            'section'   => optional($quiz->section)->name ?? 'All',
            'total_marks' => $quiz->total_marks,
            'students'  => $studentsData->values(),
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_id'              => 'required|uuid|exists:quizzes,id',
            'results'              => 'required|array|min:1',
            'results.*.student_id' => 'required|exists:students,id',
            'results.*.present'    => 'required|boolean|in:0,1',
            'results.*.obtain_marks' => 'nullable|numeric|min:0|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $quizId = $request->quiz_id;
        $results = $request->results;

        $quiz = Quiz::select('id', 'total_marks')->findOrFail($quizId);

        foreach ($results as $index => $result) {
            $isPresent = (bool) $result['present'];
            $marks = $result['obtain_marks'];

            // If student is present, obtain_marks must be provided and within range
            if ($isPresent) {
                if (!is_numeric($marks)) {
                    return response()->json([
                        'errors' => [
                            "results.$index.obtain_marks" => ["Obtained marks are required for present students."]
                        ]
                    ], 422);
                }

                if ($marks > $quiz->total_marks) {
                    return response()->json([
                        'errors' => [
                            "results.$index.obtain_marks" => ["Obtained marks cannot exceed total marks ({$quiz->total_marks})."]
                        ]
                    ], 422);
                }
            } else {
                // If absent, marks must be null
                $results[$index]['obtain_marks'] = null;
            }
        }

        $existing = QuizResult::where('quiz_id', $quizId)
            ->pluck('id', 'student_id');

        $toInsert = [];
        $toUpdate = [];

        foreach ($results as $result) {
            $studentId = $result['student_id'];
            $record = [
                'quiz_id'      => $quizId,
                'student_id'   => $studentId,
                'obtain_marks' => $result['obtain_marks'],
                'present'      => $result['present'],
                'updated_at'   => now(),
            ];

            if ($existing->has($studentId)) {
                $record['id'] = $existing[$studentId];
                $toUpdate[] = $record;
            } else {
                $record['id'] = (string) Str::uuid();
                $record['created_at'] = now();
                $toInsert[] = $record;
            }
        }

        DB::transaction(function () use ($toInsert, $toUpdate) {
            collect($toInsert)->chunk(200)->each(function ($chunk) {
                QuizResult::insert($chunk->toArray());
            });

            collect($toUpdate)->chunk(200)->each(function ($chunk) {
                foreach ($chunk as $data) {
                    QuizResult::where('id', $data['id'])->update([
                        'obtain_marks' => $data['obtain_marks'],
                        'present'      => $data['present'],
                        'updated_at'   => $data['updated_at'],
                    ]);
                }
            });
        });

        return response()->json(['message' => 'Quiz results processed successfully'], 201);
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
