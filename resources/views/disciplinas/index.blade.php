@extends('layouts.app')

@section('styles')
  @parent
  <style>
    .navbar-index {
      background-color: #d6eeff;
    }

    .navbar-index-cg {
      background-color: bisque;
      border-bottom: 3px solid red;
    }
  </style>
@endsection

@section('content')
  <div
    class="navbar navbar-light card-header-sticky justify-content-between mb-3 {{ $visao == 'docente' ? 'navbar-index' : 'navbar-index-cg' }}">
    <div class="h5">
      {{ $visao == 'docente' ? 'Minhas' : '' }}
      Disciplinas
      {{ $visao == 'cg' ? 'CG' : '' }}
      {{ $visao == 'departamento' ? 'com prefixo(s) ' . Auth::user()->prefixos()->implode(', ') : '' }}
    </div>

    <div class="form-inline">
      @include('disciplinas.partials.visoes-index')
      @include('disciplinas.partials.consultar-form')
      <button class="btn btn-sm btn-info ml-2">Ajuda <i class="fas fa-question"></i></button>
    </div>
  </div>

  <table class="table table-sm table-bordered datatable-simples dt-fixed-header dt-buttons">
    <thead>
      <tr>
        <th>Código</th>
        <th style="min-width: 70px;">Estado</th>
        <th style="min-width: 300px;">Nome</th>
        <th>Cred. Aula</th>
        <th>Cred. Trab.</th>
        <th>Ativ. ext. (horas)</th>
        <th>Responsáveis</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($discs as $disc)
        <tr>
          <td>
            {{ $disc->coddis }}
          </td>
          <td>
            @if (isset($disc->estado) && $disc->estado == 'editar')
              <a href="{{ route('disciplinas.edit', $disc->coddis) }}" class="btn btn-sm btn-outline-warning py-0">
                Em edição
              </a>
            @elseif(isset($disc->estado) && $disc->estado == 'criar')
              <a href="{{ route('disciplinas.edit', $disc->coddis) }}" class="btn btn-sm btn-outline-success py-0">
                Em criação
              </a>
            @elseif(isset($disc->estado) && $disc->estado == 'finalizado')
              <span class="text-secondary">Finalizado</span>
            @endif
          </td>
          <td>
            <a href="{{ route('disciplinas.show', $disc->coddis) }}">{{ $disc->nomdis }}</a>
          </td>
          <td>{{ $disc->creaul }}</td>
          <td>{{ $disc->cretrb }}</td>
          <td>{{ $disc->cgahoratvext }}</td>
          <td>
            {{ $disc->retornarListaResponsaveis() }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
