<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Model\Exam;
use App\Model\Classe;
use App\Model\Subject;
use App\Model\Student;
use App\Model\StudentResult;
use App\Model\ExamRemark;
use App\Model\SubjectResultAttribute;
use Carbon\Carbon;
use DB;
use Auth;
use Validator;

class ManageStudentResultCtrl extends Controller
{
    public function Index(array $data = [], $job = '')
    {
        $data['exams'] = Exam::Active()->CurrentSession()->get();
        $data['classes'] = Classe::select('id', 'name')->get();
        foreach ($data['classes'] as $key => $class) {
            $data['subjects']['class_' . $class->id] = Subject::select('name', 'id')
                ->where(['class_id' => $class->id])
                ->Examinable()
                ->get();
        }
        $data['root'] = $job;
        return view('admin.manage_result', $data);
    }

    public function MakeResult(Request $request)
    {
        $this->validate($request, [
            'exam' => 'required',
            'class' => 'required|numeric',
            'subject' => 'required',
        ]);

        //$dbdate =	Carbon::createFromFormat('d/m/Y', $request->input('date'))->toDateString();

        $data['selected_exam'] = Exam::Active()->CurrentSession()->where('id', $request->input('exam'))->first();

        if ($data['selected_exam'] == null) {
            return redirect('manage-result')
                ->withInput()
                ->with([
                    'toastrmsg' => [
                        'type' => 'error',
                        'title' => __('modules.exams_title'),
                        'msg' => __('modules.exams_not_found'),
                    ],
                ]);
        }

        $data['selected_class'] = Classe::find($request->input('class'));
        $data['selected_subject'] = Subject::find($request->input('subject'));
        $data['result_attribute'] = SubjectResultAttribute::where([
            'exam_id' => $request['exam'],
            'subject_id' => $request['subject'],
        ])->first();

        $data['students'] = Student::select('id', 'name', 'gr_no')
            ->where(['class_id' => $request['class']])
            ->CurrentSession()
            ->Active()
            ->orderBy('name');

        if ($data['result_attribute']) {
            $data['students']->with([
                'StudentSubjectResult' => function ($query) use ($data) {
                    $query->where([
                        'subject_result_attribute_id' => $data['result_attribute']->id,
                    ]);
                },
            ]);
        }

        $data['students'] = $data['students']->get();

        if ($data['students']->isEmpty()) {
            return redirect('manage-result')
                ->withInput()
                ->with([
                    'toastrmsg' => [
                        'type' => 'error',
                        'title' => __('modules.exams_title'),
                        'msg' => __('modules.exams_students_not_found_session'),
                    ],
                ]);
        }

        $data['input'] = $request->input();
        $job = 'make';
        return $this->Index($data, $job);
    }

    public function UpdateResult(Request $request)
    {
        $this->validate($request, [
            'exam' => 'required',
            'subject' => 'required',
            'total_marks' => 'required',
            'students' => 'required',
            'attributes' => 'required',
        ]);

        // $dbdate =	Carbon::createFromFormat('d/m/Y', $request->input('date'))->toDateString();

        $result_attribute = SubjectResultAttribute::updateOrCreate(
            [
                'subject_id' => $request->input('subject'),
                'class_id' => $request->input('class'),
                'exam_id' => $request->input('exam'),
            ],
            [
                'total_marks' => $request->input('total_marks'),
                'attributes' => $request->input('attributes'),
            ],
        );

        foreach ($request['students'] as $k => $student) {
            $ExamRemark = ExamRemark::firstOrCreate([
                'exam_id' => $request->input('exam'),
                'class_id' => $request->input('class'),
                'student_id' => $k,
            ]);

            $obtain_marks = collect($student['obtain_marks']);
            StudentResult::updateOrCreate(
                [
                    'subject_result_attribute_id' => $result_attribute->id,
                    'student_id' => $k,
                    'exam_remark_id' => $ExamRemark->id,
                ],
                [
                    'subject_id' => $request->input('subject'),
                    'exam_id' => $request->input('exam'),
                    'obtain_marks' => $this->MakeObtainMarks($student['obtain_marks']),
                    'total_obtain_marks' => $obtain_marks->sum('marks'),
                ],
            );
        }
        return redirect('manage-result')->with([
            'toastrmsg' => [
                'type' => 'success',
                'title' => __('modules.exams_title'),
                'msg' => __('modules.exams_update_results_success'),
            ],
        ]);
    }

