<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // comissão de graduação. tem todas as permissões
        $permission = Permission::firstOrCreate(['name' => 'disciplina-cg']);
        $permission = Permission::firstOrCreate(['name' => 'disciplina-chefe']);
        $permission = Permission::firstOrCreate(['name' => 'disciplina']); //professor, comum
        $role = Role::firstOrCreate(['name' => 'CG']);
        $role->givePermissionTo(['datagrad', 'evasao', 'disciplina', 'disciplina-cg']);

        // Coordenador de curso
        // inicialmente pode editar os PPCs (todos)
        $permission = Permission::firstOrCreate(['name' => 'disciplina-cc']); // coordenador de curso
        $role = Role::firstOrCreate(['name' => 'CC']);
        $role->givePermissionTo(['disciplina-cc']);
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
