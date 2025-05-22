<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authorize as Middleware;

class Authorize extends Middleware
{
    /**
     * The names of the attributes that should not be authorized.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
} 