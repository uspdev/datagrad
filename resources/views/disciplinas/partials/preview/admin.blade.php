@if ($disc->estado == 'Em aprovação' || $disc->estado == 'Finalizado')
  <div class="alert alert-secondary border-secondary d-print-none p-0 mb-3">
    <div class="px-3 py-2" style="background-color: rgba(108, 117, 125, 0.15);">
      <strong>Ações do Departamento</strong>
    </div>
    <div class="px-3 py-2">
      <form action="{{ route('disciplinas.update', $disc->coddis) }}" method="POST" id="departamento-edit-form"
        onsubmit="return confirm('Tem certeza que deseja executar ação?');">
        @csrf
        @method('put')
        <input type="hidden" name="id" value="{{ $disc->id }}">
        <input type="hidden" name="coddis" value="{{ $disc->coddis }}">
        <input type="hidden" name="estado" value="{{ $disc->dr ? 'Em edição' : 'Criar' }}">
        <input type="hidden" name="next" value="{{ url()->current() }}">

        <button type="submit" name="action" value="estado_undo" class="btn btn-sm btn-outline-secondary">
          <i class="fas fa-undo-alt mr-1"></i>
          @if ($disc->dr)
            Voltar para edição
          @else
            Voltar para criação
          @endif
        </button>
      </form>
    </div>
  </div>
@endif

@can('admin')
  <div class="alert alert-danger border-danger d-print-none p-0 mb-3">
    <div class="px-3 py-2" style="background-color: rgba(220, 53, 69, 0.15);">
      <strong class="text-danger">
        <i class="fas fa-shield-alt mr-1"></i> Ações do Administrador
      </strong>
    </div>
    <div class="px-3 py-2">
      <form action="{{ route('disciplinas.update', $disc->coddis) }}" method="POST" id="admin-delete-form"
        onsubmit="return confirm('Tem certeza que deseja excluir esta alteração permanentemente?');">
        @csrf
        @method('put')
        <input type="hidden" name="id" value="{{ $disc->id }}">
        <input type="hidden" name="coddis" value="{{ $disc->coddis }}">
        <input type="hidden" name="next" value="{{ url()->current() }}">

        <button type="submit" name="action" value="excluir" class="btn btn-sm btn-outline-danger">
          <span class="badge badge-pill badge-danger">Admin</span>
          Excluir alteração
        </button>
      </form>
    </div>
  </div>
@endcan
