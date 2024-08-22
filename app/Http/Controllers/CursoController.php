<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Services\Tools;
use App\Models\Disciplina;
use App\Replicado\Graduacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $codcur)
    {
        $this->authorize('viewAny', Disciplina::class);
        
        // if (!Gate::any(['disciplina-cg', 'disciplina-cc'])) {
        //     abort(403);
        // }

        $codhab = $request->codhab;
        $curso = Curso::FirstOrCreate([
            'codcur' => $codcur,
            // 'codhab' => $codhab,
        ]);
        $curso->dr = Graduacao::obterCurso($codcur, $codhab);
        $curso->save();
        return view('cursos.show', compact('curso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $codcur)
    {
        
        if (!Gate::any(['disciplina-cg', 'disciplina-cc'])) {
            abort(403);
        }

        $codhab = $request->codhab;
        $curso = Curso::FirstOrCreate([
            'codcur' => $codcur,
            'codhab' => $codhab,
        ]);
        $curso->dr = Graduacao::obterCurso($codcur, $codhab);
        $curso->save();
        return view('cursos.edit', compact('curso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('viewAny', Disciplina::class);

        $habilidades = Tools::limparLinhas($request->habilidades);
        $competencias = Tools::limparLinhas($request->competencias);
        $curso = Curso::find($id);
        $curso->habilidades = $habilidades;
        $curso->competencias = $competencias;
        $curso->save();

        // $habs = explode(PHP_EOL, $habilidades);
        // dd($habs);


        $request->session()->flash('alert-info', 'Dados salvo com sucesso!');
        return redirect()->route('cursos.show', $curso->codcur);
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
