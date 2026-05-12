{{--
  Este botão de modal foi ajustado para permitir multiplos includes
  o form-id não deve conter "." pois interfere no javascript
  Masakik, em 22/5/2024
--}}
<button type="button" class="btn btn-sm btn-outline-primary codpes-adicionar-btn py-0 ml-2">
  <i class="fas fa-plus"></i>
</button>

@once
  @section('javascripts_bottom')
    @parent
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
                  <button type="button" class="btn btn-sm btn-primary submit-btn">Salvar</button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
    <script>
      $(document).ready(function() {

        var senhaunicaUserModal = $('#adicionar-codpes-modal')
        var $oSelect2 = senhaunicaUserModal.find(':input[name=codpes_add]')
        var dataAjax = senhaunicaUserModal.find('.form').data('ajax')
        var formId;

        $('.codpes-adicionar-btn').on('click', function() {
          senhaunicaUserModal.modal()
          formId = $(this).closest('form').attr('id')
        })

        senhaunicaUserModal.find('.submit-btn').on('click', function() {
          let codpes = $oSelect2.val()
          if (!codpes) {
            alert('Selecione um usuário')
            return
          }

          let form = $('#' + formId)
          form.find('input[name="codpes_add"]').remove()

          $('<input>', {
            type: 'hidden',
            name: 'codpes_add',
            value: codpes
          }).appendTo(form)

          document.getElementById(formId).submit()
        })

        senhaunicaUserModal.on('shown.bs.modal', function() {
          $oSelect2.select2('open')
        })

        senhaunicaUserModal.on('select2:open', () => {
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
@endonce
