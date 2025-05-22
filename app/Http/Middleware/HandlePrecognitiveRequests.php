<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests as Middleware;

class HandlePrecognitiveRequests extends Middleware
{
    /**
     * The URIs that should be excluded from precognitive validation.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
} 