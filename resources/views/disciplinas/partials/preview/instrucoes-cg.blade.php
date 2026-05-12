@can('disciplina-cg')
  <div class="alert alert-info border-info d-print-none p-0">

    <div class="px-3 py-2" style="background-color: rgba(23, 162, 184, 0.2);">
      <div class="mr-2">
        <strong>Instruções para a CG</strong>
      </div>
    </div>
    <div class="px-3 py-2">
      <p class="mb-3">
        Após cadastrar a disciplina no <b>Júpiter</b>, finalize este formulário de alteração.
      </p>

      @if ($disc->estado == 'Em aprovação')
        <div class="bg-light border rounded p-3">
          <div class="mb-2 text-muted small">
            Confirme apenas após garantir que os dados já foram cadastrados no Júpiter.
          </div>

          <form action="{{ route('disciplinas.update', $disc->coddis) }}" method="POST" id="disciplinas-edit-form"
            onsubmit="return confirm('Confirmo que as alterações aqui propostas já estão cadastradas no Jupiter!');">
            @csrf
            @method('put')
            <input type="hidden" name="id" value="{{ $disc->id }}">
            <input type="hidden" name="coddis" value="{{ $disc->coddis }}">
            <input type="hidden" name="estado" value="Finalizado">
            <input type="hidden" name="next" value="{{ url()->current() }}">

            <button type="submit" class="btn btn-outline-danger btn-sm">
              ✔ Confirmar e finalizar no sistema
            </button>
          </form>
        </div>
      @else
        <div class="text-muted">
          Ação indisponível. Estado atual:
          <span class="badge badge-secondary">{{ $disc->estado }}</span>
        </div>
      @endif
    </div>
  </div>
@endcan
