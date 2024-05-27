<?php

namespace App\Replicado;

use Uspdev\Replicado\DB;
use Uspdev\Replicado\Graduacao as GraduacaoReplicado;
use Uspdev\Replicado\Replicado;

class Graduacao extends GraduacaoReplicado
{
    /**
     * tipoObrigatoriedade da tabela GRADECURRICULAR
     *
     * Conforme descrito na documentação
     */
    public static $tipobg = [
        'O' => 'Obrigatória',
        'E' => 'Eletiva',
        'P' => 'Optativa',
        'L' => 'Optativa livre',
        'F' => 'Facultativa',
        'C' => 'Complementar',
    ];

    /**
     * tipoHabilitacao da tabela HABILITACAOGR
     *
     * Os tipos foram obtidos consultando o BD da EESC e
     * os que tem ?? não são descirtos na documentação
     */
    public static $tiphab = [
        'B' => 'Grau principal exclusivo',
        'G' => 'Grau principal com sequência opcional',
        'H' => 'H ??',
        'I' => 'I ??',
        'J' => 'J ??',
        'L' => 'L ??',
        'M' => 'M ??',
        'N' => 'N ??',
        'O' => 'O ??',
        'P' => 'P ??',
        'S' => 'S ??',
        'U' => 'Núcleo básico ou geral',
    ];

    /**
     * status-turma de TURMAGR
     */
    public static $statur = [
        'A' => 'Ativa',
        'D' => 'Não ativa',
        'C' => 'Consolidada',
    ];

    /**
     * disciplina-curicular de HABILTURMA
     */
    public static $discrl = [
        'O' => 'Obrigatória',
        'L' => 'Optativa livre',
        'C' => 'Optativa complementar',
        'N' => 'Extra curricular',
    ];

    //Ciclo da disciplina dentro da grade curricular do curso de graduação:
    //B- Básico, P- Profissional, T- Profissionalizante, E- Estágio ou C- TCC.
    //OBS: O ciclo é utilizado apenas para as disciplinas obrigatórias.
    // Sem uso ainda
    public static $cicdisgdecrl = [
        'B' => 'Básico',
        'P' => 'Profissional',
        'T' => 'Profissionalizante',
        'E' => 'Estágio',
        'C' => 'TCC',
    ];

    //Indica a situação em que a disciplina se encontra:
    //PE- pendente, AU- aguardando análise da própria UNIDADE, AO- aguardando análise de OUTRAS unidades, AT- ativada, AP- aprovada, DT- desativada.
    //Esta coluna indica em qual parte do fluxo de aprovação se encontra a disciplina.
    public static $sitdis = [
        'PE' => 'Pendente',
        'AU' => 'Aguardando análise da própria unidade',
        'AO' => 'Aguardando análise de outras unidades',
        'AT' => 'Ativada',
        'AP' => 'Aprovada',
        'DT' => 'Desativada',
    ];

    /**
     * Lista os cursos e habilitações da unidade
     *
     * Refatorado de obterCursosHabilitacoes
     *
     * @return Array
     * @author Masaki K Neto, em 9/5/2023
     */
    public static function listarCursosHabilitacoes()
    {
        // $query = " SELECT CC.codpesdct codpescoord, CC.dtainicdn, CC.dtafimcdn, P.nompes nompescoord,
        //     C.*, H.* FROM CURSOGR C
        //     INNER JOIN HABILITACAOGR H ON C.codcur = H.codcur
        //     INNER JOIN CURSOGRCOORDENADOR CC ON CC.codcur = C.codcur AND CC.codhab = H.codhab
        //     INNER JOIN PESSOA P ON P.codpes = CC.codpesdct
        //     WHERE C.codclg IN (__codundclgs__)
        //         AND CC.dtafimcdn > GETDATE() -- mandato vigente do coordenador
        //         AND ( (C.dtaatvcur IS NOT NULL) AND (C.dtadtvcur IS NULL) ) -- curso ativo
        //         AND ( (H.dtaatvhab IS NOT NULL) AND (H.dtadtvhab IS NULL) ) -- habilitação ativa
        //     ORDER BY C.nomcur, H.nomhab ASC";

        // aqui está sem o coordenador que estava dando problema na dupla formacao iau
        $query = " SELECT C.*, H.* FROM CURSOGR C
        INNER JOIN HABILITACAOGR H ON C.codcur = H.codcur
        WHERE C.codclg IN (__codundclgs__)
            AND ( (C.dtaatvcur IS NOT NULL) AND (C.dtadtvcur IS NULL) ) -- curso ativo
            AND ( (H.dtaatvhab IS NOT NULL) AND (H.dtadtvhab IS NULL) ) -- habilitação ativa
        ORDER BY C.nomcur, H.nomhab ASC";

        return DB::fetchAll($query);
    }

