Vigência <span class="badge badge-secondary">versão {{ $dr['verdis'] }}</span>:

@if ($dr['dtaatvdis'] ?? null)
  a partir de <b>{{ formatarData($dr['dtaatvdis']) }}</b>
@endif
@if ($dr['dtadtvdis'] ?? null)
  até <b>{{ formatarData($dr['dtadtvdis']) }}</b>
@endif
