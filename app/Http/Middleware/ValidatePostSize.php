<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ValidatePostSize as Middleware;

class ValidatePostSize extends Middleware
{
    /**
     * The maximum allowed post size.
     *
     * @var int
     */
    protected $maxPostSize = 8 * 1024 * 1024; // 8MB
} 