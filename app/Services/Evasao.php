<?php
namespace App\Services;

use App\Replicado\Graduacao;

class Evasao
{
    /**
     * Retorna taxa de permanência, desistência e conclusão para determinado ano de ingresso
     *
     * Cuidado: o ano corrente pode estar incompleto.
     *
     * tipencpgm is NULL, dtaini qualquer -> ainda está matriculado
     * tipencpgm = 'Conclusão', dtaini: ano do evento  -> formou
     * outros -> evadiu, dtaini: ano do evento -> evasão
     */
    public static function taxaEvasao(int $anoIngresso, int $codcur = null)
    {
        $alunos = Graduacao::listarAlunosIngressantesPorAnoIngresso($anoIngresso, $codcur);

        for ($ano = $anoIngresso; $ano <= date('Y'); $ano++) {
            $contagem[$ano]['countP'] = 0;
            $contagem[$ano]['countD'] = 0;
            $contagem[$ano]['countC'] = 0;
        }

        foreach ($alunos as $aluno) {
            switch ($aluno['tipencpgm']) {
                case '':
                    // ainda está matriculado
                    break;
                case 'Conclusão':
                    $contagem[$aluno['ano']]['countC']++;
                    break;
                default:
                    $contagem[$aluno['ano']]['countD']++;
            }
        }

        // calculando taxa de evasão por ano
        for ($ano = $anoIngresso; $ano <= date('Y'); $ano++) {
            if (isset($evasao[$ano - 1])) {
                $evasao[$ano]['permanencia'] = $evasao[$ano - 1]['permanencia'] - $contagem[$ano]['countD'] - $contagem[$ano]['countC'];
                $evasao[$ano]['desistenciaAcc'] = $evasao[$ano - 1]['desistenciaAcc'] + $contagem[$ano]['countD'];
                $evasao[$ano]['conclusaoAcc'] = $evasao[$ano - 1]['conclusaoAcc'] + $contagem[$ano]['countC'];
            } else {
                $evasao[$ano]['permanencia'] = count($alunos) - $contagem[$ano]['countD'] - $contagem[$ano]['countC'];
                $evasao[$ano]['desistenciaAcc'] = $contagem[$ano]['countD'];
                $evasao[$ano]['conclusaoAcc'] = $contagem[$ano]['countC'];
            }

            $evasao[$ano]['txPermanencia'] = round($evasao[$ano]['permanencia'] / count($alunos) * 100, 2);
            $evasao[$ano]['txDesistenciaAcc'] = round($evasao[$ano]['desistenciaAcc'] / count($alunos) * 100, 2);
            $evasao[$ano]['txConclusaoAcc'] = round($evasao[$ano]['conclusaoAcc'] / count($alunos) * 100, 2);

            // se não há mais permanência, vamos parar de computar os anos
            if ($evasao[$ano]['permanencia'] == 0) {
                break;
            }
        }

        return $evasao;
    }

    /**
     * Retorna código e nome de todos os cursos em uma array
     *
     * Caso tiver o parâmetro $cod, ele retorna apenas o cod e nome de um curso.
     */

    public static function retornarCodcurNomcur(int $codcur = null)
    {

        $cursos = Graduacao::listarCursosHabilitacoes();

        $codcur_ignorados = json_decode(env('CODCUR_IGNORADOS'), true) ?? [];

        $codcur_hab_ignorados = json_decode(env('CODCUR_HAB_IGNORADOS'), true) ?? [];

        $cursosTratados = [];

        foreach ($cursos as $curso) {

            if (in_array($curso['codcur'], $codcur_ignorados)) {
                continue;
            }

            if (in_array($curso['codcur'], $codcur_hab_ignorados)) {
                $curso['nomcur'] = $curso['nomcur'];

            } else {
                $curso['nomcur'] = ($curso['nomcur'] == $curso['nomhab']) ? $curso['nomcur'] : "{$curso['nomcur']} ({$curso['codhab']}) {$curso['nomhab']}";

            }

            $elem = array(
                'codcur' => "{$curso['codcur']}",
                'nomcur' => "{$curso['nomcur']}");

            if (!in_array($elem, $cursosTratados)) {
                $cursosTratados[] = $elem;
            }

            if ($codcur == $elem['codcur']) {
                return $elem;
            }

        }

        return $cursosTratados;
    }

}
