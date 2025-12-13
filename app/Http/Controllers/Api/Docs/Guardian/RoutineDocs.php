<?php

namespace App\Http\Controllers\Api\Docs\Guardian;

/**
 * Guardian Routine API Documentation
 */
class RoutineDocs
{
    /**
     * @OA\Get(
     *     path="/guardian/routines",
     *     summary="Get Class Routines",
     *     description="Get class routines/timetables for guardian's students",
     *     operationId="guardianGetRoutines",
     *     tags={"Guardian"},
     *     security={{"bearerToken": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Routines retrieved",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="routines",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="day", type="string", example="Monday"),
     *                     @OA\Property(
     *                         property="periods",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="time", type="string", example="08:00-09:00"),
     *                             @OA\Property(property="subject", type="string", example="Mathematics"),
     *                             @OA\Property(property="teacher", type="string", example="Mr. Ali")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function getRoutines() {}
}
