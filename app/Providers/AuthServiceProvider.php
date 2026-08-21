<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

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

        Gate::define('ver-relatorio', function (User $user) {
            return Gate::allows('disciplina-cc') || Gate::allows('datagrad');
        });

        // relatorio de carga horaria cumprida por aluno
        Gate::define('relatorio-cgahoralu', fn(User $user) => $user->hasAnyRole(['CG', 'CC']));

        // relatorio de carga extensionista
        Gate::define('relatorio-cgaext', fn(User $user) => $user->hasAnyRole(['CG', 'CC']));

        // autoriza acesso à rota roles
        Gate::define('roles', function (User $user) {
            return $user->hasAnyRole(['CG', 'CC', 'biblioteca'])
                || $user->getRoleNames()->contains(
                    fn($role) => str_starts_with($role, 'disciplinas')
                );
        });
    }
}
