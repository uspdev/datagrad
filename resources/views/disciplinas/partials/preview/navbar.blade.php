<div class="navbar navbar-light text-white card-header-sticky justify-content-between d-print-none"
  style="background-color: {{ $disc->estado == 'Criar' ? 'limeGreen' : 'lightCoral' }};">
  <div>
    <span class="h5">
      Disciplinas
      <i class="fas fa-angle-right"></i> {{ $disc['coddis'] }} - @limit($disc['nomdis'], 40)
      <i class="fas fa-angle-right"></i> Preparado para impressão
    </span>

    @if ($disc->estado == 'Em edição' || $disc->estado == 'Criar')
      <a href="{{ route('disciplinas.edit', $disc['coddis']) }}" class="btn btn-sm btn-primary" type="submit">
        Voltar para edição
      </a>
    @else
      <a href="{{ route('disciplinas.show', $disc['coddis']) }}" class="btn btn-sm btn-primary" type="submit">
        Voltar para disciplina
      </a>
    @endif
  </div>

  <div class="">
    @include('disciplinas.partials.mostrar-ocultar-diff-btn')
    @include('disciplinas.partials.ajuda-modal')
  </div>
</div>
