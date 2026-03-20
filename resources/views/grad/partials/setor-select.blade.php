<div class="form-group">
  <label for="codsets" class="ml-3">OU forneça uma lista de setores</label>
  <div class="col input-group mb-3">
    <select class="select2-setor" name="codsets[]" multiple="multiple">
      @foreach (Uspdev\Replicado\Estrutura::listarSetores() as $setor)
        @php
          $setorSelecionado = (!empty($codsets) and in_array($setor['codset'], $codsets)) ? 'selected' : '';
        @endphp
        <option {{ $setorSelecionado }} value="{{ $setor['codset'] }}">
          {{ $setor['nomabvset'] }} - {{ $setor['nomset'] }}
        </option>
      @endforeach
    </select>
  </div>
</div>

@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {
      // Select2
      $('.select2-setor').select2({
        // placeholder: 'ou Setor'
      });
    });
  </script>
@endsection