<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permission = Permission::firstOrCreate(['name' => 'disciplina-biblioteca']);
        $role = Role::firstOrCreate(['name' => 'biblioteca']);
        $role->givePermissionTo($permission);

        // CG tem a permissão mais ampla e inclui biblioteca
        $cg = Role::findByName('CG');
        $cg->givePermissionTo($permission);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
