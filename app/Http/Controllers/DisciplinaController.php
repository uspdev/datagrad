<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Disciplina;
use App\Replicado\Graduacao;
use App\Replicado\Pessoa;
use App\Services\TratamentoBibliografico;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Services\Gotenberg;
use Illuminate\Validation\Rule;
use Spatie\LaravelPdf\Facades\Pdf;
use Uspdev\UspTheme\Facades\UspTheme;

class DisciplinaController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            UspTheme::activeUrl('disciplinas');
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Disciplina::class);

        // qual visão o usuario quer: docente, cg, chefe, etc
        // persistido na session
        if ($request->visao) {
            $visao = $request->visao;
            $request->session()->put('disciplinas.visao', $visao);
        } else {
            $visao = $request->session()->get('disciplinas.visao', 'docente');
        }

        $user = Auth::user();

        switch ($visao) {
            case 'cg':
                if (Gate::denies('disciplina-cg')) {
                    $request->session()->put('disciplinas.visao', 'docente');
                    return redirect()->action([self::class, 'index']);
                }
                $request->session()->put('disciplinas.visao', 'cg');
                $discs = Disciplina::listarDisciplinas();
                break;

            case 'departamento':
                if (Gate::denies('disciplina-chefe')) {
                    $request->session()->put('disciplinas.visao', 'docente');
                    return redirect()->action([self::class, 'index']);
                }
                $request->session()->put('disciplinas.visao', 'departamento');
                $discs = Disciplina::listarDisciplinasPorPrefixo($user->prefixos());
                break;
            case 'finalizados':
                $request->session()->put('disciplinas.visao', 'finalizados');
                $discs = Disciplina::listarDisciplinasFinalizadas();
                break;
            case 'docente':
                $discs = Disciplina::listarDisciplinasPorResponsavel($user->codpes);
                break;
            default:
                $discs = collect();
        }
        $discs = $discs->sortBy('coddis');

        return view('disciplinas.index', compact('discs', 'visao'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Cria nova disciplina
     *
     * O prefixo é informado pelo usuário, e o código completo
     * é gerado automaticamente para evitar colisões.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'coddis' => 'required|string|max:3',
            'nomdis' => 'required|string|max:240',
            'codpes' => 'required|integer',
        ]);
        $prefixo = strtoupper($validated['coddis']);

        // vamos garantir um coddis aleatório e único
        do {
            $coddis = $prefixo . '-N' . substr(str_shuffle('0123456789'), 0, 4);
        } while (Disciplina::where('coddis', $coddis)->exists());

        $disc = Disciplina::create([
            'coddis' => $coddis,
            'nomdis' => $validated['nomdis'],
            'responsaveis' => [
                ['codpes' => $validated['codpes'], 'nompesttd' => Pessoa::obterNome($validated['codpes'])],
            ],
            'estado' => 'Criar',
        ]);

        $disc->save();
        Disciplina::renovarCacheAfterResponse();

        return redirect()->route('disciplinas.edit', $disc->coddis);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $coddis
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $coddis)
    {
        $this->authorize('viewAny', Disciplina::class);

        $versao = $request->v ?: 'max'; // vai desativar versões anteriores?
        $coddis = strtoupper($coddis);

        if ($dr = Disciplina::obterDisciplinaReplicado($coddis, $versao)) {
            $dr['meta'] = Disciplina::meta();
        }
        $disc = Disciplina::where('coddis', $coddis)->naoFinalizado()->first() ?? Disciplina::novo($dr);
        $disc->dr = $dr;

        return view('disciplinas.show', compact('dr', 'coddis', 'disc'));

        // https://github.com/arnab/jQuery.PrettyTextDiff?tab=readme-ov-file
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $coddis
     * @return Response
     */
    public function edit(Request $request, $coddis)
    {
        $this->authorize('user');
        $disc = Disciplina::primeiroOuNovo(strtoupper($coddis));
        $this->authorize('update', $disc);

        $disc->mesclarResponsaveisReplicado();

        // disciplina-replicado -> cursos da unidade que aparece a disciplina
        $cursos = [];
        foreach ($disc->dr['cursos'] ?? [] as $curso_dr) {
            if (stripos(config('replicado.codundclgs'), $curso_dr['codclg']) !== false) {
                // é curso da unidade
                $curso = Curso::where('codcur', $curso_dr['codcur'])->first();
                if (! $curso) {
                    $curso = new Curso;
                    $curso->codcur = $curso_dr['codcur'];
                    $curso->dr = $curso_dr;
                }
                $cursos[] = $curso;
            }
        }
        $disc->cursos = $cursos;
        $cursos = Graduacao::listarCursosHabilitacoes();
        $cursosLocais = Curso::all()->keyBy('codcur');

        return view('disciplinas.edit', compact('disc', 'cursos', 'cursosLocais'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string  $coddis
     * @return Response
     */
    public function update(Request $request, $coddis)
    {
        $request->validate([]);
        $disc = Disciplina::primeiroOuNovo($coddis);
        $this->authorize('update', $disc);

        $action = $request->action ?? null;

        if ($action == 'estado_undo') {
            Gate::authorize('departamento', $disc);
            $disc->atualizarEstado($request->estado);
            $disc->save();
            return redirect()->to($request->next);
        }

        if ($action === 'excluir') {
            Gate::authorize('admin');
            $disc->delete();
            return redirect()
                ->route('disciplinas.show', $disc->coddis)
                ->with('alert-success', 'Disciplina excluída com sucesso!');
        }


        // para aprovação, finaliza a edição do pdf
        if ($action == 'mudar_em_aprovacao') {
            $erros = $disc->validarCampos();
            $pendentes = [];

            if (isset($erros['mtdens'])) {
                $pendentes[] = '"Método de ensino"';
            }
            if (isset($erros['objdslsut'])) {
                $pendentes[] = '"Objetivos de Desenvolvimento Sustentável"';
            }

            if (!empty($pendentes)) {
                $msg = 'Não é possível mudar para "Em aprovação": o(s) campo(s) ' . implode(' e ', $pendentes) . ' deve(m) ser preenchido(s).';
                return redirect()->back()->with('alert-danger', $msg);
            }

            $disc->atualizarEstado('Em aprovação');
            $disc->save();
            Disciplina::renovarCacheAfterResponse();

            return redirect()
                ->route('disciplinas.preview', $disc->coddis)
                ->with('alert-success', 'Disciplina enviada para aprovação com sucesso!');
        }

        if ($action == 'mudar_finalizado') {
            $disc->atualizarEstado('Finalizado');
            $disc->save();
            Disciplina::renovarCacheAfterResponse();

            return redirect()
                ->route('disciplinas.index', ['visao' => 'finalizados'])
                ->with('alert-success', 'Disciplina finalizada com sucesso!');
        }

        // inicio de edição
        if ($disc->estado == 'Propor alteração') {
            $disc->atualizarEstado('Em edição');
        }

        $disc->atualizado_por_id = Auth::id();

        if ($add = $request->codpes_add) {
            $disc->adicionarResponsavel($add);
        }

        if ($rem = $request->codpes_rem) {
            $disc->removerResponsavel($rem);
        }

        $avisos = [];
        if ($disc->estado == 'Criar' && $request->boolean('aba_cursos_criar')) {
            [$dadosCursos, $avisos] = $this->tratarDadosPorCurso($request, $disc);
            $request->merge($dadosCursos);
        }

        $fill = $request->except('estado', 'codpes_add', 'codpes_rem', 'action', 'next');
        $disc->fill($fill);
        if ($disc->isDirty()) {
            $disc->save();
            Disciplina::renovarCacheAfterResponse();
        }

        $request->session()->flash('alert-info', 'Dados salvo com sucesso!');

        if ($avisos) {
            $request->session()->flash('alert-warning', implode('<br>', $avisos));
        }

        if ($request->action == 'preview') {
            return redirect()->route('disciplinas.preview', $disc->coddis);
        }
        if ($request->next) {
            return redirect()->to($request->next);
        }

        return redirect()->route('disciplinas.edit', $disc->coddis);
    }

    /**
     * Normaliza os dados por curso da aba "Cursos" (disciplina em criação).
     *
     * Só mantém dados dos cursos marcados. Pré-requisitos inexistentes são descartados
     * e geram aviso, sem perder o restante do que o usuário preencheu.
     *
     * @return array [dados para merge no request, avisos]
     */
    private function tratarDadosPorCurso(Request $request, Disciplina $disc): array
    {
        $request->validate([
            'cursos_marcados' => 'nullable|array',
            'cursos_marcados.*' => 'string|max:10',
            'cursos_dados.*.tipo' => ['nullable', Rule::in(array_keys(Disciplina::$tiposCurso))],
            'cursos_dados.*.periodo' => 'nullable|integer|between:1,10',
            'cursos_dados.*.prerequisitos' => 'nullable|string|max:500',
            'habilidades.*.*' => 'string',
            'competencias.*.*' => 'string',
        ]);

        $marcados = array_values(array_unique(array_filter((array) $request->input('cursos_marcados', []))));
        $dados = ['cursos_dados' => [], 'habilidades' => [], 'competencias' => []];
        $avisos = [];

        foreach ($marcados as $codcur) {
            $dados['habilidades'][$codcur] = array_values((array) $request->input("habilidades.$codcur", []));
            $dados['competencias'][$codcur] = array_values((array) $request->input("competencias.$codcur", []));

            $validos = [];
            foreach (Disciplina::separarCodigos($request->input("cursos_dados.$codcur.prerequisitos")) as $cod) {
                if ($cod === $disc->coddis) {
                    $avisos[] = "Curso $codcur: a disciplina não pode ser pré-requisito dela mesma.";
                } elseif (Disciplina::buscarPorCodigo($cod)) {
                    $validos[] = $cod;
                } else {
                    $avisos[] = "Curso $codcur: pré-requisito \"$cod\" não encontrado e não foi salvo.";
                }
            }

            $dados['cursos_dados'][$codcur] = [
                'tipo' => $request->input("cursos_dados.$codcur.tipo") ?: null,
                'periodo' => $request->filled("cursos_dados.$codcur.periodo")
                    ? (int) $request->input("cursos_dados.$codcur.periodo")
                    : null,
                'prerequisitos' => $validos,
            ];
        }

        return [$dados, $avisos];
    }

    /**
     * Valida (ajax) se o código de disciplina existe, na base local ou no replicado
     */
    public function validarCodigo(Request $request)
    {
        $this->authorize('user');

        $disc = Disciplina::buscarPorCodigo((string) $request->coddis);

        return response()->json([
            'existe' => (bool) $disc,
            'nome' => $disc['nomdis'] ?? null,
        ]);
    }

    private function prepararPreview($coddis)
    {
        $coddis = strtoupper($coddis);
        $dr = Disciplina::obterDisciplinaReplicado($coddis);
        $disc = Disciplina::where('coddis', $coddis)
            ->naoFinalizado()
            ->first();

        if (! $disc) {
            if ($dr) {
                $dr['meta'] = Disciplina::meta();
            }

            $disc = Disciplina::novo($dr);
            $disc->dr = $dr;
            return [
                'disc' => $disc,
                'dr' => $dr,
                'coddis' => $coddis,
                'negado' => true,
            ];
        }

        $disc->dr = $dr;
        $this->authorize('update', $disc);
        $disc->mesclarResponsaveisReplicado();
        $disc->cursos = $disc->obterCursosDaUnidade($disc);
        return [
            'disc' => $disc,
            'dr' => $dr,
            'coddis' => $coddis,
            'negado' => false,
        ];
    }

    /**
     * Realiza o preview em HTML da disciplina em alteração/criação
     *
     * @param  string  $coddis
     */
    public function preview($coddis)
    {
        $this->authorize('viewAny', Disciplina::class);

        $data = $this->prepararPreview($coddis);

        if ($data['negado']) {
            return view('disciplinas.preview-negado', $data);
        }

        return view('disciplinas.preview', ['disc' => $data['disc']]);
    }

    /**
     * Realiza o download do PDF da disciplina em alteração/criação
     */
    public function downloadPdf($coddis)
    {
        $this->authorize('viewAny', Disciplina::class);

        $data = $this->prepararPreview($coddis);

        if ($data['negado']) {
            return view('disciplinas.preview-negado', $data);
        }

        if (config('laravel-pdf.driver') === 'gotenberg' && ! Gotenberg::isAvailable()) {
            return redirect()
                ->route('disciplinas.preview', $coddis)
                ->with('alert-danger', 'Serviço de geração de PDF (Gotenberg) indisponível no momento. Tente novamente mais tarde.');
        }

        $disc = $data['disc'];
        $filename = 'alteracao-disciplina-' . $coddis . '-vigencia-' . $disc->ano . $disc->semestre . '.pdf';
        return Pdf::view('disciplinas.preview', compact('disc'))
            ->waitUntilReady(timeout: 30000)
            ->format('a4')
            ->orientation('portrait')
            ->name($filename)
            ->download();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function ajuda()
    {
        $md = file_get_contents(base_path('docs/disciplinas.md'));
        return view('disciplinas.ajuda', compact('md'));
    }

    /**
     * Exibe a consulta de bibliografias das disciplinas.
     *
     * Permite consultar as bibliografias vigentes por semestre de referência
     * e carregar, via AJAX, a bibliografia de uma disciplina específica.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View|string
     *
     * @requestParam string|null $semestre Semestre de referência no formato AAAAS
     * ou "next" para o próximo semestre.
     * @requestParam string|null $coddis Código da disciplina para consulta via AJAX.
     */
    public function bibliografia(Request $request)
    {
        $this->authorize('biblioteca', Disciplina::class);

        $request->validate([
            'semestre' => ['nullable', 'regex:/^(next|\d{4}[12])$/'],
            'coddis' => ['nullable', 'string', 'max:7'], // ajax para abrir modal de disciplina
        ]);

        if ($request->coddis) { // ajax para abrir modal de disciplina
            $coddis = strtoupper($request->coddis);
            $dtainibbg = $request->dtainibbg;
            $dtafimbbg = $request->dtafimbbg;
            $bbg = TratamentoBibliografico::obterBibliografia($coddis, $dtainibbg, $dtafimbbg);
            return view('disciplinas.partials.bibliografia-detalhes', [
                'bbg' => $bbg,
                'dtainibbg' => $dtainibbg,
                'dtafimbbg' => $dtafimbbg,
            ]);
        }

        // trata semestre
        if ($request->semestre === 'next') {
            $data = now()->addMonths(6);
            $semestre = $data->year . ($data->month <= 6 ? 1 : 2);
        } else {
            $semestre = $request->semestre ?: now()->year . (now()->month <= 6 ? 1 : 2);
        }

        $visao = 'biblioteca'; // para o menu
        $bbg = TratamentoBibliografico::listarBibliografiasDaUnidadePorPeriodo($semestre);

        return view('disciplinas.bibliografia', compact('bbg', 'visao', 'semestre'));
    }
}
