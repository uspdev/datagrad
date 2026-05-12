<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Disciplina;
use App\Replicado\Graduacao;
use App\Replicado\Pessoa;
use App\Services\Diff;
use App\Services\Pdf;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
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
                $discs = collect($user->prefixos())
                    ->flatMap(
                        fn($prefixo) => Disciplina::listarDisciplinasPorPrefixo($prefixo)
                    );
                break;
            case 'finalizados':
                $request->session()->put('disciplinas.visao', 'finalizados');
                $discs = Disciplina::listarDisciplinasFinalizadas();
                break;
            default:
                $discs = Disciplina::listarDisciplinasPorResponsavel($user->codpes);
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

        $versao = $request->v ?: null; // vai desativar versões anteriores?
        $coddis = strtoupper($coddis);

        if ($dr = Disciplina::obterDisciplinaReplicado($coddis)) {
            $dr['meta'] = Disciplina::meta();
        }
        $disc = Disciplina::where('coddis', $coddis)->first() ?? Disciplina::novo($dr);
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

        return view('disciplinas.edit', compact('disc', 'cursos'));
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

        // para aprovação, finaliza a edição do pdf
        if ($request->estado == 'Em aprovação') {
            $disc->atualizarEstado('Em aprovação');
            $disc->save();
            Disciplina::renovarCacheAfterResponse();

            return redirect()
                ->route('disciplinas.preview-html', $disc->coddis)
                ->with('alert-success', 'Disciplina enviada para aprovação com sucesso!');
        }

        $disc->atualizado_por_id = Auth::id();

        if ($add = $request->codpes_add) {
            $disc->adicionarResponsavel($add);
        }

        if ($rem = $request->codpes_rem) {
            $disc->removerResponsavel($rem);
        }

        $disc->fill($request->all());
        if ($disc->isDirty()) {
            $disc->save();
            Disciplina::renovarCacheAfterResponse();
        }

        $request->session()->flash('alert-info', 'Dados salvo com sucesso!');

        if ($request->action == 'preview-html') {
            return redirect()->route('disciplinas.preview-html', $disc->coddis);
        }
        if ($request->next) {
            return redirect()->to($request->next);
        }

        return redirect()->route('disciplinas.edit', $disc->coddis);
    }

    /**
     * Realiza o preview do PDF da disciplina em alteração/criação
     *
     * @param  string  $disc
     */
    // public function preview($coddis)
    // {
    //     $disc = Disciplina::where('coddis', $coddis)->first();
    //     $url = Storage::temporaryUrl('disciplinas/disciplina-' . $coddis . '.pdf', now()->addMinutes(10), ['ResponseContentDisposition' => 'attachment; filename=file2.pdf']);

    //     return view('disciplinas.preview', compact('disc', 'url'));
    // }

    /**
     * Realiza o preview em HTML da disciplina em alteração/criação
     *
     * @param  string  $coddis
     */
    public function previewHtml($coddis)
    {
        $this->authorize('viewAny', Disciplina::class);
        // $disc = Disciplina::where('coddis', strtoupper($coddis))->first();
        $disc = Disciplina::primeiroOuNovo(strtoupper($coddis));
        $this->authorize('update', $disc);

        if (! $disc) {
            return back()
                ->with('alert-danger', 'Disciplina não encontrada!');
        }

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

        return view('disciplinas.preview-html', compact('disc'));
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
}
