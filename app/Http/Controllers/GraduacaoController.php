<?php

namespace App\Http\Controllers;

use App\Replicado\Graduacao;
use App\Replicado\Lattes;
use App\Replicado\Pessoa;
use App\Services\Evasao;
use App\Services\Grafico;
use App\Services\Tools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Uspdev\Replicado\Uteis;
use Uspdev\UspTheme\Facades\UspTheme;

class GraduacaoController extends Controller
{
    public function relatorioSintese(Request $request)
    {
        if ($request->method() == 'POST') {
            $request->validate([
                'nomes' => 'required',
            ]);
        }
        if (!$request->old()) { //repopulando old() para mostrar no form, mesmo no caso de sucesso
            session()->flashInput($request->input());
        }

        $this->authorize('datagrad');
        UspTheme::activeUrl('graduacao/relatorio/sintese');

        $nomes = Tools::limparNomes($request->nomes);
        $pessoas = [];
        $naoEncontrados = [];
        foreach ($nomes as $nome) {
            // vamos procurar 1o por nome exato e depois por fonetico
            $pessoaReplicado = Pessoa::procurarServidorPorNome($nome, $fonetico = false) ?? Pessoa::procurarServidorPorNome($nome, $fonetico = true);
            if (!$pessoaReplicado) {
                $naoEncontrados[] = $nome;
                continue;
            }
            $pessoa = [];
            $pessoa['unidade'] = $pessoaReplicado['sglclgund'];
            $pessoa['departamento'] = Pessoa::retornarSetor($pessoaReplicado['codpes']);
            $pessoa['codpes'] = $pessoaReplicado['codpes'];
            $pessoa['nome'] = $pessoaReplicado['nompesttd'];
            $pessoa['nomeFuncao'] = $pessoaReplicado['nomfnc'];
            $pessoa['tipoJornada'] = Pessoa::retornarTipoJornada($pessoa['codpes']);
            $pessoa['lattes'] = Lattes::id($pessoa['codpes']);
            $pessoa['dtaultalt'] = Lattes::retornarDataUltimaAtualizacao($pessoa['codpes']);
            $pessoa['linkOrcid'] = Lattes::retornarLinkOrcid($pessoa['codpes']);
            $pessoa['created_at'] = now();
            $pessoa['idade'] = date('Y') - substr($pessoaReplicado['dtanas'], 0, 4);
            $pessoa = array_merge($pessoa, Lattes::retornarFormacaoAcademicaFormatado($pessoa['codpes']));
            $pessoas[] = $pessoa;
        }

        session()->flashInput($request->input());
        return view('grad.relatorio-sintese', [
            'pessoas' => $pessoas,
            'naoEncontrados' => $naoEncontrados,
        ]);
    }

    public function relatorioComplementar(Request $request)
    {
        if ($request->method() == 'POST') {
            $request->validate([
                'nomes' => 'required',
                'anoIni' => 'nullable|integer|min:1970|max:' . date('Y'),
                'anoFim' => 'nullable|integer|min:1970|max:' . date('Y'),
            ]);
        }
        if (!$request->old()) {
            session()->flashInput($request->input());
        }

        $this->authorize('datagrad');
        UspTheme::activeUrl('graduacao/relatorio/complementar');

        $anoIni = $request->anoIni;
        $anoFim = $request->anoFim;
        $nomes = Tools::limparNomes($request->nomes);
        $pessoas = [];
        $naoEncontrados = [];
        foreach ($nomes as $nome) {
            // vamos procurar 1o por nome exato e depois por fonetico
            $pessoaReplicado = Pessoa::procurarServidorPorNome($nome, $fonetico = false) ?? Pessoa::procurarServidorPorNome($nome, $fonetico = true);
            if (!$pessoaReplicado) {
                $naoEncontrados[] = $nome;
                continue;
            }
            $pessoa = [];
            $pessoa['unidade'] = $pessoaReplicado['sglclgund'];
            $pessoa['departamento'] = Pessoa::retornarSetor($pessoaReplicado['codpes']);
            $pessoa['codpes'] = $pessoaReplicado['codpes'];
            $pessoa['nome'] = $pessoaReplicado['nompesttd'];
            $pessoa['lattes'] = Lattes::id($pessoa['codpes']);
            $pessoa['dtaultalt'] = Lattes::retornarDataUltimaAtualizacao($pessoa['codpes']);

            $lattesArray = Lattes::obterArray($pessoa['codpes']);
            $params = [$pessoa['codpes'], $lattesArray, 'periodo', $anoIni, $anoFim];

            $pessoa['resumoCV'] = html_entity_decode(Lattes::retornarResumoCV($pessoa['codpes'], 'pt', $lattesArray)) ?: '';
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
        }

        return view('grad.relatorio-complementar', [
            'pessoas' => $pessoas,
            'naoEncontrados' => $naoEncontrados,
        ]);
    }

