<div class="navbar navbar-light bg-warning card-header-sticky justify-content-between">
  <div>
    <span class="h5">
      Alteração da disciplina: {{ $disc['coddis'] }} - {{ $disc['nomdis'] }}
    </span>
    <span class="btn btn-sm btn-danger py-0" style="pointer-events: none;">{{ $disc->estado }}</span>
  </div>

  <form method="post" action="{{ route('disciplinas.update', $disc->coddis) }}">
    @csrf
    @method('put')
    @if ($disc->estado == 'Em edição')
      <a href="{{ route('disciplinas.edit', $disc['coddis']) }}" class="btn btn-sm btn-primary ml-2" type="submit">
        Voltar para edição
      </a>
      <button class="btn btn-sm btn-success ml-2 aprovacao-confirm" type="submit" name="submit" value="Em aprovação">
        Enviar para aprovação
      </button>
    @else
      <a href="{{ route('disciplinas.edit', $disc['coddis']) }}" class="btn btn-sm btn-primary ml-2" type="submit">
        Voltar para disciplina
      </a>
    @endif
  </form>
</div>
