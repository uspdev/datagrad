@can('admin')
  <div class="d-print-none pl-2 mt-2" style="border: 4px solid red;">
    @include('disciplinas.partials.form-historico')
    Versão de referência: {{ $disc->verdis }}

    @if ($disc->estado == 'Em aprovação' || $disc->estado == 'Finalizado')
      <form action="{{ route('disciplinas.update', $disc->coddis) }}" method="POST" id="disciplinas-edit-form"
        onsubmit="return confirm('Tem certeza que deseja voltar para edição?');">
        @csrf
        @method('put')
        <input type="hidden" name="id" value={{ $disc->id }}>
        <input type="hidden" name="coddis" value={{ $disc->coddis }}>
        <input type="hidden" name="estado" value="Em edição">
        <input type="hidden" name="next" value="{{ url()->current() }}">

        <button type="submit" class="btn btn-sm btn-outline-danger">
          <span class="badge badge-pill badge-warning">Admin</span>
          Voltar para edição
        </button>
      </form>
    @endif
  </div>
@endcan
