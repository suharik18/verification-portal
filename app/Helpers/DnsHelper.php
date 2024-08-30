<?php

namespace App\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class DnsHelper
{
    static function loadGoogleDnsList(string $location, string $type = 'TXT')
    {
        $url = config('dns.google');
        $response = Http::get("$url?name=$location&type=$type");

        return new Collection($response->json('Answer'));
    }
}
