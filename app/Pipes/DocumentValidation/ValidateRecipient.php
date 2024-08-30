<?php

namespace App\Pipes\DocumentValidation;

use App\Enums\DocumentValidationStatus;
use App\Exceptions\DocumentValidateException;
use App\Pipes\PipeInterface;
use Illuminate\Support\Arr;

class ValidateRecipient implements PipeInterface
{
    public function handle($document, $next)
    {
        $hasName = Arr::has($document, 'data.recipient.name');
        $hasEmail = Arr::has($document, 'data.recipient.email');

        throw_if(
            !$hasName || !$hasEmail,
            new DocumentValidateException(DocumentValidationStatus::InvalidRecipient->value)
        );

        return $next($document);
    }
}
