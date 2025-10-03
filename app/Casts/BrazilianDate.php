<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Carbon\Carbon;

class BrazilianDate implements CastsAttributes
{
  /**
   * Converte do banco -> para exibição
   */
  public function get($model, string $key, $value, array $attributes)
  {
    return $value ? Carbon::parse($value)->format('d/m/Y') : null;
  }

  /**
   * Converte da requisição -> para o banco
   */
  public function set($model, string $key, $value, array $attributes)
  {
    // quando vem do repliucado a data vem no formato 'Y-m-d H:i:s'
    $formatos = [
      'd/m/Y',
      'Y-m-d',
      'Y-m-d H:i:s',
    ];

    foreach ($formatos as $formato) {
      try {
        return Carbon::createFromFormat($formato, $value)->format('Y-m-d');
      } catch (\Exception $e) {
        // tenta o próximo formato
      }
    }

    return null; // não conseguiu interpretar
  }
}
