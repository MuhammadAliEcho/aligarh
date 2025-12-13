<?php

namespace App\Http\Controllers\Api\Docs\Guardian;

/**
 * Guardian Notice Board API Documentation
 */
class NoticeBoardDocs
{
    /**
     * @OA\Get(
     *     path="/guardian/noticeboard",
     *     summary="Get Notice Board",
     *     description="Get notice board announcements and notifications",
     *     operationId="guardianGetNotices",
     *     tags={"Guardian"},
     *     security={{"bearerToken": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Notices retrieved",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="notices",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Parent-Teacher Meeting"),
     *                     @OA\Property(property="description", type="string", example="PTM scheduled for December 20, 2024"),
     *                     @OA\Property(property="date", type="string", format="date", example="2024-12-14"),
     *                     @OA\Property(property="priority", type="string", example="high")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function getNotices() {}
}
