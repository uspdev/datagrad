@if ($disc->estado == 'Em aprovação')
  {{-- @if (session('alert-success')) --}}
  <div class="alert alert-info d-print-none">
    <i class="fas fa-info-circle"></i>
    O documento está pronto para os trâmites de aprovação.<br>
    Gere o PDF e encaminhe-o para os responsáveis pela aprovação.
  </div>
  {{-- @endif --}}
@endif
