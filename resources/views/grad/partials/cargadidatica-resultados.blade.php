<div class="card">
  <div class="card-header h4">
    Resultados
    {{-- @include('grad.partials.ocultar-col-5-btn') --}}
  </div>
  <div class="card-body">

    <table
      class="table table-sm table-bordered table-hover datatable-simples dt-fixed-header dt-buttons dt-buttons-visible">
      <thead>
        <tr>
          <th>Nome</th>
          <th>fração TEO</th>
          <th>fração PRA</th>

          <th>codtur</th>
          <th>disciplina</th>
          <th>ministrantes</th>
          <th>semanal</th>
          <th>horas teo</th>
          <th>horas pra</th>
          <th>vagas/matr</th>
          <th>Atividade</th>
          <th>Horas</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($pessoas as $pessoa)
          @foreach ($pessoa['turmas'] as $key => $turma)
            <tr>
              <td>{{ $pessoa['nome'] }}</td>
              <td>{{ $turma['frateo'] ?? '' }}</td>
              <td>{{ $turma['frapra'] ?? '' }}</td>

              <td>{{ $turma['codtur'] }}</td>
              <td>{{ $turma['coddis'] }} - {{ $turma['nomdis'] }}</td>
              <td>{{ $turma['ministrantes'] ?? '' }}</td>
              <td>{{ $turma['stamis'] == 'N' ? 'semanal' : 'quinzenal' }}</td>
              <td>{{ $turma['cgahorteo'] }}</td>
              <td>{{ $turma['cgahorpra'] }}</td>
              <td>{{ $turma['numvagtot'] }}/{{ $turma['nummtrtot'] }}</td>
              <td>{{ $turma['nomatv'] }}</td>
              <td>{!! $turma['horafmt'] ?? '' !!}</td>
            </tr>
          @endforeach
        @endforeach
      </tbody>
    </table>

    <div class="alert alert-secondary bg-white">
      <div class="row">

        <div class="col-sm-4">
          <div class="h5">Totais</div>
          <div>Total de créditos teórico: {{ round($totalHorasTeoricas / 15, 2) }}</div>
          <div>Total de créditos prático: {{ round($totalHorasPraticas / 15, 2) }}</div>
          <div>Total de créditos: {{ round($totalHorasTeoricas / 15 + $totalHorasPraticas / 15, 2) }}</div>
          <div>Total de turmas: {{ $totalTurmas }}</div>
          <br>

          @if (!empty($disciplinasExcluidas))
            <div class="h5">Disciplinas não computadas nos totais</div>
            @forelse ($disciplinasExcluidas as $d)
              <div>{{ $d['coddis'] }} - {{ $d['nomdis'] }}</div>
            @empty
            @endforelse
          @endif
        </div>

        @if ($docentesSetor)
          <div class="col-sm-4">
            <div class="h5">Docentes ativos <span
                class="badge badge-primary badge-pill">{{ count($docentesSetor) }}</span></div>
            @foreach ($docentesSetor as $docente)
              {{ $docente['nompes'] }}<br>
            @endforeach
          </div>
        @endif

        @if ($semCargaDidatica)
          <div class="col-sm-4">
            <div class="h5">Docentes sem carga didática <span
                class="badge badge-primary badge-pill">{{ count($semCargaDidatica) }}</span></div>
            @forelse ($semCargaDidatica as $nome)
              {{ $nome }}<br>
            @empty
            @endforelse
          </div>
        @endif

      </div>
    </div>

    <div class="alert alert-info">
      <b>Observações</b><br>
      Fração TEO e Fração PRA correspondem à fração de horas da disciplina que contribui para a carga didática do docente.
      A fração corresponde à carga horária da disciplina dividido pelo número de MINISTRANTES e dividido por 2 se for QUINZENAL.
      {{-- MS = média semestral<br>
      A média semestral de horas/créditos leva em conta a carga horária nominal da turma. <br> --}}
      {{-- Os créditos são divididos pelo número de ministrantes.<br>
      Os créditos são divididos por 2 (dois) se a disciplina for quinzenal.<br>
      A fração teórica/prática mostra a fração das horas que são contabilizadas para a média de horas do docente.
      Ela é obtida dividindo-se as horas da turma pelo número de ministrantes e divindindo por 2 se for qunzenal. --}}
      Supõe-se que os docentes não permanecam simultaneamente em sala de aula o que pode não ser verdade.<br>
      Docentes ativos não incluem sêniores mas incluem temporários e afastados. Os ativos são um retrato atual e pode não refletir o período considerado.<br>
      Nos docentes sem carga didática podem aparecer aposentados antigos.
    </div>

  </div>
</div>
