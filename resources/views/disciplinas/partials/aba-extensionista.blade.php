{{-- @include('disciplinas.partials.form-ativ-extensionista') --}}

<div class="card" id="card-extensao">
  <div class="card-header form-inline d-flex justify-content-center font-weight-bold">
    {{-- aqui não podemos usar componente pois atividade extensionista depende de cgahoratvext --}}
    Atividade extensionista
    <div class="input-group input-group-sm ml-2">
      <div class="input-group-prepend diff d-none">
        <span class="input-group-text">{{ $disc->dr['cgahoratvext'] ? 'Sim' : 'Não' }}</span>
      </div>
      <select class="form-control" name="atividade_extensionista" id="atividade-extensionista">
        <option value="1" {{ $disc->atividade_extensionista ? 'selected' : '' }}>Sim</option>
        <option value="0" {{ $disc->atividade_extensionista ? '' : 'selected' }}>Não</option>
      </select>
    </div>
  </div>
  <div class="card-body" id="div-extensionista">
    <x-disciplina-text class="ml-3" name="cgahoratvext" :model="$disc"></x-disciplina-text>

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
      const t = document.getElementById('div-extensionista');

      const toggle = () => {
        const ativo = s.value === '1';
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
