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
            $table->text('mtdens')->nullable(); // metodo-ensino
            $table->text('mtdensigl')->nullable();
            $table->text('objdslsut')->nullable(); // objetivos-desenvolvimento-sustentavel: não está no jupiter
            $table->char('stavgmdid', 1)->nullable(); // status-viagem-didatica (S/N)
            $table->char('staetr', 1)->nullable(); // status-estruturante (S/N)
            $table->text('dscatvpvs')->nullable(); // descricao-atividades-previstas

            $table->text('dscbbgdiscpl')->nullable(); // bibliografia complementar

            $table->char('stapsuatvani', 1)->nullable()->change(); // status ativ animais: de text para char
            $table->string('ptccmseiaani', 30)->nullable(); // protocolo CEUA
            $table->dateTime('dtainivalprp')->nullable(); // data ini CEUA
            $table->dateTime('dtafimvalprp')->nullable(); // data fim CEUA
            
            $table->char('codlinegr', 3)->nullable(); // Código do idioma (seguindo a ISO639-3),
            $table->tinyInteger('verdis')->unsigned()->nullable(); // Versão da disciplina na qual foi baseada a alteração


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disciplinas', function (Blueprint $table) {
            $table->dropColumn(['mtdens', 'mtdensigl', 'objdslsut', 'stavgmdid', 'staetr', 'dscatvpvs', 'dscbbgdiscpl', 'ptccmseiaani', 'dtainivalprp', 'dtafimvalprp', 'codlinegr', 'verdis']);
        });
    }
};
