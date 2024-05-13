<?php

namespace App\Http\Controllers;

use Closure;
use App\Models\User;
use App\Models\Disciplina;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Uspdev\UspTheme\Facades\UspTheme;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            UspTheme::activeUrl('roles');
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::where('name', 'not like', 'disciplinas%')->get();

        $roleCG = Role::where('name', 'CG')->first();
        $departamentos = [];
        foreach (Disciplina::listarPrefixosDisciplinas() as $prefixo) {
            $role = Role::firstOrCreate(['name' => 'disciplinas.' . $prefixo]);
            $departamentos[] = Role::firstOrCreate(['name' => 'disciplinas.' . $prefixo]);
            // $departamentos[] = 'disciplinas.' . $prefixo;
        }

        return view('roles.index', compact('roles', 'departamentos', 'roleCG'));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * 
     * Este update não segue o padrão do laravel.
     */
    public function update(Request $request, string $role_name)
    {
        if ($add = $request->codpes_add) {
            $user = User::findOrCreateFromReplicado($add);
            if ($user) {
                $user->assignRole($role_name);
            }
        }

        if ($rem = $request->codpes_rem) {
            $user = User::where('codpes', $rem)->first();
            if ($user) {
                $user->removeRole($role_name);
            }
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
