<?php

namespace App\Dto;

class ValidatedDocumentDto
{
    public string $status;
    public string $issuer;

    public function __construct(
        string $issuer,
        string $status
    ){
        $this->status = $status;
        $this->issuer = $issuer;
    }
}
