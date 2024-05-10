Atividade extensionista: <b>{!! $dr['cgahoratvext'] ? '<span class="text-danger">Sim</span>' : 'Não' !!}</b>
@if ($dr['cgahoratvext'])
  <span class="ml-3">Carga horária (horas): <b>{{ $dr['cgahoratvext'] }}</b></span>
@endif
