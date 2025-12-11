<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Model\Voucher;
use App\Model\VoucherDetail;
use App\Model\Vendor;
use App\Model\Item;
use Auth;
use DB;
use App\Http\Controllers\Controller;

class VouchersController extends Controller
{
    protected function PostValidate($request){
      $this->validate($request, [
          'vendor'  =>  'required',
          'voucher_no'  =>  'required',
          'voucher_date' =>  'required',
          'items.*.*' =>  'required',
      ], [
          'vendor.required'  =>  __('validation.vendor_required'),
          'voucher_no.required'  =>  __('validation.voucher_no_required'),
          'voucher_date.required' =>  __('validation.voucher_date_required'),
          'items.required' =>  __('validation.items_required'),
      ]);
    }

    public function GetVoucher(Request $request){
      if ($request->ajax()) {
      return DataTables::queryBuilder(DB::table('vouchers')
                                        ->leftJoin('vendors', 'vouchers.vendor_id', '=', 'vendors.id')
                                        ->select('vouchers.id', 'vouchers.net_amount', 'vouchers.voucher_no', 'vouchers.voucher_date', 'vendors.v_name'))
                                        ->make(true);
      }
    	$data['vendors']	=	Vendor::get();
    	$data['items']	=	Item::get();
    	return view('admin.voucher', $data);
    }

	public function GetDetails($id){
		$data['voucher']  = Voucher::findorfail($id);
		return view('admin.voucher_detail', $data);
	}

    public function EditVoucher($id){

      if(Voucher::where('id', $id)->count() == 0){
      return  redirect('vouchers')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  __('modules.vouchers_invalid_url_title'),
          'msg' =>  __('modules.common_url_error')
          ]
      ]);
      }
		$data['vendors']	=	Vendor::get();
		$data['items']	=	Item::get();
		$data['voucher'] = Voucher::find($id);
		return view('admin.edit_voucher', $data);
    }

    public function PostEditVoucher(Request $request, $id){

      $request = $request;
      $this->PostValidate($request);

      if(Voucher::where('id', $id)->count() == 0){
      return  redirect('vouchers')->with([
        'toastrmsg' => [
          'type' => 'warning', 
          'title'  =>  __('modules.vouchers_invalid_url_title'),
          'msg' =>  __('modules.common_url_error')
          ]
      ]);
      }

      $Voucher = Voucher::find($id);
      $this->SetAttributes($Voucher, $request);
      $Voucher->updated_by = Auth::user()->id;
      $Voucher->save();

      $this->UpdateItemDetails($Voucher, $request);

      return redirect('vouchers')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  __('modules.vouchers_registration_title'),
          'msg' =>  __('modules.common_save_success')
          ]
      ]);
    }

    public function AddVoucher(Request $request){

      $request = $request;
      $this->PostValidate($request);
      $Voucher = new Voucher;
      $this->SetAttributes($Voucher, $request);
      $Voucher->created_by = Auth::user()->id;
      $Voucher->save();

      $this->UpdateItemDetails($Voucher, $request);


      return redirect('vouchers')->with([
        'toastrmsg' => [
          'type' => 'success', 
          'title'  =>  __('modules.vouchers_registration_title'),
          'msg' =>  __('modules.common_register_success')
          ]
      ]);

    }

    protected function SetAttributes($Voucher, $request){
      $Voucher->vendor_id = $request->input('vendor');
      $Voucher->voucher_no = $request->input('voucher_no');
      $Voucher->net_amount = $request->input('net_amount');
      $Voucher->voucher_date = Carbon::createFromFormat('d/m/Y', $request->input('voucher_date'))->toDateString();
    }

	protected function UpdateItemDetails($Voucher, $request){

		foreach(VoucherDetail::where(['voucher_id' => $Voucher->id])->get() AS $d){
			$Item = Item::find($d->item_id);
			$Item->qty 	=	$Item->qty	-	$d->qty;
			$d->delete();
			$Item->save();
		}

		if (count($request->input('items')) >= 1) {
			foreach ($request->input('items') as $key => $value) {
				$VoucherDetail = new VoucherDetail;
				$Item = Item::find($value['id']);
				$Item->qty 	+=	$value['qty'];
				$Item->save();
				$VoucherDetail->voucher_id = $Voucher->id;
				$VoucherDetail->item_id = $value['id'];
				$VoucherDetail->qty = $value['qty'];
				$VoucherDetail->rate = $value['rate'];
				$VoucherDetail->save();
			}
		}
	}

}
