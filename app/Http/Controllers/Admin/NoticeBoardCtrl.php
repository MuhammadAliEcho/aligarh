<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\NoticeBoard;
use App\SmsLog;
use App\BulkSms;
use Auth;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class NoticeBoardCtrl extends Controller
{
	public function Index(){
		/*
			$username = "alisweet04" ;
			$password = "a_03132045991" ;
			$url = "http://lifetimesms.com/credit?username=".$username."&password=".$password;
			//Curl start 
			$ch = curl_init(); 
			$timeout = 30;
			curl_setopt ($ch, CURLOPT_URL, $url); 
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$response = curl_exec($ch);
			curl_close($ch);
			$data['smscredit'] = $response;
		*/
		$data['notices'] = NoticeBoard::all();
	    return view('admin.notice_board', $data);
	}

	public function CreateNotice(Request $request){
		$this->PostValidate($request);
		$NoticeBoard = new NoticeBoard;
		$this->SetAttributes($NoticeBoard, $request);
		$NoticeBoard->save();

		return redirect('noticeboard')->with([
			'toastrmsg' => [
				'type' => 'success', 
				'title'  =>  'Notice Board',
				'msg' =>  'Create Successfull'
			]
		]);
	}

	public function DeleteNotice(Request $request){

		$NoticeBoard = NoticeBoard::findOrfail($request->input('id'));
		$NoticeBoard->delete();

		if ($request->ajax()) {
			return  response(['type' => 'success','title'  =>  'Notice Board','msg' =>  'Notice Removed']);
		} else { 
			return redirect('routines')->with([
				'toastrmsg' => [
					'type' => 'success', 
					'title'  =>  'Notice Board',
					'msg' =>  'Notice Removed'
				]
			]);
		}
	}

	protected function PostValidate($request){
		$this->validate($request, [
			'title'  =>  'required',
			'notice'  =>  'required',
			'till_date' =>  'required',
		]);
	}

	protected function SetAttributes($NoticeBoard, $request){
		$NoticeBoard->title = $request->input('title');
		$NoticeBoard->notice = $request->input('notice');
		$NoticeBoard->till_date = Carbon::createFromFormat('d/m/Y', $request->input('till_date'))->toDateString();
		$NoticeBoard->user_id = Auth::user()->id;
	}

}
