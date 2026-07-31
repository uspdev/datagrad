@if ($disc->estado == 'Em aprovação')
  {{-- @if (session('alert-success')) --}}
  <div class="alert alert-info d-print-none">
    <i class="fas fa-info-circle"></i>
    O documento está pronto para os trâmites de aprovação.<br>
    Gere o PDF e encaminhe-o para os responsáveis pela aprovação.
    <p>
        <a href="{{ route('disciplinas.pdf', ['coddis' => $disc->coddis]) }}" class="btn btn-primary mt-2">
            <i class="fas fa-file-pdf"></i> Gerar PDF</a>
    </p>
  </div>
  {{-- @endif --}}
@endif
