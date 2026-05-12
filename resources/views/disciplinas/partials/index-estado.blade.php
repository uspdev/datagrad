@if ($cfg = $disc?->obterEstadoConfig())
  @if (isset($cfg['route']))
    <a href="{{ route($cfg['route'], $disc->coddis) }}" title="{{ $cfg['title'] ?? '' }}"
      class="btn btn-sm {{ $cfg['class'] }} py-0">
      {{ $cfg['label'] }}
    </a>
  @else
    <span class="btn btn-sm {{ $cfg['class'] }} py-0" style="cursor: default;">{{ $cfg['label'] }}</span>
  @endif
@endif
