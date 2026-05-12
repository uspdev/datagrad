@if ($msgs = $disc->validarCampos() and ($disc->estado == 'Em edição' || $disc->estado == 'Criar'))
  <div class="alert alert-warning p-0 d-print-none border border-warning">

    {{-- Cabeçalho --}}
    <div class="px-3 py-2" style="background-color: rgba(255, 193, 7, 0.2);">
      <strong>Mensagem informativa</strong>

      <button class="btn btn-sm btn-outline-warning ml-2 py-0" type="button" data-toggle="collapse" data-target="#alertDetalhes"
        aria-expanded="true">
        ocultar
      </button>
    </div>

    {{-- Corpo --}}
    <div class="px-3 py-2">
      <p class="mb-2">
        Há {{ count($msgs) }} campos pendentes ou inválidos. Verifique e ajuste se necessário.
      </p>

      <div id="alertDetalhes" class="collapse show">
        <ul class="mb-0 pl-3">
          @foreach ($msgs as $msg)
            <li>{{ $msg }}</li>
          @endforeach
        </ul>
      </div>
    </div>

  </div>
@endif

@section('javascripts_bottom')
  @parent
  <script>
    $(function() {
      $('#alertDetalhes').on('show.bs.collapse', function() {
        $('[data-target="#alertDetalhes"]').text('Ocultar')
      })

      $('#alertDetalhes').on('hide.bs.collapse', function() {
        $('[data-target="#alertDetalhes"]').text('Detalhes')
      })
    });
  </script>
@endsection