    protected function MakeObtainMarks($obtain_marks)
    {
        foreach ($obtain_marks as $key => $value) {
            $array[] = [
                'name' => $value['name'],
                'marks' => $value['marks'],
                'attendance' => $value['attendance'] == 'true' ? true : false,
            ];
        }
        return $array;
    }

    public function RemoveResult($request)
    {
        SubjectResultAttribute::findOrFail($request->input('SavedResultId'))->delete();
        StudentResult::where('subject_result_attribute_id', $request->input('SavedResultId'))->delete();
        return redirect('manage-result')->with([
            'toastrmsg' => [
                'type' => 'success',
                'title' => __('modules.exams_title'),
                'msg' => __('modules.exams_delete_results_success'),
            ],
        ]);
    }

    public function ResultAttributes(Request $request)
    {
        $this->validate($request, [
            'exam' => 'required',
            'class' => 'required',
        ]);

        $data['input'] = $request->input();
        $data['selected_exam'] = Exam::Active()->CurrentSession()->where('id', $request->input('exam'))->first();

        if ($data['selected_exam'] == null) {
            return redirect('manage-result')
                ->withInput()
                ->with([
                    'toastrmsg' => [
                        'type' => 'error',
                        'title' => __('modules.exams_title'),
                        'msg' => __('modules.exams_not_found'),
                    ],
                ]);
        }

        $data['selected_class'] = Classe::findOrFail($request->input('class'));

        $data['subject_result'] = SubjectResultAttribute::where(['exam_id' => $data['selected_exam']->id, 'class_id' => $data['selected_class']->id])
            ->with('Subject')
            ->get();

        $job = 'resultattributes';
        return $this->Index($data, $job);
    }

    public function MakeTranscript(Request $request)
    {
        $this->validate($request, [
            'exam' => 'required',
            'class' => 'required',
        ]);

        $data['input'] = $request->input();
        $data['selected_exam'] = Exam::Active()->CurrentSession()->where('id', $request->input('exam'))->first();

        if ($data['selected_exam'] == null) {
            return redirect('manage-result')
                ->withInput()
                ->with([
                    'toastrmsg' => [
                        'type' => 'error',
                        'title' => __('modules.exams_title'),
                        'msg' => __('modules.exams_not_found'),
                    ],
                ]);
        }

        $data['selected_class'] = Classe::findOrFail($request->input('class'));

        $data['transcripts'] = ExamRemark::where([
            'exam_id' => $data['selected_exam']->id,
            'class_id' => $data['selected_class']->id,
        ])
            ->with([
                'Student' => function ($qry) {
                    $qry->select('id', 'name', 'gr_no', 'father_name');
                },
            ])
            ->with([
                'StudentResult' => function ($qry) {
                    $qry->with('Subject')->with('SubjectResultAttribute');
                },
            ])
            ->get();

        return view('admin.make_transcript', $data);
    }

    public function SaveTranscript(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return [
                    'type' => 'error',
                    'title' => __('modules.student_results_title'),
                    'msg' => __('modules.exams_validation_error'),
                ];
            }

            $ExamRemark = ExamRemark::findOrFail($request->input('id'));
            $ExamRemark->remarks = $request->input('remarks');
            $ExamRemark->save();

