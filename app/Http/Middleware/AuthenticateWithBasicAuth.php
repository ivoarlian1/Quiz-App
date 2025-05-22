<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth as Middleware;

class AuthenticateWithBasicAuth extends Middleware
{
    /**
     * The names of the attributes that should not be authenticated.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
} 