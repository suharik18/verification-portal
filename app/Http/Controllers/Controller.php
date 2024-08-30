<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Document Verification API",
 *     description="API for verifying documents.",
 *     version="1.0.0"
 * )
 *
 * @OA\Server(
 *     url="http://localhost/api",
 *     description="API server"
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      description="Use the Bearer token for authorization. Prefix the token with 'Bearer '."
 *  )
 */
abstract class Controller
{
    //
}
