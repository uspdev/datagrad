<?php
namespace App\Services;

use App\Replicado\Graduacao;

class Tools
{
    /**
     * Retorna array com uma linha por elemento do array
     */
    public static function linhasToArray($multiLinhas)
    {
        $multiLinhas = str_replace("\r", '', $multiLinhas); // remove carriage return, mantém new line
        $multiLinhas = explode(PHP_EOL, $multiLinhas);
        $multiLinhas = array_filter($multiLinhas); // remove elementos empty()
        $multiLinhas = array_map('trim', $multiLinhas);
        $multiLinhas = array_unique($multiLinhas); // sem repetidos
        return $multiLinhas;
    }

    /**
     * Condiciona uma string com multiplas linhas
     */
    public static function limparLinhas($multiLinhas)
    {
        $multiLinhas = str_replace("\r", '', $multiLinhas); // remove carriage return, mantém new line
        $multiLinhas = explode(PHP_EOL, $multiLinhas);
        $multiLinhas = array_filter($multiLinhas); // remove elementos empty()
        $multiLinhas = array_map('trim', $multiLinhas);
        $multiLinhas = array_unique($multiLinhas); // sem repetidos
        $multiLinhas = implode(PHP_EOL, $multiLinhas);
        return $multiLinhas;
    }

    /**
     * Realiza os calculos das medias por turma
     */
    protected static function computarTurma($turma, $prev = null)
    {
        $count            = 0;
        $somaHorasTeorica = 0;
        $somaHorasPratica = 0;

        $turma['ministrantes'] = collect(Graduacao::listarMinistrantes($turma))->pluck('nompes');

        $divisorQuinzenal    = ($turma['stamis'] == 'N') ? 1 : 2; // 'N' -> semanal
        $divisorMinistrantes = count($turma['ministrantes']);

        // acho que aqui exclui quando a disciplina/turma está repetido, ou seja tem mais de um horario ministrado
        if (is_null($prev)) {
            $somaHorasTeorica += (int) $prev['cgahorteo'] / $divisorQuinzenal;
            $somaHorasPratica += (int) $prev['cgahorpra'] / $divisorQuinzenal;
            $count++;
        } else {
            if (! ($prev['codtur'] == $turma['codtur'] && $prev['coddis'] == $turma['coddis'])) {
                $somaHorasTeorica += (int) $turma['cgahorteo'] / $divisorQuinzenal / $divisorMinistrantes;
                $somaHorasPratica += (int) $turma['cgahorpra'] / $divisorQuinzenal / $divisorMinistrantes;
                $count++;
            }
        }

        $turma['ministrantes']  = $turma['ministrantes']->implode(', ');

        return $turma;
    }

    /**
     * Trata string contendo nomes separados por linhas, retornando array de nomes sem repetidos
     */
    public static function limparNomes($nomes)
    {
        $nomes = str_replace("\r", '', $nomes); // remove carriage return, mantém new line
        $nomes = explode(PHP_EOL, $nomes);
        $nomes = array_filter($nomes); // remove elementos empty()
        $nomes = array_map('trim', $nomes);
        $nomes = array_unique($nomes); // sem repetidos
        sort($nomes);
        return $nomes;
    }

    /**
     * Retorna os semestres entre $semestreIni e $semestreFim, inclusive
     */
    public static function iniFim($semestreIni, $semestreFim)
    {
        $semestres = self::semestres();

        if ($semestreFim < $semestreIni) {
            $tmp         = $semestreFim;
            $semestreFim = $semestreIni;
            $semestreIni = $tmp;
        }

        $defaultSemestreFim = date('Y') . (date('m') < 7 ? 1 : 2);
        $semestreFim        = $semestreFim ? $semestreFim : $defaultSemestreFim;

        $defaultSemestreIni = $semestres[array_search($semestreFim, $semestres) + 1];
        $semestreIni        = $semestreIni ? $semestreIni : $defaultSemestreIni;

        $keyIni = array_search($semestreIni, $semestres); // chave do array correspondente ao semestreIni
        $keyFim = array_search($semestreFim, $semestres);

        $ret = array_slice($semestres, $keyFim, $keyIni - $keyFim + 1);
        return $ret;
    }

    /**
     * Retorna lista de semestres
     */
    public static function semestres()
    {
        $semestres = ['20261', '20252', '20251', '20242', '20241', '20232', '20231', '20222', '20221', '20212', '20211', '20202', '20201', '20192', '20191', '20182', '20181'];
        return $semestres;
    }
}
