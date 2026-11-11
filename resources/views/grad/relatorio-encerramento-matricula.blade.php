@extends('layouts.app')

@section('content')
  <div class="h4 my-3">Relatório de encerramento de matrículas</div>
  <div class="mb-3">
        Lista os alunos com matrícula encerrada no ano selecionado de um determinado curso.
  </div>
  <div class="mb-3">
        Selecione o curso e ano
  </div>

  <form method="POST" action="{{ route('graduacao.relatorio.encerramento-matricula.post') }}" class="form-inline mb-3">
    @csrf
    <label class="mr-2" for="codcur">Curso</label>
    <select name="codcur" id="codcur" class="form-control mr-3" required>
      <option value="">-- Selecione --</option>
      @foreach ($cursos as $c)
        <option value="{{ $c['codcur'] }}" @selected(old('codcur', request('codcur')) == $c['codcur'])>
          ({{ $c['codcur'] }}) {{ $c['nomcur'] }}
        </option>
      @endforeach
    </select>

    <label class="mr-2" for="ano">Ano de desligamento</label>
    <select name="ano" id="ano" class="form-control mr-3" required>
      @foreach ($anos as $a)
        <option value="{{ $a }}" @selected(old('ano', request('ano')) == $a)>{{ $a }}</option>
      @endforeach
    </select>

    <button type="submit" class="btn btn-primary">Gerar</button>
  </form>

  @if (!is_null($alunos))
    <br>
    <div class="mb-2 alert alert-primary" role="alert">
        <b>{{ count($alunos) }}</b> matrícula(s) encerrada(s) em {{ request('ano') }}.
    </div>
    <br> 
    
    <table class="table table-bordered table-hover table-sm datatable-simples dt-buttons dt-fixed-header">
      <thead>
        <tr>
          <th>Nº USP</th>
          <th>Nome</th>
          <th>Ano de ingresso</th>
          <th>Tipo de ingresso</th>
          <th>Motivo do desligamento</th>
          <th>Desligamento (ano/sem.)</th>
          <th>Status atual</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($alunos as $a)
          <tr>
            <td>{{ $a['numero_usp'] }}</td>
            <td>{{ $a['nome_aluno'] }}</td>
            <td>{{ $a['ano_ingresso'] }}</td>
            <td>{{ $a['tipo_ingresso'] }}</td>
            <td>{{ $a['motivo_desligamento'] }}</td>
            <td>{{ $a['ano_desligamento'] }}/{{ $a['semestre_desligamento'] }}</td>
            <td>
              @php
                  $status = $statusPgm[$a['status_atual']] ?? $a['status_atual'];
                  $badgeClass = match($a['status_atual']) {
                      'E' => 'badge-danger',      // Vermelho para Encerrado
                      'A' => 'badge-success',     // Verde para Ativo
                      'T' => 'badge-warning',     // Amarelo/Laranja para Trancado
                      'S' => 'badge-secondary',   // Cinza para Suspenso
                      'P' => 'badge-info',        // Azul para Pendente
                      'R' => 'badge-primary',     // Azul escuro para Reativado
                      default => 'badge-dark',
                  };
              @endphp

              <span class="badge {{ $badgeClass }} p-2" style="font-size: 0.85rem;">
                  {{ $status }}
              </span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center">Nenhuma matrícula encerrada encontrada para os filtros.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  @endif
@endsection