<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisciplinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disciplinas', function (Blueprint $table) {
            $table->id();
            $table->string('coddis')->nullable(); // codigo da disciplina
            $table->string('nomdis')->nullable(); // nome da disciplina
            $table->string('nomdisigl')->nullable();
            $table->string('tipdis')->nullable(); // A - Anual / S - Semestral / Q - Quadrimestral.
            $table->string('creaul')->nullable(); // creditos aula
            $table->string('cretrb')->nullable(); // creditos trabalho
            $table->string('numvagdis')->nullable(); // número de vagas
            $table->string('durdis')->nullable(); // duração

            $table->text('objdis')->nullable(); // objetivos
            $table->text('objdisigl')->nullable();
            $table->text('pgmdis')->nullable(); // programa
            $table->text('pgmdisigl')->nullable();
            $table->text('pgmrsudis')->nullable(); // programa resumido
            $table->text('pgmrsudisigl')->nullable();

            $table->string('cgahoratvext')->nullable(); // carga horaria extensionista
            $table->text('grpavoatvext')->nullable(); // grupo social alvo
            $table->text('grpavoatvextigl')->nullable();
            $table->text('objatvext')->nullable(); // objetivos
            $table->text('objatvextigl')->nullable();
            $table->text('dscatvext')->nullable(); // descricao
            $table->text('dscatvextigl')->nullable();
            $table->text('idcavlatvext')->nullable(); // indicadores de avaliação
            $table->text('idcavlatvextigl')->nullable();

            $table->text('dscmtdavl')->nullable(); // método de avaliação
            $table->text('dscmtdavligl')->nullable();
            $table->text('crtavl')->nullable(); // critério de avaliação
            $table->text('crtavligl')->nullable();
            $table->text('dscnorrcp')->nullable(); // norma de recuperação
            $table->text('dscnorrcpigl')->nullable();

            $table->text('dscbbgdis')->nullable(); // bibliografia

            $table->string('stapsuatvani')->nullable(); // Atividades práticas com animais e/ou materiais biológicos
            
            $table->string('ano')->nullable(); // alteração para o ano / semestre
            $table->string('semestre')->nullable();
            $table->text('justificativa')->nullable();
            $table->boolean('atividade_extensionista')->nullable();
            $table->json('responsaveis')->nullable(); // codpes dos responsáveis pela disciplina, separados por vírgula
            $table->string('pdf')->nullable();
            $table->datetime('pdf_date')->nullable();
            $table->string('estado')->nullable(); // criar, editar, finalizado
            $table->foreignId('criado_por_id')->nullable()->constrained('users');
            $table->foreignId('atualizado_por_id')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disciplinas');
    }
}
