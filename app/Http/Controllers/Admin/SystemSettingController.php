<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Larapack\ConfigWriter\Repository as ConfigWriter;
use App\SystemInvoice;
use PDF;

class SystemSettingController extends Controller
{
	public function GetSetting(){
		$data['system_invoices']	=	SystemInvoice::all();
		return view('admin.system_setting', $data);
	}

	public function PrintInvoiceHistory(){
		$data['system_invoices']	=	SystemInvoice::all();
		$pdf = PDF::loadView('admin.printable.system_invoice_history', $data)->setPaper('a4');
		return $pdf->stream('invoice-history-2018.pdf');
		//return $pdf->download('invoice-history-2018.pdf');
		//return view('admin.printable.system_invoice_history', $data);
	}

	public function UpdateSetting(Request $request){

		$this->validate($request, [
			'name'  =>  'required',
			'title'  =>  'required',
			'email'  =>  'nullable|email',
			'address'  =>  'required',
			'bank_name'  =>  'required',
			'bank_address'  =>  'required',
			'bank_account_no'  =>  'required',
		]);

		$ConfigWriter = new ConfigWriter('systemInfo');
		$ConfigWriter->set([
				'name' => $request->input('name'),
				'title' => $request->input('title'),
				'email' => $request->input('email'),
				'address' => $request->input('address'),
				'contact_no' => $request->input('contact_no'),
				'bank_name'  =>  $request->input('bank_name'),
				'bank_address'  =>  $request->input('bank_address'),
				'bank_account_no'  =>  $request->input('bank_account_no'),
			]);
		$ConfigWriter->save();

		return redirect('system-setting')->with([
			'toastrmsg' => [
				'type' => 'success', 
				'title'  =>  'System Settings',
				'msg' =>  'General Info Changed'
			]
		]);

	}

}
