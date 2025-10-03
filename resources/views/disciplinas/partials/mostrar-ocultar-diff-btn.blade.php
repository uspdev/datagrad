<button id="mostrar-ocultar-diff" type="button" class="btn btn-sm btn-outline-danger ml-2"
  style="background-color: lightsalmon;">
  Mostrar/ocultar diferenças
</button>

@once

  @section('styles')
    @parent
    <style>
      /* formatando as cores do diff, importante para impressão */
      ins {
        background-color: lightgreen !important; // setado no JS
        text-decoration: none;
        -webkit-print-color-adjust: exact;
        /* Chrome/Edge */
        print-color-adjust: exact;
        /* Firefox */
      }

      del {
        background-color: lightsalmon;
      }

      .diff {
        background-color: #e9ecef !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
    </style>
  @endsection

  @section('javascripts_bottom')
    @parent
    <script src="{{ asset('js/diff-match-patch.js') }}"></script>
    <script src="{{ asset('js/jquery.pretty-text-diff.js') }}"></script>
    <script>
      $(function() {

        // recupera estado do diff do sessionStorage ou padrão true
        let mostrarDiff = sessionStorage.getItem('mostrarDiff') === 'nao' ? false : true;

        // botão de mostrar/ocultar
        $('#mostrar-ocultar-diff').on('click', function(e) {
          e.preventDefault();
          mostrarDiff = !mostrarDiff;
          mostrarOcultarDiff();
          sessionStorage.setItem('mostrarDiff', mostrarDiff ? 'sim' : 'nao');
        });

        // função para mostrar/ocultar diffs
        function mostrarOcultarDiff() {
          $('.diff').toggleClass('d-none', !mostrarDiff);

          $('ins').css({
            'background-color': mostrarDiff ? 'lightgreen' : 'inherit',
            'text-decoration': mostrarDiff ? 'underline' : 'inherit'
          });

          // recalcula altura dos textareas caso o componente esteja presente
          if (typeof autoExpand === 'function') {
            $('textarea.autoexpand').each(function() {
              autoExpand(this); // usa a função autoExpand que você já tem
            });
          }
        }

        // inicializa estado
        mostrarOcultarDiff();

        // função para computar diff
        function computarDiff(el) {
          const changedContent = el.is('textarea, input') ? el.val() + ' ' : el.html() + ' ';
          el.closest('table').prettyTextDiff({
            cleanup: true,
            debug: false,
            originalContainer: false,
            changedContainer: false,
            originalContent: el.data('original') + ' ',
            changedContent: changedContent,
            diffContainer: '.diff',
          });
          mostrarOcultarDiff();
        }

        // aplica diff inicialmente
        $('textarea, input, .textarea, .input').each(function() {
          computarDiff($(this));
        });

        // debounce para atualizar diff ao digitar
        let diffTimer = null;
        $('body').on('input change keyup paste cut', 'textarea, input', function() {
          const el = $(this);
          if (diffTimer) clearTimeout(diffTimer);
          diffTimer = setTimeout(() => computarDiff(el), 500);
        });

      });
    </script>
  @endsection
@endonce
