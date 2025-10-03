<div class="form-inline my-1">
  <x-disciplina-sim-nao id="viagem-didatica-select" name="stavgmdid" :model="$disc"></x-disciplina-sim-nao>
  <x-disciplina-sim-nao class="ml-3 staetr d-none" name="staetr" :model="$disc"></x-disciplina-sim-nao>
</div>
<div class="staetr d-none small text-muted ml-3">
  Há campos específicos a serem preenchidos para viagens didáticas mais abaixo no formulário!
</div>

@section('javascripts_bottom')
  @parent
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const s = document.getElementById("viagem-didatica-select");
      const t = document.querySelectorAll(".staetr, #card-viagem-didatica");
      const toggle = () => t.forEach(e => e.classList.toggle("d-none", s.value !== "1"));
      s.addEventListener("change", toggle);
      toggle();
    });
  </script>
@endsection
