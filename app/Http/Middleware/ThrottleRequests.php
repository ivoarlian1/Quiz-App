<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests as Middleware;

class ThrottleRequests extends Middleware
{
    /**
     * The names of the attributes that should not be throttled.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
} 