    /**
     * Relatorio de carga didática
     *
     * Em principio desativado daqui pois foi para o proposta-orcamentária
     */
    public function cargaDidatica(Request $request)
    {
        if (!Gate::any(['datagrad', 'disciplina-chefe'])) {
            abort(403);
        }

        UspTheme::activeUrl('graduacao/relatorio/cargadidatica');

        $semestreIni = $request->semestreIni;
        $semestreFim = $request->semestreFim;

        $semestres = Tools::iniFim($request->semestreIni, $request->semestreFim);

        if ($request->codsets && $request->nomes) {
            $request->session()->flash('alert-warning', 'Forneça somente nomes ou somente setores!');
        }

        $ocultarTurmas = ($request->ocultarTurmas == 1) ? true : false;
        $excluirTcc = $request->excluirTcc == 1 ? true : false;

        $codsets = [];
        if ($request->codsets) {
            // se foi passado codsets
            $codsets = $request->codsets;
            $pessoas = Pessoa::listarDocentesSetor($codsets);
            $nomes = collect($pessoas)->pluck('nompes')->toArray();
            $ocultarTurmas = 1;
            $docentesSetor = Pessoa::listarDocentesSetor($codsets, false);
        } else {
            // se foi passado nomes
            $nomes = Tools::limparNomes($request->nomes);
            $docentesSetor = 0;
        }

        // estagio: vamos excluir disciplinas que não contam para carga didática
        $exclusao = ['1800078', '1800090', '1800096', '1800097', '1800122', '1800200', '1807100', 'SAA0170', 'SEL0425', 'SEL0625', 'SEM0398', 'SEP0622', 'SMM0324'];

        // TCC
        $exclusao = array_merge($exclusao, ['1800080', '1800081']);

        // projeto de final de curso
        $exclusao = array_merge($exclusao, ['1800083', '1800093', '1800094', 'SAA0346', 'SEL0442', 'SEL0444', 'SEM0399', 'SEM0404', 'SMM0325']);

        // projeto de formatura
        $exclusao = array_merge($exclusao, ['SEL0624']);

        // tutoria academica
        // $exclusao = array_merge($exclusao, ['1800120', '1800121']);

        $pessoas = [];
        $naoEncontrados = [];
        $semCargaDidatica = [];
        $disciplinasExcluidas = [];
        $totalHorasTeoricas = 0;
        $totalHorasPraticas = 0;
        $totalTurmas = 0;
        foreach ($nomes as $nome) {

            $pessoaReplicado = Pessoa::procurarPorCodpesOuNome($nome, $ativos = true) ?? Pessoa::procurarServidorPorNome($nome, $fonetico = true);

            if (!$pessoaReplicado) {
                $naoEncontrados[] = $nome;
                continue;
            }
            $pessoa = [];
            $pessoa['unidade'] = $pessoaReplicado['sglclgund'];
            $pessoa['departamento'] = Pessoa::retornarSetor($pessoaReplicado['codpes']);
            $pessoa['codpes'] = $pessoaReplicado['codpes'];
            $pessoa['nome'] = $pessoaReplicado['nompesttd'];

            // ordenando por collect pois o retorno do DB não vem ordenado pois junta varias queries
            $turmas = collect(Graduacao::listarTurmasPorCodpes($pessoa['codpes'], $semestres))->sortBy(['coddis', 'codtur'])->toArray();

            if (empty($turmas)) {
                $semCargaDidatica[] = $nome;
                continue;
            }

            $count = 0; // contagem do nro de turmas/disciplinas consideradas por pessoa
            $prev = null;
            $somaHorasTeorica = 0;
            $somaHorasPratica = 0;
            for ($k = 0; $k < count($turmas); $k++) {
                $adicionar = true; // permite ignorar adicao na lista pois é repetido dos dias da asemana

                $turma = $turmas[$k];
                $turma['ministrantes'] = collect(Graduacao::listarMinistrantes($turma))->pluck('nompes');
                $turma['horafmt'] = '(' . $turma['diasmnocp'] . ') ' . $turma['horent'] . ' - ' . $turma['horsai'];

                if (!$excluirTcc || !in_array($turma['coddis'], $exclusao)) {
                    $divisorQuinzenal = ($turma['stamis'] == 'N') ? 1 : 2; // 'N' -> semanal
                    $divisorMinistrantes = count($turma['ministrantes']);

                    if ($divisorMinistrantes == 0) {
                        $divisorMinistrantes = 1;
                    }

                    if (is_null($prev)) {
                        // primeira turma da lista
                        $somaHorasTeorica += (int) $turma['cgahorteo'] / $divisorQuinzenal / $divisorMinistrantes;
                        $somaHorasPratica += (int) $turma['cgahorpra'] / $divisorQuinzenal / $divisorMinistrantes;
                        $turma['frateo'] = round((int) $turma['cgahorteo'] / $divisorQuinzenal / $divisorMinistrantes, 3);
                        $turma['frapra'] = round((int) $turma['cgahorpra'] / $divisorQuinzenal / $divisorMinistrantes, 3);
                        $count++;
                    } else {
                        // demais turmas
                        // aqui exclui quando a disciplina/turma está repetido, ou seja tem mais de um horário ministrado
                        if (!($prev['codtur'] == $turma['codtur'] && $prev['coddis'] == $turma['coddis'])) {
                            $somaHorasTeorica += (int) $turma['cgahorteo'] / $divisorQuinzenal / $divisorMinistrantes;
                            $somaHorasPratica += (int) $turma['cgahorpra'] / $divisorQuinzenal / $divisorMinistrantes;
                            $turma['frateo'] = round((int) $turma['cgahorteo'] / $divisorQuinzenal / $divisorMinistrantes, 3);
                            $turma['frapra'] = round((int) $turma['cgahorpra'] / $divisorQuinzenal / $divisorMinistrantes, 3);
                            $count++;
                        } else {
                            $pessoa['turmas'][$k - 1]['horafmt'] = $turma['horafmt'] . '; ' . $pessoa['turmas'][$k - 1]['horafmt'];
                            $adicionar = false;
                        }
                    }
                } else {
                    $disciplinasExcluidas[] = $turma;
                }

                $turma['ministrantes'] = $turma['ministrantes']->implode(', ');
                $prev = $turma;

                if ($adicionar == true) {
                    $pessoa['turmas'][$k] = $turma;
                }
            }

            $pessoa['mediaHorasTeorica'] = $somaHorasTeorica / count($semestres);
            $pessoa['mediaHorasPratica'] = $somaHorasPratica / count($semestres);
            $pessoa['mediaTurmas'] = $count / count($semestres);

            $totalHorasTeoricas += $somaHorasTeorica;
            $totalHorasPraticas += $somaHorasPratica;
            $totalTurmas += $count;

            // só vamos incluir se tiver ministrado alguma coisa
            if ($pessoa['mediaTurmas'] != 0) {
                $pessoas[] = $pessoa;
            } else {
                $semCargaDidatica[] = $nome;
            }
        }

        $disciplinasExcluidas = collect($disciplinasExcluidas)->sort()->unique('coddis');
        $turmaSelect = Tools::semestres();

        session()->flashInput($request->input());

        return view('grad.relatorio-cargadidatica', compact(
            'pessoas',
            'naoEncontrados',
            'semCargaDidatica',
            'totalHorasTeoricas',
            'totalHorasPraticas',
            'totalTurmas',
            'disciplinasExcluidas',
            'semestreIni',
            'semestreFim',
            'turmaSelect',
            'ocultarTurmas',
            'excluirTcc',
            'codsets',
            'docentesSetor'
        ));
    }




