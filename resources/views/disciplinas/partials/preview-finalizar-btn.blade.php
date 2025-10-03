<div class="pl-2" style="border-left:4px solid red;">
  <form action="{{ route('disciplinas.update', $disc->coddis) }}" method="POST" id="disciplinas-edit-form"
    onsubmit="return confirm('Confirmo que as alterações aqui propostas já estão cadastradas no Jupiter!');">
    @csrf
    @method('put')
    <input type="hidden" name="id" value={{ $disc->id }}>
    <input type="hidden" name="coddis" value={{ $disc->coddis }}>
    <input type="hidden" name="estado" value="Finalizado">
    <input type="hidden" name="next" value="{{ url()->current() }}">

    <button type="submit" class="btn btn-sm btn-outline-danger">
      Estas alterações foram cadastradas no Júpiter
    </button>
  </form>
</div>