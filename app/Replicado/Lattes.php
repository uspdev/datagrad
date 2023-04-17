<?php

namespace App\Replicado;

use Exception;
use GuzzleHttp\Client;
use Uspdev\Replicado\DB;
use Uspdev\Replicado\Lattes as LattesReplicado;
use Uspdev\Replicado\Uteis;

class Lattes extends LattesReplicado
{
    /**
     * Retorna array contendo os títulos (graduacao, mestrado ...) na chave e o ano de conclusão no valor
     */
    public static function retornarFormacaoAcademicaFormatado($codpes)
    {
        $ret = [];
        if ($formacao = Lattes::retornarFormacaoAcademica($codpes)) {
            foreach ($formacao as $nome => $titulos) {
                $ret[strtolower($nome)] = SELF::retornarTitulosFormatado($titulos);
            }
        }
        return $ret;
    }

    public static function retornarLinkCurriculo($codpes)
    {
        $id = SELF::id($codpes);
        if ($id) {
            return '<a href="https://lattes.cnpq.br/' . $id . '" target="_lattes">' . $id . '</a>';
        } else {
            return '-';
        }
    }

    public static function retornarLinkOrcid($codpes)
    {
        return ($orcid = Lattes::retornarOrcidID($codpes))
        ? '<a href="' . $orcid . '" target="_orcid">' . str_replace('https://orcid.org/', '', $orcid) . '</a>'
        : '-';
    }

    /**
     * Auxiliar para retornarFormacaoAcademicaFormatado()
     */
    protected static function retornarTitulosFormatado($titulos)
    {
        $ret = '';
        foreach ($titulos as $titulo) {
            if (!empty($titulo['ANO-DE-CONCLUSAO'])) {
                $ret .= $titulo['ANO-DE-CONCLUSAO'];
                if ($titulo['STATUS-DO-CURSO'] != 'CONCLUIDO' && !empty($titulo['STATUS-DO-CURSO'])) {
                    $ret .= ' (' . strtolower($titulo['STATUS-DO-CURSO']) . ')';
                }
                $ret .= ', ';
            } else {
                $ret .= str_replace('_', ' ', strtolower($titulo['STATUS-DO-CURSO'])) . ', ';
            }
        }
        return substr($ret, 0, -2);
    }

    /**
     * Retorna data da última alteração fornecida pelo CNPq.
     *
     * Indica a data da última atualização nos dados do LATTES para esta pessoa
     * (detectada pelo 'robô' do CurriculumCPNQ no site do LATTES).
     *
     * @param Int $codpes
     * @return String formatado em dd/mm/yyyy
     * @author Masaki K Neto, em 10/3/3023
     */
    public static function retornarDataUltimaAlteracao(int $codpes)
    {
        $query = "SELECT  CONVERT(VARCHAR(10), dtaultalt ,103) dtaultalt
            FROM DIM_PESSOA_XMLUSP
            WHERE codpes = convert(int,:codpes)";

        $param['codpes'] = $codpes;
        $result = DB::fetch($query, $param);

        return $result ? $result['dtaultalt'] : false;
    }

    /**
     * Retorna a foto do currículo lattes
     *
     * Este método consulta a url do curriculo lattes que redireciona para
     * outra url que usa um id que começa com k....
     * Com esse outro id é possível acessar a url de foto.
     *
     * @param Int $id Id lattes da pessoa
     * @param $cachePath (opt) Indica o caminho no filesystem para salvar a foto
     * @return Img Blob da imagem
     * @author Masakik, em 3/4/2023
     */
    public static function obterFoto($id, $cachePath = null)
    {
        $curriculoUrl = 'http://buscatextual.cnpq.br/buscatextual/cv?id=';
        $fotoUrl = 'http://servicosweb.cnpq.br/wspessoa/servletrecuperafoto?tipo=1&id=';
        $foto = '';

        if ($cachePath) {
            $cachePath = rtrim($cachePath, '/') . '/'; // garantindo trailing slash
            is_dir($cachePath) || mkdir($cachePath, 0755, true);
            $filename = $id . '.jpg';

            // recuperando do cache se existir
            if (file_exists($cachePath . $filename)) {
                $foto = file_get_contents($cachePath . $filename);
            }
        }

        if (!$foto) {
            try {
                $client = new Client();
                $response = $client->request('GET', $curriculoUrl . $id, ['allow_redirects' => false]);
                $headers = $response->getHeader('Location');
                parse_str($headers[0], $parsedUrl);
                $idk = $parsedUrl['id'] ?? null;
                $foto = file_get_contents($fotoUrl . $idk);

                // salvando cache
                file_put_contents($cachePath . $filename, $foto);
            } catch (Exception $e) {
                // gerar log do exception
            }
        }

        return $foto;
    }
}
