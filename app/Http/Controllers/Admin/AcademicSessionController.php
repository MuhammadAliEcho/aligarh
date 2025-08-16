<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\AcademicSession;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AcademicSessionController extends Controller
{
  public function index(Request $request)
  {
    if ($request->ajax()) {
      return DataTables::eloquent(AcademicSession::select('id', 'title', 'start', 'end'))->make(true);
    }
    return view('admin.academic_sessions');
  }


  public function create(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        'start' => ['required', 'date', 'date_format:Y-m-d'],
        'end'   => ['required', 'date', 'date_format:Y-m-d', 'after:start'],
      ],
      [
        'end.after' => 'The end month must be after the start month.',
      ]
    );

    if ($validator->fails()) {
      return redirect()->back()
          ->withErrors($validator)
          ->withInput()
          ->with([
              'toastrmsg' => [
                  'type' => 'error', 
                  'title' => 'Academic Session',
                  'msg' => 'There was an issue while Updating Role'
              ]
          ]);
    }
    $data = $validator->validated();
    $data['title'] = Carbon::parse($data['start'])->year . '-' . Carbon::parse($data['end'])->year;

    $duplicate = AcademicSession::where('start', $data['start'])
                                ->where('end', $data['end'])
                                ->exists();

    if ($duplicate) {
        return redirect()->back()
            ->withErrors(['start' => 'This academic session already exists.'])
            ->withInput()
            ->with([
                'toastrmsg' => [
                    'type' => 'warning',
                    'title' => 'Academic Session',
                    'msg' => 'A session with the same start and end date already exists.'
                ]
            ]);
    }

    $AcademicSession =  AcademicSession::create($data);
    if(Auth::user()->hasRole('Developer')) {
      $user = Auth::user();
      $user->update(['allow_session' => collect($user->allow_session)->push((string) $AcademicSession->id)->unique()->values()->toArray()]);
    }
    return redirect()->back()->with([
        'toastrmsg' => [
            'type' => 'success', 
            'title' => 'Academic Session',
            'msg' => 'Academic Session Created Successfully'
        ]
    ]);
  }
}