    public function cursos()
    {
        $this->authorize('disciplinas');
        \UspTheme::activeUrl('graduacao/cursos');

        $cursos = Graduacao::listarCursosHabilitacoes();
        $u = new Uteis;

        return view('grad.cursos', compact('cursos', 'u'));
    }

    public function gradeCurricular(Request $request, int $codcur, int $codhab)
    {
        $this->authorize('disciplinas');
        \UspTheme::activeUrl('graduacao/cursos');

        $curso = Graduacao::obterCurso($codcur, $codhab);

        $disciplinas = Graduacao::listarGradeCurricular($codcur, $codhab);
        $disciplinas = collect($disciplinas)->sortBy(['numsemidl', ['tipobg', 'desc']]);

        // dd($codcur, $curso);
        return view('grad.grade-curricular', compact('disciplinas', 'curso'));
    }

    public function relatorioGradeHoraria(Request $request)
    {

        // if ($request->method() == 'POST') {
        //     $request->validate([
        //         'nomes' => 'required',
        //     ]);
        // }
        if (!$request->old()) { //repopulando old() para mostrar no form, mesmo no caso de sucesso
            session()->flashInput($request->input());
        }

        $this->authorize('datagrad');
        UspTheme::activeUrl('graduacao/relatorio/gradehoraria');

        $entradas = Tools::limparNomes($request->nusps);  //limpando os números usp na verdade

        $codpesParaProcessar = [];
        $horarios = [];
        $naoEncontrados = [];

        foreach ($entradas as $entrada) {
            if (is_numeric($entrada)) {
                $codpesParaProcessar[] = $entrada;
            } else {
                $pessoas = Pessoa::procurarPorNome($entrada);

                if (!empty($pessoas)) {
                    foreach ($pessoas as $pessoa) {
                        $codpesParaProcessar[] = $pessoa['codpes'];
                    }
                } else {
                    $naoEncontrados[] = $entrada;
                }
            }
        }

        $nomesEncontrados = [];

        if (!empty($codpesParaProcessar)) {
            $nomesEncontrados = Pessoa::obterNome($codpesParaProcessar);
        }

        foreach ($codpesParaProcessar as $codpes) {
            $nome = $nomesEncontrados[$codpes] ?? null;

            if (!$nome) {
                $naoEncontrados[] = $codpes . " (Número USP não encontrado na base)";
                continue;
            }

            $gradeAluno = Graduacao::obterGradeHoraria($codpes);

            if (!$gradeAluno) {
                $naoEncontrados[] = $codpes . " - " . $nome . " (Sem grade horária)";
                continue;
            }

            $gradeAluno = array_map(function ($horario) use ($nome, $codpes) {
                $horario['nome'] = $nome;
                $horario['codpes'] = $codpes;
                return $horario;
            }, $gradeAluno);
            $horarios = array_merge($horarios, $gradeAluno);
        }
        $diaSemana = ['seg' => 1, 'ter' => 2, 'qua' => 3, 'qui' => 4, 'sex' => 5, 'sab' => 6];
        $horarios = collect($horarios)->sortBy([
            ['nome', 'asc'],
            fn($a, $b) => $diaSemana[$a['diasmnocp']] <=> $diaSemana[$b['diasmnocp']],
            ['horent', 'asc'],
        ])->unique()->toArray();

        session()->flashInput($request->input());
        return view('grad.relatorio-gradehoraria', compact('horarios', 'naoEncontrados'));
    }

