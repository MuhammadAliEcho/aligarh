<?php

namespace App\Http\Controllers\Api\Guardian;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InvoiceMaster;
use Illuminate\Support\Facades\Validator;

class StudentFeeController extends Controller
{
    public function GetFeeInvoices(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'student_id' => 'required|exists:students,id',
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $Invoice = InvoiceMaster::where('student_id', $request->input('student_id'))->with('InvoiceDetail')->orderBy('payment_month', 'desc')->first();
        return response()->json($Invoice);
    }
}