    /**
     * Retorna dados da tabela CURSOGR e HABILITACAOGR e CURRICULOGR
     */
    public static function obterCurso($codcur, $codhab)
    {
        $query = " SELECT C.*, H.*, CR.* FROM CURSOGR C
        INNER JOIN HABILITACAOGR H ON C.codcur = H.codcur
        INNER JOIN CURRICULOGR CR ON H.codcur = CR.codcur AND H.codhab = CR.codhab
        WHERE H.codcur = CONVERT(INT, :codcur)
            AND H.codhab = CONVERT(INT, :codhab)
            AND ( C.dtaatvcur IS NOT NULL AND C.dtadtvcur IS NULL ) -- curso ativo
            AND ( H.dtaatvhab IS NOT NULL AND H.dtadtvhab IS NULL ) -- habilitação ativa
            AND (
                CR.dtainicrl < GETDATE() AND (CR.dtafimcrl > GETDATE() OR CR.dtafimcrl IS NULL)
            ) -- pega o CURRICULO ativo
        ORDER BY C.nomcur, H.nomhab ASC";

        $params = [
            'codcur' => $codcur,
            'codhab' => $codhab,
        ];

        return DB::fetch($query, $params);
    }

    /**
     * Obtém o curso ativo de um aluno de graduação, procurando em todas as unidades disponíveis na replicação
     *
     * Modificado de obterCursoAtivo para incluir a sigla da unidade e procurar em qualquer unidade
     *
     */
    public static function obterCursoAtivoUnidades($codpes)
    {
        $query = "SELECT L.codpes, L.nompes, F.sglfusclgund AS sglund, C.codcur, C.nomcur, H.codhab, H.nomhab, V.dtainivin, V.codcurgrd
        FROM LOCALIZAPESSOA L
        INNER JOIN VINCULOPESSOAUSP V ON (L.codpes = V.codpes) AND (L.codundclg = V.codclg)
        INNER JOIN CURSOGR C ON (V.codcurgrd = C.codcur)
        INNER JOIN HABILITACAOGR H ON (H.codhab = V.codhab)
        INNER JOIN FUSAOCOLEGIADOUNIDADE F ON (F.codfusclgund = L.codundclg)
        WHERE (L.codpes = convert(int,:codpes))
            AND (L.tipvin = 'ALUNOGR')
            AND (V.codcurgrd = H.codcur AND V.codhab = H.codhab)
        ";
        return DB::fetch($query, ['codpes' => $codpes]);

        // Aqui seria para reaproveitar utilizando qualquer unidade mas não inclui a sigla da unidade
        // Config::setConfig(['codundclgs' => 'SELECT codund FROM UNIDADE']);
        // $ret = Graduacao::obterCursoAtivo($codpes);
        // Config::setConfig(['reset' => true]);
        // return $ret;
    }

