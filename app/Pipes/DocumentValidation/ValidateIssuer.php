<?php

namespace App\Pipes\DocumentValidation;

use App\Enums\DocumentValidationStatus;
use App\Exceptions\DocumentValidateException;
use App\Helpers\DnsHelper;
use App\Pipes\PipeInterface;
use Illuminate\Support\Arr;

class ValidateIssuer implements PipeInterface
{
    public function handle($document, $next)
    {
        $hasName = Arr::has($document, 'data.issuer.name');
        $hasEmail = Arr::has($document, 'data.issuer.identityProof');

        throw_if(
            !$hasName || !$hasEmail,
            new DocumentValidateException(DocumentValidationStatus::InvalidIssuer->value)
        );

        $key = Arr::get($document, 'data.issuer.identityProof.key');
        $isKeyCorrect = DnsHelper::loadDns()->contains(fn ($value) => str_contains($value['data'], $key));

        throw_if(
            !$isKeyCorrect,
            new DocumentValidateException(DocumentValidationStatus::InvalidIssuer->value)
        );

        return $next($document);
    }
}
