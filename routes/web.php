<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GraduacaoController;

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

Route::get('graduacao/relatorio/nomes', [GraduacaoController::class, 'relatorioPorNomes'])->name('graduacao.relatorio.porNomes');
Route::post('graduacao/relatorio/nomes', [GraduacaoController::class, 'relatorioPorNomes'])->name('graduacao.relatorio.porNomes.post');

Route::get('graduacao/cursos', [GraduacaoController::class, 'cursos'])->name('graduacao.cursos');
Route::get('graduacao/cursos/{codcur}/{codhab}/gradeCurricular', [GraduacaoController::class, 'gradeCurricular'])->name('graduacao.gradeCurricular');
Route::get('graduacao/cursos/{codcur}/{codhab}/turmas', [GraduacaoController::class, 'turmas'])->name('graduacao.turmas');