    /**
     * ????????????????
     */
    public static function obterCursoFinalizadoUnidades($codpes)
    {
        $query = "SELECT V.*, C.nomcur, H.*, P.*, F.sglfusclgund AS sglund
            FROM PESSOA P
            INNER JOIN VINCULOPESSOAUSP V on V.codpes = P.codpes AND V.tipvin = 'ALUNOGR'
            INNER JOIN VINCSATHABILITACAOGR VS ON VS.codpes = P.codpes
            INNER JOIN CURSOGR C ON VS.codcur = C.codcur
            INNER JOIN HABILITACAOGR H ON H.codhab = VS.codhab AND H.codcur = C.codcur
            INNER JOIN FUSAOCOLEGIADOUNIDADE F ON (F.codfusclgund = V.codclg)
            WHERE
                P.codpes = :codpes
        ";
        return DB::fetch($query, ['codpes' => $codpes]);
    }

    /**
     * Disciplinas (grade curricular) para um currículo atual no JúpiterWeb
     *
     * Se informado semestre, procura a grade ativa no semestre (não implementado ainda)
     *
     * a partir do código do curso e da habilitação
     * adaptado de listarDisciplinasGradeCurricular
     *
     * @param String $codcur
     * @param Int $codhab
     * @return Array (coddis, nomdis, verdis, numsemidl, tipobg)
     * @author Masakik, em 13/6/2023
     */
    public static function listarGradeCurricular($codcur, $codhab, $semestre = null)
    {
        // estava dando erro no TOP na FFLCH. Então tirei o top e incuí
        // o dtafimcrl para pegar o ativo.
        // acrescentado dtainicrl pois tem aqueles que ainda não iniciaram
        // em tese deve retornar somente 1 codcrl
        $query = "SELECT G.*, D.*, C.*
            FROM GRADECURRICULAR G
            INNER JOIN DISCIPLINAGR D ON (G.coddis = D.coddis AND G.verdis = D.verdis)
            INNER JOIN CURRICULOGR C ON G.codcrl = C.codcrl
            WHERE C.codcur = CONVERT(INT, :codcur)
                AND C.codhab = CONVERT(INT, :codhab)
                AND C.dtainicrl < GETDATE() -- pega o curriculogr vigente no dia de hoje
                AND (C.dtafimcrl > GETDATE() OR C.dtafimcrl IS NULL)
            ";
        $param = [
            'codcur' => $codcur,
            'codhab' => $codhab,
        ];
        $ret = DB::fetchAll($query, $param);
        return $ret;
    }

    /**
     * Lista as turmas oferecidas em determinado semestre
     *
     * @param Int $codcur Codigo do curso
     * @param String $codhab Código da habilitação
     * @param String $codtur Código da turma que será concatenado com %
     * @return Array
     * @author Masaki K Neto, em 28/3/2023
     */
    public static function listarTurmas(int $codcur, int $codhab, $semestre)
    {
        $listaCoddis = array_column(self::listarGradeCurricular($codcur, $codhab), 'coddis');
        $strCoddis = implode("','", $listaCoddis);

        // turmas baseadas em habilturma
        $query = "SELECT T.*, D.*, H.*
                , (nummtr + nummtrturcpl + nummtropt + nummtrecr+ nummtroptlre) as nummtrtot -- total de matriculados
                , (numvagtur + numvagturcpl + numvagopt + numvagecr+ numvagoptlre) as numvagtot -- total de vagas
            FROM TURMAGR T
            INNER JOIN DISCIPLINAGR D ON D.coddis = T.coddis AND D.verdis = T.verdis
            INNER JOIN HABILTURMA H ON H.coddis = T.coddis AND H.verdis = T.verdis AND H.codtur = T.codtur
            -- INNER JOIN ATIVIDADEDOCENTE A ON A.coddis = T.coddis AND A.verdis = T.verdis AND A.codtur = T.codtur
            WHERE T.codtur LIKE :semestre
                AND H.codcur = CONVERT(INT, :codcur)
                AND H.codhab = CONVERT(INT, :codhab)
                AND T.coddis IN ('$strCoddis')
                AND T.statur != 'D' -- exclui as não ativas
                AND T.statur is not null -- exclui sem status
        ";
        $param['codcur'] = $codcur;
        $param['codhab'] = $codhab;
        $param['semestre'] = $semestre . '%';
        $res = DB::fetchAll($query, $param);

        // removendo coddis que já foram selecionados
        $listaCoddis = array_diff($listaCoddis, array_column($res, 'coddis'));
        $strCoddis = implode("','", $listaCoddis);

        // lista baseada em listaCoddis menos as que já forma selecionadas de habilturma
        $query = "SELECT T.*, D.*
                , (nummtr + nummtrturcpl + nummtropt + nummtrecr+ nummtroptlre) as nummtrtot
                , (numvagtur + numvagturcpl + numvagopt + numvagecr+ numvagoptlre) as numvagtot
            FROM TURMAGR T
            INNER JOIN DISCIPLINAGR D ON D.coddis = T.coddis AND D.verdis = T.verdis
            WHERE T.codtur LIKE :semestre
                AND T.coddis IN ('$strCoddis')
                AND T.statur != 'D'
        ";
        $res2 = DB::fetchAll($query, ['semestre' => $semestre . '%']);

        return array_merge($res, $res2);
    }

