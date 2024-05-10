<?php

namespace App\Providers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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

        $permission = Permission::firstOrCreate(['name' => 'disciplina-cg']);
        $permission = Permission::firstOrCreate(['name' => 'disciplina-cc']); // coordenador de curso
        $permission = Permission::firstOrCreate(['name' => 'disciplina-chefe']);
        $permission = Permission::firstOrCreate(['name' => 'disciplina']); //professor, comum

        // deve ir para migration
        // comissão de graduação. tem todas as permissões
        $role = Role::firstOrCreate(['name' => 'CG']);
        $role->givePermissionTo(['datagrad', 'evasao', 'disciplina', 'disciplina-cg']);

    }
}
