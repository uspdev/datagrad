<?php

namespace App\Services;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;

/**
 * Classe auxiliar para geração de arquivos PDF
 */
class Pdf
{

    /**
     * Gera o arquivo pdf de uma disicplina em alteração/criação e salva no filesystem
     *
     * @param App\Models\Disciplina $disc
     * @return String Nome do arquivo PDF
     */
    public static function gerarRelatorioAlteracaoDisciplina($disc)
    {
        $html = Blade::render('disciplinas.pdf.conteudo', compact('disc'));

        // echo $html; exit;
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $filename = 'disciplina-' . $disc->coddis . '.pdf';
        Storage::put($filename, $dompdf->output());

        return $filename;
    }

    /**
     * Caso tenha algum texto que não caiba em uma folha vamos colocar indicação de quebra
     *
     *
     */
    public static function quebrarTextoLongo($disc, $linhas = 33)
    {
        $font = base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans.ttf');
        $width = '500';
        $size = '14';
        $numlinhas = 31;
        foreach ($disc->meta as $campo => $valor) {
            
            // vamos remover alguns desnecessarios. Tem de rever ******
            if (isset($valor['diff']) && $valor['diff'] == false) {
                continue;
            }

            // quebra o texto em linhas com base nas dimensões da caixa (valores empíricos)
            $wrapped = self::pixel_word_wrap($disc->{$campo}, $width, $size, $font);

            // convertendo para fim de linha do linux
            $wrapped = str_replace("\r", '', $wrapped);

            // se houver menos que $numlinhas vamos finalizar antes
            if (substr_count($wrapped, '__x__') + substr_count($wrapped, "\n") <= $numlinhas) {
                continue;
            }

            $offset = 0;
            $count = 0;
            $quebras = 1;
            while (true) {
                $pos1 = mb_strpos($wrapped, '__x__', $offset);
                $pos2 = mb_strpos($wrapped, "\n", $offset);

                if ($pos1 === false && $pos2 === false) {
                    // dd('pos1 ' . $pos1, 'pos2 ' . $pos2, 'offset ' . $offset, 'count ' . $count,
                    //    'strlen ' . strlen($wrapped), $wrapped);
                    break;
                }

                // se chegou ao fim vamos colocar o máximo
                $pos1 = $pos1 ?: strlen($wrapped);
                $pos2 = $pos2 ?: strlen($wrapped);

                if ($pos1 < $pos2) {
                    // remove marcador de quebra
                    if ($count == $numlinhas * $quebras) {
                        $wrapped = mb_substr($wrapped, 0, $pos1) . '__quebrar__' . mb_substr($wrapped, $pos1 + 5);
                        $quebras++;
                    } else {
                        $wrapped = mb_substr($wrapped, 0, $pos1) . mb_substr($wrapped, $pos1 + 5);
                    }
                    $offset = $pos1 + 1;
                } else {
                    // quebra de linha
                    if ($count == $numlinhas * $quebras) {
                        $wrapped = mb_substr($wrapped, 0, $pos2) . '__quebrar__' . mb_substr($wrapped, $pos2 + 1);
                        $quebras++;
                    }
                    $offset = $pos2 + 1;
                }

                $count++;

                // limitando forçadamente (nunca deve acontecer)
                if ($count > 1000) {
                    break;
                }
            }

            $disc->{$campo} = $wrapped;
        }
        return $disc;
    }

    /**
     * Wraps a string to a given number of pixels.
     *
     * This function operates in a similar fashion as PHP's native wordwrap function; however,
     * it calculates wrapping based on font and point-size, rather than character count. This
     * can generate more even wrapping for sentences with a consider number of thin characters.
     * obtido em 4/2040 por masaki de https://www.php.net/manual/en/function.wordwrap.php
     *
     * @static $mult;
     * @param string $text - Input string.
     * @param float $width - Width, in pixels, of the text's wrapping area.
     * @param float $size - Size of the font, expressed in pixels.
     * @param string $font - Path to the typeface to measure the text with.
     * @return string The original string with line-breaks manually inserted at detected wrapping points.
     */
    public static function pixel_word_wrap($text, $width, $size, $font)
    {
        # Passed a blank value? Bail early.
        if (!$text) {
            return $text;
        }

        # Check if imagettfbbox is expecting font-size to be declared in points or pixels.
        static $mult;
        $mult = $mult ?: (version_compare(GD_VERSION, '2.0', '>=') ? .75 : 1);

        # Text already fits the designated space without wrapping.
        $box = imagettfbbox($size * $mult, 0, $font, $text);
        if ($box[2] - $box[0] / $mult < $width) {
            return $text;
        }

        # Start measuring each line of our input and inject line-breaks when overflow's detected.
        $output = '';
        $length = 0;

        $words = preg_split('/\b(?=\S)|(?=\s)/', $text);
        $word_count = count($words);
        for ($i = 0; $i < $word_count; ++$i) {

            # Newline
            if (PHP_EOL === $words[$i]) {
                $length = 0;
            }

            # Strip any leading tabs.
            if (!$length) {
                $words[$i] = preg_replace('/^\t+/', '', $words[$i]);
            }

            $box = imagettfbbox($size * $mult, 0, $font, $words[$i]);
            $m = $box[2] - $box[0] / $mult;

            # This is one honkin' long word, so try to hyphenate it.
            if (($diff = $width - $m) <= 0) {
                $diff = abs($diff);

                # Figure out which end of the word to start measuring from. Saves a few extra cycles in an already heavy-duty function.
                if ($diff - $width <= 0) {
                    for ($s = strlen($words[$i]); $s; --$s) {
                        $box = imagettfbbox($size * $mult, 0, $font, substr($words[$i], 0, $s) . '-');
                        if ($width > ($box[2] - $box[0] / $mult) + $size) {
                            $breakpoint = $s;
                            break;
                        }
                    }
                } else {
                    $word_length = strlen($words[$i]);
                    for ($s = 0; $s < $word_length; ++$s) {
                        $box = imagettfbbox($size * $mult, 0, $font, substr($words[$i], 0, $s + 1) . '-');
                        if ($width < ($box[2] - $box[0] / $mult) + $size) {
                            $breakpoint = $s;
                            break;
                        }
                    }
                }

                if ($breakpoint) {
                    $w_l = substr($words[$i], 0, $s + 1) . '-';
                    $w_r = substr($words[$i], $s + 1);

                    $words[$i] = $w_l;
                    array_splice($words, $i + 1, 0, $w_r);
                    ++$word_count;
                    $box = imagettfbbox($size * $mult, 0, $font, $w_l);
                    $m = $box[2] - $box[0] / $mult;
                }
            }

            # If there's no more room on the current line to fit the next word, start a new line.
            if ($length > 0 && $length + $m >= $width) {
                // $output .= PHP_EOL;
                $output .= '__x__';
                $length = 0;

                # If the current word is just a space, don't bother. Skip (saves a weird-looking gap in the text).
                if (' ' === $words[$i]) {
                    continue;
                }
            }

            # Write another word and increase the total length of the current line.
            $output .= $words[$i];
            $length += $m;
        }

        return $output;
    }
}
