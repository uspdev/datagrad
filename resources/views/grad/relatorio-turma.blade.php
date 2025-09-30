@extends('layouts.app')

@section('content')

  @isset($formRequest)
    <h4>
      <a href="{{ route('graduacao.relatorio.turma') }}">Resultados da disciplina</a>
      {{ $formRequest['disciplina'] }}
      <i class="fas fa-angle-right"></i>
      de {{ $formRequest['anoInicio'] }} a {{ $formRequest['anoFim'] }}
    </h4>
  @else
    <h4>
      Resultados da disciplina
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

  @include('grad.partials.turma-form')

  @isset($resultadosTurmas)
    <table class="table table-sm table-hover datatable-simples dt-fixed-header">
      <thead class="thead-light">
        <tr>
          <th>Turma</th>
          <th>Ministrante</th>
          <th>Matriculados</th>
          <th>Aprovados</th>
          <th>Reprovados</th>
          <th>De recuperação</th>
          <th>Aprovados na recuperação</th>
          <th>Reprovados na recuperação</th>
          <th>Média</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($resultadosTurmas as $resultado)
            <tr>
              <td>{{ $resultado['codigoTurma'] }}</td>
              <td>{{ $resultado['ministrante'] }}</td>
              <td>{{ $resultado['matriculados'] }}</td>
              <td>{{ $resultado['aprovados'] }}</td>
              <td>{{ $resultado['reprovados'] }}</td>
              <td>{{ $resultado['recuperacao'] }}</td>
              <td>{{ $resultado['recuperacaoAprovados'] }}</td>
              <td>{{ $resultado['recuperacaoReprovados'] }}</td>
              <td>{{ round((float)$resultado['mediaTurma'], 2) }}</td>
            </tr>
        @endforeach
      </tbody>
    </table>
  @endisset

@endsection
