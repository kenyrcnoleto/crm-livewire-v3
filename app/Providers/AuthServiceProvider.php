<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Enum\Can;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        foreach (Can::cases() as $can) {

            Gate::define(
                //$can->value)->snake('-')->toString(),
                $can->value,
                fn (User $user) => $user->hasPermissionTo($can)
            );
        }
    }
}
