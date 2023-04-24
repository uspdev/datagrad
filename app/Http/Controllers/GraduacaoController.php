<?php

namespace App\Http\Controllers;

use App\Replicado\Graduacao;
use App\Replicado\Lattes;
use App\Replicado\Pessoa;
use Illuminate\Http\Request;
use Uspdev\Replicado\Uteis;

class GraduacaoController extends Controller
{
    public function relatorioSintese(Request $request)
    {
        $this->authorize('datagrad');
        \UspTheme::activeUrl('graduacao/relatorio/sintese');

        $pessoas = [];
        $naoEncontrados = [];

        if ($request->nomes) {
            $nomes = $request->nomes;
            $nomes = str_replace("\r", '', $nomes); // remove carriage return, mantém new line
            $nomes = explode(PHP_EOL, $nomes);
            $nomes = array_filter($nomes); // remove elementos empty()
            $nomes = array_map('trim', $nomes);
            $nomes = array_unique($nomes); // sem repetidos

            foreach ($nomes as $nome) {
                // vamos procurar 1o por nome exato e depois por fonetico
                $pessoaReplicado = Pessoa::procurarServidorPorNome($nome, $fonetico = false) ?? Pessoa::procurarServidorPorNome($nome, $fonetico = true);
                if ($pessoaReplicado) {
                    $pessoa = [];
                    $pessoa['unidade'] = $pessoaReplicado['sglclgund'];
                    $pessoa['nome'] = $pessoaReplicado['nompesttd'];
                    $pessoa['codpes'] = $pessoaReplicado['codpes'];
                    $pessoa['lattes'] = Lattes::id($pessoa['codpes']);
                    $pessoa['nomeFuncao'] = $pessoaReplicado['nomfnc'];
                    $pessoa['dtaultalt'] = Lattes::retornarDataUltimaAtualizacao($pessoa['codpes']);
                    $pessoa['orcid_id'] = Lattes::retornarOrcidId($pessoa['codpes']);
                    $pessoa['tipoJornada'] = Pessoa::retornarTipoJornada($pessoa['codpes']);
                    $pessoa['departamento'] = Pessoa::retornarSetor($pessoa['codpes']);
                    $pessoa = array_merge($pessoa, Lattes::retornarFormacaoAcademicaFormatado($pessoa['codpes']));

                    $pessoas[] = $pessoa;
                } else {
                    $naoEncontrados[] = $nome;
                }
            }
        }

        return view('grad.relatorio-sintese', [
            'nomes' => $request->nomes,
            'pessoas' => $pessoas,
            'naoEncontrados' => $naoEncontrados,
        ]);
    }

