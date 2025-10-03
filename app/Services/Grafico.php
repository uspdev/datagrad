<?php

namespace App\Services;

use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;

class Grafico
{
    /**
     * Retorna o gráfico da evasão em base64.
     */
    public static function criarGraficoEvasao($taxaEvasao, $formRequest)
    {
        $anos = array_keys($taxaEvasao);

        $graph = new Graph\Graph(800, 500);
        $graph->SetScale('textlin', 0, 100);
        $graph->SetShadow();
        $graph->title->Set("Fluxo de alunos - {$formRequest['nomcur']} ({$formRequest['codcur']}) - Ingressantes {$formRequest['anoIngresso']}");
        $graph->xaxis->SetTickLabels($anos);
        $graph->xaxis->title->Set("anos");
        $graph->yaxis->title->Set("%");

        $tipos = [
            ['txPermanencia', 'blue', 'Taxa de permanência'],
            ['txDesistenciaAcc', 'red', 'Taxa de evasão acumulada'],
            ['txConclusaoAcc', 'orange', 'Taxa de conclusão acumulada'],
        ];

        foreach ($tipos as $tipo) {
            $dados_tipo = [];
            foreach ($taxaEvasao as $dados) {
                array_push($dados_tipo, $dados[$tipo[0]]);
            }
            $lineplot = new Plot\LinePlot($dados_tipo);
            $lineplot->mark->SetType(MARK_FILLEDCIRCLE);
            $lineplot->mark->SetColor($tipo[1]);
            $lineplot->mark->SetFillColor($tipo[1]);
            $lineplot->SetColor($tipo[1]);
            $lineplot->SetLegend($tipo[2]);
            $lineplot->value->SetColor($tipo[1]);
            $lineplot->value->Show();

            $graph->Add($lineplot, $tipo[2]);
        }

        // Exibição do gráfico
        ob_start();
        $graph->Stroke();
        $image_data = ob_get_clean();

        // Codificar a imagem em base64
        return base64_encode($image_data);
    }
}
