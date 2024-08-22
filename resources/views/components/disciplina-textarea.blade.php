@props([
    'class' => '',
    'name' => '',
    'model' => '',
    'id' => $name . rand(10000, 99999),
])

<table class="table table-bordered table-sm {{ $model->meta[$name]['class'] ?? '' }}" id="{{ $id }}">
  <tr>
    <th colspan="2" class="titulo text-center" style="background-color: aliceBlue">
      {{ $model->meta[$name]['titulo'] }}
      @if (isset($model->meta[$name]['ajuda']))
        <span class="text-primary">
          <i class="fas fa-question-circle" data-toggle="popover" data-trigger="hover"
            title="{{ $model->meta[$name]['ajuda'] }}"></i>
        </span>
      @endif

      {{-- <button class="btn copy-to-clipboard" type="button" data-clipboard-target="#{{ $id }}" data-clipboard-action="copy">
        Copy to clipboard
      </button>
       --}}

      <button class="copiar-btn btn btn-secondary float-right py-0 mt-1" data-placement="left">
        {{-- data-toggle="popover" data-trigger="hover" title="Copia o conteúdo para a área de transferência"> --}}
        <i class="fas fa-copy"></i>
      </button>

    </th>
  </tr>
  <tr>
    <td class="col-6 d-none diff" style="background-color: #e9ecef; padding: 12px">
      {!! str_replace("\n", '&para;<br>', $model->dr[$name]) !!}
    </td>
    <td class="col-6">
      <textarea name="{{ $name }}" class="form-control changed autoexpand" data-original="{!! $model->dr[$name] !!}">{!! htmlspecialchars_decode($model[$name]) !!}</textarea>
    </td>
  </tr>
</table>

@once
  @section('javascripts_bottom')
    @parent
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script> --}}
    <script>
      $(document).ready(function() {
        // new ClipboardJS('.copy-to-clipboard');

        // https://stackoverflow.com/questions/37658524/copying-text-of-textarea-in-clipboard-when-button-is-clicked
        // $('.traduzir-btn').on('click', function(e) {
        //   e.preventDefault()
        //   var copyText = $(this).parent().parent().parent().find('textarea')
        //   copyText.select();
        //   //   navigator.clipboard.writeText(copyText.value);
        //   document.execCommand('copy');
        //   window.open('https://translate.google.com.br', "_blank")
        // })

        $('.copiar-btn').on('click', function(e) {
          e.preventDefault()

          var copyText = $(this).parent().parent().parent().find('textarea').val()

          // compativel somente com https
          navigator.clipboard.writeText(copyText);

          // compatível com http
          // copyText.select();
          // document.execCommand('copy');

          var thistooltip = $(this)
          thistooltip.attr('title', 'Copiado')
          thistooltip.tooltip('toggle')
          setTimeout(function() {
            thistooltip.tooltip('dispose')
          }, 800)

        })

      })
    </script>
  @endsection
  
  @section('styles')
  @parent
  <style>

  </style>
@endsection
@endonce
