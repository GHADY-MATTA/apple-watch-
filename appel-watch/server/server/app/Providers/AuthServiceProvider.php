<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    // protected $policies = [
    //     // Define model-policy mappings here if needed
    // ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Enable Passport routes (this is the part that was failing)
        // Passport::routes();
        // This is now the correct method since Laravel Passport 12.x
    Passport::ignoreRoutes(); // Because
    }
}
