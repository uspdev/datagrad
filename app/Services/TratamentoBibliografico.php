<?php

namespace App\Services;

use Carbon\Carbon;
use Uspdev\Replicado\DB;

class TratamentoBibliografico
{

    public static function obterBibliografia($coddis, $dtainibbg = null, $dtafimbbg = null)
    {
        $dtainibbg = $dtainibbg === '-' ? null : $dtainibbg;
        $dtafimbbg = $dtafimbbg === '-' ? null : $dtafimbbg;

        $query = "SELECT B.dscbbgdis, B.dscbbgdiscpl
            FROM DISCIPGRBIBLIOG B
            WHERE B.coddis = :coddis";

        $params = [
            'coddis' => $coddis,
        ];


        if (!empty($dtainibbg)) {
            $query .= " AND (B.dtafimbbg IS NULL OR B.dtafimbbg >= :dtainibbg)";
            $params['dtainibbg'] = $dtainibbg;
        }

        if (!empty($dtafimbbg)) {
            $query .= " AND (B.dtainibbg IS NULL OR B.dtainibbg <= :dtafimbbg)";
            $params['dtafimbbg'] = $dtafimbbg;
        }

        // return ($query . json_encode($params));
        $res = DB::fetch($query, $params);
        return $res;
    }

    public static function listarBibliografiasDaUnidadePorPeriodo($periodo = null)
    {
        if (!$periodo) {
            $periodo = date('Y') . (date('n') <= 6 ? '1' : '2');
        }
        $ano = substr($periodo, 0, 4);
        $semestre = substr($periodo, 4, 1);
        $dataRef = ($semestre == '1') ? "{$ano}-03-01" : "{$ano}-08-01";

        $query = "SELECT D.coddis, B.dtainibbg, B.dtafimbbg, B.dscbbgdis, B.dscbbgdiscpl
              FROM DISCIPLINAGR D
              INNER JOIN DISCIPGRBIBLIOG B ON D.coddis = B.coddis
              INNER JOIN DISCIPGRCODIGO C ON D.coddis = C.coddis
              WHERE D.sitdis IN ('AT', 'AP')
                AND C.codclg IN (__codundclgs__)
                AND B.dtainibbg <= :dataRef
                AND (B.dtafimbbg >= :dataRef OR B.dtafimbbg IS NULL)
              ORDER BY D.coddis";

        $query = DB::automaticReplaces($query);
        $params = ['dataRef' => $dataRef];

        $resultados = DB::fetchAll($query, $params);
        if (!$resultados) return null;

        $registrosTabela = [];

        // Coleta, parsing e estruturação dos dados
        foreach ($resultados as $row) {
            $coddis = trim($row['coddis']);

            if (isset($disciplinasProcessadas[$coddis])) {
                // elimina repetidos aqui ao invés de no SQL, pois o SQL não consegue eliminar duplicados de forma confiável
                continue;
            }
            $disciplinasProcessadas[$coddis] = true;

            $dtaIni = !empty($row['dtainibbg']) ? Carbon::parse($row['dtainibbg'])->format('Y-m-d') : '-';
            $dtaFim = !empty($row['dtafimbbg']) ? Carbon::parse($row['dtafimbbg'])->format('Y-m-d') : '-';

            $mapeamentoTipos = [
                'Principal'    => $row['dscbbgdis'],
                'Comple.' => $row['dscbbgdiscpl']
            ];

            foreach ($mapeamentoTipos as $tipo => $textoBruto) {
                // Processa o bloco de texto e retorna um array de obras individuais
                $obrasExtraidas = self::extrairObrasDoTexto($textoBruto);

                if (!empty($obrasExtraidas)) {
                    foreach ($obrasExtraidas as $obra) {
                        $registrosTabela[] = [
                            'periodo' => $periodo,
                            'coddis'  => $coddis,
                            'dtaini'  => $dtaIni,
                            'dtafim'  => $dtaFim,
                            'tipo'    => $tipo,
                            'obra'    => $obra
                        ];
                    }
                }
            }
        }

        usort($registrosTabela, function ($a, $b) {
            if (class_exists('Collator')) {
                $collator = new \Collator('pt_BR');
                return $collator->compare($a['obra'], $b['obra']);
            }
            return strnatcasecmp($a['obra'], $b['obra']);
        });

        // $saidaFinal = self::gerarTabelaMarkdown($registrosTabela);

        return $registrosTabela;
    }

