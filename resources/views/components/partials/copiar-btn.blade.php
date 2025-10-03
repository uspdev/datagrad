{{-- 
Este botão copia o conteúdo do primeiro elemento .textarea, .input, textarea ou input
dentro da mesma tabela ou div com a classe .copy-limit, ignorando elementos dentro de
modais.
--}}
<button class="copiar-btn btn btn-secondary float-right py-0 d-print-none" data-placement="left">
  <i class="fas fa-copy"></i>
</button>

@once
  @section('javascripts_bottom')
    @parent
    <script>
      $(document).ready(function() {

        $('.copiar-btn').on('click', function(e) {
          e.preventDefault();

          // encontra o elemento a ser copiado
          var target = $(this).closest('table, .copy-limit')
            .find('.textarea, .input, textarea, input')
            .not('.modal *')
            .first();
          var copyText;

          if (target.is('input, textarea')) {
            copyText = target.val();
          } else {
            copyText = target
              .clone() // clona para não alterar o DOM
              .find('br').replaceWith('\n') // substitui <br> por \n
              .end()
              .text() // pega o texto completo
              .split('\n') // divide em linhas
              .map(line => line.trim()) // trim em cada linha
              .filter(line => line.length) // remove linhas vazias
              .join('\n'); // junta de volta
          }

          // copia para a área de transferência
          navigator.clipboard.writeText(copyText)
            .then(() => {
              dlog('copiado:\n', copyText);

              // tooltip de feedback
              var btn = $(this);
              btn.attr('title', 'Copiado').tooltip('show');
              setTimeout(() => btn.tooltip('dispose'), 1000);
            })
            .catch(err => console.error('Erro ao copiar:', err));
        });

      });
    </script>
  @endsection

  @section('styles')
    @parent
    <style>
      .popover {
        font-size: 1rem;
        background-color: turquoise;
      }
    </style>
  @endsection
@endonce
