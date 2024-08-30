<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifyDocumentRequest;
use App\Services\VerifyService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VerifyController
{
    private $verifyService;

    public function __construct(VerifyService $verifyService)
    {
        $this->verifyService = $verifyService;
    }

    public function verify(VerifyDocumentRequest $request)
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
