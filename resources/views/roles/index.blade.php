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
      <br>
      Esta interface permite gerenciar as funções dos usuários dentro do sistema.
      Acessível apenas para as pessoas listadas em CG.
    </div>
    <div class="form-inline">
    </div>
  </div>

  <div class="row">
    <div class="col-md-4">
      <div class="card">

        <div class="card-header h5">
          Departamentos (Grupos de disciplinas)
        </div>
        <div class="card-body">
          @foreach ($departamentos as $role)
            <div class="card my-2">
              <form method="post" id="{{ $role->name }}" action="{{ route('roles.update', $role->name) }}">
                @csrf
                @method('put')
                <div class="card-header py-1">
                  Prefixo {{ substr($role->name, 12) }}
                  @include('disciplinas.partials.codpes-adicionar-btn')
                </div>
                <div class="card-body py-1">
                  @foreach ($role->users->sortBy('name') as $user)
                    <div class="hover">
                      <span>{{ $user->name }}</span>
                      <span class="hide">
                        @include('disciplinas.partials.codpes-remover-btn', ['codpes' => $user->codpes])
                      </span>
                    </div>
                  @endforeach
                </div>
              </form>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <form method="post" id="cc" action="{{ route('roles.update', 'cc') }}">
          @csrf
          @method('put')
          <div class="card-header py-1">
            <span class="h5">
              Coordenadores de cursos (CC)
              @include('disciplinas.partials.codpes-adicionar-btn')
            </span><br>
            <span class="text-secondary">
              Os coordenadores podem cadastrar as habilidades e competências dos cursos, bem como as pessoas em CG.
            </span>
          </div>
          <div class="card-body py-1">
            @foreach ($roleCC->users->sortBy('name') as $user)
              <div class="hover">
                <span>{{ $user->name }}</span>
                <span class="hide">
                  @include('disciplinas.partials.codpes-remover-btn', ['codpes' => $user->codpes])
                </span>
              </div>
            @endforeach
          </div>
        </form>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <form method="post" id="cg" action="{{ route('roles.update', 'cg') }}">
          @csrf
          @method('put')
          <div class="card-header py-1">
            <span class="h5">
              Comissão de graduação (CG)
              @include('disciplinas.partials.codpes-adicionar-btn')
            </span><br>
            <span class="text-secondary">
              Os nomes na função CG tem acesso a todos os relatórios do sistema e acesso atodas as disciplinas da Unidade.
            </span>
          </div>
          <div class="card-body py-1">
            @foreach ($roleCG->users->sortBy('name') as $user)
              <div class="hover">
                <span>{{ $user->name }}</span>
                <span class="hide">
                  @include('disciplinas.partials.codpes-remover-btn', ['codpes' => $user->codpes])
                </span>
              </div>
            @endforeach
          </div>
        </form>
      </div>
    </div>

  </div>
@endsection
