{{--
Bloco para autoexpandir textarea conforme necessidade.

Uso:
- Incluir no layouts.app ou em outro lugar: @include('laravel-usp-theme::blocos.textarea-autoexpand')
- Adiconar a classe 'autoexpand'

@author Masakik, em 8/5/2024
--}}
@once
  @section('javascripts_bottom')
    @parent
    <script>
      $(document).ready(function() {

        //{{-- https://stackoverflow.com/questions/2948230/auto-expand-a-textarea-using-jquery --}}
        $(document).on('change keyup paste cut', '.autoexpand', function(e) {
          $(this).height(0).height(this.scrollHeight)
          // $(this).height(0).height(
          //   this.scrollHeight +
          //   parseFloat($(this).css('borderTopWidth')) +
          //   parseFloat($(this).css('borderBottomWidth'))
          // )
        })

        // aparentemente precisa dar um tempinho para poder disparar o autoexpand
        setTimeout(() => {
          $('.autoexpand').trigger('change')
        }, 500)

      })
    </script>
  @endsection
@endonce
