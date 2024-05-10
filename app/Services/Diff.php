<?php

namespace App\Services;

use DiffMatchPatch\DiffMatchPatch;

class Diff
{
    public static function computar($disc)
    {
        $diffs = [];
        foreach ($disc->meta as $campo => $valor) {
            $dmp = new DiffMatchPatch();
            // vamos concatenar um whitespace para o caso de ser null
            $diff = $dmp->diff_main($disc->dr[$campo]. ' ', $disc->{$campo} . ' ', false);
            $diffs[$campo] = $dmp->diff_prettyHtml($diff);
        }
        return $diffs;
    }
}
