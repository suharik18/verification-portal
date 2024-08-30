<?php

namespace App\Services;

use App\Dto\ValidatedDocumentDto;
use App\Enums\DocumentValidationStatus;
use App\Exceptions\DocumentValidateException;
use App\Models\User;
use App\Models\VerificationResult;
use App\Pipes\DocumentValidation\ValidateIssuer;
use App\Pipes\DocumentValidation\ValidateRecipient;
use App\Pipes\DocumentValidation\ValidateSignature;
use Illuminate\Pipeline\Pipeline;

class VerifyService
{
    public function validateDocument(string $documentContent): ValidatedDocumentDto
    {
        $document = json_decode($documentContent, true);
        $status = DocumentValidationStatus::Verified->value;

        try {
            app(Pipeline::class)
                ->send($document)
                ->through([
                    ValidateRecipient::class,
                    ValidateIssuer::class,
                    ValidateSignature::class,
                ])
                ->thenReturn();
        } catch (DocumentValidateException $exception) {
            $status = $exception->getMessage();
        }

        return new ValidatedDocumentDto($document['data']['issuer']['name'], $status);
    }

    public function storeResult(User $user, string $status, string $fileType = 'json')
    {
        VerificationResult::create([
            'user_id' => $user->id,
            'file_type' => $fileType,
            'status' => $status,
        ]);
    }
}
