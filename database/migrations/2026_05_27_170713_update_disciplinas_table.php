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
        Schema::table('disciplinas', function (Blueprint $table) {
            $table->datetime('dtainibbg')->nullable(); // data-inicio-bibliografia: referência para saber a versão vigente
            $table->datetime('dtainiifmavl')->nullable(); // data-inicio-informacao-avaliacao: referência para saber a versão vigente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disciplinas', function (Blueprint $table) {
            $table->dropColumn([
                'dtainibbg',
                'dtainiifmavl',
            ]);
        });
    }
};
