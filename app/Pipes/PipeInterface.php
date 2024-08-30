<?php

namespace App\Pipes;

interface PipeInterface
{
    public function handle($passable, $next);
}
