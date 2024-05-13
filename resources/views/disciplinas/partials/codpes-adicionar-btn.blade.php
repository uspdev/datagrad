<button type="button" class="btn btn-sm btn-outline-primary codpes-adicionar-btn py-0">
  <i class="fas fa-plus"></i>
</button>

<div class="modal fade" id="adicionar-codpes-modal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Adicionar Pessoa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="form" data-ajax="{{ route('SenhaunicaFindUsers') }}">
          <div class="mb-3">
            <select name="codpes_add" class="form-control form-control-sm">
              <option value="0">Digite o nome ou codpes..</option>
            </select>
          </div>

          <div>
            <div class="float-right">
              <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-sm btn-primary">Salvar</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {

      var senhaunicaUserModal = $('#adicionar-codpes-modal')
      var $oSelect2 = senhaunicaUserModal.find(':input[name=codpes_add]')
      var dataAjax = senhaunicaUserModal.find('.form').data('ajax')

      $('.codpes-adicionar-btn').on('click', function() {
        senhaunicaUserModal.modal();
      })

      // abre o select2 automaticamente
      senhaunicaUserModal.on('shown.bs.modal', function() {
        $oSelect2.select2('open')
      })

      // coloca o focus no select2
      // https://stackoverflow.com/questions/25882999/set-focus-to-search-text-field-when-we-click-on-select-2-drop-down
      $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
      });

      $oSelect2.select2({
        ajax: {
          url: dataAjax,
          dataType: 'json',
          delay: 1000
        },
        dropdownParent: senhaunicaUserModal,
        minimumInputLength: 4,
        theme: 'bootstrap4',
        width: 'resolve',
        language: 'pt-BR'
      })

    })
  </script>
@endsection
