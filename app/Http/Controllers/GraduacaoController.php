<?php

namespace App\Http\Controllers;

use App\Replicado\Graduacao;
use App\Replicado\Lattes;
use App\Replicado\Pessoa;
use App\Services\Evasao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Uspdev\Replicado\Uteis;

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
        \UspTheme::activeUrl('graduacao/relatorio/sintese');

        $nomes = SELF::limparNomes($request->nomes);
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
        \UspTheme::activeUrl('graduacao/relatorio/complementar');

        $anoIni = $request->anoIni;
        $anoFim = $request->anoFim;
        $nomes = SELF::limparNomes($request->nomes);
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

    public function cargaDidatica(Request $request)
    {
        $this->authorize('datagrad');
        \UspTheme::activeUrl('graduacao/relatorio/cargadidatica');

        $semestreIni = $request->semestreIni;
        $semestreFim = $request->semestreFim;

        $semestres = $this->iniFim($request->semestreIni, $request->semestreFim);

        $nomes = SELF::limparNomes($request->nomes);
        $pessoas = [];
        $naoEncontrados = [];
        foreach ($nomes as $nome) {
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
            $pessoa['turmas'] = Graduacao::listarTurmasPorCodpes($pessoa['codpes'], $semestres);

            // vamos calcular as cargas horárias médias
            $somaHorasTeorica = 0;
            $somaHorasPratica = 0;
            $count = 0;
            if (!empty($pessoa['turmas'])) {
                $k = 0;
                $prev = $pessoa['turmas'][$k];
                $divisorQuinzenal = ($prev['stamis'] == 'N') ? 1 : 2; // 'N' -> semanal
                $somaHorasTeorica += (int) $prev['cgahorteo'] / $divisorQuinzenal;
                $somaHorasPratica += (int) $prev['cgahorpra'] / $divisorQuinzenal;
                $count++;
                for ($k = 1; $k < count($pessoa['turmas']); $k++) {
                    $turma = $pessoa['turmas'][$k];
                    $divisorQuinzenal = ($turma['stamis'] == 'N') ? 1 : 2; // 'N' -> semanal
                    if (!($prev['codtur'] == $turma['codtur'] && $prev['coddis'] == $turma['coddis'])) {
                        $somaHorasTeorica += (int) $turma['cgahorteo'] / $divisorQuinzenal;
                        $somaHorasPratica += (int) $turma['cgahorpra'] / $divisorQuinzenal;
                        $count++;
                    }
                    $prev = $turma;
                }
            }
            $pessoa['mediaHorasTeorica'] = $somaHorasTeorica / count($semestres);
            $pessoa['mediaHorasPratica'] = $somaHorasPratica / count($semestres);
            $pessoa['mediaTurmas'] = $count / count($semestres);

            $pessoas[] = $pessoa;
        }

        session()->flashInput($request->input());

        // dd($pessoas);
        return view('grad.relatorio-cargadidatica', [
            'pessoas' => $pessoas,
            'naoEncontrados' => $naoEncontrados,
            'semestreIni' => $semestreIni,
            'semestreFim' => $semestreFim,
            'turmaSelect' => $this->semestres(),
        ]);
    }

    protected static function limparNomes($nomes)
    {
        $nomes = str_replace("\r", '', $nomes); // remove carriage return, mantém new line
        $nomes = explode(PHP_EOL, $nomes);
        $nomes = array_filter($nomes); // remove elementos empty()
        $nomes = array_map('trim', $nomes);
        $nomes = array_unique($nomes); // sem repetidos
        return $nomes;
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
        \UspTheme::activeUrl('graduacao/relatorio/gradehoraria');

        $nusps = SELF::limparNomes($request->nusps); //limpando os números usp na verdade

        $horarios = [];
        $naoEncontrados = [];

        foreach ($nusps as $codpes) {
            // recupera a grade completa do aluno
            $gradeAluno = Graduacao::obterGradeHoraria($codpes);
            if (!$gradeAluno) {
                $naoEncontrados[] = $codpes;
                continue;
            }

            $nome = Pessoa::obterNome($codpes);
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
        ])->toArray();

        session()->flashInput($request->input());
        return view('grad.relatorio-gradehoraria', compact('horarios', 'naoEncontrados'));
    }

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
            'turmaSelect' => $this->semestres(),
            'nomes' => $ret['nomes'],
            'nomesCount' => $ret['nomesCount'],
            'timestamp' => $ret['timestamp'],
        ]);
    }

    /**
     * Retorna os semestres entre $semestreIni e $semestreFim, inclusive
     */
    protected function iniFim($semestreIni, $semestreFim)
    {
        $semestres = $this->semestres();

        if ($semestreFim < $semestreIni) {
            $tmp = $semestreFim;
            $semestreFim = $semestreIni;
            $semestreIni = $tmp;
        }
        // dd($semestreIni, $semestreFim, $semestres);

        $defaultSemestreFim = date('Y') . (date('m') < 7 ? 1 : 2);
        $semestreFim = $semestreFim ? $semestreFim : $defaultSemestreFim;

        $defaultSemestreIni = $semestres[array_search($semestreFim, $semestres) + 1];
        $semestreIni = $semestreIni ? $semestreIni : $defaultSemestreIni;

        $keyIni = array_search($semestreIni, $semestres); // chave do array correspondente ao semestreIni
        $keyFim = array_search($semestreFim, $semestres);

        $ret = array_slice($semestres, $keyFim, $keyIni - $keyFim + 1);
        return $ret;
    }

    protected function semestres()
    {
        $semestres = ['20232', '20231', '20222', '20221', '20212', '20211', '20202', '20201', '20192', '20191', '20182', '20181'];
        return $semestres;
    }

    protected function turmasNoCache(Request $request, int $codcur, int $codhab)
    {
        $this->authorize('datagrad');
        \UspTheme::activeUrl('graduacao/cursos');

        $semestreIni = $request->semestreIni;
        $semestreFim = $request->semestreFim;

        $curso = Graduacao::obterCurso($codcur, $codhab);

        $semestres = $this->iniFim($request->semestreIni, $request->semestreFim);
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

        $CodcurNomecur = Evasao::retornarCodcurNomcur();

        if ($request->isMethod('get')) {

            return view('grad.relatorio-evasao', ['cursoOpcao' => $CodcurNomecur]);
        }

        $request->validate([
            'curso' => 'nullable',
            'ano' => 'required|integer|between:2015,' . (date('Y') - 1),
        ]);

        $taxaEvasao = Evasao::taxaEvasao($request->ano, ($request->curso !== null ? $request->curso : null));

        $formRequest = ($request->curso !== null ? Evasao::retornarCodcurNomcur((int) $request->curso) : null);

        $formRequest = array_merge($formRequest, ['anoIngresso' => $request->ano]);

        return view('grad.relatorio-evasao', ['taxaEvasao' => $taxaEvasao, 'formRequest' => $formRequest, 'cursoOpcao' => $CodcurNomecur]);
    }

}
