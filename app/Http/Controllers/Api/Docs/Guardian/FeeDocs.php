<?php

namespace App\Http\Controllers\Api\Docs\Guardian;

/**
 * Guardian Fee API Documentation
 */
class FeeDocs
{
    /**
     * @OA\Post(
     *     path="/guardian/fee",
     *     summary="Get Fee Invoices",
     *     description="Retrieve fee invoices and payment history for guardian's students",
     *     operationId="guardianGetFeeInvoices",
     *     tags={"Guardian"},
     *     security={{"bearerToken": {}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="student_id", type="integer", description="Filter by student ID", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fee invoices retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="invoice_id", type="integer", example=100),
     *                 @OA\Property(property="student_id", type="integer", example=1),
     *                 @OA\Property(property="amount", type="number", format="float", example=5000.00),
     *                 @OA\Property(property="paid", type="number", format="float", example=3000.00),
     *                 @OA\Property(property="balance", type="number", format="float", example=2000.00),
     *                 @OA\Property(property="month", type="string", example="January")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function getFeeInvoices() {}

    /**
     * @OA\Post(
     *     path="/guardian/student-invoices",
     *     summary="Get Student Invoices",
     *     description="Get detailed invoice information for a specific student",
     *     operationId="guardianGetStudentInvoices",
     *     tags={"Guardian"},
     *     security={{"bearerToken": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"student_id"},
     *             @OA\Property(property="student_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Student invoices retrieved"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function getStudentInvoices() {}
}