    /**
     * Lista os ministrantes de uma turma
     *
     * $turma são os dados de TURMAGR, especificamente coddis, verdis e codtur
     *
     * @param Array $turma
     * @return Array
     * @author Masaki K Neto, em 28/3/3023
     */
    public static function listarMinistrante($turma)
    {
        $params['coddis'] = $turma['coddis'];
        $params['verdis'] = $turma['verdis'];
        $params['codtur'] = $turma['codtur'];

        $query = "SELECT P.nompesttd nompes, M.codpes, M.stamis
            FROM MINISTRANTE M
            INNER JOIN PESSOA P ON P.codpes = M.codpes
            WHERE coddis = :coddis
                AND verdis = CONVERT(INT, :verdis)
                AND codtur = :codtur";
        $res = DB::fetchAll($query, $params);
        return empty($res) ? $res : array_unique($res, SORT_REGULAR);
    }

    /**
     * Lista as turmas que o professor ministrou aulas nos semestres informados
     *
     * Não está pegando as horas efetivas pois precisa juntar com os feriados do semestre
     *
     * @param Int $codpes
     * @param Array $semestres Array no formato [20221, 20222, 20231, etc]
     * @return Array
     * @author Masakik, em 13/6/2023
     */
    public static function listarTurmasPorCodpes($codpes, $semestres)
    {
        $filterSemestres = ' (';
        foreach ($semestres as $semestre) {
            $filterSemestres .= " T.codtur LIKE '$semestre%' OR";
        }
        $filterSemestres = substr($filterSemestres, 0, -3) . ')';

        $commonSelect = "
            M.stamis --semanal/quinzenal
            , M.diasmnocp -- dia da semana
            , CONVERT(VARCHAR, T.dtainitur, 103) as dtainitur -- inicio e
            , CONVERT(VARCHAR, T.dtafimtur, 103) as dtafimtur -- fim das aulas
            , D.nomdis -- nome da disciplina
            , (T.numvagtur + T.numvagturcpl + T.numvagopt + T.numvagecr + T.numvagoptlre) as numvagtot -- total de vagas
            , (T.nummtr + T.nummtrturcpl + T.nummtropt + T.nummtrecr + T.nummtroptlre) as nummtrtot -- total de matriculados
            , T.codtur, T.coddis, T.cgahorteo, T.cgahorpra -- dados da turma
            , PH.horsai, PH.horent
            , CONVERT(varchar(5), DATEADD(minute, DATEDIFF(minute, PH.horent, PH.horsai), 0), 114) AS horas
            , DATEDIFF(week, M.dtainiaul, M.dtafimaul) semanas --nro de semanas
        ";

        $commonJoin = "
            INNER JOIN DISCIPLINAGR D ON D.coddis = T.coddis AND D.verdis = T.verdis
            INNER JOIN MINISTRANTE M ON T.coddis = M.coddis AND T.verdis = M.verdis AND T.codtur = M.codtur
            INNER JOIN PERIODOHORARIO PH ON M.codperhor = PH.codperhor
        ";

        $commonWhere = "
            (T.nummtr + T.nummtrturcpl + T.nummtropt + T.nummtrecr + T.nummtroptlre) != 0 -- exclui turmas sem matriculados
            AND $filterSemestres
        ";

        $query = "SELECT
                    $commonSelect
                    , 'Ministrante' nomatv
                FROM TURMAGR T
                $commonJoin
                WHERE
                    $commonWhere
                    AND M.codpes = convert(int, :codpes)
                    AND NOT EXISTS ( -- sem atividade docente
                        SELECT 1
                        FROM ATIVIDADEDOCENTE AD
                        WHERE T.coddis = AD.coddis AND T.verdis = AD.verdis AND T.codtur = AD.codtur
                    )
            UNION ( --aqui pega as atividades de docentes senior
                SELECT
                    $commonSelect
                    , AD.nomatv
                FROM TURMAGR T
                $commonJoin
                INNER JOIN ATIVIDADEDOCENTE AD -- pega atividade docente que foi excluido antes
                    ON T.coddis = AD.coddis AND T.verdis = AD.verdis AND T.codtur = AD.codtur
                WHERE
                    $commonWhere
                    AND AD.codpes = convert(int, :codpes)
            ) UNION ( -- atividades docente de estagio e tcc
                SELECT
                    'N' stamis -- N =semanal
                    , '-' diasmnocp -- dia da semana
                    , CONVERT(VARCHAR, T.dtainitur, 103) as dtainitur -- inicio e
                    , CONVERT(VARCHAR, T.dtafimtur, 103) as dtafimtur -- fim das aulas
                    , D.nomdis -- nome da disciplina
                    , (T.numvagtur + T.numvagturcpl + T.numvagopt + T.numvagecr + T.numvagoptlre) as numvagtot -- total de vagas
                    , (T.nummtr + T.nummtrturcpl + T.nummtropt + T.nummtrecr + T.nummtroptlre) as nummtrtot -- total de matriculados
                    , T.codtur, T.coddis
                    , AD.cgahoratv cgahorteo -- colocando a carga horaria da atividade na teorica
                    , T.cgahorpra -- aqui deve ser zerado sempre, mas nao conferi
                    , '-' horsai, '-' horent, '-' horas, '-' semanas
                    , AD.nomatv
                FROM TURMAGR T
                INNER JOIN DISCIPLINAGR D ON D.coddis = T.coddis AND D.verdis = T.verdis
                INNER JOIN ATIVIDADEDOCENTE AD -- pega atividade docente que foi excluido antes
                    ON T.coddis = AD.coddis AND T.verdis = AD.verdis AND T.codtur = AD.codtur
                WHERE
                    $commonWhere
                    AND AD.codpes = convert(int, :codpes)
                    AND NOT EXISTS ( -- remove os já selecionados dos docentes senior
                        SELECT 1
                            FROM TURMAGR T
                            $commonJoin
                        INNER JOIN ATIVIDADEDOCENTE AD -- pega atividade docente que foi excluido antes
                            ON T.coddis = AD.coddis AND T.verdis = AD.verdis AND T.codtur = AD.codtur
                        WHERE
                            $commonWhere
                            AND AD.codpes = convert(int, :codpes)
                    )
        ) ORDER BY T.codtur, T.coddis
        ";

        // dd($query);
        $res = DB::fetchAll($query, ['codpes' => $codpes]);
        return $res;

        $X = "
        -- funcionou no dbeaver para contar feriados no periodo. Mas nao funcionou na query
        -- precisa continuar investigando
        select a.feriados
        --datename(dw,dtacld) as semana,
        --format(dtacld, 'ddd') data,
        --cast('2022-03-14' as date) teste,
        FROM (
        SELECT count(dtacld) feriados
        from dbo.CALENDLOCALIDADEUSP
        WHERE codloc=4080
        and dtacld > cast('2022-03-14' as date) and dtacld < cast('2022-07-23' as date)
        and  format(dtacld, 'ddd','pt-br') = 'qui'
        ) a
        ";
    }

