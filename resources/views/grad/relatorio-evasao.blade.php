@extends('layouts.app')

@section('content')

  @isset($formRequest)
    <h4>
      <a href="{{ route('graduacao.relatorio.evasao') }}">Relatório de Evasão</a>
      <i class="fas fa-angle-right"></i> {{ $formRequest['codcur'] }} - {{ $formRequest['nomcur'] }}
    </h4>
  @else
    <h4>
      @if (isset($taxaEvasao))
        <a href="{{ route('graduacao.relatorio.evasao') }}">Relatório de Evasão</a>
        <i class="fas fa-angle-right"></i> Todos os Cursos
      @else
        Relatório de Evasão
      @endif
    </h4>
  @endisset

  @include('grad.partials.evasao-form')

  @isset($taxaEvasao)
    <img src="data:image/png;base64, {{ $imagemEvasao }}" alt="Gráfico em base64">
    <br>
    <table class="table table-bordered table-hover">
      <tr>
        <th>Ano</th>
        <th>Permanência (%)</th>
        <th>Desistência (%)</th>
        <th>Conclusão (%)</th>
      </tr>
      @foreach ($taxaEvasao as $ano => $taxa)
        <tr>
          <td>{{ $ano }}</td>
          <td> {{ $taxa['txPermanencia'] }}</td>
          <td> {{ $taxa['txDesistenciaAcc'] }}</td>
          <td> {{ $taxa['txConclusaoAcc'] }}</td>
        </tr>
      @endforeach
    </table>
  @endisset
@endsection
