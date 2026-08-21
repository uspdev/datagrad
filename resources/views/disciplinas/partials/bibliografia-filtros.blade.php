{{--
    Se passar $btn ele vai mostrar o botão,
    caso contrário mostrará o conteúdo
 --}}
@if ($btn ?? false)
  <button type="button" class="btn btn-outline-info btn-sm" data-toggle="collapse" data-target="#filtro-bibliografia"
    aria-expanded="false" aria-controls="filtro-bibliografia">
    <i class="fas fa-filter"></i> Filtros
  </button>
@else
  <div class="border rounded p-3 mb-4 mx-3 collapse" id="filtro-bibliografia">
    <div class="h5 ml-2"><i class="fas fa-filter"></i> Filtros</div>
    <hr />
    <form method="GET" action="{{ route('disciplinas.bibliografia') }}" class="form-inline gap-2">
      <label for="semestre">Semestre:</label>
      <span>
        <a href="{{ route('disciplinas.bibliografia') }}">este semestre</a>,
        <a href="{{ route('disciplinas.bibliografia', ['semestre' => 'next']) }}">próximo semestre</a>
        ou
      </span>

      <input type="text" class="form-control" id="semestre" name="semestre" value="{{ $semestre }}"
        maxlength="5" pattern="\d{4}[12]" style="width: 150px;">

      <button type="submit" class="btn btn-outline-primary">Aplicar</button>

      <small class="text-muted ml-3">Formato AAAAS. Ex.: 20262.</small>
    </form>
    <div class="text-muted mt-2">Insira o semestre para consultar as bibliografias vigentes.</div>
  </div>
@endif
