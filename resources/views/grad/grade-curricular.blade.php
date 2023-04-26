@extends('layouts.app')

@section('content')
  @include('grad.partials.curso-menu', ['view' => 'Grade'])
  <hr />

  <table class="table table-sm table-hover datatable-simples dt-fixed-header dt-buttons">
    <thead class="thead-light">
      <tr>
        <th>Semestre ideal</th>
        <th>Código</th>
        <th>Nome</th>
        <th>Versão</th>
        <th>Tipo</th>
        <th>Créditos aula/trab</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($disciplinas as $d)
        <tr>
          <td>{{ $d['numsemidl'] }}</td>
          <td>{{ $d['coddis'] }}</td>
          <td>{{ $d['nomdis'] }}</td>
          <td>{{ $d['verdis'] }}</td>
          <td>{{ App\Replicado\Graduacao::$tipobg[$d['tipobg']] }}</td>
          <td>{{ $d['creaul'] }}/{{ $d['cretrb'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
