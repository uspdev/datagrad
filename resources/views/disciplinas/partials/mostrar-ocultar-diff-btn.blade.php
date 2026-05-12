{{--
Este código usa a classe diff para ocultar e mostrar ela.
usa também as classes .ins e .del similar às tags ins e del

se estado == criar nunca vai mostrar botão de diff
se está no preview, forca sempre diff=true
--}}

@if (!request()->routeIs('disciplinas.preview-html') && $disc->estado !== 'Criar')
  <button id="mostrar-ocultar-diff" type="button" class="btn btn-sm btn-outline-danger ml-2"
    style="background-color: lightsalmon;">
    Mostrar/ocultar diferenças
  </button>
@endif

@pushOnce('styles')
  <style>
    /* formatando as cores do diff, importante para impressão */
    ins,
    .ins {
      background-color: lightgreen; // setado no JS
      text-decoration: none;
      -webkit-print-color-adjust: exact;
      /* Chrome/Edge */
      print-color-adjust: exact;
      /* Firefox */
    }

    del,
    .del {
      background-color: lightsalmon !important;
      text-decoration: line-through;
    }

    .diff {
      background-color: #e9ecef !important;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }
  </style>
@endpushOnce

@pushOnce('scripts')
  <script src="{{ asset('js/diff-match-patch.js') }}"></script>
  <script src="{{ asset('js/jquery.pretty-text-diff.js') }}"></script>
  <script>
    $(function() {
      const isPreviewHtml =
        @json(request()->routeIs('disciplinas.preview-html'));

      // recupera estado do diff do sessionStorage ou padrão true
      // preview-html sempre mostra diff
      let mostrarDiff = isPreviewHtml ?
        true :
        sessionStorage.getItem('mostrarDiff') !== 'nao';

      // botão de mostrar/ocultar
      $('#mostrar-ocultar-diff').on('click', function(e) {
        if (isPreviewHtml) return;
        e.preventDefault();
        mostrarDiff = !mostrarDiff;
        mostrarOcultarDiff();
        sessionStorage.setItem('mostrarDiff', mostrarDiff ? 'sim' : 'nao');
      });

      // função para mostrar/ocultar diffs
      function mostrarOcultarDiff() {
        $('.diff').toggleClass('d-none', !mostrarDiff);

        $('ins, .ins').css({
          'background-color': mostrarDiff ? 'lightgreen' : 'inherit',
          'text-decoration': mostrarDiff ? 'underline' : 'inherit'
        });

        $('.del').toggleClass('d-none', !mostrarDiff);

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
@endpushOnce
