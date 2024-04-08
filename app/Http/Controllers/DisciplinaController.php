<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use App\Replicado\Graduacao;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class DisciplinaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return redirect()->route('disciplinas.show', 0);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $this->authorize('user');

        $versao = $request->v ?: null;
        $coddis = strtoupper($id);
        $responsaveis = [];
        $cursos = [];

        $disciplina = Graduacao::obterDisciplina($coddis, $versao);
        if ($disciplina) {
            $responsaveis = Graduacao::listarResponsaveisDisciplina($coddis);
            foreach (['dtaatvdis', 'dtadtvdis'] as $campo) {
                $disciplina[$campo] = $disciplina[$campo] ? Carbon::parse($disciplina[$campo]) : null;
            }
            $cursos = Graduacao::listarCursosDisciplina($coddis);
        }

        return view('disciplinas.show', compact('disciplina', 'responsaveis', 'cursos', 'coddis'));

        // https://github.com/arnab/jQuery.PrettyTextDiff?tab=readme-ov-file
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $coddis
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $coddis)
    {
        $this->authorize('user');

        $coddis = strtoupper($coddis);
        $responsaveis = [];
        $cursos = [];

        $dr = Graduacao::obterDisciplina($coddis, 'max');
        if ($dr) {
            $responsaveis = Graduacao::listarResponsaveisDisciplina($coddis);

            // vamos transformar estes campos em objeto de data
            foreach (['dtaatvdis', 'dtadtvdis'] as $campo) {
                $dr[$campo] = $dr[$campo] ? Carbon::parse($dr[$campo]) : null;
            }

            $cursos = Graduacao::listarCursosDisciplina($coddis);

            $disc = Disciplina::where('coddis', $coddis)->first();
            if (!$disc) {
                $disc = new Disciplina;
                $disc->fill($dr);
                $disc->atualizado_por_id = Auth::user()->id;
                $disc->criado_por_id = Auth::user()->id;
                $disc->save();
            }
            // $disc['meta'] = Disciplina::$meta;

            $disc['dr'] = $dr;
            // dd($novo);
        }

        return view('disciplinas.edit', compact('disc', 'responsaveis', 'cursos', 'coddis'));

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

        // dd($request->all());
        $disc = Disciplina::find($request->id);
        $disc->fill($request->all());
        $disc->save();
        $request->session()->flash('alert-info', 'Dados salvo com sucesso!');

        if ($request->submit == 'preview') {

        }
        return back();

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
