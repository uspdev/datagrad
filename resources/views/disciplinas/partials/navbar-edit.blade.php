<div class="navbar navbar-light bg-warning card-header-sticky justify-content-between">
  <div>
    <span class="h5">
      Alteração da disciplina: {{ $disc['coddis'] }} - {{ $disc['nomdis'] }}
    </span>

    @if (Request::is('*/edit'))
      <button id="mostrar-ocultar-diff" class="btn btn-sm btn-outline-danger ml-2" style="background-color: lightsalmon;">
        Mostrar/ocultar diferenças
      </button>
    @endif
    @if (Request::is('*/preview'))
    <span class="btn btn-sm btn-danger py-0" style="pointer-events: none;">{{ $disc->estado }}</span>
    @endif
  </div>

  <div class="">
    @if (Request::is('*/edit'))
      <a href="{{ route('disciplinas.show', $disc->coddis) }}" class="btn btn-sm btn-secondary ml-2">
        Cancelar
      </a>
      <button class="btn btn-sm btn-primary ml-2 default-submit-btn" type="submit" name="submit" value="save">
        Salvar e continuar
      </button>
      <button class="btn btn-sm btn-success ml-2" type="submit" name="submit" value="preview">
        Salvar e visualizar documento
      </button>
    @endif
    @if (Request::is('*/preview'))
      <form method="post" action="{{ route('disciplinas.update', $disc->coddis) }}">
        @csrf
        @method('put')
        @if ($disc->estado == 'Em edição')
          <a href="{{ route('disciplinas.edit', $disc['coddis']) }}" class="btn btn-sm btn-primary ml-2" type="submit">
            Voltar para edição
          </a>
          <button class="btn btn-sm btn-success ml-2" type="submit" name="submit" value="Em aprovação">
            Enviar para aprovação
          </button>
        @else
        <a href="{{ route('disciplinas.show', $disc['coddis']) }}" class="btn btn-sm btn-primary ml-2" type="submit">
          Voltar para disciplina
        </a>
        @endif
      </form>
    @endif
  </div>

</div>
