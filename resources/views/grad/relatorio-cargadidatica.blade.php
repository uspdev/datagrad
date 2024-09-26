@extends('layouts.app')

@section('content')

  <h4>Relatório carga didática</h4>

  <form method="POST" action="">
    @csrf
    <div class="form-group">
      <label for="nomesTextarea">Forneça uma lista de nomes (1 por linha)</label>
      <textarea name="nomes" class="form-control" id="nomesTextarea" rows="4">{{ old('nomes') }}</textarea>
    </div>

    <div class="mt-3 ml-3">
      <div class="border border-primary rounded d-inline-flex py-1">
        <div class="mx-2"><b>Semestre</b>: de</div>
        <select class="border-0 input-small font-weight-bold" name="semestreIni" id="select-semestre-ini">
          @foreach ($turmaSelect as $t)
            <option {{ $t == $semestreIni ? 'selected' : '' }}>{{ $t }}</option>
          @endforeach
        </select>
        <div class="mx-2">a</div>
        <select class="border-0 input-small font-weight-bold" name="semestreFim" id="select-semestre-fim">
          @foreach ($turmaSelect as $t)
            <option {{ $t == $semestreFim ? 'selected' : '' }}>{{ $t }}</option>
          @endforeach
        </select>
        <div class="mx-2"></div>
        {{-- <button class="btn btn-sm btn-primary d-none btn-spinner py-0 mr-2" type="submit">OK</button> --}}
      </div>
      <div class="d-inline-flex" style="padding-top: 7px;">
        <button type="submit" class="btn btn-sm btn-primary spinner m-0">Enviar</button>
      </div>
    </div>

    <div class="my-2"></div>
  </form>

  @if ($naoEncontrados)
    <hr>
    <div class="h4">Não encontrados</div>
    @foreach ($naoEncontrados as $nome)
      {{ $nome }}<br>
    @endforeach
  @endif

  <div class="card">
    <div class="card-header h4">
      Resultados
    </div>
    <div class="card-body">
      <div class="row">

        <div class="col-md-4">
          <div class="h5">Sem carga didática</div>
          @forelse ($semCargaDidatica as $nome)
            {{ $nome }}<br>
          @empty
          @endforelse
        </div>

        <div class="col-md-4">
          <div class="h5">Totais</div>
          <div>Total de horas teóricas/15 semanas: {{ $totalHorasTeoricas / 15 }}</div>
          <div>Total de horas práticas/15 semanas: {{ $totalHorasPraticas / 15 }}</div>
          <div>Total de horas: {{ $totalHorasTeoricas / 15 + $totalHorasPraticas / 15 }}</div>
        </div>

        <div class="col-md-4">
          <div class="h5">Disciplinas não computadas nos totais</div>
          @forelse ($disciplinasExcluidas as $d)
          {{-- @dd($d) --}}
          <div>{{ $d['coddis'] }} - {{ $d['nomdis'] }}</div>
          @empty
          @endforelse
        </div>
        
      </div>
    </div>
  </div>

  @if ($pessoas)
    <div class="alert alert-info">
      <b>Observações</b><br>
      A média semestral de horas/créditos leva em conta a carga horária da turma. <br>
    </div>

    <table class="table table-sm table-bordered table-hover datatable-simples dt-fixed-header">
      <thead>
        <tr>
          <th>Número USP</th>
          <th>Nome</th>
          <th>Média semestral turmas</th>
          <th>Média semestral horas teo/pra</th>
          <th>Média semestral créditos teo/pra</th>
          <th>turmas</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($pessoas as $pessoa)
          <tr>
            <td>{{ $pessoa['codpes'] }}</td>
            <td>{{ $pessoa['nome'] }}</td>
            <td class="text-center">{{ $pessoa['mediaTurmas'] }}</td>
            <td class="text-center" data-order="{{ $pessoa['mediaHorasTeorica'] + $pessoa['mediaHorasPratica'] }}">
              {{ $pessoa['mediaHorasTeorica'] }}/{{ $pessoa['mediaHorasPratica'] }}
            </td>
            <td class="text-center"
              data-order="{{ ($pessoa['mediaHorasTeorica'] + $pessoa['mediaHorasPratica']) / 15 }}">
              {{-- cada crédito são 15h --}}
              {{ $pessoa['mediaHorasTeorica'] / 15 }}/{{ $pessoa['mediaHorasPratica'] / 15 }}
            </td>
            <td class="text-secondary">
              <table class="table-sm">
                <tr>
                  <th>codtur</th>
                  <th>disciplina</th>
                  <th>semanal</th>
                  <th>periodo aula</th>
                  <th>horas teo/pra</th>
                  <th>vagas/matr</th>
                  <th>Atividade</th>
                  <th>Semanas</th>
                  <th>Horas</th>
                </tr>
                @foreach ($pessoa['turmas'] as $key => $turma)
                  <tr>
                    <td>{{ $turma['codtur'] }}</td>
                    <td>{{ $turma['coddis'] }} - {{ $turma['nomdis'] }}</td>
                    <td>
                      @if ($turma['stamis'] == 'N')
                        semanal
                      @else
                        quinzenal
                      @endif
                    </td>
                    <td>{{ $turma['dtainitur'] }} a {{ $turma['dtafimtur'] }}</td>
                    <td>{{ $turma['cgahorteo'] }}/{{ $turma['cgahorpra'] }}</td>
                    <td>{{ $turma['numvagtot'] }}/{{ $turma['nummtrtot'] }}</td>
                    <td>{{ $turma['nomatv'] }}</td>
                    <td>{{ $turma['semanas'] }}</td>
                    <td>{{ $turma['diasmnocp'] }} {{ $turma['horent'] }} {{ $turma['horsai'] }}
                      <i class="fas fa-caret-right"></i> {{ $turma['horas'] }}
                    </td>
                  </tr>
                @endforeach
                {{-- @dd($turma) --}}
              </table>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

@endsection
