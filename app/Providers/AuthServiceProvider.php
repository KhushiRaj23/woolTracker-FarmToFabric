<?php

namespace App\Providers;

use App\Models\Batch;
use App\Models\Farm;
use App\Models\Order;
use App\Models\Product;
use App\Policies\BatchPolicy;
use App\Policies\FarmPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
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
        Farm::class => FarmPolicy::class,
        Batch::class => BatchPolicy::class,
        Product::class => ProductPolicy::class,
        Order::class => OrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('is-farmer', function ($user) {
            return $user->role === 'farmer';
        });

        Gate::define('is-processor', function ($user) {
            return $user->role === 'processor';
        });

        Gate::define('is-distributor', function ($user) {
            return $user->role === 'distributor';
        });

        Gate::define('is-retailer', function ($user) {
            return $user->role === 'retailer';
        });
    }
} 