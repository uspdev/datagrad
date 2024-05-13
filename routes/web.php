<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ArquivoController;
use App\Http\Controllers\GraduacaoController;
use App\Http\Controllers\DisciplinaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});

# Graduação
Route::get('pessoas/{codpes}', [GraduacaoController::class, 'pessoa'])->name('pessoas.show');

Route::get('graduacao/relatorio/gradehoraria', [GraduacaoController::class, 'relatorioGradeHoraria'])->name('graduacao.relatorio.gradehoraria');
Route::post('graduacao/relatorio/gradehoraria', [GraduacaoController::class, 'relatorioGradeHoraria'])->name('graduacao.relatorio.gradehoraria.post');

Route::get('graduacao/relatorio/sintese', [GraduacaoController::class, 'relatorioSintese'])->name('graduacao.relatorio.sintese');
Route::post('graduacao/relatorio/sintese', [GraduacaoController::class, 'relatorioSintese'])->name('graduacao.relatorio.sintese.post');

Route::get('graduacao/relatorio/complementar', [GraduacaoController::class, 'relatorioComplementar'])->name('graduacao.relatorio.complementar');
Route::post('graduacao/relatorio/complementar', [GraduacaoController::class, 'relatorioComplementar'])->name('graduacao.relatorio.complementar.post');

Route::get('graduacao/relatorio/cargadidatica', [GraduacaoController::class, 'cargaDidatica'])->name('graduacao.relatorio.cargadidatica');
Route::post('graduacao/relatorio/cargadidatica', [GraduacaoController::class, 'cargaDidatica'])->name('graduacao.relatorio.cargadidatica.post');

Route::get('graduacao/relatorio/evasao', [GraduacaoController::class, 'relatorioEvasao'])->name('graduacao.relatorio.evasao');
Route::post('graduacao/relatorio/evasao', [GraduacaoController::class, 'relatorioEvasao'])->name('graduacao.relatorio.evasao.post');

Route::get('graduacao/cursos', [GraduacaoController::class, 'cursos'])->name('graduacao.cursos');
Route::get('graduacao/cursos/{codcur}/{codhab}/gradeCurricular', [GraduacaoController::class, 'gradeCurricular'])->name('graduacao.gradeCurricular');
Route::get('graduacao/cursos/{codcur}/{codhab}/turmas', [GraduacaoController::class, 'turmas'])->name('graduacao.turmas');
Route::post('graduacao/cursos/{codcur}/{codhab}/turmas', [GraduacaoController::class, 'turmas'])->name('graduacao.turmas.post');

Route::resource('disciplinas', DisciplinaController::class)->parameters(['disciplinas' => 'coddis']);
Route::get('disciplinas/{coddis}/preview',[DisciplinaController::class, 'preview'])->name('disciplinas.preview');

Route::get('arquivos/download',[ArquivoController::class, 'download'])->name('arquivos.download');

Route::get('/roles', [RoleController::class,'index'])->name('roles.index');