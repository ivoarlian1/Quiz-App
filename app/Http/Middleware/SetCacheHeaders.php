<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\SetCacheHeaders as Middleware;

class SetCacheHeaders extends Middleware
{
    /**
     * The names of the attributes that should not be cached.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
} 