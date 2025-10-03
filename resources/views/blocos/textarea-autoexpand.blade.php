{{--
Bloco para autoexpandir textarea conforme necessidade.

Uso:
- Incluir no layouts.app ou em outro lugar: @include('laravel-usp-theme::blocos.textarea-autoexpand')
- Adiconar a classe 'autoexpand'
- utiliza rows do textarea para definir minrows (default = 2)

@author Masakik, em 8/5/2024
--}}
@once
  @section('javascripts_bottom')
    @parent
    <script>
      function autoExpand(el) {
        const lineHeight = parseFloat(getComputedStyle(el).lineHeight) || 20;
        const minRows = parseInt(el.getAttribute('rows')) || 2; // lê do atributo rows
        const minHeight = minRows * lineHeight;

        el.style.height = '0px';
        el.style.height = Math.max(el.scrollHeight, minHeight) + 'px';
      }

      function autoExpandAll(container = document) {
        container.querySelectorAll('.autoexpand').forEach(autoExpand);
      }

      document.addEventListener('DOMContentLoaded', () => {
        // Autoexpand ao abrir aba do BS4
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
          const targetId = e.target.getAttribute('href'); // ex: "#aba1"
          const tab = document.querySelector(targetId);
          autoExpandAll(tab);
        });

        // Autoexpand enquanto digita
        document.querySelectorAll('textarea.autoexpand').forEach(el => {
          autoExpand(el); // roda 1x no carregamento
          el.addEventListener('input', () => autoExpand(el));
        });

        // chama autoExpandAll ao redimensionar a janela
        window.addEventListener('resize', () => {
          autoExpandAll();
        });
      });
    </script>
  @endsection
@endonce
