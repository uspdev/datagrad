@php
  $color = match ($dr['sitdis']) {
      'AT' => 'body',
      'PE', 'AU', 'AO' => 'warning',
      'DT' => 'danger',
      'AP' => 'primary',
      default => 'secondary',
  };
@endphp

<span class="text-{{ $color }}">{{ $dr['sitdistxt'] ?? '-' }}</span>
