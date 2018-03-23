<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Larapack\ConfigWriter\Repository as ConfigWriter;

class FeeScenarioController extends Controller
{

	protected $data, $request, $feeses;


	public function __Construct($Routes){
		$this->data['root'] = $Routes;
	}


	public function Index(){
		return view('admin.fee_scenario', $this->data);
	}

	public function UpdateScenario(Request $request){

		$this->request = $request;

		$this->validate($request, [
			'tuition_fee'  =>  'required',
			'fee'	=>	'required',
		]);

		$ConfigWriter = new ConfigWriter('feeses');
		$ConfigWriter->set($this->SetFeeses());
		$ConfigWriter->save();

		return redirect('fee-scenario')->with([
			'toastrmsg' => [
				'type' => 'success', 
				'title'  =>  'System Settings',
				'msg' =>  'Feeses Updated For New Students'
			]
		]);
	}

	protected function SetFeeses(){
		$this->feeses = [
				'compulsory'	=>	[
					'tuition_fee'	=>	(int)$this->request->input('tuition_fee'),
				],
			];

		foreach ($this->request->input('fee') as $key => $value) {
			$this->feeses['additional_fee'][$key]['fee_name']	=	$value['fee_name'];
			$this->feeses['additional_fee'][$key]['amount']	=	(int)$value['amount'];
		}

		$this->feeses['additional_fee'] = json_encode($this->feeses['additional_fee']);

		return $this->feeses;
	}


}
