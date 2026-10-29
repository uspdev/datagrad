@php
    $erros = $disc->validarCampos();
    $pendentes = [];

    if (isset($erros['mtdens'])) {
        $pendentes[] = 'Método de ensino';
    }
    if (isset($erros['objdslsut'])) {
        $pendentes[] = 'Objetivos de Desenvolvimento Sustentável';
    }
@endphp

@if ($disc->estado == 'Em edição' || $disc->estado == 'Criar')
  <div class="pl-2 d-print-none mb-3" style="border-left:4px solid red;">
      <div class="alert alert-info mt-3">
        <i class="fas fa-info-circle"></i>
        Esta é uma visualização do documento em HTML para conferência e geração de PDF.<br>
        - Para fazer alterações, volte para a edição da disciplina.<br>
        - Você pode gerar o PDF a qualquer momento para conferência.<br>
        - Quando estiver tudo pronto, mude o estado para "Em aprovação" para encaminhar aos responsáveis pela aprovação.
      </div>
      
      <div class="d-flex align-items-center flex-wrap">
          <a href="{{ route('disciplinas.pdf', ['coddis' => $disc->coddis]) }}" class="btn btn-primary mr-2" target="_blank">
              <i class="fas fa-file-pdf"></i> Gerar PDF
          </a>

          @if (!empty($pendentes))
              <button type="button" class="btn btn-danger" disabled title="Preencha os campos obrigatórios para avançar">
                  Mudar para "Em aprovação"
              </button>
              <small class="text-danger d-block w-100 mt-1">
                  * Para mudar para "Em aprovação", preencha o(s) campo(s): <strong>{{ implode(' e ', $pendentes) }}</strong>.
              </small>
          @else
              <form action="{{ route('disciplinas.update', $disc->coddis) }}" method="POST" class="d-inline">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="action" value="mudar_em_aprovacao">
                  <button type="submit" class="btn btn-danger">
                      Mudar para "Em aprovação"
                  </button>
              </form>
          @endif
      </div>
    </div>
@endif