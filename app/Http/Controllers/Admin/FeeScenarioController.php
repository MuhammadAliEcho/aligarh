<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Larapack\ConfigWriter\Repository as ConfigWriter;

use App\Student;
use App\AdditionalFee;

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
			'type'	=>	'required',
			'tuition_fee'  =>  'required',
			'fee'	=>	'required',
		]);

		switch ($request->input('type')) {
			case 1:
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
				break;
			
			case 2:
				return $this->ApplyAllStudent();
				break;
		}


	}

	protected function SetFeeses(){
		$this->feeses = [
				'compulsory'	=>	[
					'tuition_fee'	=>	(int)$this->request->input('tuition_fee'),
					'late_fee'		=>	(int)$this->request->input('late_fee')
				],
			];

		foreach ($this->request->input('fee') as $key => $value) {
			$this->feeses['additional_fee'][$key]['fee_name']	=	$value['fee_name'];
			$this->feeses['additional_fee'][$key]['amount']	=	(int)$value['amount'];
			$this->feeses['additional_fee'][$key]['active']	=	isset($value['active'])? 1 : 0;
			$this->feeses['additional_fee'][$key]['onetime']	=	isset($value['onetime'])? 1 : 0;
		}

		$this->feeses['additional_fee'] = json_encode($this->feeses['additional_fee']);

		return $this->feeses;
	}

	protected function ApplyAllStudent(){

		Student::Active()->chunk(200, function($students){
			foreach ($students as $key => $student) {
				$student->tuition_fee	=	$this->request->input('tuition_fee');
				$student->total_amount	=	$this->request->input('total_amount');
				$student->net_amount	=	($this->request->input('total_amount') - $student->discount);
				$student->late_fee	=	$this->request->input('late_fee');
				$student->save();

				$student->AdditionalFee()->delete();
				if (COUNT($this->request->input('fee')) >= 1) {
					foreach ($this->request->input('fee') as $key => $value) {
						$AdditionalFee = new AdditionalFee;
						$AdditionalFee->student_id = $student->id;
						$AdditionalFee->fee_name = $value['fee_name'];
						$AdditionalFee->amount = $value['amount'];
						$AdditionalFee->onetime = isset($value['onetime'])? 1 : 0;
						$AdditionalFee->active = isset($value['active'])? 1 : 0;
						$AdditionalFee->save();
					}
				}
			}
		});

		return redirect('fee-scenario')->with([
			'toastrmsg' => [
				'type' => 'success', 
				'title'  =>  'System Settings',
				'msg' =>  'Feeses Updated All Students'
			]
		]);

	}


}
