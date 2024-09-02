<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->integer('codcur'); // codigo curso
            $table->integer('codhab')->nullable(); // codigo habilitacao
            $table->text('habilidades')->nullable();
            $table->text('competencias')->nullable();
            $table->text('habilidades_igl')->nullable();
            $table->text('competencias_igl')->nullable();
            $table->text('dr')->nullable(); // dados replicados
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
