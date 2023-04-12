<div class="row">
  {{-- Coluna da esquerda - informações institucionais --}}
  <div class="col-md-8">

    <div class="mb-3">
      <div class="font-weight-bold">Dados funcionais</div>
      <ul class="list-group">
        <li class="list-group-item py-1">
            {{ $pessoa['unidade'] }} |
            {{ $pessoa['departamento'] }} |
            {{ $pessoa['nomeFuncao'] }} |
            {{ $pessoa['tipoJornada'] }}
        </li>
      </ul>
    </div>

    <div class="my-3">
        <div class="font-weight-bold">Lattes
          <span class="badge badge-info">atualizado em
            {{ $pessoa['dtaultalt'] }}</span>
        </div>
        <ul class="list-group">
          <li class="list-group-item py-1">Lattes: {!! $pessoa['linkLattes'] !!}</li>
          <li class="list-group-item py-1">Orcid: {!! $lattes::retornarLinkOrcid($codpes) !!}</li>
        </ul>
      </div>

  </div>
  <div class="col-md-4">
    <div class="float-right ml-2">
        @if($pessoa['fotoLattes'])
        <div class="mt-3">
          <div class="font-weight-bold">Foto Lattes</div>
          <img src="data:image/png;base64, {{ $pessoa['fotoLattes'] }}" width="160px" alt="foto">
        </div>
        @endif
      </div>
  </div>
</div>
