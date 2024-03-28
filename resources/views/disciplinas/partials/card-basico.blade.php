@section('styles')
  @parent
  <style>
    #card-basico {
      border: 1px solid Bisque;
      border-top: 3px solid Bisque;
    }
  </style>
@endsection

<div class="card" id="card-basico">
  <div class="card-header">Informações básicas</div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-6">

        <div>Código: <b>{{ $disciplina['coddis'] }}</b></div>
        <div>Nome/Title: <b>{{ $disciplina['nomdis'] }} / {{ $disciplina['nomdisigl'] }}</b></div>
        <div>Tipo/Type:
          <b>
            @if ($disciplina['tipdis'] == 'S')
              Semestral
            @elseif($disciplina['tipdis'] == 'A')
              Anual
            @elseif($disciplina['tipdis'] == 'Q')
              Quadrimestral
            @endif
          </b>
        </div>
        <div>Créditos-aula/Semester hour credits: <b>{{ $disciplina['creaul'] }}</b></div>
        <div>Créditos-trabalho/Practice hour credits: <b>{{ $disciplina['cretrb'] }}</b></div>
        <div>Número de vagas/Number of places: <b>{{ $disciplina['numvagdis'] }}</b></div>
        <div>Duração (semanas): <b>{{ $disciplina['durdis'] }}</b></div>
        <div>
          Responsáveis/Professors:
          @foreach ($responsaveis as $r)
            <b>{{ $r['codpes'] }} - {{ $r['nompesttd'] }}</b>,
          @endforeach
        </div>

      </div>
      <div class="col-md-6">

        <div>
          Atividades práticas com animais e/ou materiais biológicos: <b>{!! $disciplina['stapsuatvani'] == 'S' ? '<span class="text-danger">Sim</span>' : 'Não' !!}</b>
        </div>
        <div>
          Atividades de extensão: <b>{!! $disciplina['cgahoratvext'] ? '<span class="text-danger">Sim</span>' : 'Não' !!}</b>
        </div>
        <div>@include('disciplinas.partials.vigencia')</div>
        <div>Última versão: <b>{{ $disciplina['maxverdis'] }}</b></div>
        <div>Situação: <b>{{ $disciplina['sitdistxt'] }}</b></div>
        @if ($disciplina['codlinegr'])
          <div>Idioma: <b><span class="text-danger">{{ $disciplina['codlinegr'] }}</span></b></div>
        @endif
      </div>

    </div>

    <hr />

    <div class="row">
      <div class="col-6">
        <b>Objetivos</b>
        <textarea class="form-control">{!! $disciplina['objdis'] !!}</textarea>
      </div>
      <div class="col-6">
        <b>Objectives</b>
        <textarea class="form-control" lang="en">{{ $disciplina['objdisigl'] }}</textarea>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-6">
        <b>Programa resumido</b>
        <textarea class="form-control">{{ $disciplina['pgmrsudis'] }}</textarea>
      </div>
      <div class="col-6">
        <b>Short program</b>
        <textarea class="form-control">{{ $disciplina['pgmrsudisigl'] }}</textarea>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-6">
        <b>Programa completo</b>
        <textarea class="form-control">{!! htmlspecialchars_decode($disciplina['pgmdis']) !!}</textarea>
      </div>
      <div class="col-6">
        <b>Full program</b>
        <textarea class="form-control">{{ $disciplina['pgmdisigl'] }}</textarea>
      </div>
    </div>

  </div>
</div>
