@php
//todo: avaliar se podemos usar $disc->retornarResponsaveis()
  $responsaveis = $dr['responsaveis'] ?? [];

  usort($responsaveis, function ($a, $b) {
      return strcmp($a['nompesttd'], $b['nompesttd']);
  });

  $responsaveis = implode(', ', array_column($responsaveis, 'nompesttd'));

@endphp
<b>{{ $responsaveis }}</b>
