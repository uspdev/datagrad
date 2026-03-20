<div class="card">
  <div class="card-header h4 card-header-sticky">
    Resultados
    @include('grad.partials.ocultar-col-5-btn')
  </div>
  <div class="card-body">

    <table
      class="table table-sm table-bordered table-hover datatable-simples dt-fixed-header dt-buttons dt-buttons-visible">
      <thead>
        <tr>
          <th>Número USP</th>
          <th>Nome</th>
          <th>MS turmas</th>
          <th>MS horas teo</th>
          <th>MS horas pra</th>
          {{-- <th>MS créditos teo</th> --}}
          {{-- <th>MS créditos pra</th> --}}
          <th>turmas</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($pessoas as $pessoa)
          <tr>
            <td>{{ $pessoa['codpes'] }}</td>
            <td>{{ $pessoa['nome'] }}</td>
            <td class="text-center">{{ round($pessoa['mediaTurmas'], 2) }}</td>
            <td class="text-center">{{ round($pessoa['mediaHorasTeorica'], 2) }}</td>
            <td class="text-center">{{ round($pessoa['mediaHorasPratica'], 2) }}</td>
            {{-- <td class="text-center">{{ round($pessoa['mediaHorasTeorica'] / 15, 2) }}</td> --}}
            {{-- <td class="text-center">{{ round($pessoa['mediaHorasPratica'] / 15, 2) }}</td> --}}
            <td class="text-secondary">
              <table class="table-sm">
                <tr>
                  <th>codtur</th>
                  <th>disciplina</th>
                  <th>ministrantes</th>
                  <th>semanal</th>
                  <th>periodo aula</th>
                  <th>horas teo/pra</th>
                  <th>fração teo/pra</th>
                  <th>vagas/matr</th>
                  <th>Atividade</th>
                  <th>Semanas</th>
                  <th>Horas</th>
                </tr>
                @foreach ($pessoa['turmas'] as $key => $turma)
                  <tr>
                    <td>{{ $turma['codtur'] }}</td>
                    <td>{{ $turma['coddis'] }} - {{ $turma['nomdis'] }}</td>
                    <td>{{ $turma['ministrantes'] }}</td>
                    <td>
                      {{ $turma['stamis'] == 'N' ? 'semanal' : 'quinzenal' }}
                    </td>
                    <td>{{ $turma['dtainitur'] }} a {{ $turma['dtafimtur'] }}</td>
                    <td>{{ $turma['cgahorteo'] }}/{{ $turma['cgahorpra'] }}</td>
                    <td>{{ $turma['frateo'] ?? '' }}/{{ $turma['frapra'] ?? '' }}</td>
                    <td>{{ $turma['numvagtot'] }}/{{ $turma['nummtrtot'] }}</td>
                    <td>{{ $turma['nomatv'] }}</td>
                    <td>{{ $turma['semanas'] }}</td>
                    <td>{{ $turma['diasmnocp'] }} {{ $turma['horent'] }} {{ $turma['horsai'] }}
                      <i class="fas fa-caret-right"></i> {{ $turma['horas'] }}
                    </td>
                  </tr>
                @endforeach
              </table>
            </td>
          </tr>
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

    <div class="alert alert-info d-print-none">
      <b>Observações</b><br>
      MS = média semestral<br>
      A média semestral de horas/créditos leva em conta a carga horária nominal da turma. <br>
      Os créditos são divididos pelo número de ministrantes.<br>
      Os créditos são divididos por 2 (dois) se a disciplina for quinzenal.<br>
      A fração teórica/prática mostra a fração das horas que são contabilizadas para a média de horas do docente.
      Ela é obtida dividindo-se as horas da turma pelo número de ministrantes e divindindo por 2 se for qunzenal.
      Supõe-se que os docentes não permanecam simultaneamente em sala de aula o que pode não ser verdade.<br>
      Docentes ativos não incluem sêniores.
    </div>

  </div>
</div>
