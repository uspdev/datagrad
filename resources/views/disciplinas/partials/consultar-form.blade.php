<form id="disciplina-form" class="form-inline ml-2" method="get" action="">
  <div class="input-group input-group-sm">
    <div class="input-group-prepend">
      <span class="input-group-text">Consultar</span>
    </div>
    <input class="form-control" type="text" name="coddis" required placeholder="Codigo da disciplina ..">
    <div class="input-group-append">
      <button class="btn btn-primary" type="submit">OK</button>
    </div>
  </div>
</form>

@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {

      // redireciona para o show da disciplina do form
      $('#disciplina-form').on('submit', function(e) {
        e.preventDefault()
        var coddis = $(this).find('input[name=coddis]').val()
        window.location.href = "{{ route('disciplinas.index') }}/" + coddis
      })

    })
  </script>
@endsection
