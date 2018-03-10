<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Voucher;
use App\VoucherDetail;
use App\Vendor;
use App\Item;
use Auth;
use DB;

class VouchersController extends Controller
{

    protected $data, $Voucher, $Request;

    public function __Construct($Routes, $request){
      $this->data['root'] = $Routes;
      $this->Request = $request;
    }

    protected function PostValidate(){
      $this->validate($this->Request, [
          'vendor'  =>  'required',
          'voucher_no'  =>  'required',
          'voucher_date' =>  'required',
          'items.*.*' =>  'required',
      ]);
    }

    public function GetVoucher(){
    	$this->data['vendors']	=	Vendor::get();
    	$this->data['items']	=	Item::get();
    	return view('voucher', $this->data);
    }

	public function GetDetails(){
		$this->data['voucher']  = Voucher::findorfail($this->data['root']['option']);
		return view('voucher_detail', $this->data);
	}

    public function AjaxGetVoucher(){
    	if ($this->Request->ajax()) {
	    return Datatables::queryBuilder(DB::table('vouchers')
                                        ->leftJoin('vendors', 'vouchers.vendor_id', '=', 'vendors.id')
                                        ->select('vouchers.id', 'vouchers.net_amount', 'vouchers.voucher_no', 'vouchers.voucher_date', 'vendors.v_name'))
                                        ->make(true);
    	}
    	return abort(404);
    }

    public function EditVoucher(){

      if(Voucher::where('id', $this->data['root']['option'])->count() == 0){
      return  redirect('vouchers')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
      }
		$this->data['vendors']	=	Vendor::get();
		$this->data['items']	=	Item::get();
		$this->data['voucher'] = Voucher::find($this->data['root']['option']);
		return view('edit_voucher', $this->data);
    }

    public function PostEditVoucher(Request $request){

      $this->Request = $request;
      $this->PostValidate();

      if(Voucher::where('id', $this->data['root']['option'])->count() == 0){
      return  redirect('vouchers')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  '# Invalid URL',
          'msg' =>  'Do Not write hard URL\'s'
          ]
      ]);
      }

      $this->Voucher = Voucher::find($this->data['root']['option']);
      $this->SetAttributes();
      $this->Voucher->updated_by = Auth::user()->id;
      $this->Voucher->save();

      $this->UpdateItemDetails();

      return redirect('vouchers')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Vouchers Registration',
          'msg' =>  'Save Changes Successfull'
          ]
      ]);
    }

    public function AddVoucher(Request $request){

      $this->Request = $request;
      $this->PostValidate();
      $this->Voucher = new Voucher;
      $this->SetAttributes();
      $this->Voucher->created_by = Auth::user()->id;
      $this->Voucher->save();

      $this->UpdateItemDetails();


      return redirect('vouchers')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  'Vouchers Registration',
          'msg' =>  'Registration Successfull'
          ]
      ]);

    }

    protected function SetAttributes(){
      $this->Voucher->vendor_id = $this->Request->input('vendor');
      $this->Voucher->voucher_no = $this->Request->input('voucher_no');
      $this->Voucher->net_amount = $this->Request->input('net_amount');
      $this->Voucher->voucher_date = Carbon::createFromFormat('d/m/Y', $this->Request->input('voucher_date'))->toDateString();
    }

	protected function UpdateItemDetails(){

		foreach(VoucherDetail::where(['voucher_id' => $this->Voucher->id])->get() AS $d){
			$Item = Item::find($d->item_id);
			$Item->qty 	=	$Item->qty	-	$d->qty;
			$d->delete();
			$Item->save();
		}

		if (COUNT($this->Request->input('items')) >= 1) {
			foreach ($this->Request->input('items') as $key => $value) {
				$VoucherDetail = new VoucherDetail;
				$Item = Item::find($value['id']);
				$Item->qty 	+=	$value['qty'];
				$Item->save();
				$VoucherDetail->voucher_id = $this->Voucher->id;
				$VoucherDetail->item_id = $value['id'];
				$VoucherDetail->qty = $value['qty'];
				$VoucherDetail->rate = $value['rate'];
				$VoucherDetail->save();
			}
		}
	}

}
