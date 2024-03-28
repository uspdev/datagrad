<?php

namespace App\Http\Controllers;

use App\Replicado\Graduacao;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        $coddis = $id;
        $responsaveis = [];
        $cursos = [];

        $disciplina = Graduacao::obterDisciplina(strtoupper($id), $versao);
        if ($disciplina) {
            $responsaveis = Graduacao::listarResponsaveisDisciplina(strtoupper($id));
            foreach (['dtaatvdis', 'dtadtvdis'] as $campo) {
                $disciplina[$campo] = $disciplina[$campo] ? Carbon::parse($disciplina[$campo]) : null;
            }
            $cursos = Graduacao::listarCursosDisciplina($disciplina['coddis']);
        }

        return view('disciplinas.show', compact('disciplina', 'responsaveis', 'cursos', 'coddis'));

        // https://github.com/arnab/jQuery.PrettyTextDiff?tab=readme-ov-file
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
