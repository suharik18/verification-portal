<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifyDocumentRequest;
use App\Services\VerifyService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VerifyController extends Controller
{
    private VerifyService $verifyService;

    public function __construct(VerifyService $verifyService)
    {
        $this->verifyService = $verifyService;
    }

    /**
     * @OA\Post(
     *     path="/verify",
     *     summary="Verify a document",
     *     description="Uploads and verifies a document, then returns the result of the verification.",
     *     operationId="verifyDocument",
     *     tags={"Document Verification"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="document",
     *                     type="string",
     *                     format="binary",
     *                     description="The document file to be verified."
     *                 ),
     *                 required={"document"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Document verification successful",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="issuer",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="result",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string"
     *             )
     *         )
     *     )
     * )
     */
    public function verify(VerifyDocumentRequest $request): JsonResponse
    {
        $file = $request->file('document');

        try {
            $validatedDocument = $this->verifyService->validateDocument($file->get());
            $this->verifyService->storeResult(
                $request->user(),
                $validatedDocument->status,
                $file->extension()
            );
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }

        return response()->json([
            'data' => [
                'issuer' => $validatedDocument->issuer,
                'result' => $validatedDocument->status,
            ],
        ]);
    }
}
