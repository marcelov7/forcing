<?php

namespace App\Providers;

use App\Models\Forcing;
use App\Models\LogicChange;
use App\Policies\ForcingPolicy;
use App\Policies\LogicChangePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Forcing::class => ForcingPolicy::class,
        LogicChange::class => LogicChangePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