    /**
     * Lista os professores que constam na tabela ATIVIDADEDOCENTE de uma turma
     *
     * $turma são os dados de TURMAGR, especificamente coddis, verdis e codtur
     *
     * @param Array $turma
     * @return Array
     * @author Masaki K Neto, em 28/3/3023
     */
    public static function listarAtivDidaticas($turma)
    {
        $params['coddis'] = $turma['coddis'];
        $params['verdis'] = $turma['verdis'];
        $params['codtur'] = $turma['codtur'];

        $query = "SELECT P.nompesttd nompes, A.codpes, A.nomatv
            FROM ATIVIDADEDOCENTE A
            INNER JOIN PESSOA P ON P.codpes = A.codpes
            WHERE coddis = :coddis
                AND verdis = CONVERT(INT, :verdis)
                AND codtur = :codtur
        ";

        $res = DB::fetchAll($query, $params);
        return empty($res) ? $res : array_unique($res, SORT_REGULAR);
    }

    /**
     * Lista as turmas e os respectivos ministrantes
     */
    public static function listarTurmasMinistrantes(int $codcur, int $codhab, $semestre)
    {
        $turmas = Graduacao::listarTurmas($codcur, $codhab, $semestre);
        foreach ($turmas as &$turma) {
            $turma['ministrantes'] = Graduacao::listarMinistrante($turma);
            $turma['ativDidaticas'] = Graduacao::listarAtivDidaticas($turma);
        }
        return $turmas;
    }

