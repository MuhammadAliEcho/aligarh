<?php

namespace App\Http\Controllers\Admin;


use App\Classe;
use App\Student;
use App\Teacher;
use App\Employee;
use App\Guardian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendMsgJob;
use Illuminate\Support\Facades\Validator;

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
                $data = Classe::with('Students')->get()->map(function ($classe) {
                    return [
                        'id' => $classe->id,
                        'class_name' => $classe->name,
                        'students' => $classe->students->map(function ($student) {
                            return [
                                'id' => $student->id,
                                'name' => $student->name
                            ];
                        })
                    ];
                });
                break;
            case 'teachers':
                $data = Teacher::all();
                break;
            case 'guardians':
                $data = Guardian::all();
                break;
            case 'employees':
                $data = Employee::NotDeveloper()->get();
                break;
            default:
                return response()->json(['error' => 'Invalid type provided.'], 400);
        }

        return response()->json($data);
    }

    public  function send(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'type' => 'required|in:students,teachers,guardians,employees',
            'selected_class_id' => 'nullable|exists:classes,id',
            'selected_student_id' => 'nullable|exists:students,id',
            'selected_guardian_id' => [
                'nullable',
                fn($attribute, $value, $fail) => $value !== null && $value !== 'all' && !Guardian::where('id', $value)->exists()
                    ? $fail('The selected Guardian ID is invalid.')
                    : null,
            ],
            'selected_employee_id' => [
                'nullable',
                fn($attribute, $value, $fail) => $value !== null && $value !== 'all' && !Employee::where('id', $value)->exists()
                    ? $fail('The selected Employee ID is invalid.')
                    : null,
            ],
            'selected_teacher_id' => [
                'nullable',
                fn($attribute, $value, $fail) => $value !== null && $value !== 'all' && !Teacher::where('id', $value)->exists()
                    ? $fail('The selected Teacher ID is invalid.')
                    : null,
            ],
        ]);

        if ($request->input('type') === 'guardians' && is_null($request->input('selected_guardian_id'))) {
            $validator->after(fn($validator) => $validator->errors()->add('selected_guardian_id', 'Plese select a guardian to send the message.'));
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // dd($request->all());


        $type = $request->input('type');
        $message = $request->input('message');
        $classId = $request->input('selected_class_id');
        $studentId = $request->input('selected_student_id');
        $guardianId = $request->input('selected_guardian_id');
        $teacherId = $request->input('selected_teacher_id');
        $employeeId = $request->input('selected_employee_id');

        switch ($type) {

            case 'students':
                $students = collect();

                if ($studentId) {
                    $student = Student::with(['Guardian:id,email,phone', 'StdClass:id,name', 'section:id,name'])->find($studentId);
                    if ($student) {
                        $students->push($student);
                    }
                } elseif ($classId) {
                    $students = Student::with(['Guardian:id,email,phone', 'StdClass:id,name', 'section:id,name'])
                        ->where('class_id', $classId)
                        ->get();
                }

                foreach ($students as $student) {
                    $tokens = $this->buildStudentTokens($student);
                    $personalMessage = $this->replaceTokens($message, $tokens);

                    SendMsgJob::dispatch(
                        optional($student->Guardian)->email,
                        optional($student->Guardian)->phone,
                        optional($student->Guardian)->phone,
                        $personalMessage
                    );
                }

                if ($students->isNotEmpty()) {
                    return $this->returnNotifications();
                }

                break;

            case 'guardians':
                $guardians = $guardianId === 'all'
                    ? Guardian::all()
                    : Guardian::where('id', $guardianId)->get();

                foreach ($guardians as $guardian) {
                    $tokens = $this->buildGuardianTokens($guardian);
                    $personalMessage = $this->replaceTokens($message, $tokens);

                    SendMsgJob::dispatch(
                        $guardian->email,
                        $guardian->phone,
                        $guardian->phone,
                        $personalMessage
                    );
                }

                if ($guardians->isNotEmpty()) {
                    return $this->returnNotifications();
                }
                break;

            case 'teachers':
                $teachers = $teacherId === 'all'
                    ? Teacher::all()
                    : Teacher::where('id', $teacherId)->get();

                foreach ($teachers as $teacher) {
                    $tokens = $this->buildTeacherTokens($teacher);
                    $personalMessage = $this->replaceTokens($message, $tokens);

                    SendMsgJob::dispatch(
                        $teacher->email,
                        $teacher->phone,
                        $teacher->phone,
                        $personalMessage
                    );
                }

                if ($teachers->isNotEmpty()) {
                    return $this->returnNotifications();
                }
                break;
            case 'employees':
                $employees = $employeeId === 'all'
                    ? Employee::all()
                    : Employee::where('id', $employeeId)->get();

                foreach ($employees as $employee) {
                    $tokens = $this->buildEmployeeTokens($employee);
                    $personalMessage = $this->replaceTokens($message, $tokens);

                    SendMsgJob::dispatch(
                        $employee->email,
                        $employee->phone,
                        $employee->phone,
                        $personalMessage
                    );
                }

                if ($employees->isNotEmpty()) {
                    return $this->returnNotifications();
                }
                break;
            default:
                return redirect()->back()
                    ->withInput()
                    ->with([
                        'toastrmsg' => [
                            'type' => 'error',
                            'title' => 'Error',
                            'msg' => 'There was an issue while Sending message'
                        ]
                    ]);
        }
    }


    private function returnNotifications()
    {
        return redirect('notifications')->with([
            'toastrmsg' => [
                'type' => 'success',
                'title'  =>  'Message Send',
                'msg' =>  'Messages sent to successfully'
            ]
        ]);
    }

    private function replaceTokens(string $message, array $tokens): string
    {
        foreach ($tokens as $key => $value) {
            $message = str_replace("{{$key}}", $value ?? '', $message);
        }
        return $message;
    }

    private function buildStudentTokens($student): array
    {
        return [
            'student_name' => $student->name,
            'class_name'   => optional($student->StdClass)->name,
            'section_name' => optional($student->section)->name,
            'father_name'  => $student->father_name,
            'address'      => $student->address,
            'gender'       => $student->gender,
            'gr_no'        => $student->gr_no,
        ];
    }

    private function buildGuardianTokens($guardian): array
    {
        return [
            'guardian_name' => $guardian->name,
            'email'         => $guardian->email,
            'phone'         => $guardian->phone,
            'address'       => $guardian->address,
            'profession'    => $guardian->profession,
            'income'        => $guardian->income,
        ];
    }

    private function buildTeacherTokens($teacher): array
    {
        return [
            'teacher_name'  => $teacher->name,
            'qualification' => $teacher->qualification,
            'gender'        => $teacher->gender,
            'address'       => $teacher->address,
            'phone'         => $teacher->phone,
            'subject'       => $teacher->subject,
        ];
    }

    private function buildEmployeeTokens($employee): array
    {
        return [
            'employee_name'  => $employee->name,
            'qualification'  => $employee->qualification,
            'gender'         => $employee->gender,
            'address'        => $employee->address,
            'email'          => $employee->email,
            'role'           => $employee->role,
            'phone'          => $employee->phone,
        ];
    }
}
