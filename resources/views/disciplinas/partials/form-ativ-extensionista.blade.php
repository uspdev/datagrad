<div class="form-inline my-1">
  <label class="col-form-label" for="atividade-extensionista">Atividade extensionista</label>
  <div class="input-group input-group-sm ml-2">
    <div class="input-group-prepend diff d-none">
      <span class="input-group-text">{{ $disc->dr['cgahoratvext'] ? 'Sim' : 'Não' }}</span>
    </div>
    <select class="form-control" name="atividade_extensionista" id="atividade-extensionista">
      <option value="">Selecione ..</option>
      <option value="1" {{ $disc->atividade_extensionista ? 'selected' : '' }}>Sim</option>
      <option value="0" {{ $disc->atividade_extensionista ? '' : 'selected' }}>Não</option>
    </select>
  </div>
  <x-disciplina-numero class="ml-3 cgahoratvext d-none" name="cgahoratvext" :model="$disc"></x-disciplina-numero>
</div>

@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {

      var mostrarOcultarAtivExtensionista = function() {
        // console.log('extensao', $('#atividade-extensionista').val())
        if ($('#atividade-extensionista').val() == 1) {
          $('.cgahoratvext').removeClass('d-none')
          $('.atividade-extensionista').removeClass('d-none')
        } else {
          $('.cgahoratvext').addClass('d-none')
          $('.atividade-extensionista').addClass('d-none')
        }
      }
      
      mostrarOcultarAtivExtensionista()
      
      $('#atividade-extensionista').on('change', mostrarOcultarAtivExtensionista)

    })
  </script>
@endsection
