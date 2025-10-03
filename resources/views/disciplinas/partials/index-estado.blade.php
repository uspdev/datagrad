@if (isset($disc->estado) && $disc->estado == 'Em edição')
  <a href="{{ route('disciplinas.edit', $disc->coddis) }}" class="btn btn-sm btn-outline-warning py-0">
    Em edição
  </a>
@elseif(isset($disc->estado) && $disc->estado == 'criar')
  <a href="{{ route('disciplinas.edit', $disc->coddis) }}" class="btn btn-sm btn-outline-success py-0">
    Em criação
  </a>
@elseif(isset($disc->estado) && $disc->estado == 'Em aprovação')
  <a href="{{ route('disciplinas.preview-html', $disc->coddis) }}" class="btn btn-sm btn-outline-danger py-0">
    Em aprovação
  </a>
@elseif(isset($disc->estado) && $disc->estado == 'Aprovado')
  <span class="text-secondary">Aprovado</span>
@elseif(isset($disc->estado) && $disc->estado == 'Finalizado')
  <span class="text-secondary">Finalizado</span>
@endif
