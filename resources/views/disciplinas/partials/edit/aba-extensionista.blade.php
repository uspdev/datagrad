{{-- @include('disciplinas.partials.form-ativ-extensionista') --}}
<div class="card" id="card-extensao">
  <div class="card-header form-inline d-flex justify-content-center font-weight-bold">
    {{-- aqui não podemos usar componente pois atividade extensionista depende de cgahoratvext --}}
    Atividade extensionista
    <div class="input-group input-group-sm ml-2">
      <div class="input-group-prepend diff d-none">
        <span class="input-group-text">{{ $disc->dr['cgahoratvext'] ?? null ? 'Sim' : 'Não' }}</span>
      </div>
      <select class="form-control" name="atividade_extensionista" id="atividade-extensionista">
        <option value="1" {{ $disc->atividade_extensionista ? 'selected' : '' }}>Sim</option>
        <option value="0" {{ $disc->atividade_extensionista ? '' : 'selected' }}>Não</option>
      </select>
    </div>
  </div>
  <div class="card-body div-extensionista">
    <div class="d-flex justify-content-center mb-3">
      <x-disciplina-numero class="" min="0" max="1000" name="cgahoratvext" :model="$disc"></x-disciplina-numero>
    </div>

    <x-disciplina-textarea name="grpavoatvext" :model="$disc"></x-disciplina-textarea>
    <x-disciplina-textarea name="grpavoatvextigl" :model="$disc"></x-disciplina-textarea>

    <x-disciplina-textarea name="objatvext" :model="$disc"></x-disciplina-textarea>
    <x-disciplina-textarea name="objatvextigl" :model="$disc"></x-disciplina-textarea>

    <x-disciplina-textarea name="dscatvext" :model="$disc"></x-disciplina-textarea>
    <x-disciplina-textarea name="dscatvextigl" :model="$disc"></x-disciplina-textarea>

    <x-disciplina-textarea name="idcavlatvext" :model="$disc"></x-disciplina-textarea>
    <x-disciplina-textarea name="idcavlatvextigl" :model="$disc"></x-disciplina-textarea>
  </div>
</div>

@section('javascripts_bottom')
  @parent
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const s = document.getElementById('atividade-extensionista');
      const divs = document.querySelectorAll('.div-extensionista');

      const toggle = () => {
        const ativo = s.value === '1';

        divs.forEach(div => {
          div.classList.toggle('d-none', !ativo);
        });


        // if (ativo) {
        //   div.querySelectorAll('textarea.autoexpand').forEach(el => autoExpand(el));
        // }
      };

      s.addEventListener('change', toggle);
      toggle();
    });
  </script>
@endsection