    public function relatorioComplementar(Request $request)
    {
        $this->authorize('datagrad');
        \UspTheme::activeUrl('graduacao/relatorio/complementar');

        // $request->validate(

        // );

        $anoIni = $request->anoIni;
        $anoFim = $request->anoFim;

        $pessoas = [];
        $naoEncontrados = [];

        if ($request->nomes) {
            $nomes = $request->nomes;
            $nomes = str_replace("\r", '', $nomes); // remove carriage return, mantém new line
            $nomes = explode(PHP_EOL, $nomes);
            $nomes = array_filter($nomes); // remove elementos empty()
            $nomes = array_map('trim', $nomes);
            $nomes = array_unique($nomes); // sem repetidos

            foreach ($nomes as $nome) {
                // vamos procurar 1o por nome exato e depois por fonetico
                $pessoaReplicado = Pessoa::procurarServidorPorNome($nome, $fonetico = false) ?? Pessoa::procurarServidorPorNome($nome, $fonetico = true);
                if ($pessoaReplicado) {
                    $pessoa = [];
                    $pessoa['codpes'] = $pessoaReplicado['codpes'];
                    $pessoa['unidade'] = $pessoaReplicado['sglclgund'];
                    $pessoa['departamento'] = Pessoa::retornarSetor($pessoa['codpes']);
                    $pessoa['nome'] = $pessoaReplicado['nompesttd'];
                    $pessoa['lattes'] = Lattes::id($pessoa['codpes']);

                    $lattesArray = Lattes::obterArray($pessoa['codpes']);
                    $pessoa['resumoCV'] = html_entity_decode(Lattes::retornarResumoCV($pessoa['codpes'], 'pt', $lattesArray)) ?: '';

                    $params = [$pessoa['codpes'], $lattesArray, 'periodo', $anoIni, $anoFim];

                    $pessoa['artigos'] = Lattes::listarArtigos(...$params) ?: [];
                    $pessoa['livrosPublicados'] = Lattes::listarLivrosPublicados(...$params) ?: [];
                    $pessoa['capitulosLivros'] = Lattes::listarCapitulosLivros(...$params) ?: [];
                    $pessoa['textosJornaisRevistas'] = Lattes::listarTextosJornaisRevistas(...$params) ?: [];
                    $pessoa['trabalhosAnais'] = Lattes::listarTrabalhosAnais(...$params) ?: [];
                    $pessoa['apresentacaoTrabalho'] = Lattes::listarApresentacaoTrabalho(...$params) ?: [];
                    $pessoa['outrasProducoesBibliograficas'] = Lattes::listarOutrasProducoesBibliograficas(...$params) ?: [];
                    $pessoa['premios'] = Lattes::listarPremios(...$params) ?: [];
                    $pessoa['trabalhosTecnicos'] = Lattes::listarTrabalhosTecnicos(...$params) ?: [];
                    $pessoa['organizacaoEventos'] = Lattes::listarOrganizacaoEvento(...$params) ?: [];
                    $pessoa['outrasProducoesTecnicas'] = Lattes::listarOutrasProducoesTecnicas(...$params) ?: [];
                    $pessoa['cursosCurtaDuracao'] = Lattes::listarCursosCurtaDuracao(...$params) ?: [];
                    $pessoa['materialDidaticoInstrucional'] = Lattes::listarMaterialDidaticoInstrucional(...$params) ?: [];
                    $pessoa['orientacoesConcluidasIC'] = Lattes::listarOrientacoesConcluidasIC(...$params) ?: [];
                    $pessoa['orientacoesEmAndamentoIC'] = Lattes::listarOrientacoesEmAndamentoIC(...$params) ?: [];
                    $pessoa['orientacoesConcluidasTccGraduacao'] = Lattes::listarOrientacoesConcluidasTccGraduacao(...$params) ?: [];
                    $pessoa['orientacoesEmAndamentoIC'] = Lattes::listarOrientacoesEmAndamentoIC(...$params) ?: [];
                    $pessoa['orientacoesConcluidasMestrado'] = Lattes::listarOrientacoesConcluidasMestrado(...$params) ?: [];
                    $pessoa['orientacoesEmAndamentoMestrado'] = Lattes::listarOrientacoesEmAndamentoMestrado(...$params) ?: [];
                    $pessoa['orientacoesConcluidasDoutorado'] = Lattes::listarOrientacoesConcluidasDoutorado(...$params) ?: [];
                    $pessoa['orientacoesEmAndamentoDoutorado'] = Lattes::listarOrientacoesEmAndamentoDoutorado(...$params) ?: [];
                    $pessoa['orientacoesConcluidasPosDoutorado'] = Lattes::listarOrientacoesConcluidasPosDoutorado(...$params) ?: [];
                    $pessoa['orientacoesEmAndamentoPosDoutorado'] = Lattes::listarOrientacoesEmAndamentoPosDoutorado(...$params) ?: [];
                    $pessoa['monografiasConcluidasAperfeicoamentoEspecializacao'] = Lattes::listarMonografiasConcluidasAperfeicoamentoEspecializacao(...$params) ?: [];




                    $pessoas[] = $pessoa;
                } else {
                    $naoEncontrados[] = $nome;
                }
            }
        }

        return view('grad.relatorio-complementar', [
            'nomes' => $request->nomes,
            'pessoas' => $pessoas,
            'naoEncontrados' => $naoEncontrados,
            'anoIni' => $anoIni,
            'anoFim' => $anoFim,
        ]);
    }

    public function cursos()
    {
        $this->authorize('datagrad');
        \UspTheme::activeUrl('graduacao/cursos');

        $cursos = Graduacao::listarCursosHabilitacoes();
        $u = new Uteis;

        return view('grad.cursos', compact('cursos', 'u'));
    }

    public function gradeCurricular(Request $request, int $codcur, int $codhab)
    {
        $this->authorize('datagrad');
        \UspTheme::activeUrl('graduacao/cursos');

        foreach (Graduacao::listarCursosHabilitacoes() as $curso) {
            if ($curso['codcur'] == $codcur && $curso['codhab'] == $codhab) {
                break;
            }
        }

        $disciplinas = Graduacao::listarGradeCurricular($codcur, $codhab);
        $disciplinas = collect($disciplinas)->sortBy(['numsemidl', ['tipobg', 'desc']]);

        return view('grad.grade-curricular', compact('disciplinas', 'curso'));
    }

