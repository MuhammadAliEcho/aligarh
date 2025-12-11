<?php

namespace App\Http\Controllers\Api\Guardian;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Model\NoticeBoard;

class NoticeBoardController extends Controller
{
	protected $Notices;

	public function GetNotices(Request $request){

        $this->Notices = NoticeBoard::select('id', 'till_date', 'title', 'notice', 'created_at')->orderBy('id', 'desc')->simplePaginate($request->input('per_page'));

        return response()->json($this->Notices, 200, ['Content-Type' => 'application/json'], JSON_NUMERIC_CHECK);
	}

}
