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
  <div class="pl-2" style="border-left:4px solid red;">
      <div class="alert alert-info mt-3">
        <i class="fas fa-info-circle"></i>
        Esta é uma visualização do documento em HTML para geração de PDF.<br>
        - Para fazer alterações, volte para a edição da disciplina.<br>
        - Quando estiver tudo pronto, mude o estado para "Em aprovação", gere o PDF e encaminhe-o para os
        responsáveis
        pela aprovação.
      </div>
      
      @if (!empty($pendentes))
          <button type="button" class="btn btn-danger" disabled title="Preencha os campos obrigatórios para avançar">
              Mudar para "Em aprovação"
          </button>
          <small class="text-danger d-block mt-1">
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
@endif