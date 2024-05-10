<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use App\Services\Diff;
use App\Services\Pdf;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use UspTheme;

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
                if (!Gate::allows('disciplina-cg')) {
                    $request->session()->put('disciplinas.visao', 'docente');
                    return redirect()->action([self::class, 'index']);
                } else {
                    $discs = Disciplina::listarDisciplinas();
                    $request->session()->put('disciplinas.visao', 'cg');
                    $visao = 'cg';
                }
                break;

            case 'chefe':
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  String  $coddis
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $coddis)
    {
        $this->authorize('viewAny', Disciplina::class);

        $versao = $request->v ?: null; // vai desarivar versões anteriores?
        $coddis = strtoupper($coddis);
        $cursos = [];

        $disc = Disciplina::primeiroOuNovo($coddis);
        $dr = $disc->dr;

        return view('disciplinas.show', compact('dr', 'coddis', 'disc'));

        // https://github.com/arnab/jQuery.PrettyTextDiff?tab=readme-ov-file
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $coddis
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $coddis)
    {
        $disc = Disciplina::primeiroOuNovo(strtoupper($coddis));
        $this->authorize('update', $disc);

        $disc->mesclarResponsaveisReplicado();

        return view('disciplinas.edit', compact('disc'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $coddis
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $coddis)
    {
        $disc = Disciplina::primeiroOuNovo($coddis);
        $this->authorize('update', $disc);

        if ($add = $request->responsavel_add) {
            $disc->adicionarResponsavel($add);
        }

        if ($rem = $request->responsavel_rem) {
            $disc->removerResponsavel($rem);
        }

        $disc->fill($request->all());
        $disc->save();

        // renova o cache depois de enviar o response para o navegador
        // https://laravel.com/docs/10.x/queues#dispatching-after-the-response-is-sent-to-browser
        dispatch(function () {
            Disciplina::listarDisciplinas(true);
        })->afterResponse();

        if ($request->submit == 'preview') {
            // $save = clone $disc;
            $disc->diffs = Diff::computar($disc);
            $disc = Pdf::quebrarTextoLongo($disc);
            // dd('save', $save->pgmdis, $disc->pgmdis);
            // dd($disc->objdis, explode('__quebrar__',$disc->objdis));
            $pdf = Pdf::gerarRelatorioAlteracaoDisciplina($disc);

            return redirect()->route('disciplinas.preview', $disc->coddis);
        }

        $request->session()->flash('alert-info', 'Dados salvo com sucesso!');
        return redirect()->route('disciplinas.edit', $disc->coddis);
    }

    /**
     * Realiza o preview do PDF da disciplina em alteração/criação
     *
     * @param String $disc
     */
    public function preview($coddis)
    {
        $disc = Disciplina::where('coddis', $coddis)->first();

        $url = Storage::temporaryUrl('disciplina-' . $coddis . '.pdf', now()->addMinutes(10));

        return view('disciplinas.preview', compact('disc', 'url'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
