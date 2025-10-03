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
      @include('disciplinas.partials.ajuda-modal')
    </div>
  </div>

  <table class="table table-sm table-bordered datatable-simples dt-fixed-header dt-buttons">
    <thead>
      <tr>
        <th>Código</th>
        <th style="min-width: 100px;">Estado</th>
        <th style="min-width: 300px;">Nome</th>
        <th>Cred. Aula</th>
        <th>Cred. Trab.</th>
        <th>Ativ. ext. (horas)</th>
        <th>Viagem didática (VD)</th>
        <th>Habilidades e competências</th>
        <th>Situação</th>
        <th>Últ. versão</th>
        <th>Data últ. alteração</th>
        <th>Responsáveis</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($discs as $disc)
        <tr>
          <td>
            {{ $disc->coddis }}
          </td>
          <td>@include('disciplinas.partials.index-estado')
          </td>
          <td>
            <a href="{{ route('disciplinas.show', $disc->coddis) }}">{{ $disc->nomdis }}</a>
          </td>
          <td>{{ $disc->creaul }}</td>
          <td>{{ $disc->cretrb }}</td>
          <td>{{ $disc->atividade_extensionista ? $disc->cgahoratvext : '' }}</td>
          <td>{{ $disc->stavgmdid == '1' ? 'Sim' : '' }}</td>
          <td>{{ $disc->habilidades ? 'Sim' : '' }}</td>
          <td>{{ $disc->dr['sitdistxt'] ?? '-' }}</td>
          <td>{{ $disc->dr['verdis'] ?? '' }}</td>
          <td data-order={{ $disc->dr['dtaultalt'] ?? '' }}>
            {{ formatarData($disc->dr['dtaultalt'] ?? '') }}
          </td>
          <td>
            {{ $disc->retornarListaResponsaveis() }}
          </td>
          {{-- @if (isset($disc->dr['sitdis']) && $disc->dr['sitdis'] == 'AP') @dd($disc)@endif --}}
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
