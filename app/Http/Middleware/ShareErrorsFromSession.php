<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ShareErrorsFromSession as Middleware;

class ShareErrorsFromSession extends Middleware
{
    /**
     * The names of the attributes that should not be shared.
     *
     * @var array<int, string>
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];
} 