<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Larapack\ConfigWriter\Repository as ConfigWriter;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Student;
use App\Guardian;
use App\Teacher;
use App\Classe;
use App\Employee;
use App\SmsLog;

use Validator;
use Auth;


class SmsController extends Controller
{

	public function __Construct($Routes){
		$this->data['root'] = $Routes;
	}

	public function Index(){
		$this->data['Students']	=	Student::CurrentSession()->HaveCellNo()->active()->with('Guardian')->get();
		$this->data['Teachers']	=	Teacher::HaveCellNo()->get();
		$this->data['Employee']	=	Employee::HaveCellNo()->get();
		$this->data['Classe']	=	Classe::all();
		$this->data['availableSms']	=	config('systemInfo.available_sms');
		$this->data['smsValidity']	=	config('systemInfo.sms_validity');
	    return view('admin.sms_notifications', $this->data);
	}

	public function SendSms(Request $request){

		if($request->ajax()){

			$validator = Validator::make($request->all(), [
				'send_to' => 'required',
				'message' => 'required',
				'phoneinfo'	=>	'required',
			]);

			if ($validator->fails()) {
				return  [
					'errors'	=>	true,
					'toastrmsg'	=>	[
						'type'	=> 'error', 
						'title'	=>  'Notifications',
						'msg'	=>  'Something is wrong!'
					]
				];
			}

			$responseApi = $this->SendSmsAPI('0'.implode(',0', (array_pluck($request->input('phoneinfo'), 'no'))), $request->input('message'));
			$totalprice = 0;
			if(array_has($responseApi, 'totalprice')){
				$totalprice = $responseApi->totalprice;
				$this->UpdateAvailableSms(config('systemInfo.available_sms') - $totalprice);
			}

			SmsLog::create([
				'phone_info'	=>	json_encode($request->input('phoneinfo'), JSON_NUMERIC_CHECK),
				'message'	=>	$request->input('message'),
				'api_response'	=>	json_encode($responseApi),
				'total_price'	=>	$totalprice,
				'created_by'	=>	Auth::user()->id
			]);

			if($totalprice == 0){
				return	[
					'errors'	=>	true,
					'toastrmsg'	=>	[
						'type'	=> 'error', 
						'title'	=>  'Invalid SMS',
						'msg'	=>  'Contact service center'
					],
//					[$request->all(), '0'.implode(',0', (array_pluck($request->input('phoneinfo'), 'no')))],
				];
			}

			return	[
				'errors'	=>	false,
				'toastrmsg'	=>	[
					'type'	=> 'success', 
					'title'	=>  'Notifications',
					'msg'	=>  'Query Submitted'
				],
				'availableSms' => config('systemInfo.available_sms') - $totalprice,
			];
		}

		return redirect('smsnotifications')->with([
									'toastrmsg' => [
										'type'	=> 'warning', 
										'title'	=>  'Notifications',
										'msg'	=>  'Something is wrong!'
									]
								]);
	}

	public function SendBulkSms(Request $request){

		$raw = [
			'students' => 'Student',
			'guardians' => 'Guardian',
			'teachers' => 'Teacher',
			'emloys' => 'Employee',
		];

		if($request->ajax()){

			$validator = Validator::make($request->all(), [
				'bulk_to' => 'required',
				'message' => 'required',

				$request->input('bulk_to') => 'required',
			]);

			if ($validator->fails()) {
				return  [
					'errors'	=>	true,
					'toastrmsg'	=>	[
						'type'	=> 'error', 
						'title'	=>  'Notifications',
						'msg'	=>  'Something is wrong!'
					]
				];
			}

			$phoneInfo = $this->MakePhoneInfo($request->input('bulk_to'), $request->input($request->input('bulk_to')));

			$responseApi = $this->SendSmsAPI('0'.implode(',0', $request->input($request->input('bulk_to'))), $request->input('message'));

			$totalprice = 0;
			if(array_has($responseApi, 'totalprice')){
				$totalprice = $responseApi->totalprice;
				$this->UpdateAvailableSms(config('systemInfo.available_sms') - $totalprice);
			}

			SmsLog::create([
				'phone_info'	=>	json_encode($phoneInfo, JSON_NUMERIC_CHECK),
				'message'	=>	$request->input('message'),
				'api_response'	=>	json_encode($responseApi),
				'total_price'	=>	$totalprice,
				'created_by'	=>	Auth::user()->id
			]);

			if($totalprice == 0){
				return	[
					'errors'	=>	true,
					'toastrmsg'	=>	[
						'type'	=> 'error', 
						'title'	=>  'Invalid SMS',
						'msg'	=>  'Contact service center'
					],
//					[$request->all(), '0'.implode(',0', $request->input($request->input('bulk_to')))],
				];
			}

			return	[
				'errors'	=>	false,
				'toastrmsg'	=>	[
					'type'	=> 'success', 
					'title'	=>  'Notifications',
					'msg'	=>  'Query Submitted'
				],
				'availableSms' => config('systemInfo.available_sms') - $totalprice,
//				[implode(',', $request->input($request->input('bulk_to')))]
//				[$request->all()]
			];
		}

		return redirect('smsnotifications')->with([
									'toastrmsg' => [
										'type'	=> 'warning', 
										'title'	=>  'Notifications',
										'msg'	=>  'Something is wrong!'
									]
								]);

	}



	protected function SendSmsAPI($to='', $message=''){

/*		$client = new Client(['base_uri' => 'https://lifetimesms.com', 'verify' => false]);

		$response = $client->request('POST', '/json', [
			'form_params' => [
				'username' => 'muhammadali',
				'password' => '53718',
//				'to'	=>	$to,
				'to'	=>	'03132045991',
				'from'	=>	'SmartSMS',
				'message'	=>	$message,
				]
			]
		);
		return json_decode($response->getBody());
*/

		return json_decode('{
			"messages": [
				{
					"status": "-2",
					"error": "After Correction No Recepient Left For Submit"
				}
			]
		}');

/*
		return json_decode('{
			"type": "text",
			"totalprice": "1",
			"totalgsm": "1",
			"remaincredit": "4",
			"messages": [
			    {
			        "status": "1",
			        "messageid": "220401354020",
			        "gsm": "923132045991"
			    }
			]
		}');
*/
	}

	protected function UpdateAvailableSms($no){
		$ConfigWriter = new ConfigWriter('systemInfo');
		$ConfigWriter->set([
				'available_sms' => $no,
			]);
		$ConfigWriter->save();
	}

	protected function MakePhoneInfo($bulk_to, $phones){
		$phoneInfo = [];
		foreach ($phones as $key => $value) {
			$phoneInfo[] = [
				'send_to' => $bulk_to,
				'id' => $key,
				'no' => $value,
			];
		}
		return $phoneInfo;
	}

}