            return [
                'type' => 'success',
                'title' => __('modules.student_results_title'),
                'msg' => __('modules.exams_update_results_success'),
            ];
        }

        return redirect('manage-result')->with([
            'toastrmsg' => [
                'type' => 'warning',
                'title' => __('modules.student_results_title'),
                'msg' => __('modules.exams_validation_error'),
            ],
        ]);
    }

    /**
     * Display bulk marks entry form
     */
    public function BulkMakeResult(Request $request)
    {
        $data['exams'] = Exam::Active()->CurrentSession()->get();
        $data['classes'] = Classe::select('id', 'name')->get();
        
        return view('admin.bulk_make_result', $data);
    }

    /**
     * Get students and subjects data for bulk entry (AJAX)
     */
    public function GetBulkStudents(Request $request)
    {
        $this->validate($request, [
            'exam' => 'required|numeric',
            'class' => 'required|numeric',
        ]);

        $exam = Exam::Active()->CurrentSession()->findOrFail($request->exam);
        $class = Classe::findOrFail($request->class);

        // Get all examinable subjects for the class with their result attributes
        $subjects = Subject::select('id', 'name')
            ->where('class_id', $request->class)
            ->Examinable()
            ->get()
            ->map(function ($subject) use ($request) {
                $resultAttribute = SubjectResultAttribute::where([
                    'subject_id' => $subject->id,
                    'exam_id' => $request->exam,
                    'class_id' => $request->class
                ])->first();
                
                $subject->subject_result_attribute = $resultAttribute;
                return $subject;
            });

        // Get all active students in the class for current session
        $students = Student::select('id', 'name', 'gr_no')
            ->where('class_id', $request->class)
            ->CurrentSession()
            ->Active()
            ->orderBy('name')
            ->with(['StudentSubjectResult' => function ($query) use ($request) {
                $query->whereHas('SubjectResultAttribute', function ($q) use ($request) {
                    $q->where(['exam_id' => $request->exam, 'class_id' => $request->class]);
                })->with('SubjectResultAttribute');
            }])
            ->get();

        // Count students with existing results
        $studentsWithResults = $students->filter(function($student) {
            return $student->StudentSubjectResult->count() > 0;
        })->count();

        return response()->json([
            'success' => true,
            'exam' => $exam,
            'class' => $class,
            'subjects' => $subjects,
            'students' => $students,
            'stats' => [
                'total_students' => $students->count(),
                'students_with_results' => $studentsWithResults,
            ],
        ]);
    }

    /**
     * Save bulk marks entry with transaction and upsert
     */
    public function BulkSaveResult(Request $request)
    {
        $this->validate($request, [
            'exam' => 'required|numeric',
            'class' => 'required|numeric',
            'bulk_data' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $userId = Auth::id();
            $now = now();

            // Arrays to collect records for batch upsert
            $resultAttributesData = [];
            $studentResultsData = [];
            $examRemarksData = [];

            // Process each subject's data
            foreach ($request->bulk_data as $subjectId => $subjectData) {
                // Skip if no attributes defined
                if (empty($subjectData['attributes'])) {
                    continue;
                }

                // Prepare result attribute record
                $resultAttributesData[] = [
                    'subject_id' => $subjectId,
                    'class_id' => $request->class,
                    'exam_id' => $request->exam,
                    'total_marks' => $subjectData['total_marks'],
                    'attributes' => ($subjectData['attributes']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // Get the result attribute ID after upsert (we'll handle this separately)
                $resultAttribute = SubjectResultAttribute::updateOrCreate(
                    [
                        'subject_id' => $subjectId,
                        'class_id' => $request->class,
                        'exam_id' => $request->exam
                    ],
                    [
                        'total_marks' => $subjectData['total_marks'],
                        'attributes' => ($subjectData['attributes']),
                    ]
                );

                // Process student marks for this subject
                if (!empty($subjectData['students'])) {
                    foreach ($subjectData['students'] as $studentId => $studentMarks) {
                        // Ensure exam remark exists
                        $examRemark = ExamRemark::firstOrCreate(
                            [
                                'exam_id' => $request->exam,
                                'class_id' => $request->class,
                                'student_id' => $studentId
                            ],
                            [
                                'remarks' => '',
                                'rank' => 0,
                            ]
                        );

                        // Calculate total obtain marks
                        $totalObtainMarks = 0;
                        $obtainMarksArray = [];
                        
                        foreach ($studentMarks['obtain_marks'] as $attributeMark) {
                            $marks = $attributeMark['attendance'] ? (float)$attributeMark['marks'] : 0;
                            $totalObtainMarks += $marks;
                            $obtainMarksArray[] = [
                                'name' => $attributeMark['name'],
                                'marks' => $marks,
                                'attendance' => $attributeMark['attendance']
                            ];
                        }

                        // Save student result
                        StudentResult::updateOrCreate(
                            [
                                'subject_result_attribute_id' => $resultAttribute->id,
                                'student_id' => $studentId,
                                'exam_remark_id' => $examRemark->id
                            ],
                            [
                                'subject_id' => $subjectId,
                                'exam_id' => $request->exam,
                                'obtain_marks' => ($obtainMarksArray),
                                'total_obtain_marks' => $totalObtainMarks,
                                'created_by' => $userId,
                                'updated_by' => $userId,
                            ]
                        );
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'type' => 'success',
                'title' => __('modules.student_results_title'),
                'msg' => __('modules.exams_update_results_success'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'type' => 'error',
                'title' => __('modules.student_results_title'),
                'msg' => __('messages.error_occurred') . ': ' . $e->getMessage(),
            ], 500);
        }
    }
}
