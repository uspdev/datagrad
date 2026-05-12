<button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#criarModal"
  title="Criar nova disciplina">
  <i class="fas fa-plus"></i> Novo
</button>

@push('modals')
  <!-- Modal -->
  <div class="modal fade" id="criarModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form method="POST" action="{{ route('disciplinas.store') }}">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="modalDisciplinaLabel">Criar nova disciplina</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-3 mb-3">
                <label for="coddis" class="form-label">Prefixo</label>
                <select name="coddis" id="coddis" class="form-control" required>
                  <option value="" disabled selected>Selecione um prefixo ..</option>
                  @foreach (App\Replicado\Graduacao::listarPrefixosDisciplinas() as $prefixo)
                    <option value="{{ $prefixo }}">{{ $prefixo }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-9 mb-3">
                <label for="nomdis" class="form-label">Nome</label>
                <input type="text" name="nomdis" id="nomdis" class="form-control" maxlength="240" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="responsavel" class="form-label">Responsável</label>
              <select name="codpes" id="responsavel" class="form-control" required></select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endpush

@push('scripts')
  <script>
    $(document).ready(function() {
      // inicializa o select2 somente quando o modal abrir
      $('#criarModal').on('shown.bs.modal', function() {
        $('#responsavel').select2({
          placeholder: 'Selecione um responsável',
          allowClear: true,
          dropdownParent: $('#criarModal'), // importante para funcionar no modal
          ajax: {
            url: '{{ route('SenhaunicaFindUsers') }}',
            dataType: 'json',
            delay: 1000,
          },
          minimumInputLength: 4,
          theme: 'bootstrap4',
          width: 'resolve',
          language: 'pt-BR'
        });
      });

      // opcional: destruir o select2 ao fechar o modal (evita bug de duplicar)
      $('#modalDisciplina').on('hidden.bs.modal', function() {
        $('#responsavel').select2('destroy');
      });

      // coloca o focus no select2
      // https://stackoverflow.com/questions/25882999/set-focus-to-search-text-field-when-we-click-on-select-2-drop-down
      $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
      });
    });
  </script>
@endpush
