{{-- O modal de pessoa e o js correspnodente precisa ser incluido 1x somente --}}
@once
  @section('javascripts_bottom')
    @parent
    <div class="modal fade" id="pessoaModal" tabindex="-1" aria-labelledby="pessoaModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title h4" id="pessoaModalLabel">Nome da pessoa</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            ...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      jQuery(function() {

        $('.showPessoaModal').on('click', function(e) {
          e.preventDefault()
          var route = $(this).attr('href')
          var codpes = route.substring(route.lastIndexOf('/') + 1)
          var nome = $(this).html()
          $('#pessoaModal').find('.modal-title').html('(' + codpes + ') ' + nome)

          console.log(nome, route)
          // carregando spinner enquanto espera o ajax
          let spinner = '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
          $('#pessoaModal').find('.modal-body').html(spinner)
          $('#pessoaModal').modal('show')

          $.get(route, function(data) {
            $('#pessoaModal').find('.modal-body').html(data)
          })
        })

      })
    </script>
  @endsection
@endonce
