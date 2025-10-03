<div class="pl-2" style="border-left:4px solid red;">
  <div class="alert alert-info mt-3">
    <i class="fas fa-info-circle"></i>
    Esta é uma visualização do documento em HTML para geração de PDF.<br>
    - Para fazer alterações, volte para a edição da disciplina.<br>
    - Quando estiver tudo pronto, mude o estado para "Em aprovação", gere o PDF e encaminhe-o para os responsáveis
    pela aprovação.
  </div>
  <form action="{{ route('disciplinas.update', $disc->coddis) }}" method="POST" id="disciplinas-edit-form"
    onsubmit="return confirm('Tem certeza que deseja mudar o estado para Em aprovação?');">
    @csrf
    @method('put')
    <input type="hidden" name="id" value={{ $disc->id }}>
    <input type="hidden" name="coddis" value={{ $disc->coddis }}>
    <input type="hidden" name="estado" value="Em aprovação">

    <button type="submit" class="btn btn-sm btn-danger">Mudar para "Em aprovação"</button>
  </form>
</div>
