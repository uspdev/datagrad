{{--
Bloco para autoexpandir textarea conforme necessidade.
Aplica em todos os textarea

Uso:
- Incluir no layouts.app ou em outro lugar: @include('laravel-usp-theme::blocos.textarea-autoexpand')
- Adiconar a classe 'datatable-simples'

@author Masakik, em 8/5/2024
--}}
@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {

      //{{-- https://stackoverflow.com/questions/2948230/auto-expand-a-textarea-using-jquery --}}
      $('body').on('change keyup keydown paste cut', 'textarea', function() {
        $(this).height(0).height(this.scrollHeight)
      }).find('textarea').trigger('change')

    })
  </script>
@endsection
