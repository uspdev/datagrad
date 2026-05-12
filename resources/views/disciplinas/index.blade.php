@extends('layouts.app')

@section('content')
  @include('disciplinas.partials.index-navbar')
  <table class="table table-sm table-bordered datatable-simples dt-fixed-header dt-buttons dt-state-save">
    <thead>
      <tr>
        <th>Código</th>
        <th style="min-width: 170px;">Estado</th>
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
          <td>{{ $disc->coddis }}</td>
          <td style="min-width: 170px;" data-order="{{ $disc?->obterEstadoConfig()['order'] ?? '' }}">
            @include('disciplinas.partials.index-estado')
          </td>
          <td><a href="{{ route('disciplinas.show', $disc->coddis) }}">{{ $disc->nomdis }}</a></td>
          <td>{{ $disc->creaul }}</td>
          <td>{{ $disc->cretrb }}</td>
          <td>{{ $disc->atividade_extensionista ? $disc->cgahoratvext : '' }}</td>
          <td>{{ $disc->stavgmdid == '1' ? 'Sim' : '' }}</td>
          <td>{{ $disc->habilidades ? 'Sim' : '' }}</td>
          <td>{{ $disc->dr['sitdistxt'] ?? '-' }}</td>
          <td>{{ $disc->dr['verdis'] ?? '' }}</td>
          <td data-order={{ $disc->dr['dtaultalt'] ?? '' }}>@date($disc->dr['dtaultalt'] ?? '')</td>
          <td>{{ $disc->retornarResponsaveis() }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
