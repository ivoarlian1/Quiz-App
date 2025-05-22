<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Quiz;
use App\Policies\QuizPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Quiz::class => QuizPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
} 