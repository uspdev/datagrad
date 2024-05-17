@extends('layouts.app')

@section('styles')
  <style>
    .hover:hover {
      background-color: gainsboro;
    }

    .hide {
      display: none;
    }

    .hover:hover .hide {
      display: inline;
      color: red;
    }

    .navbar {
      background-color: bisque;
      border-bottom: 3px solid red;
    }
  </style>
@endsection

@section('content')
  <div class="navbar navbar-light card-header-sticky justify-content-between mb-3 pb-1">
    <div>
      <span class="h5">Funções</span>
      <i class="fas fa-angle-right"></i> Esta interface permite gerenciar as funções dos usuários dentro do sistema.
    </div>
    <div class="form-inline">
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <form method="post" action="{{ route('roles.update', 'cg') }}">
        @csrf
        @method('put')
        <div>
          <div class="h5">
            Comissão de graduação (CG)
            @include('disciplinas.partials.codpes-adicionar-btn')
          </div>
          <div class="ml-3">
            @foreach ($roleCG->users->sortBy('name') as $user)
              <div class="hover"><span>{{ $user->name }}</span>
                <span class="hide">
                  @include('disciplinas.partials.codpes-remover-btn', ['codpes' => $user->codpes])
                </span>
              </div>
            @endforeach
          </div>
        </div>
      </form>

      <br>
      <div class="h5">Coordenadores de cursos</div>

    </div>
    <div class="col-md-6">

      <div class="h5">Grupos de disciplinas / Chefes de departamentos</div>
      @foreach ($departamentos as $role)
        <b>{{ $role->name }}</b><br>
        <div class="ml-3">
          @foreach ($role->users as $user)
            {{ $user->name }}
            {{-- - {{ $user->permissions->pluck('name') }} --}}
            <br>
          @endforeach
        </div>
      @endforeach

    </div>
  </div>
@endsection