    /**
     * Converte o array de registros de bibliografia em uma tabela formatada em Markdown.
     *
     * @param array $registrosTabela Lista de registros formatados.
     * @return string Tabela em Markdown acompanhada do contador de registros.
     */
    private static function gerarTabelaMarkdown(array $registrosTabela): string
    {
        $total = count($registrosTabela);

        if ($total === 0) {
            return "Nenhum registro encontrado.\n";
        }

        $saida = "Registros encontrados: {$total}\n\n";
        $saida .= "| Ano_Semestre | Cod_Disciplina | Data_Inicio_Bib | Data_Fim_Bib | Tipo_Bibliografia | Obra_Referencia |\n";
        $saida .= "| :--- | :--- | :--- | :--- | :--- | :--- |\n";

        foreach ($registrosTabela as $item) {
            // Sanitiza para evitar que barras verticais ou quebras de linha dentro da obra quebrem a tabela
            $obraLimpa = str_replace(['|', "\n", "\r"], ['&#124;', ' ', ''], $item['obra'] ?? '');

            $saida .= sprintf(
                "| %s | %s | %s | %s | %s | %s |\n",
                $item['periodo'] ?? '',
                $item['coddis'] ?? '',
                $item['dtaini'] ?? '',
                $item['dtafim'] ?? '',
                $item['tipo'] ?? '',
                $obraLimpa
            );
        }

        return $saida;
    }

