<?php

namespace App\Http\Middleware;

use Illuminate\Session\Middleware\StartSession as Middleware;

class StartSession extends Middleware
{
    /**
     * The session store that should be used.
     *
     * @var string|null
     */
    protected $store = null;

    /**
     * The session lifetime in minutes.
     *
     * @var int
     */
    protected $lifetime = 120;

    /**
     * The session cookie name.
     *
     * @var string
     */
    protected $cookie = 'laravel_session';

    /**
     * The session cookie path.
     *
     * @var string
     */
    protected $path = '/';

    /**
     * The session cookie domain.
     *
     * @var string|null
     */
    protected $domain = null;

    /**
     * The session cookie secure flag.
     *
     * @var bool
     */
    protected $secure = false;

    /**
     * The session cookie http only flag.
     *
     * @var bool
     */
    protected $httpOnly = true;

    /**
     * The session cookie same site flag.
     *
     * @var string
     */
    protected $sameSite = 'lax';
} 