<button class="remover-responsavel-btn btn btn-sm py-0" data-codpes="{{ $r['codpes'] }}">
  <i class="fas fa-trash text-danger"></i>
</button>
<input type="hidden" name="responsavel_rem" value="0">

@once
  @section('javascripts_bottom')
    @parent
    <script>
      $(document).ready(function() {

        $('.remover-responsavel-btn').on('click', function() {
          if( confirm('Tem certeza?')) {
            $(':input[name=responsavel_rem]').val($(this).data('codpes'))
          } else {
            return false
          }
        })

      })
    </script>
  @endsection
@endonce