    /**
     * Retorna lista de alunos que ingressaram em determinado ano
     *
     * Se passado codcur, retorna somente do curso
     *
     * @param Int $anoIngresso
     * @param Int $codcur (opcional)
     * @return Array
     * @author Masakik, em 11/10/2023
     */
    public static function listarAlunosIngressantesPorAnoIngresso(int $anoIngresso, int $codcur = null)
    {
        $query = "SELECT p.codpes, p.tipencpgm, YEAR(p.dtaini) ano
        FROM PROGRAMAGR AS p
        JOIN HABILPROGGR AS h ON (p.codpes = h.codpes AND p.codpgm = h.codpgm)
        JOIN CURSOGR AS c ON (h.codcur = c.codcur)
        JOIN HABILITACAOGR as a ON (h.codhab = a.codhab AND c.codcur = a.codcur)
        JOIN PESSOA ps ON (p.codpes = ps.codpes)
        WHERE
            c.codclg IN (__codundclgs__)
            AND YEAR(p.dtaing) = CONVERT(INT, :anoIngresso) -- ingresso no ano
            __filtroCurso__
            ORDER BY p.dtaini, p.tipencpgm
        ";
        $params['anoIngresso'] = $anoIngresso;

        if ($codcur) {
            $query = str_replace('__filtroCurso__', 'AND c.codcur = CONVERT(INT, :codcur)', $query);
            $params['codcur'] = $codcur;
        } else {
            $query = str_replace('__filtroCurso__', '', $query);
        }
        return DB::fetchAll($query, $params);
    }