    /**
     * Lista de turmas de um curso/habilitação
     */
    public function turmas(Request $request, int $codcur, int $codhab)
    {
        $this->authorize('datagrad');
        \UspTheme::activeUrl('graduacao/cursos');

        // dd($request->all());

        $key = sha1('turmas' . $codcur . $codhab . $request->semestreFim . $request->semestreIni);
        if ((isset($request->acao) && $request->acao == 'cache_refresh')) {
            $ret = $this->turmasNoCache($request, $codcur, $codhab);
            Cache::put($key, $ret);
            return back();
        } elseif (Cache::has($key)) {
            $ret = Cache::get($key);
        } else {
            $ret = $this->turmasNoCache($request, $codcur, $codhab);
            Cache::put($key, $ret);
        }

        return view('grad.turmas', [
            // 'codtur' => $codtur,
            'semestreFim' => $ret['semestreFim'],
            'semestreIni' => $ret['semestreIni'],
            'curso' => $ret['curso'],
            'turmas' => $ret['turmas'],
            'graduacao' => Graduacao::class,
            'turmaSelect' => Tools::semestres(),
            'nomes' => $ret['nomes'],
            'nomesCount' => $ret['nomesCount'],
            'timestamp' => $ret['timestamp'],
        ]);
    }

    protected function turmasNoCache(Request $request, int $codcur, int $codhab)
    {
        $this->authorize('datagrad');
        \UspTheme::activeUrl('graduacao/cursos');

        $semestreIni = $request->semestreIni;
        $semestreFim = $request->semestreFim;

        $curso = Graduacao::obterCurso($codcur, $codhab);

        $semestres = Tools::iniFim($request->semestreIni, $request->semestreFim);
        $turmas = [];
        foreach ($semestres as $semestre) {
            $turmas = array_merge($turmas, Graduacao::listarTurmasMinistrantes($codcur, $codhab, $semestre));
        }
        $nomes = [];
        $AtivDidaticas = [];
        foreach ($turmas as &$turma) {
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
        $timestamp = now();

        return compact('semestres', 'semestreIni', 'semestreFim', 'curso', 'turmas', 'nomes', 'nomesCount', 'timestamp');
    }

    /**
     * Retorna dados da pessoa nos relatórios síntese e complementar
     */
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

    public function relatorioEvasao(Request $request)
    {
        $this->authorize('evasao');
        \UspTheme::activeUrl('graduacao/relatorio/evasao');

        $cursoOpcao = Evasao::retornarCodcurNomcur();

        if ($request->isMethod('get')) {
            return view('grad.relatorio-evasao', ['cursoOpcao' => $cursoOpcao]);
        }

        $request->validate([
            'curso' => 'nullable',
            'ano' => 'required|integer|between:2015,' . (date('Y') - 1),
        ]);

        [$taxa, $espacoAmostral] = Evasao::taxaEvasao($request->ano, $request->curso);
        if (empty($taxa)) {
            return redirect()
                ->route('graduacao.relatorio.evasao')
                ->with('alert-warning', 'Não há alunos no intervalo informado.')
                ->withInput();
        }

        $formRequest = ($request->curso !== null ? Evasao::retornarCodcurNomcur((int) $request->curso) : ['codcur' => '18', 'nomcur' => 'Todos os cursos']);
        $formRequest = array_merge($formRequest, ['anoIngresso' => $request->ano]);

        $taxaEvasao['data'] = $taxa;
        $taxaEvasao['title'] = "Fluxo de alunos | {$formRequest['nomcur']} ({$formRequest['codcur']}) | Ingressantes {$formRequest['anoIngresso']} | Total: {$espacoAmostral} alunos";

        $imagemEvasao = Grafico::criarGraficoEvasao($taxaEvasao, $formRequest);

        return view('grad.relatorio-evasao', compact('taxaEvasao', 'formRequest', 'cursoOpcao', 'imagemEvasao'));
    }

    /**
     * Gera relatório de turmas ministradas por disciplina e por semestre
     *
     * Com média de alunos por turma e média de horas teóricas e práticas ministradas por semestre
     */
    public function relatorioTurma(Request $request)
    {
        $this->authorize('datagrad');
        \UspTheme::activeUrl('graduacao/relatorio/turma');

        $disciplinas = Graduacao::listarDisciplinas();
        $resultadosTurmas = Graduacao::listarTurmasResultados($request->disciplina, $request->anoInicio, $request->anoFim);

        if ($request->isMethod('get')) {
            return view('grad.relatorio-turma', ['disciplinas' => $disciplinas]);
        }

        $rules = [
            'disciplina' => 'required',
            'anoInicio'  => 'required|integer',
            'anoFim'     => 'required|integer|lte:' . date('Y'),
        ];

        $messages = [
            'disciplina.required' => 'Informe a disciplina.',
            'anoInicio.required'  => 'Informe o ano de início.',
            'anoFim.required'     => 'Informe o ano final.',
            'anoFim.lte'          => 'O ano final não pode ser depois do ano atual.',
        ];

        $validated = $request->validate($rules, $messages);

        if ($request->filled('anoInicio') && $request->filled('anoFim')) {
            if ($request->anoFim < $request->anoInicio) {
                return back()
                    ->withErrors(['anoFim' => 'O ano final deve ser maior ou igual ao ano de início.'])
                    ->withInput();
            }
        }

        $formRequest = [
            'disciplina' => $request->disciplina,
            'anoInicio'  => $request->anoInicio,
            'anoFim'     => $request->anoFim,
        ];

        return view('grad.relatorio-turma', ['formRequest' => $formRequest, 'disciplinas' => $disciplinas, 'resultadosTurmas' => $resultadosTurmas]);
    }

    public function relatorioCargaHorariaAcumulada(Request $request)
    {
        if (!$request->old()) {
            session()->flashInput($request->input());
        }

        $this->authorize('relatorio-cgahoralu');
        \UspTheme::activeUrl('graduacao/relatorio/carga-acumulada');

        $cursos = Evasao::retornarCodcurNomcur();
        $resultados = [];
        $naoEncontrados = [];

        if ($request->isMethod('get')) {
            return view('grad.relatorio-carga-acumulada', compact('cursos', 'resultados', 'naoEncontrados'));
        }

        $codcur = $request->input('codcur') ? trim($request->input('codcur')) : null;
        $ano_ingresso = $request->input('ano_ingresso') ? trim($request->input('ano_ingresso')) : null;
        
        $limparNusps = $request->nusps ? Tools::limparNomes($request->nusps) : [];
        $entradas = array_filter($limparNusps); 

        // MODO 1: Busca pelo Curso e Ano de Ingresso (Lista de alunos vazia)
        if (empty($entradas)) {
            if (!$codcur || !$ano_ingresso) {
                return redirect()->back()->withErrors(['Erro' => 'Selecione o Curso E o Ano de ingresso ou preencha a lista de alunos.']);
            }

            $vincAlunos = Graduacao::obterCargaHorariaAcumuladaAluno(null, $codcur, $ano_ingresso);
            
            if (empty($vincAlunos)) {
                $naoEncontrados[] = "Nenhum aluno ativo localizado para o Curso: {$codcur} e Ano: {$ano_ingresso}.";
            } else {
                foreach ($vincAlunos as $dadosAluno) {
                    $totalAcumulado = $dadosAluno['carga_obrigatoria'] + 
                                    $dadosAluno['carga_optativa'] + 
                                    $dadosAluno['carga_estagio'] + 
                                    $dadosAluno['carga_complementar'] + 
                                    $dadosAluno['carga_extensionista'];

                    $resultados[] = [
                        'codpes'              => $dadosAluno['codpes'],
                        'nompes'              => $dadosAluno['nompes'],
                        'email'               => $dadosAluno['email'],
                        'codcur'              => $dadosAluno['codcur'],
                        'codhab'              => $dadosAluno['codhab'],
                        'ano_ingresso'        => $dadosAluno['ano_ingresso'],
                        'carga_obrigatoria'   => round($dadosAluno['carga_obrigatoria']),
                        'carga_optativa'      => round($dadosAluno['carga_optativa']),
                        'carga_estagio'       => round($dadosAluno['carga_estagio']),
                        'carga_complementar'  => round($dadosAluno['carga_complementar']),
                        'carga_extensionista' => round($dadosAluno['carga_extensionista']),
                        'total_acumulado'     => round($totalAcumulado)
                    ];
                }
            }
        } 

        // MODO 2: Busca com base na Lista de nomes/números USP fornecida
        else {
            $codpesParaProcessar = [];

            foreach ($entradas as $entrada) {
                if (is_numeric($entrada)) {
                    $codpesParaProcessar[] = $entrada;
                } else {
                    $pessoas = Pessoa::procurarPorNome($entrada);
                    if (!empty($pessoas)) {
                        foreach ($pessoas as $pessoa) {
                            $codpesParaProcessar[] = $pessoa['codpes'];
                        }
                    } else {
                        $naoEncontrados[] = $entrada . " (Nome não localizado)";
                    }
                }
            }

            $codpesParaProcessar = array_unique($codpesParaProcessar);
            $nomesEncontrados = !empty($codpesParaProcessar) ? Pessoa::obterNome($codpesParaProcessar) : [];

            foreach ($codpesParaProcessar as $codpes) {
                $nome = $nomesEncontrados[$codpes] ?? null;

                if (!$nome) {
                    $naoEncontrados[] = $codpes . " (Número USP não encontrado na base)";
                    continue;
                }

                $vincAlunos = Graduacao::obterCargaHorariaAcumuladaAluno($codpes, $codcur, $ano_ingresso);

                if (empty($vincAlunos)) {
                    $naoEncontrados[] = $codpes . " - " . $nome . " (Não corresponde aos filtros selecionados ou sem histórico válido)";
                    continue;
                }

                foreach ($vincAlunos as $dadosAluno) {
                    $totalAcumulado = $dadosAluno['carga_obrigatoria'] + 
                                    $dadosAluno['carga_optativa'] + 
                                    $dadosAluno['carga_estagio'] + 
                                    $dadosAluno['carga_complementar'] + 
                                    $dadosAluno['carga_extensionista'];

                    $resultados[] = [
                        'codpes'              => $dadosAluno['codpes'],
                        'nompes'              => $dadosAluno['nompes'],
                        'email'               => $dadosAluno['email'],
                        'codcur'              => $dadosAluno['codcur'],
                        'codhab'              => $dadosAluno['codhab'],
                        'ano_ingresso'        => $dadosAluno['ano_ingresso'],
                        'carga_obrigatoria'   => round($dadosAluno['carga_obrigatoria']),
                        'carga_optativa'      => round($dadosAluno['carga_optativa']),
                        'carga_estagio'       => round($dadosAluno['carga_estagio']),
                        'carga_complementar'  => round($dadosAluno['carga_complementar']),
                        'carga_extensionista' => round($dadosAluno['carga_extensionista']),
                        'total_acumulado'     => round($totalAcumulado)
                    ];
                }
            }
        }

        $resultados = collect($resultados)->sortBy('nompes')->toArray();

        session()->flashInput($request->input());
        return view('grad.relatorio-carga-acumulada', compact('resultados', 'naoEncontrados', 'cursos'));
    }

    public function gradeHorarios(Request $request)
    {
        $this->authorize('relatorio-curso');
        \UspTheme::activeUrl('graduacao/horarios');

        $cursosHabilitacoes = Graduacao::listarTodosCursosHabilitacoes();

        $semestreSelect = Tools::semestres();
        $cursoHab = $request->input('curso_hab');
        $codcur = null;
        $codhab = null;

        if (!empty($cursoHab) && str_contains($cursoHab, '-')) {
            [$codcur, $codhab] = explode('-', $cursoHab);
        }

        $semestrePadrao = !empty($semestreSelect) ? array_key_first($semestreSelect) : '20261';
        $semestre = $request->input('semestre', $semestrePadrao);

        $gradePorPeriodo = [];
        $turmasCurso = [];
        $ocupacoes = [];

        if (!is_null($codcur) && !is_null($codhab) && $codcur !== '' && $codhab !== '' && $semestre) {
            $numSemestre = (int) substr($semestre, -1);
            $codcurConsulta = (int) $codcur;
            $codhabConsulta = (int) $codhab;

            // Identifica se é curso de dupla formação (18023 com hab 200 ou 99002 com hab 100)
            $cursosDupla = ['18023/200', '99002/100', '18023', '99002'];
            $chaveCursoHab = $codcurConsulta . '/' . $codhabConsulta;
            $isDuplaFormacao = in_array($codcurConsulta, [18023, 99002]) || in_array($chaveCursoHab, $cursosDupla);

            if ($isDuplaFormacao) {
                $periodosAlvo = ($numSemestre === 2) ? [2, 4] : [1, 3];
            } else {
                $periodosAlvo = ($numSemestre === 2) ? [2, 4, 6, 8, 10] : [1, 3, 5, 7, 9];
            }

            $gradeCurricular = Graduacao::listarGradeCurricular($codcurConsulta, $codhabConsulta);
            $mapaSemestreIdeal = collect($gradeCurricular)
                ->pluck('numsemidl', 'coddis')
                ->map(fn($item) => (int) $item)
                ->toArray();

            $turmasCompletas = Graduacao::listarTurmas($codcurConsulta, $codhabConsulta, $semestre);

            if (!empty($turmasCompletas)) {
                $turmasCurso = collect($turmasCompletas)
                    ->map(function ($item) use ($mapaSemestreIdeal) {
                        $coddis = trim((string) ($item['coddis'] ?? ''));

                        // Define o período com base no Replicado ou na grade ideal
                        $numper = isset($item['numper']) && !is_null($item['numper']) && (int) $item['numper'] > 0
                            ? (int) $item['numper']
                            : ($mapaSemestreIdeal[$coddis] ?? 0);

                        return [
                            'coddis' => $coddis,
                            'codtur' => trim((string) ($item['codtur'] ?? '')),
                            'nomdis' => $item['nomdis'] ?? '',
                            'numper' => $numper,
                        ];
                    })
                    ->values()
                    ->toArray();

                if (!empty($turmasCurso)) {
                    $ocupacoes = Graduacao::obterHorariosOcupacaoTurmas($turmasCurso);
                    $gradePorPeriodo = $this->montarGridHorarios($turmasCurso, $ocupacoes, $periodosAlvo, $semestre);
                }
            }
        }

        return view('grad.horarios', compact(
            'cursosHabilitacoes',
            'semestreSelect',
            'codcur',
            'codhab',
            'semestre',
            'gradePorPeriodo',
            'turmasCurso',
            'ocupacoes'
        ));
    }

    private function montarGridHorarios($turmasCurso, $ocupacoes, $periodosAlvo, $semestre = '20261')
    {
        $grid = [];

        $mapDias = [
            'seg' => 'Segunda-feira',
            'ter' => 'Terça-feira',
            'qua' => 'Quarta-feira',
            'qui' => 'Quinta-feira',
            'sex' => 'Sexta-feira',
            'sab' => 'Sábado',
            'sáb' => 'Sábado',
        ];

        // Mapeamento exato de início de slot
        $mapSlotsInicio = [
            '07:00' => 1, '07h00' => 1, '07:10' => 1, '07h10' => 1, '07:20' => 1, '07h20' => 1, '07:30' => 1, '07h30' => 1,
            '08:00' => 2, '08h00' => 2, '08:10' => 2, '08h10' => 2, '08:20' => 2, '08h20' => 2, '08:30' => 2, '08h30' => 2,
            '09:00' => 3, '09h00' => 3, '09:10' => 3, '09h10' => 3, '09:20' => 3, '09h20' => 3, '09:30' => 3, '09h30' => 3,
            '10:00' => 4, '10h00' => 4, '10:10' => 4, '10h10' => 4, '10:20' => 4, '10h20' => 4, '10:30' => 4, '10h30' => 4,
            '11:00' => 5, '11h00' => 5, '11:10' => 5, '11h10' => 5, '11:20' => 5, '11h20' => 5, '11:30' => 5, '11h30' => 5,
            '13:00' => 6, '13h00' => 6, '13:10' => 6, '13h10' => 6, '13:20' => 6, '13h20' => 6, '13:30' => 6, '13h30' => 6,
            '14:00' => 7, '14h00' => 7, '14:10' => 7, '14h10' => 7, '14:20' => 7, '14h20' => 7, '14:30' => 7, '14h30' => 7,
            '15:00' => 8, '15h00' => 8, '15:10' => 8, '15h10' => 8, '15:20' => 8, '15h20' => 8, '15:30' => 8, '15h30' => 8,
            '16:00' => 9, '16h00' => 9, '16:10' => 9, '16h10' => 9, '16:20' => 9, '16h20' => 9, '16:30' => 9, '16h30' => 9,
            '17:00' => 10, '17h00' => 10, '17:10' => 10, '17h10' => 10, '17:20' => 10, '17h20' => 10, '17:30' => 10, '17h30' => 10,
        ];

        $mapTurmas = collect($turmasCurso)->keyBy(fn($item) => $item['coddis'] . '_' . $item['codtur']);
        $ocupacoesPorDia = [];

        // Identifica o número máximo de períodos do curso (ex: 4 para dupla formação ou até 10 para os demais)
        $maxPeriodoCurso = !empty($periodosAlvo) ? max($periodosAlvo) : 10;

        // Identifica se o semestre atual do sistema é ímpar (1º sem) ou par (2º sem)
        $ultimoDigito = (int) substr($semestre, -1);
        $isSemestreImpar = ($ultimoDigito % 2 !== 0);

        foreach ($ocupacoes as $ocp) {
            $key = $ocp['coddis'] . '_' . $ocp['codtur'];

            if (!$mapTurmas->has($key)) {
                continue;
            }

            $turma = $mapTurmas->get($key);
            $coddis = trim($turma['coddis']);
            $periodo = (int) $turma['numper'];

            // DISCIPLINAS OFERECIDAS NOS DOIS SEMESTRES DO ANO
            $disciplinasAmbosSemestres = ['SGS0404', 'SET0408', 'SGS0403', 'SEM0550'];

            if (in_array($coddis, $disciplinasAmbosSemestres)) {
                $isPeriodoImpar = ($periodo % 2 !== 0);

                // No 1º semestre (ímpar), se a matéria tiver período PAR (ex: 2, 4, 6, 8, 10):
                if ($isSemestreImpar && !$isPeriodoImpar) {
                    if ($periodo >= $maxPeriodoCurso) {
                        $periodo -= 1;
                    } else {
                        $periodo += 1;
                    }
                }
                // No 2º semestre (par), se a matéria tiver período ÍMPAR (ex: 1, 3, 5, 7, 9):
                elseif (!$isSemestreImpar && $isPeriodoImpar) {
                    if ($periodo >= $maxPeriodoCurso) {
                        $periodo -= 1;
                    } else {
                        $periodo += 1;
                    }
                }
            }

            // Se o período (já reajustado) não for um dos períodos exibidos na tela, ignora
            if (!in_array($periodo, $periodosAlvo)) {
                continue;
            }

            $diaStr = strtolower(trim($ocp['diasmnocp'] ?? ''));
            $diaNome = $mapDias[$diaStr] ?? null;

            $horaInicio = trim($ocp['horent'] ?? '');
            $horaFim = trim($ocp['horsai'] ?? '');

            $slotInicio = $mapSlotsInicio[$horaInicio] ?? null;

            if ($diaNome && $slotInicio) {
                // Converte hh:mm para minutos totais do dia para calcular a duração real
                $pInicio = explode(':', str_replace('h', ':', $horaInicio));
                $pFim    = explode(':', str_replace('h', ':', $horaFim));

                $minInicio = ((int)($pInicio[0] ?? 0) * 60) + (int)($pInicio[1] ?? 0);
                $minFim    = ((int)($pFim[0] ?? 0) * 60) + (int)($pFim[1] ?? 0);
                $duracaoMinutos = $minFim - $minInicio;

                // Mapeamento rigoroso de duração em número de slots (blocos de 50min/aula)
                if ($duracaoMinutos >= 200) {
                    $duracaoSlots = 4; // Ex: 08:10 às 12:00 ou 13:20 às 17:00 (4 aulas)
                } elseif ($duracaoMinutos >= 140) {
                    $duracaoSlots = 3; // Ex: 07:20 às 10:00 ou 14:20 às 17:00 (3 aulas)
                } elseif ($duracaoMinutos >= 90) {
                    $duracaoSlots = 2; // Ex: 08:10 às 10:00 ou 14:20 às 16:00 (2 aulas)
                } else {
                    $duracaoSlots = 1; // 1 aula (50min)
                }

                $coddis = $turma['coddis'];
                $codtur = $turma['codtur'];
                $sala = $ocp['salsol'] ?? $ocp['sala'] ?? '';

                if (!isset($ocupacoesPorDia[$periodo][$diaNome])) {
                    $ocupacoesPorDia[$periodo][$diaNome] = [];
                }

                $chaveAula = $coddis . '_' . $slotInicio . '_' . $duracaoSlots;

                if (!isset($ocupacoesPorDia[$periodo][$diaNome][$chaveAula])) {
                    $ocupacoesPorDia[$periodo][$diaNome][$chaveAula] = [
                        'coddis'      => $coddis,
                        'nomdis'      => $turma['nomdis'],
                        'slot_inicio' => $slotInicio,
                        'duracao'     => $duracaoSlots,
                        'turmas'      => [],
                        'salas'       => []
                    ];
                }

                if (!in_array($codtur, $ocupacoesPorDia[$periodo][$diaNome][$chaveAula]['turmas'])) {
                    $ocupacoesPorDia[$periodo][$diaNome][$chaveAula]['turmas'][] = $codtur;
                }
                if (!empty($sala) && !in_array($sala, $ocupacoesPorDia[$periodo][$diaNome][$chaveAula]['salas'])) {
                    $ocupacoesPorDia[$periodo][$diaNome][$chaveAula]['salas'][] = $sala;
                }
            }
        }

        foreach ($periodosAlvo as $p) {
            foreach (['Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'] as $dia) {
                $aulasDoDia = $ocupacoesPorDia[$p][$dia] ?? [];

                $grid[$p][$dia] = [
                    'manha' => $this->construirLinhasTurno($aulasDoDia, 1, 5),
                    'tarde' => $this->construirLinhasTurno($aulasDoDia, 6, 10)
                ];
            }
        }

        return $grid;
    }

    private function construirLinhasTurno($aulasDoDia, $slotInicioTurno, $slotFimTurno)
    {
        $aulasTurno = array_filter($aulasDoDia, function($aula) use ($slotInicioTurno, $slotFimTurno) {
            $fimAula = $aula['slot_inicio'] + $aula['duracao'] - 1;
            return ($aula['slot_inicio'] <= $slotFimTurno && $fimAula >= $slotInicioTurno);
        });

        if (empty($aulasTurno)) {
            return [];
        }

        usort($aulasTurno, fn($a, $b) => $a['slot_inicio'] <=> $b['slot_inicio']);

        $linhas = [];

        foreach ($aulasTurno as $aula) {
            $alocado = false;

            foreach ($linhas as &$linha) {
                $conflito = false;
                $inicioAula = max($aula['slot_inicio'], $slotInicioTurno);
                $fimAula = min($aula['slot_inicio'] + $aula['duracao'] - 1, $slotFimTurno);

                for ($s = $inicioAula; $s <= $fimAula; $s++) {
                    if (isset($linha[$s])) {
                        $conflito = true;
                        break;
                    }
                }

                if (!$conflito) {
                    $colspanEfetivo = $fimAula - $inicioAula + 1;
                    $linha[$inicioAula] = array_merge($aula, ['colspan' => $colspanEfetivo]);

                    for ($s = $inicioAula + 1; $s <= $fimAula; $s++) {
                        $linha[$s] = 'OCUPADO';
                    }
                    $alocado = true;
                    break;
                }
            }

            if (!$alocado) {
                $novaLinha = [];
                $inicioAula = max($aula['slot_inicio'], $slotInicioTurno);
                $fimAula = min($aula['slot_inicio'] + $aula['duracao'] - 1, $slotFimTurno);

                $colspanEfetivo = $fimAula - $inicioAula + 1;
                $novaLinha[$inicioAula] = array_merge($aula, ['colspan' => $colspanEfetivo]);

                for ($s = $inicioAula + 1; $s <= $fimAula; $s++) {
                    $novaLinha[$s] = 'OCUPADO';
                }

                $linhas[] = $novaLinha;
            }
        }

        return $linhas;
    }
}