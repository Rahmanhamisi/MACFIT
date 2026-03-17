<?php

namespace App\Providers;

use App\Policies\Userpolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ProvidersAuthServiceProvider;

class AuthServiceProvider extends ProvidersAuthServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\User' => 'App\Policies\Userpolicy',
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
