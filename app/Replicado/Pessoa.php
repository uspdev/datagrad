<?php

namespace App\Replicado;

use Uspdev\Replicado\DB;
use Uspdev\Replicado\Pessoa as PessoaReplicado;
use Uspdev\Replicado\Uteis;

class Pessoa extends PessoaReplicado
{
    /**
     * Retorna os vinculos encerrados de VINCULOPESSOAUSP e respectivo setor
     *
     * Talvez precise limitar por unidade
     *
     * @param Int $codpes
     * @return Array
     * @author Masaki K Neto, em 21/3/2023
     */
    public static function listarVinculosEncerrados(int $codpes)
    {
        $query = "SELECT S.nomabvset, S.nomset, V.*
            FROM VINCULOPESSOAUSP V
                LEFT JOIN SETOR S ON S.codset = V.codset
            WHERE codpes = convert(INT, :codpes)
                AND sitatl != 'A'
            ORDER BY dtafimvin";
        $param['codpes'] = $codpes;
        $result = DB::fetchAll($query, $param);

        return $result;
    }

    /**
     * Lista os dados de vinculos ativos da pessoa de VINCULOPESSOAUSP
     *
     * O método no replicado usa localizapessoa, que parece melhor que esse
     *
     * Não limita por unidade pois a tabela possui dados de outras unidades.
     * Pode não incluir designações
     *
     * @param $codpes
     * @param $designados Se false não retorna designados
     * @return Array
     * @author Masaki K Neto, em 14/3/2022
     * @author Masaki K Neto, modificado em 30/3/2023
     */
    public static function listarVinculosAtivosDeVinculo($codpes, $designados = true)
    {
        $queryDesignados = $designados ? '' : 'AND tipdsg is NULL';

        $query = "SELECT S.nomabvset, S.nomset, V.*
            FROM VINCULOPESSOAUSP V
                INNER JOIN SETOR S ON S.codset = V.codset
            WHERE codpes = convert(INT, :codpes)
                AND sitatl = 'A'
                $queryDesignados
            ORDER BY dtafimvin";

        $param['codpes'] = $codpes;

        return DB::fetchAll($query, $param);
    }

    /**
     *
     */
    public static function retornarTipoJornada($codpes)
    {
        if ($vinculos = Pessoa::listarVinculosAtivosDeVinculo($codpes)) {
            $tipoJornada = $vinculos[0]['tipjor'];
        } else {
            // se nao tiver jornada deve ser aposentado ???
            $tipoJornada = 'Aposentado';
        }
        return $tipoJornada;
    }

    /**
     * Retorna o setor do 1o vinculo ativo da pessoa
     */
    public static function retornarSetor($codpes)
    {
        if ($vinculos = Pessoa::listarVinculosAtivosDeVinculo($codpes)) {
            $nomeAbreviadoSetor = $vinculos[0]['nomabvset'];
        } else {
            $nomeAbreviadoSetor = '-';
        }
        return $nomeAbreviadoSetor;
    }

    /**
     * Mostra o endereço formatado em pessoa->replicado()
     */
    public static function retornarEnderecoFormatado($codpes)
    {
        if ($endereco = PessoaReplicado::obterEndereco($codpes)) {
            $endereco = "
                {$endereco['nomtiplgr']} {$endereco['epflgr']},
                {$endereco['numlgr']} {$endereco['cpllgr']} -
                {$endereco['nombro']} - {$endereco['cidloc']}  -
                {$endereco['sglest']} - CEP: {$endereco['codendptl']}
            ";
        } else {
            $endereco = 'Não encontrado';
        }
        return $endereco;
    }

    public static function procurarServidorPorNome($nome, $fonetico = true)
    {
        // return PessoaReplicado::procurarPorCodigoOuNome($nome, true);
        $procurar = PessoaReplicado::procurarPorNome($nome, $fonetico, $ativos = true);
        foreach ($procurar as $pessoa) {
            if (isset($pessoa['tipvin']) && $pessoa['tipvin'] == 'SERVIDOR') {
                return $pessoa;
            }
        }
        return [];
    }

    /**
     * Método para buscar pessoas por codpes ou parte do nome
     *
     * A busca é fonética, somente ativos ou todos
     * O método foi ajustado para compatibilizar com procuraPorNome() e dump()
     *
     * 9/3/2022 Revertido em parte para o método original, mas mantendo fonético
     *
     * @param String $busca Código ou Nome a ser buscado
     * @param Bool $ativos Se true faz busca somente entre os ativos, se false busca em toda a base
     * @return array
     * @author André Canale Garcia <acgarcia@sc.sp.br> // Adaptação do método procurarPorNome
     * @author Masaki K Neto, modificado em 1/2/2022
     */
    public static function procurarPorCodpesOuNome(string $busca, bool $ativos = true)
    {
        if ($ativos) {
            # se ativos vamos fazer join com LOCALIZAPESSOA
            if (is_numeric($busca)) { // é codpes
                $query = "SELECT DISTINCT P.*, L.* FROM PESSOA P
                INNER JOIN LOCALIZAPESSOA L on L.codpes = P.codpes
                WHERE P.codpes = convert(int,:codpes)
                AND L.codpes = P.codpes
                AND L.tipdsg = NULL --exclui designações
                
                ORDER BY P.nompes ASC";
                $param['codpes'] = $busca;
            } else { // é string (nome)
                $query = "SELECT DISTINCT P.*, L.* FROM PESSOA P
                INNER JOIN LOCALIZAPESSOA L on L.codpes = P.codpes
                WHERE P.nompesfon LIKE :nome
                AND L.codpes = P.codpes
                AND L.tipdsg = NULL --exclui designações
                ORDER BY P.nompes ASC";
                $param['nome'] =  '%' . Uteis::fonetico($busca) . '%';
                
            }

        } 

        $res = DB::fetch($query, $param);
        if (isset($res[0]) && is_array($res[0])) {
            dd($res);
        }
        return $res;
    }

    /**
     * Método para retornar *array* com a lista de servidores docentes por setor(es)
     *
     * Pode passar codigos dos setores em array ou inteiro
     * Se aposentados = true (default), lista também os docentes aposentados
     *
     * @param Array|Integer $codset
     * @param Bool $aposentados (opt) Default true
     * @return Array
     * @author Masakik, em 30/09/2024
     */
    public static function listarDocentesSetor($codsets, bool $aposentados = true)
    {
        if (is_array($codsets)) {
            $codsets = implode(',', $codsets);
        }

        $aposentados = $aposentados ? "OR L.tipvinext='Docente Aposentado'" : '';
        
        $query = "SELECT DISTINCT P.*
            FROM PESSOA P
            INNER JOIN LOCALIZAPESSOA L ON (P.codpes = L.codpes)
            WHERE L.codset IN ($codsets) AND L.codfncetr = 0 AND (L.tipvinext='Docente' $aposentados)
            ORDER BY P.nompes";
        return DB::fetchAll($query);
    }
}
