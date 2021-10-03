<?php

namespace App\Helpers;

class ResponseMyApi
{
    public function __construct(
        public array|string|object $data,
        public int $statusCode,
        public bool $success
    ) {
    }
}
