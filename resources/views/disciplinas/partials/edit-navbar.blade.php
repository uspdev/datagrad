<div class="navbar navbar-light bg-warning card-header-sticky justify-content-between">
  <div>
    <span class="h5">
      <a href="{{ route('disciplinas.index') }}">Disciplinas</a>
      <i class="fas fa-angle-right"></i> {{ $disc['coddis'] }} - {{ $disc['nomdis'] }}
      <i class="fas fa-angle-right"></i> {{ $disc->estado }}

      <a href="{{ route('disciplinas.show', $disc->coddis) }}" class="btn btn-sm btn-secondary ml-2">
        {{ $disc->estado == 'Propor alteração' ? 'Cancelar' : 'Voltar para disciplina' }}
      </a>

      @if ($disc->estado == 'Em edição' || $disc->estado == 'Propor alteração')
        <button class="btn btn-sm btn-primary ml-2 default-submit-btn" type="submit" name="submit" value="save">
          Salvar e continuar
        </button>
      @endif

      @if ($disc->estado == 'Em edição')
        <button class="btn btn-sm btn-danger ml-2" type="submit" name="submit" value="preview-html">
          Salvar e visualizar HTML
        </button>
      @else
        <a href="{{ route('disciplinas.preview-html', $disc->coddis) }}" target="_blank"
          class="btn btn-sm btn-danger ml-2">Visualizar HTML</a>
      @endif
    </span>
  </div>

  <div>

    @include('disciplinas.partials.mostrar-ocultar-diff-btn')

    @include('disciplinas.partials.ajuda-modal')
  </div>
</div>

@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {

      $('.aprovacao-confirm').on('click', function() {
        if (confirm('Ao enviar para aprovação, não será possivel mais alterar. Confirma?')) {
          return true
        } else {
          return false
        }
      })

    })
  </script>
@endsection
