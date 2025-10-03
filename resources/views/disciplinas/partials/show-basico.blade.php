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
        <div>Créditos-aula/Semester hour credits: <b>{{ $dr['creaul'] }}</b></div>
        <div>Créditos-trabalho/Practice hour credits: <b>{{ $dr['cretrb'] }}</b></div>
        <div>Carga Horária Total: <b>{{ ($dr['creaul'] + $dr['cretrb']) * $dr['durdis'] }}h</b></div>
        <div>Tipo/Type: <b>{{ $disc->tipdis() }}</b></div>
        <div>Número de vagas/Number of places: <b>{{ $dr['numvagdis'] }}</b></div>
        <div>Duração (semanas): <b>{{ $dr['durdis'] }}</b></div>
        <div>Responsáveis/Professors: <b>{{ $disc->retornarListaResponsaveis(true) }}</b></div>

      </div>
      <div class="col-md-6">

        <div>
          Atividades práticas com animais e/ou materiais biológicos:
          <b>{!! $dr['stapsuatvani'] == 'S' ? '<span class="text-danger">Sim</span>' : 'Não' !!}</b>
        </div>
        
        <div>
          Atividade extensionista: <b>{!! $dr['cgahoratvext'] ? '<span class="text-danger">Sim</span>' : 'Não' !!}</b>
          @if ($dr['cgahoratvext'])
            <span class="ml-3">Carga horária (horas): <b>{{ $dr['cgahoratvext'] }}</b></span>
          @endif
        </div>

        <div>
          Viagens didáticas:
          <b>{!! $disc->stavgmdid == 'S' ? '<span class="text-danger">Sim</span>' : 'Não' !!}</b><br>
        </div>

        <div>@include('disciplinas.partials.vigencia')</div>
        <div>Última versão: <b>{{ $dr['maxverdis'] }}</b></div>
        <div>Situação: <b>{{ $dr['sitdistxt'] }}</b></div>
        <div>Idioma: <b>{{ $disc->codlinegr($dr['codlinegr']) }}</b></div>
      </div>

    </div>

    <hr />
    <x-disciplina-textarea-show name='pgmrsudis' :model=$disc></x-disciplina-textarea-show>
    <x-disciplina-textarea-show name='objdis' :model=$disc></x-disciplina-textarea-show>
    <x-disciplina-textarea-show name='pgmdis' :model=$disc></x-disciplina-textarea-show>
    <x-disciplina-textarea-show name='mtdens' :model=$disc></x-disciplina-textarea-show>

  </div>
</div>
