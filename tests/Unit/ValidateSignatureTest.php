<?php

namespace Tests\Unit;

use App\Pipes\DocumentValidation\ValidateIssuer;
use App\Pipes\DocumentValidation\ValidateRecipient;
use App\Pipes\DocumentValidation\ValidateSignature;
use Tests\TestCase;

class ValidateSignatureTest extends TestCase
{
    private function getMockedDocument(): array
    {
        return [
            "data" => [
                "id" => "63c79bd9303530645d1cca00",
                "name" => "Certificate of Completion",
                "recipient" => [
                    "name" => "Marty McFly",
                    "email" => "marty.mcfly@gmail.com"
                ],
                "issuer" => [
                    "name" => "Accredify",
                    "identityProof" => [
                        "type" => "DNS-DID",
                        "key" => "did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller",
                        "location" => "ropstore.accredify.io"
                    ]
                ],
                "issued" => "2022-12-23T00:00:00+08:00"
            ],
            "signature" => [
                "type" => "SHA3MerkleProof",
                "targetHash" => "288f94aadadf486cfdad84b9f4305f7d51eac62db18376d48180cc1dd2047a0e"
            ]
        ];
    }

    private function testPipe($pipeClass)
    {
        $document = $this->getMockedDocument();

        // Create an instance of the pipe
        $pipe = new $pipeClass();

        // Define a mock Closure for $next that simply returns the document
        $next = fn ($doc) => $doc;

        // Execute the pipe's handle method
        $result = $pipe->handle($document, $next);

        // Assert that the returned document is the same as the input
        $this->assertEquals($document, $result);
    }

    public function test_valid_issuer_passes()
    {
        $this->testPipe(ValidateIssuer::class);
    }

    public function test_valid_recipient_passes()
    {
        $this->testPipe(ValidateRecipient::class);
    }

    public function test_valid_signature_passes()
    {
        $this->testPipe(ValidateSignature::class);
    }
}
