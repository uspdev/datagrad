@extends('layouts.app')

@section('content')
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
@endsection
