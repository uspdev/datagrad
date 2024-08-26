<div class="navbar navbar-light card-header-sticky justify-content-between" style="background-color: #d6eeff;">
  <div>
    <span class="h5">
      <a href="{{ route('disciplinas.index') }}">Disciplinas</a>
    </span>
    @if ($dr)
      <span class="h5">
        <i class="fas fa-chevron-right"></i> Disciplina {{ $dr['coddis'] }} - {{ $dr['nomdis'] }}
      </span>
      {{-- @include('disciplinas.partials.badge-vigencia') --}}

      {{-- badge extensao --}}
      @if ($dr['cgahoratvext'])
        <a href="{{ Request::getRequestUri() }}#card-extensao" class="badge badge-info ml-2" data-toggle="popover"
          data-trigger="hover" data-placement="bottom" data-content="Possui atividades de extensão">
          Extensão: {{ $dr['cgahoratvext'] }}
        </a>
      @endif

      {{-- badge animais --}}
      @if ($dr['stapsuatvani'] == 'S')
        <span class="badge badge-warning ml-2" data-toggle="popover" data-trigger="hover" data-placement="bottom"
          data-content="Atividades práticas com animais e/ou materiais biológicos">
          BIO <i class="fas fa-biohazard"></i>
        </span>
      @endif

      {{-- link jupiter --}}
      <a href="https://uspdigital.usp.br/jupiterweb/obterDisciplina?nomdis=&sgldis={{ $dr['coddis'] }}"
        class="badge badge-secondary ml-2" target="_BLANK">Jupiter Web <i class="fas fa-link"></i></a>

      @can('update', $disc)
        @if ($disc->estado == 'Em aprovação')
          <button role="button" class="btn btn-sm btn-danger py-0">
            Em aprovação
          </button>
        @endif
        <a href="{{ route('disciplinas.edit', $dr['coddis']) }}" class="btn btn-sm btn-warning ml-2" type="submit">
          @if ($disc->id)
            @if ($disc->estado != 'Em aprovação')
              Editar alteração em andamento
            @else
              Ver dados de edição
            @endif
          @else
            Propor alteração da disciplina
          @endif
        </a>

      @endcan
    @endif
  </div>
  <div>@include('disciplinas.partials.consultar-form')</div>
</div>
