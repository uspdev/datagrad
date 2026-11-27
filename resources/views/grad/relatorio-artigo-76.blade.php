@extends('layouts.app')

@section('content')
  <div class="h4 my-3">Relatório artigo 76</div>

  <p class="text-muted">
    Alunos em situação de desligamento: menos de 20% de créditos aprovados nos últimos 2 semestres
    com matrícula até o semestre de apuração.
  </p>

  <form method="POST" action="{{ route('graduacao.relatorio.artigo-76.post') }}" class="form-inline mb-3">
    @csrf
    <label class="mr-2" for="ano">Semestre de apuração</label>
    <select name="ano" id="ano" class="form-control mr-2" required>
      @foreach ($anos as $a)
        <option value="{{ $a }}" @selected(old('ano', request('ano', $anoPadrao)) == $a)>{{ $a }}</option>
      @endforeach
    </select>
    <select name="semestre" id="semestre" class="form-control mr-3" required>
      @foreach ([1, 2] as $s)
        <option value="{{ $s }}" @selected(old('semestre', request('semestre', $semestrePadrao)) == $s)>{{ $s }}º semestre</option>
      @endforeach
    </select>

    <button type="submit" class="btn btn-primary">Gerar</button>
  </form>

  @if (!is_null($alunos))
    <div class="mb-2">
      <b>{{ count($alunos) }}</b> aluno(s) no semestre de apuração
      {{ substr($semAtual, 0, 4) }}/{{ substr($semAtual, 4, 1) }}.
    </div>
    <hr>

    <div class="table-responsive">
      <table class="table table-bordered table-sm table-striped datatable-simples dt-buttons dt-fixed-header">
        <thead class="text-center">
          <tr>
            <th rowspan="2" class="text-center">Nº USP</th>
            <th rowspan="2" class="text-center">Aluno</th>
            <th rowspan="2" class="text-center">E-mail do aluno</th>
            <th rowspan="2" class="text-center">Professor tutor</th>
            <th rowspan="2" class="text-center">E-mail do tutor</th>
            <th rowspan="2" class="text-center">Ingresso</th>
            <th colspan="3" class="text-center">Créditos no curso</th>
            <th colspan="3" class="text-center">Créditos considerados</th>
            <th rowspan="2" class="text-center">20%</th>
            <th rowspan="2" class="text-center">Primeiro sem. considerado</th>
          </tr>
          <tr>
            <th class="text-center">Matric.</th>
            <th class="text-center">Aprov.</th>
            <th class="text-center">Disp.</th>
            <th class="text-center">Matric.</th>
            <th class="text-center">Aprov.</th>
            <th class="text-center">Disp.</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($alunos as $a)
            <tr>
              <td>{{ $a['numusp'] }}</td>
              <td>{{ $a['aluno'] }}</td>
              <td>{{ $a['email_aluno'] }}</td>
              <td>{{ $a['professor_tutor'] }}</td>
              <td>{{ $a['email_tutor'] }}</td>
              <td>{{ $a['ingresso'] }}</td>
              <td>{{ $a['curso_matric'] }}</td>
              <td>{{ $a['curso_aprov'] }}</td>
              <td>{{ $a['curso_disp'] }}</td>
              <td>{{ $a['consid_matric'] }}</td>
              <td>{{ $a['consid_aprov'] }}</td>
              <td>{{ $a['consid_disp'] }}</td>
              <td>{{ $a['vinte_pct'] }}</td>
              <td>{{ $a['primeiro_sem_considerado'] }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="14" class="text-center">Nenhum aluno encontrado para o semestre informado.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  @endif
@endsection