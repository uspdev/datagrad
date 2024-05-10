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

        <div>Código: <b>{{ $dr['coddis'] }}</b></div>
        <div>Nome/Title: <b>{{ $dr['nomdis'] }} / {{ $dr['nomdisigl'] }}</b></div>
        <div>Tipo/Type: <b>{{ $disc->tipdis() }}</b></div>
        <div>Créditos-aula/Semester hour credits: <b>{{ $dr['creaul'] }}</b></div>
        <div>Créditos-trabalho/Practice hour credits: <b>{{ $dr['cretrb'] }}</b></div>
        <div>Número de vagas/Number of places: <b>{{ $dr['numvagdis'] }}</b></div>
        <div>Duração (semanas): <b>{{ $dr['durdis'] }}</b></div>
        <div>Responsáveis/Professors: <b>{{ $disc->retornarListaResponsaveis(true) }}</b></div>

      </div>
      <div class="col-md-6">

        <div>
          Atividades práticas com animais e/ou materiais biológicos: <b>{!! $dr['stapsuatvani'] == 'S' ? '<span class="text-danger">Sim</span>' : 'Não' !!}</b>
        </div>
        <div>@include('disciplinas.partials.atividade-extensionista')</div>
        <div>@include('disciplinas.partials.vigencia')</div>
        <div>Última versão: <b>{{ $dr['maxverdis'] }}</b></div>
        <div>Situação: <b>{{ $dr['sitdistxt'] }}</b></div>
        <div>Idioma: <b><span class="text-danger">{{ $dr['codlinegr'] }}</span></b></div>
      </div>

    </div>

    <hr />

    <div class="row">
      <div class="col-6">
        <b>Objetivos</b>
        <textarea class="form-control">{!! $dr['objdis'] !!}</textarea>
      </div>
      <div class="col-6">
        <b>Objectives</b>
        <textarea class="form-control" lang="en">{{ $dr['objdisigl'] }}</textarea>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-6">
        <b>Programa resumido</b>
        <textarea class="form-control">{{ $dr['pgmrsudis'] }}</textarea>
      </div>
      <div class="col-6">
        <b>Short program</b>
        <textarea class="form-control">{{ $dr['pgmrsudisigl'] }}</textarea>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-6">
        <b>Programa completo</b>
        <textarea class="form-control">{!! htmlspecialchars_decode($dr['pgmdis']) !!}</textarea>
      </div>
      <div class="col-6">
        <b>Full program</b>
        <textarea class="form-control">{{ $dr['pgmdisigl'] }}</textarea>
      </div>
    </div>

  </div>
</div>