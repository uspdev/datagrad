<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Disciplina' => 'App\Policies\DisciplinaPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // disciplinas autorizado para servidores, docentes e estagiários
        Gate::define('disciplinas', function (User $user) {
            return Gate::check('senhaunica.servidor')
            || Gate::check('senhaunica.estagiario')
            || Gate::check('senhaunica.docente');
        });
    }
}
