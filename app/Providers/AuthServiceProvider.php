<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\HelpDesk;
use App\Models\Operator;
use App\Models\Role;
use App\Models\User;
use App\Policies\HelpDeskPolicy;
use App\Policies\OperatorPolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Access\Response;
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
        User::class => UserPolicy::class,
        HelpDesk::class => HelpDeskPolicy::class,
        Operator::class => OperatorPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('admin',function (User $user): Response {
            $result = false;
            foreach ($user->role as $role) {
                if ($role->name == 'admin') {
                    $result = true;
                    break;
                }
            }
            return $result ? Response::allow() : Response::deny('You must be an admin.');;
        });

        Gate::define('operator',function (User $user): Response {
            $result = false;
            foreach ($user->role as $role) {
                if ($role->name == 'operator') {
                    $result = true;
                    break;
                }
            }
            return $result ? Response::allow() : Response::deny('You must be an operator.');;
        });

        Gate::define('help_desk',function (User $user): Response {
            $result = false;
            foreach ($user->role as $role) {
                if ($role->name == 'help_desk') {
                    $result = true;
                    break;
                }
            }
            return $result ? Response::allow() : Response::deny('You must be an help_desk.');;
        });
    }
}