    public function turmas(Request $request, int $codcur, int $codhab)
    {
        $this->authorize('datagrad');
        \UspTheme::activeUrl('graduacao/cursos');

        $semestres = ['20232', '20231', '20222', '20221', '20212', '20211'];
        $semestreFim = date('Y') . (date('m') < 7 ? 1 : 2);
        $semestreFim = $request->semestreFim ? $request->semestreFim : $semestreFim;

        $semestreIni = $semestres[array_search($semestreFim, $semestres) + 1];
        $semestreIni = $request->semestreIni ? $request->semestreIni : $semestreIni;

        foreach (Graduacao::listarCursosHabilitacoes() as $curso) {
            if ($curso['codcur'] == $codcur && $curso['codhab'] == $codhab) {
                break;
            }
        }

        $keyIni = array_search($semestreIni, $semestres);
        $keyFim = array_search($semestreFim, $semestres);
        if ($keyFim > $keyIni) {
            $tmp = $keyIni;
            $keyIni = $keyFim;
            $keyFim = $tmp;
        }
        $turmas = [];
        for ($i = $keyFim; $i <= $keyIni; $i++) {
            $turmas = array_merge($turmas, Graduacao::listarTurmas($codcur, $codhab, $semestres[$i]));
        }
        $nomes = [];
        $AtivDidaticas = [];
        foreach ($turmas as &$turma) {
            $turma['ministrantes'] = Graduacao::listarMinistrante($turma);
            $turma['ativDidaticas'] = Graduacao::listarAtivDidaticas($turma);
            if ($turma['ativDidaticas']) {
                $nomes = array_merge($nomes, $turma['ativDidaticas']);
            } else {
                $nomes = array_merge($nomes, $turma['ministrantes']);
            }
        }
        $nomes = array_column($nomes, 'nompes');
        $nomes = array_unique($nomes); // sem repetidos
        $nomesCount = count($nomes);
        $nomes = implode(PHP_EOL, $nomes);

        return view('grad.turmas', [
            // 'codtur' => $codtur,
            'semestreFim' => $semestreFim,
            'semestreIni' => $semestreIni,
            'curso' => $curso,
            'turmas' => $turmas,
            'graduacao' => Graduacao::class,
            'turmaSelect' => $semestres,
            'nomes' => $nomes,
            'nomesCount' => $nomesCount,
        ]);
    }

    public function pessoa($codpes)
    {
        $this->authorize('datagrad');

        $pessoa = Pessoa::dump($codpes);
        $pessoaReplicado = Pessoa::procurarServidorPorNome($pessoa['nompes'], $fonetico = false);
        if ($pessoaReplicado) {

            $pessoa = [];
            $pessoa['unidade'] = $pessoaReplicado['sglclgund'];
            $pessoa['nome'] = $pessoaReplicado['nompesttd'];
            $pessoa['codpes'] = $pessoaReplicado['codpes'];
            $pessoa['lattes'] = Lattes::id($pessoa['codpes']);
            $pessoa['linkLattes'] = Lattes::retornarLinkCurriculo($pessoa['codpes']);
            $pessoa['nomeFuncao'] = $pessoaReplicado['nomfnc'];
            $pessoa['dtaultalt'] = Lattes::retornarDataUltimaAtualizacao($pessoa['codpes']);
            $pessoa['linkOrcid'] = Lattes::retornarLinkOrcid($pessoa['codpes']);
            $pessoa['tipoJornada'] = Pessoa::retornarTipoJornada($pessoa['codpes']);
            $pessoa['departamento'] = Pessoa::retornarSetor($pessoa['codpes']);
            $pessoa = array_merge($pessoa, Lattes::retornarFormacaoAcademicaFormatado($pessoa['codpes']));
            $pessoa['fotoLattes'] = base64_encode(Lattes::obterFoto($pessoa['lattes'], storage_path('app/fotos-lattes')));

            return view('blocos.partials.modal-pessoa-body', [
                'codpes' => $codpes,
                'lattes' => \App\Replicado\Lattes::class,
                'pessoa' => $pessoa,

            ]);
        }
    }
}
