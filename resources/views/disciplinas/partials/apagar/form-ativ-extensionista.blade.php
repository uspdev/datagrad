<div class="form-inline my-1">
  {{-- aqui não podemos usar componente pois atividade extensionista depende de cgahoratvext --}}
  <label class="col-form-label" for="atividade-extensionista">Atividade extensionista</label>
  <div class="input-group input-group-sm ml-2">
    <div class="input-group-prepend diff d-none">
      <span class="input-group-text">{{ $disc->dr['cgahoratvext'] ? 'Sim' : 'Não' }}</span>
    </div>
    <select class="form-control" name="atividade_extensionista" id="atividade-extensionista">
      <option value="1" {{ $disc->atividade_extensionista ? 'selected' : '' }}>Sim</option>
      <option value="0" {{ $disc->atividade_extensionista ? '' : 'selected' }}>Não</option>
    </select>
  </div>

  <x-disciplina-numero class="ml-3 cgahoratvext d-none" name="cgahoratvext" :model="$disc"></x-disciplina-numero>
</div>

<div class="small text-muted ml-3 cgahoratvext d-none">
  Há campos específicos a serem preenchidos para esta atividade mais abaixo no formulário!
</div>

@section('javascripts_bottom')
  @parent
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const input = document.getElementById("atividade-extensionista");
      const targets = document.querySelectorAll(".cgahoratvext, #card-extensao");
      const toggle = () => targets.forEach(el => el.classList.toggle("d-none", input.value !== "1"));

      input.addEventListener("input", toggle);
      input.addEventListener("change", toggle);
      toggle();
    });
  </script>
@endsection
