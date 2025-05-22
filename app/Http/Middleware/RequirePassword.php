<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\RequirePassword as Middleware;

class RequirePassword extends Middleware
{
    /**
     * The names of the attributes that should not be required.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
} 