<?php

namespace App\Services;

use App\Replicado\Pessoa;
use App\Replicado\Graduacao;

class CargaDidatica
{

    public static function listarExclusao()
    {
        // estagio: vamos excluir disciplinas que não contam para carga didática
        $exclusao = ['1800078', '1800090', '1800096', '1800097', '1800122', '1800200', '1807100', 'SAA0170', 'SEL0425', 'SEL0625', 'SEM0398', 'SEP0622', 'SMM0324'];

        // TCC
        $exclusao = array_merge($exclusao, ['1800080', '1800081']);

        // projeto de final de curso
        $exclusao = array_merge($exclusao, ['1800083', '1800093', '1800094', 'SAA0346', 'SEL0442', 'SEL0444', 'SEM0399', 'SEM0404', 'SMM0325']);

        // projeto de formatura
        $exclusao = array_merge($exclusao, ['SEL0624']);
    }

    public static function CalcularPorNome($nome, $semestres, $excluirTcc)
    {

        $pessoaReplicado = Pessoa::procurarServidorPorNome($nome, $fonetico = false) ?? Pessoa::procurarServidorPorNome($nome, $fonetico = true);
        if (!$pessoaReplicado) {
            $naoEncontrados[] = $nome;
            // continue;
        }
        $pessoa = [];
        $pessoa['unidade'] = $pessoaReplicado['sglclgund'];
        $pessoa['departamento'] = Pessoa::retornarSetor($pessoaReplicado['codpes']);
        $pessoa['codpes'] = $pessoaReplicado['codpes'];
        $pessoa['nome'] = $pessoaReplicado['nompesttd'];

        // ordenando por collect pois o retorno do DB não vem ordenado pois junta varias queries
        $turmas = collect(Graduacao::listarTurmasPorCodpes($pessoa['codpes'], $semestres))->sortBy(['coddis', 'codtur'])->toArray();

        if (empty($turmas)) {
            $semCargaDidatica[] = $nome;
            // continue;
        }

        $count = 0; // contagem do nro de turmas/disciplinas consideradas por pessoa
        $prev = null;
        $somaHorasTeorica = 0;
        $somaHorasPratica = 0;
        for ($k = 0; $k < count($turmas); $k++) {
            $adicionar = true; // permite ignorar adicao na lista pois é repetido dos dias da asemana

            $turma = $turmas[$k];
            $turma['ministrantes'] = collect(Graduacao::listarMinistrantes($turma))->pluck('nompes');
            $turma['horafmt'] = '(' . $turma['diasmnocp'] . ') ' . $turma['horent'] . ' - ' . $turma['horsai'];

            if (!$excluirTcc || !in_array($turma['coddis'], $exclusao)) {
                $divisorQuinzenal = ($turma['stamis'] == 'N') ? 1 : 2; // 'N' -> semanal
                $divisorMinistrantes = count($turma['ministrantes']);

                if ($divisorMinistrantes == 0) {
                    $divisorMinistrantes = 1;
                }

                if (is_null($prev)) {
                    // primeira turma da lista
                    $somaHorasTeorica += (int) $turma['cgahorteo'] / $divisorQuinzenal / $divisorMinistrantes;
                    $somaHorasPratica += (int) $turma['cgahorpra'] / $divisorQuinzenal / $divisorMinistrantes;
                    $turma['frateo'] = round((int) $turma['cgahorteo'] / $divisorQuinzenal / $divisorMinistrantes, 3);
                    $turma['frapra'] = round((int) $turma['cgahorpra'] / $divisorQuinzenal / $divisorMinistrantes, 3);
                    $count++;
                } else {
                    // demais turmas
                    // aqui exclui quando a disciplina/turma está repetido, ou seja tem mais de um horário ministrado
                    if (!($prev['codtur'] == $turma['codtur'] && $prev['coddis'] == $turma['coddis'])) {
                        $somaHorasTeorica += (int) $turma['cgahorteo'] / $divisorQuinzenal / $divisorMinistrantes;
                        $somaHorasPratica += (int) $turma['cgahorpra'] / $divisorQuinzenal / $divisorMinistrantes;
                        $turma['frateo'] = round((int) $turma['cgahorteo'] / $divisorQuinzenal / $divisorMinistrantes, 3);
                        $turma['frapra'] = round((int) $turma['cgahorpra'] / $divisorQuinzenal / $divisorMinistrantes, 3);
                        $count++;
                    } else {
                        $pessoa['turmas'][$k - 1]['horafmt'] = $turma['horafmt'] . '; ' . $pessoa['turmas'][$k - 1]['horafmt'];
                        $adicionar = false;
                    }
                }
            } else {
                $disciplinasExcluidas[] = $turma;
            }

            $turma['ministrantes'] = $turma['ministrantes']->implode(', ');
            $prev = $turma;

            if ($adicionar == true) {
                $pessoa['turmas'][$k] = $turma;
            }
        }

        $pessoa['mediaHorasTeorica'] = $somaHorasTeorica / count($semestres);
        $pessoa['mediaHorasPratica'] = $somaHorasPratica / count($semestres);
        $pessoa['mediaTurmas'] = $count / count($semestres);

        $totalHorasTeoricas += $somaHorasTeorica;
        $totalHorasPraticas += $somaHorasPratica;
        $totalTurmas += $count;

        // só vamos incluir se tiver ministrado alguma coisa
        if ($pessoa['mediaTurmas'] != 0) {
            $pessoas[] = $pessoa;
        } else {
            $semCargaDidatica[] = $nome;
        }
    }
}
