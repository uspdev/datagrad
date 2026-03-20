<button class="btn btn-sm btn-outline-secondary toggle-vis d-print-none" role="button" id="ocultar5">
  Mostrar/ocultar turmas
</button>

@section('javascripts_bottom')
  @parent
  <script>
    var column = 5
    var ocultarTurmas = {{ $ocultarTurmas }}
    $(document).ready(function() {

      if (ocultarTurmas == 1) {
        oDatatableSimples.column(column).visible(false)
      }
      console.log(ocultarTurmas)
      $('#ocultar5').on('click', function(e) {
        e.preventDefault()
        // console.log($(this).data('column'))
        let column = oDatatableSimples.column(column);
        column.visible(!column.visible())
      })
    })
  </script>
@endsection
