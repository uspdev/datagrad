<div class="alert alert-info mt-3 small">
  <div>Estado atual:
    <b>{{ $disc->estado }}</b>, por {{ $disc->atualizadoPor->name }}<br>
    Chave: {{ $disc->hash() }}
  </div>
  <hr>

  <div><b>Histórico</b></div>
  <div>
    @forelse($disc->historico->reverse() as $h)
      {{ $h['estado'] }}, {{ $h['data'] }}, {{ $h['user'] }}<br>
      <div class="ml-3">
        @if ($h['comentario'])
          {{ $h['comentario'] }}
        @endif
      </div>
    @empty
    @endforelse
  </div>
</div>
