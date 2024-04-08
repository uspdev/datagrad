@props([
    'class' => '',
    'name' => '',
    'model' => '',
])

<table class="table table-bordered table-sm {{ $model->meta[$name]['class'] ?? '' }}">
  <tr>
    <th colspan="2" class="titulo text-center" style="background-color: aliceBlue">
      {{ $model->meta[$name]['titulo'] }}
      @if (isset($model->meta[$name]['ajuda']))
        <span class="text-primary">
          <i class="fas fa-question-circle" data-toggle="popover" data-trigger="hover"
            title="{{ $model->meta[$name]['ajuda'] }}"></i>
        </span>
      @endif

      {{-- @if (!isset($model->meta[$name]['class']))
        <button class="traduzir-btn btn btn-secondary float-right py-0 mt-1 ml-2" data-toggle="popover"
          data-trigger="hover" title="Copia o conteúdo e abre google translator">
          <i class="fas fa-globe"></i>
        </button>
      @endif

      <button class="btn" type="button" data-clipboard-text="Just because you can doesn't mean you should — clipboard.js">
        Copy to clipboard
      </button> --}}

      <button class="copiar-btn btn btn-secondary float-right py-0 mt-1" data-clipboard-target="#foo" data-toggle="popover" data-trigger="hover"
        title="Copia o conteúdo para a área de transferência">
        <i class="fas fa-copy"></i>
      </button>

    </th>
  </tr>
  <tr>
    <td class="col-6 d-none diff" style="background-color: #e9ecef; padding: 12px">
      {!! str_replace("\n", '&para;<br>', $model->dr[$name]) !!}
    </td>
    <td class="col-6">
      <textarea name="{{ $name }}" class="form-control changed" data-original="{!! $model->dr[$name] !!}">{!! htmlspecialchars_decode($model[$name]) !!}</textarea>
    </td>
  </tr>
</table>

@once
  @section('javascripts_bottom')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script>
    <script>
      $(document).ready(function() {

        navigator.permissions.query({ name: "clipboard-write" }).then((result) => {
  if (result.state === "granted" || result.state === "prompt") {
    /* write to the clipboard now */
  }
});

        new ClipboardJS('textarea');

        // https://stackoverflow.com/questions/37658524/copying-text-of-textarea-in-clipboard-when-button-is-clicked
        $('.traduzir-btn').on('click', function(e) {
          e.preventDefault()
          var copyText = $(this).parent().parent().parent().find('textarea')
          copyText.select();
        //   navigator.clipboard.writeText(copyText.value);
            document.execCommand('copy');
          window.open('https://translate.google.com.br', "_blank")
        })

        $('.copiar-btn').on('click', function(e) {
          e.preventDefault()
          var copyText = $(this).parent().parent().parent().find('textarea')
          // console.log(copyText)
          // navigator.clipboard.writeText(copyText);
          copyText.select();
          document.execCommand('copy');
        })

      })
    </script>
  @endsection
@endonce
