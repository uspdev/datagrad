@extends('layouts.app')

@section('content')
  @include('disciplinas.partials.index-navbar')
  <div class="alert alert-info">
    Se em um campo houver <b>AA <span class="text-danger">→ BB</span></b>, <b>AA</b> é o valor do Júpiter e <b><span
        class="text-danger">BB</span></b> é o valor proposto.<br>
    Os dados aqui apresentados correspondem às versões <b>vigentes</b> das diciplinas.<br>
    Ao clicar no nome da disciplina e acessar os detalhes, será mostrado a versão <b>mais recente</b>, que pode ser
    diferente da versão vigente.
    Nesse caso a versão vigente pode ser consultada no <b>Júpiter Web</b>.

  </div>
  <table class="table table-sm table-bordered datatable-simples dt-fixed-header dt-buttons dt-state-save">
    <thead>
      <tr>
        <th>Código</th>
        <th style="min-width: 170px;">Estado</th>
        <th style="min-width: 300px;">Nome</th>
        <th>Cred. Aula</th>
        <th>Cred. Trab.</th>
        <th>AEx (horas)</th>
        <th title="Viagem didática">V.Didat.</th>
        <th style="min-width: 20px;" title="Habilidades e competências">H&C</th>
        <th>Situação</th>
        <th>Versão</th>
        <th>Data ativação</th>
        <th>Data últ. alteração</th>
        <th>Responsáveis</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($discs as $disc)
        <tr>
          <td>
            {{ $disc->coddis }}
            {{-- @include('disciplinas.partials.badge-origem-disciplina') --}}
          </td>
          <td style="min-width: 170px;" data-order="{{ $disc?->obterEstadoConfig()['order'] ?? '' }}">
            @include('disciplinas.partials.index-estado')
          </td>
          <td><a href="{{ route('disciplinas.show', $disc->coddis) }}">{{ $disc->nomdis }}</a></td>
          <td class="text-center">
            {{ $disc->dr['creaul'] ?? 'n/a' }}
            @if ($disc->creaul && ($disc->dr['creaul'] ?? null) != $disc->creaul)
              <span class="text-danger">→ {{ $disc->creaul }}</span>
            @endif
          </td>
          <td class="text-center">
            {{ $disc->dr['cretrb'] ?? 'n/a' }}
            @if ($disc->cretrb && ($disc->dr['cretrb'] ?? null) != $disc->cretrb)
              <span class="text-danger">→ {{ $disc->cretrb }}</span>
            @endif
          </td>
          <td class="text-center">
            {{ $disc->dr['cgahoratvext'] ?? '' }}
            @if ($disc->cgahoratvext && ($disc->dr['cgahoratvext'] ?? null) != $disc->cgahoratvext)
              <span class="text-danger">→ {{ $disc->cgahoratvext }}</span>
            @endif
          </td>
          <td class="text-center"> {{-- Viagem didática --}}
            @if (($disc->dr['stavgmdid'] ?? '') == 'S')
              {{ $disc->dr['staetr'] == 'S' ? 'Estruturante' : 'Não estrut.' }}
            @endif
          </td>
          <td class="text-center">
            {{ $disc->habilidades ? 'Sim' : '' }}
          </td>
          <td class="text-center">{{ $disc->dr['sitdistxt'] ?? 'n/a' }}</td>
          <td class="text-center">
            {{ $disc->dr['verdis'] ?? 'n/a' }}
          </td>
          <td data-order={{ $disc->dr['dtaatvdis'] ?? '' }}>
            @date($disc->dr['dtaatvdis'] ?? 'n/a')
          </td>
          <td data-order={{ $disc->dr['dtaultalt'] ?? '' }}>
            @date($disc->dr['dtaultalt'] ?? 'n/a')
          </td>
          <td>
            {{ $disc->retornarResponsaveisDr() }}
            @if ($disc->retornarResponsaveisDr() != $disc->retornarResponsaveisLocal())
              <span class="text-danger">→ {{ $disc->retornarResponsaveisLocal() }}</span>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
