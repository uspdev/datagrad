<div class="navbar navbar-light bg-light card-header-sticky">
  <div class="h5 form-inline">
    @if ($disciplina)
      Disciplina {{ $disciplina['coddis'] }} - {{ $disciplina['nomdis'] }}

      @include('disciplinas.partials.badge-vigencia')

      {{-- badge extensao --}}
      @if ($disciplina['cgahoratvext'])
        <a href="{{ Request::getRequestUri() }}#card-extensao" class="badge badge-info ml-2" data-toggle="popover"
          data-trigger="hover" data-placement="bottom" data-content="Possui atividades de extensão">
          Extensão: {{ $disciplina['cgahoratvext'] }}
        </a>
      @endif

      {{-- badge extensao --}}
      @if ($disciplina['stapsuatvani'] == 'S')
        <span class="badge badge-warning ml-2" data-toggle="popover" data-trigger="hover" data-placement="bottom"
          data-content="Atividades práticas com animais e/ou materiais biológicos">
          BIO <i class="fas fa-biohazard"></i>
        </span>
      @endif

      {{-- link jupiter --}}
      <a href="https://uspdigital.usp.br/jupiterweb/obterDisciplina?nomdis=&sgldis={{ $disciplina['coddis'] }}"
        class="badge badge-secondary ml-2" target="_BLANK">Jupiter Web <i class="fas fa-link"></i></a>

      <button class="btn btn-sm btn-warning ml-2" type="submit">Nova alteração / em andamento</button>
    @else
      Digite o código da disciplina:
    @endif

    <form id="disciplina-form" method="get" action="">
      <div class="input-group ml-2">
        <input class="form-control form-control-sm" type="text" name="coddis" required
          placeholder="Codigo da disciplina ..">
        <div class="input-group-append">
          <button class="btn btn-sm btn-primary" type="submit">{!! $disciplina ? '<i class="fas fa-exchange-alt"></i>' : 'OK' !!}</button>
        </div>
      </div>
    </form>

</div>
</div>