    /**
     * Extrai obras bibliográficas pressupondo que cada linha do texto contém exatamente uma referência.
     */
    public static function extrairObrasDoTexto(?string $textoBruto): array
    {
        if (empty($textoBruto)) {
            return [];
        }

        // 1. Decodifica entidades HTML (&quot;, &amp;, &para;, etc)
        $texto = html_entity_decode($textoBruto, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 2. Normaliza quebras de linha de sistema (\r\n e \r -> \n)
        $texto = str_replace(["\r\n", "\r"], "\n", $texto);

        // 3. Transforma tags de quebra (<br>, </p>, </p><p>) e pilcrows (¶) em \n
        $texto = preg_replace('/(<\s*\/?\s*(br|p)\b[^>]*>|¶)/iu', "\n", $texto);

        // 4. Remove o restante das tags HTML residuais
        $texto = strip_tags($texto);

        // 5. Quebra o texto linha por linha
        $linhas = explode("\n", $texto);

        $obras = [];

        foreach ($linhas as $linha) {
            $linhaTratada = trim($linha);

            // Ignora linhas totalmente em branco
            if (empty($linhaTratada)) {
                continue;
            }

            // Sanitiza a linha (remove rótulos, marcadores e numerações)
            $obraLimpa = self::limparPrefixosECabecalhos($linhaTratada);

            // Se sobrou conteúdo válido (não era apenas um rótulo isolado), adiciona ao array
            if (!empty($obraLimpa) && !preg_match('/^([\-\*\•\.\s]|\[\d+\]|\d+[\.\)])+$/u', $obraLimpa)) {
                $obras[] = $obraLimpa;
            }
        }

        // Retorna apenas referências únicas e reindexa o array
        return array_values(array_unique($obras));
    }

    /**
     * Remove rótulos, marcadores, numerações e prefixos institucionais do início do texto.
     */
    private static function limparPrefixosECabecalhos(string $texto): string
    {
        $textoLimpo = trim($texto);

        // 1. Remove ícones e caracteres especiais de controle/PUA (\p{Co} e \p{C}) e espaços no início
        $textoLimpo = preg_replace('/^[\p{Co}\p{C}\s]+/u', '', $textoLimpo);

        // 1. Remove ícones e caracteres especiais invisíveis/PUA (\p{Co} e \p{C}) no início do texto
        $textoLimpo = preg_replace('/^[\p{Co}\p{C}\s]+/u', '', $textoLimpo);

        // 2. Remove marcadores, numerações e [N] ou |N| (ex: "|2|", "39-", "34", "6.", "[5] -", "(*)")
        $textoLimpo = preg_replace('/^([\-\*\•\●]|\([\-\*\•\●]\)|\[\d+\][\s\-\–\—]*|\|\s*\d+\s*\||\d+[\.\)\-\–\—]*)\s*/u', '', $textoLimpo);

        // 3. Remove pontos e espaços residuais no início
        $textoLimpo = preg_replace('/^\.+\s*/u', '', $textoLimpo);

        // 3. Lista de prefixos ordenados por prioridade (compostas primeiro, simples por último)
        $padroesPrefixos = [
            // Compostos / Qualificados / Bilíngues
            '/^(Principal\s*\/\s*Main|Main\s*\/\s*Principal)\s*:*\s*/iu',
            '/^(Complementar\s*\/\s*(Complementary|Complemetary)|(Complementary|Complemetary)\s*\/\s*Complementar)\s*:*\s*/iu',
            '/^(Bibliografia|Bibliografias|Refer[êe]ncias?)\s*\(\s*(principal|b[áa]sica|complementar)(\s+e\s+complementar)?\s*\)\s*:*\s*/iu',
            '/^Bibliografia\s+(b[áa]sica|fundamental)\s*:?\s*\/\s*References?\s*:*\s*/iu',
            '/^Bibliografia\s+(b[áa]sica|fundamental)\s*\/\s*Bibliography\s*:*\s*/iu',
            '/^Bibliografia\s*:?\s*\/\s*References?\s*:*\s*/iu',
            '/^(Bibliografia|Refer[êe]ncias?)\s+(b[áa]sica|fundamental|complementar|obrigat[óo]ria|principal)s?\.?\s*:*\s*/iu',
            '/^(Bibliografia\s+complementar|Complementar)\s*\/\s*(Complementary|Complemetary|Complementar|Additional)\s+(references?|reading)?\s*:*\s*/iu',
            '/^Bibliografias?\s*\(?\s*principal\s*e\s*complementar\s*\)?\s*:*\s*/iu',
            '/^Bibliografia\s+b[áa]sica\s*\/\s*Bibliography\s*:*\s*/iu',
            '/^(Main|Supplementary|Additional)\s+(reading|bibliography|references?)\.?\s*:*\s*/iu',
            '/^Complemet?ary\s+references?\.?\s*:*\s*/iu',
            '/^Notas?\s+de\s+aulas?\.?\s*:*\s*/iu',
            '/^N[ãaõo]{1,2}\s*(indicada|informada)\.?\s*/iu',
            '/^A\s+indicar\.?\s*/iu',

            // Simples / Genéricos
            '/^(Bibliografia|Bibliography|References?|Bibliografias|Refer[êe]ncias?)\.?\s*:*\s*/iu',
            '/^(B[áa]sica|Complementar|Principal)s?\.?\s*:*\s*/iu',
            '/^Complemet?ary\.?\s*:*\s*/iu'
        ];

        foreach ($padroesPrefixos as $padrao) {
            $textoLimpo = preg_replace($padrao, '', $textoLimpo);
        }

        return trim($textoLimpo);
    }
    /**
     * Verifica se o texto é apenas uma frase institucional/rótulo a ser ignorado.
     */
    private static function deveIgnorarObra(string $texto): bool
    {
        $textoLimpo = trim($texto);

        if (empty($textoLimpo)) {
            return true;
        }

        $padroesIgnorados = [
            // Linhas contendo apenas ponto, números ou pontuações/espaços
            '/^([\-\*\•\.\s]|\[\d+\]|\d+[\.\)])+$/u',

            // Indicações vazias (ex: "1. Não indicada", "[1] Nõa indicada", "A indicar")
            '/^([\-\*\•]|\[\d+\]|\d+[\.\)])?\s*\.*?\s*\(?\s*N[ãaõo]{1,2}\s*(indicada|informada)\.?\s*\)?$/iu',
            '/^([\-\*\•]|\[\d+\]|\d+[\.\)])?\s*\.*?\s*\(?\s*A\s+indicar\.?\s*\)?$/iu',

            // Rótulos simples (ex: "Notas de aula", "1. Bibliografia:", "References:")
            '/^([\-\*\•]|\[\d+\]|\d+[\.\)])?\s*\.*?\s*\(?\s*Notas?\s+de\s+aulas?\.?\s*:?\s*\)?$/iu',
            '/^([\-\*\•]|\[\d+\]|\d+[\.\)])?\s*\.*?\s*\(?\s*(Bibliografia|Bibliography|References?|Bibliografias|Refer[êe]ncias?)\.?\s*:?\s*\)?$/iu',
            '/^([\-\*\•]|\[\d+\]|\d+[\.\)])?\s*\.*?\s*\(?\s*(Main|Supplementary|Additional)\s+(reading|bibliography|references?)\.?\s*:?\s*\)?$/iu',
            '/^([\-\*\•]|\[\d+\]|\d+[\.\)])?\s*\.*?\s*\(?\s*Complementary(\s+references?)?\.?\s*:?\s*\)?$/iu',

            // Rótulos compostos e palavras únicas (ex: "Básica:", "1. BÁSICA", "Referências Básicas:")
            '/^([\-\*\•]|\[\d+\]|\d+[\.\)])?\s*\.*?\s*\(?\s*(Bibliografia|Refer[êe]ncias?)\s+(b[áa]sica|fundamental|complementar|obrigat[óo]ria|principal)s?\.?\s*:?\s*\)?$/iu',
            '/^([\-\*\•]|\[\d+\]|\d+[\.\)])?\s*\.*?\s*\(?\s*(B[áa]sica|Complementar|Principal)s?\.?\s*:?\s*\)?$/iu',

            // Combinações específicas (ex: ".Bibliografia (principal e complementar):")
            '/^([\-\*\•]|\[\d+\]|\d+[\.\)])?\s*\.*?\s*\(?\s*Bibliografias?\s*\(?\s*principal\s*e\s*complementar\s*\)?\s*:?\s*\)?$/iu',

            // Rótulos com barra
            '/^([\-\*\•]|\[\d+\]|\d+[\.\)])?\s*\.*?\s*\(?\s*Bibliografia\s+b[áa]sica\s*\/\s*Bibliography\s*:?\s*\)?$/iu',
            '/^([\-\*\•]|\[\d+\]|\d+[\.\)])?\s*\.*?\s*\(?\s*(Bibliografia\s+complementar|Complementar)\s*\/\s*(Complementary|Additional)\s+references?\s*:?\s*\)?$/iu',
            '/^([\-\*\•]|\[\d+\]|\d+[\.\)])?\s*\.*?\s*\(?\s*Bibliografia\s*\/\s*References?\s*:?\s*\)?$/iu'
        ];

        foreach ($padroesIgnorados as $padrao) {
            if (preg_match($padrao, $textoLimpo)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Limpa e sanitiza o texto bruto de bibliografias vindo do banco de dados.
     *
     * @param string|null $texto
     * @return string
     */
    public static function limparTextoBibliografico(?string $texto): string
    {
        if (empty($texto)) {
            return '';
        }

        // 1. Decodifica entidades HTML (ex: &ecirc; -> ê, &amp; -> &, &rsquo; -> ')
        $texto = html_entity_decode($texto, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 2. Substitui quebras de linha em tags HTML (<br>, <p>) por quebra de linha real
        $texto = preg_replace('/<br\s*\/?>/i', "\n", $texto);
        $texto = preg_replace('/<\/p>/i', "\n", $texto);

        // 3. Remove todas as demais tags HTML mantendo apenas o texto puro
        $texto = strip_tags($texto);

        // 4. Normaliza quebras de linha do Windows/Mac (\r\n, \r) para padrão Unix (\n)
        $texto = str_replace(["\r\n", "\r"], "\n", $texto);

        // 5. Substitui o espaço não-quebrável (NBSP) por espaço simples
        $texto = str_replace("\xc2\xa0", ' ', $texto);

        // 6. Remove múltiplos espaços em branco horizontais no meio das linhas
        $texto = preg_replace('/[ \t]+/', ' ', $texto);

        // 7. Limita o excesso de linhas em branco consecutivas a no máximo uma
        $texto = preg_replace("/\n{3,}/", "\n\n", $texto);

        return trim($texto);
    }
}
