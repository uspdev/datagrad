<div class="navbar navbar-light {{ $disc->estado == 'Criar' ? 'bg-success text-white' : 'bg-warning'}} card-header-sticky justify-content-between">
  <div>
    <span class="h5">
      <a href="{{ route('disciplinas.index') }}">Disciplinas</a>
      <i class="fas fa-angle-right"></i> {{ $disc['coddis'] }} - @limit($disc['nomdis'], 40)
      <i class="fas fa-angle-right"></i> {{ $disc->estado }}

      <a href="{{ route('disciplinas.show', $disc->coddis) }}" class="btn btn-sm btn-secondary ml-2">
        {{ $disc->estado == 'Propor alteração' ? 'Cancelar' : 'Voltar para disciplina' }}
      </a>

      @if (in_array($disc->estado, ['Em edição', 'Propor alteração']))
        <button class="btn btn-sm btn-primary ml-2 default-submit-btn" type="submit" name="submit" value="save">
          Salvar e continuar
        </button>
      @endif

      @if (in_array($disc->estado, ['Em edição', 'Criar']))
        <button class="btn btn-sm btn-danger ml-2" type="submit" name="action" value="preview-html">
          Salvar e visualizar HTML
        </button>
      @else
        @if ($disc->estado != 'Propor alteração')
          <a href="{{ route('disciplinas.preview-html', $disc->coddis) }}" target="_blank"
            class="btn btn-sm btn-danger ml-2">Visualizar HTML</a>
        @endif
      @endif
    </span>
  </div>

  <div>
    @includeWhen($disc->estado != 'Criar', 'disciplinas.partials.mostrar-ocultar-diff-btn')
    @include('disciplinas.partials.ajuda-modal')
  </div>
</div>

