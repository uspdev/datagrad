@extends('layouts.app')

@section('content')
  <h4>Relatório grade horária</h4>
  <div>
    Lista a grade horária para a lista informada do semestre corrente.
  </div>

  <form method="POST" action="">
    @csrf
    <div class="form-group">
      <label for="nuspsTextarea">Forneça uma lista de números USP (1 por linha)</label>
      <textarea name="nusps" class="form-control" id="nuspsTextarea" rows="4">{{ old('nusps') }}</textarea>
    </div>
    <button type="submit" class="btn btn-sm btn-primary spinner mt-3">Enviar</button>
  </form>

  @if ($naoEncontrados)
    <hr>
    <div class="h4">Não encontrados</div>
    @foreach ($naoEncontrados as $nome)
      {{ $nome }}<br>
    @endforeach
  @endif

  @if ($horarios)
    <hr>
    <div class="h4 mt-3">Resultados</div>
    <table class="table table-bordered table-hover table-sm datatable-simples dt-buttons dt-fixed-header">
      <thead class="thead-light">
        <tr>
          <th>Nº USP</th>
          <th>Nome</th>
          <th>Cod. Disciplina</th>
          <th>Cod. Turma</th>
          <th>Ministrante</th>
          <th>Dia da Semana</th>
          <th>Hora de Início</th>
          <th>Hora de Término</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($horarios as $horario)
          <tr>
            <td>{{ $horario['codpes'] }}</td>
            <td>{{ $horario['nome'] }}</td>
            <td>{{ $horario['coddis'] }}</td>
            <td>{{ $horario['codtur'] }}</td>
            <td>{{ $horario['nompesmin'] }}</td>
            <td>{{ $horario['diasmnocp'] }}</td>
            <td>{{ $horario['horent'] }}</td>
            <td>{{ $horario['horsai'] }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

@endsection
