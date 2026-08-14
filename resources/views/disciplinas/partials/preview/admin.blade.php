@can('admin')
  <div class="d-print-none pl-2 pb-2 mt-2 mb-2" style="border: 4px solid red;">
    @include('disciplinas.partials.form-historico')
    Versão de referência: {{ $disc->verdis }}

    <form action="{{ route('disciplinas.update', $disc->coddis) }}" method="POST" id="disciplinas-edit-form"
      onsubmit="return confirm('Tem certeza que deseja executar ação?');">
      @csrf
      @method('put')
      <input type="hidden" name="id" value={{ $disc->id }}>
      <input type="hidden" name="coddis" value={{ $disc->coddis }}>
      <input type="hidden" name="estado" value="{{ $disc->dr ? 'Em edição' : 'Criar' }}">
      <input type="hidden" name="next" value="{{ url()->current() }}">

      @if ($disc->estado == 'Em aprovação' || $disc->estado == 'Finalizado')
        <button type="submit" name="action" value="estado_undo" class="btn btn-sm btn-outline-warning mr-4">
          <span class="badge badge-pill badge-danger">Admin</span>
          @if ($disc->dr)
            Voltar para edição
          @else
            Voltar para criação
          @endif
        </button>
      @endif

      <button type="submit" name="action" value="excluir" class="btn btn-sm btn-outline-danger">
        <span class="badge badge-pill badge-danger">Admin</span>
        Excluir alteração
      </button>


    </form>
  </div>
@endcan
