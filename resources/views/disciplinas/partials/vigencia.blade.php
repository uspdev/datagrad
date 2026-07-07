{{-- <span class="badge badge-secondary">versão {{ $dr['verdis'] }}</span> --}}

@if ($dr['dtaatvdis'] ?? null)
  a partir de <b>@date($dr['dtaatvdis'])</b>
  @else
  <b>-</b>
@endif
@if ($dr['dtadtvdis'] ?? null)
  até <b>@date($dr['dtadtvdis'])</b>
  @if($dr['dtadtvdis'] < now())
    <span class="text-danger">(não vigente)</span>
  @endif
@endif
