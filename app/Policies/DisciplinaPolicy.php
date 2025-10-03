<?php

namespace App\Policies;

use App\Models\Disciplina;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class DisciplinaPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool | null
    {
        if ($user->hasRole('CG')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canAny(['senhaunica.docente', 'senhaunica.admin']) ||
            $user->canAny(['disciplina-cg', 'disciplina-cc', 'disciplina-chefe']);

        // servidores, estagiários e docentes
        // return Gate::check('senhaunica.servidor') || Gate::check('senhaunica.estagiario') || Gate::check('senhaunica.docente');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Disciplina $disciplina): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Disciplina $disciplina): bool
    {
        // docente está na lista de responsáveis pela disciplina
        if (strpos(json_encode($disciplina->responsaveis), $user->codpes) !== false || strpos(json_encode($disciplina->dr['responsaveis']), $user->codpes) !== false) {
            return true;
        }

        // docente é chefe
        $prefixo = substr($disciplina->coddis, 0, 3);
        if ($user->roles->where('name', 'disciplinas_' . $prefixo)->all()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Disciplina $disciplina): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Disciplina $disciplina): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Disciplina $disciplina): bool
    {
        //
    }
}
