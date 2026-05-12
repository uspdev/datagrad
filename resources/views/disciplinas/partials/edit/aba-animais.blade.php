<div class="card animais">
  <div class="card-header d-flex justify-content-center font-weight-bold">
    <x-disciplina-sim-nao id="stapsuatvani-select" name="stapsuatvani" :model="$disc"></x-disciplina-sim-nao>
  </div>
  <div class="card-body pb-3" id="div-animais">
    <div class="alert alert-info">
      Atividades práticas que envolvam animais e/ou materiais biológicos em ensino ou pesquisa,
      como as realizadas em cursos de graduação e pós-graduação, devem passar por análise da
      CEUA (Comissão de Ética no Uso de Animais) antes de serem realizadas. Isso é necessário
      para garantir o cumprimento da legislação (Lei nº 11.794/2008) e das normas de bem-estar animal.
    </div>
    <x-disciplina-text name="ptccmseiaani" :model="$disc"></x-disciplina-text>
    <x-disciplina-data name="dtainivalprp" :model="$disc"></x-disciplina-data>
    <x-disciplina-data name="dtafimvalprp" :model="$disc"></x-disciplina-data>
  </div>
</div>

@section('javascripts_bottom')
  @parent
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const s = document.getElementById('stapsuatvani-select');
      const t = document.getElementById('div-animais');

      const toggle = () => {
        const ativo = s.value === 'S';
        t.classList.toggle('d-none', !ativo);

        if (ativo) {
          t.querySelectorAll('textarea.autoexpand').forEach(el => autoExpand(el));
        }
      };

      s.addEventListener('change', toggle);
      toggle();
    });
  </script>
@endsection
