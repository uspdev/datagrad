@extends('layouts.app')

@section('styles')
  @parent
  <style>
    td {
      max-width: 200px;
      white-space: pre-wrap;
    }

    .c1,
    .c2,
    .c4,
    .c6 {
      width: 50px;
    }

    .c5 {
      width: 70px;
    }
  </style>
@endsection

@section('content')
  @include('grad.partials.curso-menu', [
      'view' => 'Grade (' . substr($curso['codcrl'], -3, -1) . '/' . substr($curso['codcrl'], -1) . ')',
  ])
  <div>
    Curso: Ini: {{ formatarData($curso['dtaatvcur']) }} Fim: {{ formatarData($curso['dtadtvcur']) }}
    | Habilitação: Ini: {{ formatarData($curso['dtaatvhab']) }} Fim: {{ formatarData($curso['dtadtvhab']) }}
    | Currículo: Ini: {{ formatarData($curso['dtainicrl']) }} Fim: {{ formatarData($curso['dtafimcrl']) }};
    Horas total:
    <span class="btn-link" title="Carga horária total do curso (soma de todas as cargas horárias exceto a de estágio).">
      {{ $curso['cgahortot'] }}</span>;
    Horas AAC:
    <span class="btn-link"
      title="Carga horária total obrigatória de Atividades Acadêmicas Complementares (AAC) no currículo (1.a fase da RESOLUÇÃO CoG, CoCEx e CoPq Nº 7788, DE 26 DE AGOSTO DE 2019)">
      {{ $curso['cgahorobgaac'] }}
    </span>
    @if (!empty($curso['obscrl']))
      <button class="btn btn-link toggle-obscrl" title="Há observações do currículo: clique para ver."><i
          class="fas fa-info-circle"></i></button>
    @endif
  </div>
  <div class="obscrl alert-info toggle-obscrl" style="display:none">
    <b>Observações do currículo</b><br>
    {!! $curso['obscrl'] !!}
  </div>
  <hr />
  <table class="table table-sm table-striped table-hover datatable-simples dt-fixed-header dt-buttons">
    <thead class="thead-light">
      <tr>
        <th class="c1">Sem. ideal</th>
        <th class="c2">Código</th>
        <th>Nome</th>
        <th>Nome (en)</th>
        <th class="c4">Versão</th>
        <th class="c4">Data Ativação</th>
        <th class="c5">Tipo</th>
        <th class="c6">Créditos aula/trab</th>
        <th class="c6 text-center" title="Possui atividade de extensão cadastrada">Atv. extensionista</th>
        <th>Objetivos</th>
        <th>Objetivos (en)</th>
        <th>Programa Resumido</th>
        <th>Programa Resumido (en)</th>
        <th>Programa</th>
        <th>Programa (en)</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($disciplinas as $d)
        <tr>
          <td>{{ $d['numsemidl'] }}</td>
          <td><a href="{{ route('disciplinas.show', $d['coddis']) }}">{{ $d['coddis'] }}</a></td>
          <td>{{ $d['nomdis'] }}</td>
          <td><div class="text-info">{{ $d['nomdisigl'] }}</div></td>
          <td class="text-center">{{ $d['verdis'] }}</td>
          <td class="text-center">{{ formatarData($d['dtaatvdis']) }}</td>
          <td>{{ App\Replicado\Graduacao::$tipobg[$d['tipobg']] }}</td>
          <td class="text-center">{{ $d['creaul'] }}/{{ $d['cretrb'] }}</td>
          <td class="text-center">{{ $d['cgahoratvext'] ? $d['cgahoratvext'] : 'Não' }}</td>
          <td class="text-truncate">{!! $d['objdis'] !!}</div></td>
          <td class="text-truncate"><div class="text-info">{!! $d['objdisigl'] !!}</div></td>
          <td class="text-truncate">{!! $d['pgmrsudis'] !!}</td>
          <td class="text-truncate"><div class="text-info">{!! $d['pgmrsudisigl'] !!}</div></td>
          <td class="text-truncate">{!! $d['pgmdis'] !!}</td>
          <td class="text-truncate"><div class="text-info">{!! $d['pgmdisigl'] !!}</div></td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection

@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {
      $('.toggle-obscrl').on('click', function() {
        $('.obscrl').toggle()
      })

      // expande/contrai uma coluna
      $('tr').on('click', function() {
        $(this).find('td').toggleClass('text-truncate')
      })
    })
  </script>
@endsection
