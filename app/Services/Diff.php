<?php

namespace App\Services;

use DiffMatchPatch\DiffMatchPatch;

class Diff
{
    public static function computar($disc)
    {
        $diffs = [];
        $dmp = new DiffMatchPatch();

        foreach ($disc::meta() as $campo => $valor) {
            $campoDr = $disc::campoDr($campo);
            $valorDr = $disc->dr[$campoDr] ?? '';
            if (is_array($valorDr)) {
                $valorDr = implode('', $valorDr);
            }

            $valorCampo = $disc->{$campo} ?? '';
            if (is_array($valorCampo)) {
                $valorCampo = implode('', $valorCampo);
            }

            $diff = $dmp->diff_main((string)$valorDr, (string)$valorCampo, false);
            $diffs[$campo] = $dmp->diff_prettyHtml($diff);
            $diffs[$campoDr] = $diffs[$campo];
        }

        return $diffs;
    }
}