    /**
     * Método para obter informações de uma disciplina de graduação
     *
     * Pode retornar informações de disciplina de qualquer unidade USP
     * Se informado verdis = 'max', retornará a maior versão, mesmo que não esteja vigente
     * Se não informado verdis, retornará a versão vigente
     *
     * Inclui também 'maxverdis' que corresponde ao maior verdis da disciplina.
     *   - Usado para paginar as versões em datatagrad.
     *
     * @param String $coddis
     * @param Int|Null $verdis
     * @return Array|Null
     * @author Masaki K Neto em 25/3/2024
     */
    public static function obterDisciplina($coddis, $verdis = null)
    {
        $query = "SELECT verdis, dtaatvdis, dtadtvdis
            FROM DISCIPLINAGR
            WHERE coddis = :coddis
            ORDER BY verdis DESC
        ";
        $dis = DB::fetchAll($query, ['coddis' => $coddis]);
        if (!$dis) {
            return false;
        }
        $maxverdis = $dis[0]['verdis'];

        if ($verdis == 'max') {
            $verdis = $maxverdis;
        }
        if ($verdis == null) {
            foreach ($dis as $d) {
                if ($d['dtaatvdis'] && date_create($d['dtaatvdis']) < date_create() && (!$d['dtadtvdis'] || date_create($d['dtadtvdis']) > date_create())) {
                    // versão vigente da disciplina
                    $verdis = $d['verdis'];
                }
            }
        }

        $query = " SELECT D.*, DA.*, DB.* FROM DISCIPLINAGR D
            INNER JOIN DISCIPGRAVALIACAO DA ON DA.coddis = D.coddis AND (DA.dtafimifmavl IS NULL OR DA.dtafimifmavl >=GETDATE()) -- avaliação
            INNER JOIN DISCIPGRBIBLIOG DB ON DB.coddis = D.coddis AND (DB.dtafimbbg IS NULL OR DB.dtafimbbg >= GETDATE()) -- bibliografia
            WHERE D.coddis = :coddis
            AND D.verdis = CONVERT(INT, :verdis)
        ";
        $params['verdis'] = $verdis;
        $params['coddis'] = $coddis;

        $ret = DB::fetch($query, $params);
        if ($ret) {
            $ret['maxverdis'] = $maxverdis;
            $ret['sitdistxt'] = self::$sitdis[$ret['sitdis']] ?? '';
        }

        return $ret;
    }

    /**
     * Retorna os responsáveis de uma disciplina
     *
     * @param String $coddis
     * @return Array
     */
    public static function listarResponsaveisDisciplina($coddis): array
    {
        $query = "SELECT DR.*, P.nompesttd FROM DISCIPGRRESP DR
            INNER JOIN PESSOA P ON P.codpes = DR.codpes
            WHERE coddis = :coddis
            AND dtafimrsp is NULL
        ";

        $params['coddis'] = $coddis;

        return DB::fetchAll($query, $params);
    }

    /**
     * Lista os cursos/habilitações que uma disciplina está presente
     *
     * Para separar as habilitações de sua unidade das outras, pode-se filtrar com
     * stripos(config('replicado.codundclgs'), $curso['codclg']) !== false
     *
     * @param String $coddis
     * @return Array
     */
    public static function listarCursosDisciplina($coddis): array
    {
        $query = "SELECT F.sglfusclgund, F.codfusclgund,
                CG.nomcur, CG.codclg,
                H.nomhab,  G.*, C.* FROM GRADECURRICULAR  G
            INNER JOIN CURRICULOGR C ON G.codcrl = C.codcrl
            --   AND C.dtainicrl < GETDATE() -- exclui futuros
              AND (C.dtafimcrl > GETDATE() OR C.dtafimcrl IS NULL) -- pega os curriculos ativos
            INNER JOIN CURSOGR CG ON CG.codcur = C.codcur --AND CG.codclg IN (__codundclgs__)
            INNER JOIN HABILITACAOGR H ON H.codcur = C.codcur AND H.codhab = C.codhab -- dados da habilitação
            INNER JOIN FUSAOCOLEGIADOUNIDADE F ON F.codoriclgund = CG.codclg -- pega dados de o colegiado de cursos interunidades
              AND F.codfusclgund < 3000 -- somente graduação (grad +2000, pos +3000, etc)
            WHERE G.coddis = :coddis
            ORDER BY CG.codclg, CG.nomcur -- ordena por colegiado e depois por curso
        ";

        $ret = DB::fetchAll($query, ['coddis' => $coddis]);

        foreach ($ret as &$r) {
            // adicionando texto correspondente às siglas de alguns campos, conforme definição nesta classe
            $r['cicdisgdecrltxt'] = self::$cicdisgdecrl[$r['cicdisgdecrl']] ?? '';
            $r['tipobgtxt'] = self::$tipobg[$r['tipobg']] ?? '';
        }

        return $ret;
    }

