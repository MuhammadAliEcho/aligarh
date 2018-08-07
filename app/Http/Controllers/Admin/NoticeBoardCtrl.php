<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Http\Request;
use App\Http\Requests;
use App\NoticeBoard;
use App\SmsLog;
use App\BulkSms;
use Auth;
use Carbon\Carbon;
use Request;
use App\Http\Controllers\Controller;

class NoticeBoardCtrl extends Controller
{

	//  protected $Routes;
	protected $data, $NoticeBoard, $Request, $Input, $SmsLog;

	public function __Construct($Routes, $Request){
		$this->data['root'] = $Routes;
		// Illuminate\hTTP\Request;
		$this->Request = $Request;
		$this->Input = $Request->input();
	}

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
			$this->data['smscredit'] = $response;
*/
		$this->data['notices'] = NoticeBoard::all();
	    return view('admin.notice_board', $this->data);
	}

	public function CreateNotice(){
		$this->PostValidate();
		$this->NoticeBoard = new NoticeBoard;
		$this->SetAttributes();
		$this->NoticeBoard->save();

		return redirect('noticeboard')->with([
			'toastrmsg' => [
				'type' => 'success', 
				'title'  =>  'Notice Board',
				'msg' =>  'Create Successfull'
			]
		]);
	}

	public function DeleteNotice(){

		$this->NoticeBoard = NoticeBoard::findOrfail($this->Input['id']);
		$this->NoticeBoard->delete();

		if (Request::ajax()) {
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

	protected function PostValidate(){
		$this->validate($this->Request, [
			'title'  =>  'required',
			'notice'  =>  'required',
			'till_date' =>  'required',
		]);
	}

	protected function SetAttributes(){
		$this->NoticeBoard->title = $this->Input['title'];
		$this->NoticeBoard->notice = $this->Input['notice'];
		$this->NoticeBoard->till_date = Carbon::createFromFormat('d/m/Y', $this->Input['till_date'])->toDateString();
		$this->NoticeBoard->user_id = Auth::user()->id;
	}

}
