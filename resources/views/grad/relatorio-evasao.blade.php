@extends('layouts.app')

@section('content')

  @isset($formRequest)
    <h4>
      <a href="{{ route('graduacao.relatorio.evasao') }}">Relatório de evasão</a>
      <i class="fas fa-angle-right"></i>
      {{ $formRequest['codcur'] == 18 ? $formRequest['nomcur'] : "{$formRequest['codcur']} - {$formRequest['nomcur']}" }}
    </h4>
  @else
    <h4>
      Relatório de evasão
    </h4>
  @endisset

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
         @foreach ($errors->all() as $error)
           <li>{{ $error }}</li>
         @endforeach
       </ul>
    </div>
  @endif

  @include('grad.partials.evasao-form')

  @isset($taxaEvasao)
    <img src="data:image/png;base64, {{ $imagemEvasao }}" alt="Gráfico em base64">
    <br>
    <table class="table table-bordered table-hover">
      <tr>
        <th>Ano</th>
        <th>Taxa de permanência (%)</th>
        <th>Taxa de evasão acumulada (%)</th>
        <th>Taxa de conclusão acumulada (%)</th>
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