    /**
     * Retorna lista de disciplinas de responsabilidade de $codpes
     *
     * @param Integer $codpes
     * @return Array
     */
    public static function listarDisciplinasPorResponsavel($codpes): array
    {
        $query = 'SELECT DR.codpes, DR.dtainirsp, D.*
            FROM DISCIPGRRESP DR
            INNER JOIN DISCIPLINAGR D ON D.coddis = DR.coddis
                AND D.verdis = (SELECT max(verdis) FROM DISCIPLINAGR where coddis = DR.coddis)
            WHERE DR.dtafimrsp is NULL
                AND DR.codpes = CONVERT(INT,:codpes)
            ORDER BY D.coddis';

        $params['codpes'] = $codpes;

        return DB::fetchAll($query, $params);
    }

    /**
     * Lista disciplinas por prefixo.
     *
     * O prefixo define o departamento de origem da disciplina.
     * Deve estar em miúscula ou da forma cadastrada no replicado.
     * As disciplinas retornadas estão ativas mas podem não estar vigentes ainda,
     *   ou seja, a entrar em vigência no futuro.
     * Ex.: SET0199, o prefixo é SET
     *
     * @param String $prefixo
     * @return Array
     * @author Masakik, em 27/5/2024
     */
    public static function listarDisciplinasPorPrefixo($prefixo)
    {
        $query = "SELECT D1.*
        FROM DISCIPLINAGR AS D1
        WHERE (D1.verdis = (SELECT MAX(D2.verdis) FROM DISCIPLINAGR AS D2 WHERE (D2.coddis = D1.coddis)))
            AND D1.dtadtvdis IS NULL -- nao foi desativado
            AND D1.dtaatvdis IS NOT NULL -- foi ativado
            AND D1.coddis LIKE :prefixo
        ORDER BY D1.nomdis ASC";

        $params['prefixo'] = $prefixo . '%';

        return DB::fetchAll($query, $params);
    }

    /**
     * Ajustado do replicado para incluir o nome do minsitrante da disciplina
     * @author Masakik, em 21/5/2024
     */
    public static function obterGradeHoraria($codpes)
    {
        $current = date("Y") . (date("m") > 6 ? 2 : 1);

        $query = "SELECT h.coddis, h.codtur, o.diasmnocp, ph.horent, ph.horsai,
                M.codpes codpesmin, P.nompesttd nompesmin
            FROM HISTESCOLARGR h
            INNER JOIN OCUPTURMA o ON (h.coddis = o.coddis AND h.codtur = o.codtur)
            INNER JOIN PERIODOHORARIO ph ON (o.codperhor = ph.codperhor)
            INNER JOIN MINISTRANTE M ON (h.coddis = M.coddis AND h.codtur = M.codtur)
            INNER JOIN PESSOA P ON (P.codpes = M.codpes)
            WHERE h.codpes = convert(int,:codpes) AND h.codtur LIKE '%{$current}%'";
        $param = [
            'codpes' => $codpes,
        ];
        return DB::fetchAll($query, $param);
    }
}
