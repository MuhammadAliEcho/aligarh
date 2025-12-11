<?php

namespace App\Http\Controllers\Api\Guardian;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

//use Illuminate\Support\Facades\Auth;
use App\Model\Guardian;

class HomeController extends Controller
{

    public function Home(Request $request){
//                return $request->user()->token()->id;
//				return response('error',	404);
//				return $request->user();
		$images_base64 = [];
        $guardian   =    Guardian::where('id', $request->user()->foreign_id)
        ->with(['Student' => function($query){
            $query->with(['StdClass' => function($qry){
                $qry->select('id', 'name');
            }])
            ->with(['AdditionalFee' => function($qry){
                $qry->Active();
            }])
            ->Active();
        }])->first();

        foreach ($guardian->Student as $key => $student) {
			if($student->image_dir){
				$image = Storage::get($student->image_dir);
				$type = pathinfo($student->image_dir, PATHINFO_EXTENSION);
				$images_base64[$student->id] = 'data:image/' . $type . ';base64,' . base64_encode($image);
			}
        }

        return response()->json(['User' => ['User'  =>  $request->user(), 'Profile' =>  $guardian], 'Students' => $guardian->Student, 'ImagesBase64' => $images_base64], 200, ['Content-Type' => 'application/json'], JSON_NUMERIC_CHECK);
    }

}
