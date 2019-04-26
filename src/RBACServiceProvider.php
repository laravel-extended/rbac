<?php

namespace RBAC;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class RBACServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'pods' => \RBAC\Policies\Policy::class,
        // \App\User::class => \RBAC\Policies\Policy::class,
    ];

    protected $subjects = [
        'user' => \App\User::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'rbac');

        Relation::morphMap($this->subjects);

        Gate::guessPolicyNamesUsing(function ($modelClass) {
            return \RBAC\Policies\Policy::class;
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->singleton('rbac');
    }
}
