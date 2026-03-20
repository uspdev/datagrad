@php
  $badgeClass = match ($disc->estado) {
      'Criar' => 'primary',
      'Em edição' => 'warning',
      'Em aprovação' => 'danger',
      'Aprovado' => 'success',
      'Finalizado' => 'secondary',
      default => 'light',
  };
@endphp

<span class="font-weight-bold text-{{ $badgeClass }}">
  {{ $disc->estado }}
</span>