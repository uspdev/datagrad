<div class="card viagem-didatica">
  <div class="card-header d-flex justify-content-center font-weight-bold">
    <x-disciplina-sim-nao id="viagem-didatica-select" name="stavgmdid" :model="$disc"></x-disciplina-sim-nao>
  </div>
  <div class="card-body py-1" id="div-viagem-didatica">
    <div class="d-flex flex-column align-items-center pb-2">
      <x-disciplina-sim-nao class="py-1" name="staetr" :model="$disc"></x-disciplina-sim-nao>
    </div>
    <x-disciplina-textarea name="dscatvpvs" :model="$disc"></x-disciplina-textarea>
  </div>
</div>

@section('javascripts_bottom')
  @parent
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const s = document.getElementById("viagem-didatica-select")
      const t = document.getElementById("div-viagem-didatica")

      const toggle = () => {
        const ativo = s.value === "S"
        t.classList.toggle("d-none", !ativo)

        if (ativo) {
          t.querySelectorAll("textarea.autoexpand").forEach(el => autoExpand(el))
        }
      }

      s.addEventListener("change", toggle)
      toggle()
    })
  </script>
@endsection
