<?php

namespace App\Pipes\DocumentValidation;

use App\Enums\DocumentValidationStatus;
use App\Exceptions\DocumentValidateException;
use App\Pipes\PipeInterface;
use Illuminate\Support\Arr;

class ValidateSignature implements PipeInterface
{
    public function handle($document, $next)
    {
        $flattenedDocument = Arr::dot($document['data']);

        $hashed = [];
        foreach ($flattenedDocument as $key => $value) {
            $json = json_encode([$key => $value]);
            $hashed[] = hash('sha256', $json);
        }

        sort($hashed);

        $hashed = hash('sha256', implode('', $hashed));
        $isHashedValid = $hashed === Arr::get($document, 'signature.targetHash');

        // The result doesn't work for target hash 288f94aadadf486cfdad84b9f4305f7d51eac62db18376d48180cc1dd2047a0e
        throw_if(
            !$isHashedValid,
            new DocumentValidateException(DocumentValidationStatus::InvalidSignature->value)
        );

        return $next($document);
    }
}
