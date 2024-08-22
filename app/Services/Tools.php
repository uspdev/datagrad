<?php

namespace App\Services;



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
}
