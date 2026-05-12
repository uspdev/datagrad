<span class="badge badge-secondary">versão {{ $dr['verdis'] }}</span>

@if ($dr['dtaatvdis'] ?? null)
  a partir de <b>@date($dr['dtaatvdis'])</b>
@endif
@if ($dr['dtadtvdis'] ?? null)
  até <b>@date($dr['dtadtvdis'])</b>
@endif
