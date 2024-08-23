@extends('layouts.app')

@section('styles')
  @parent
  <style>
    .card {
      margin-top: 18px;
    }

    .card-header {
      font-size: 20px;
      padding-top: 6px;
      padding-bottom: 6px;
    }

    /* formatando as cores do diff */
    ins {
      /* background-color: lightgreen; // setado no JS */
      text-decoration: none;
    }

    del {
      background-color: lightsalmon;
    }

    /* * {
          scroll-behavior: smooth;
        } */
  </style>
@endsection

@section('content')
  <form method="post" id="disciplinas-edit-form" action="{{ route('disciplinas.update', $disc->coddis) }}">
    @csrf
    @method('put')
    <input type="hidden" name="id" value={{ $disc->id }}>
    <input type="hidden" name="coddis" value={{ $disc->coddis }}>
    @include('disciplinas.partials.navbar-edit')

    <fieldset {{ ($disc->estado == 'Em edição') ? '' : 'disabled' }}>
      @include('disciplinas.partials.edit-form')
      {{-- @include('disciplinas.partials.card-curso') --}}
    </fieldset>
  </form>
@endsection

@section('javascripts_bottom')
  @parent
  <script src="{{ asset('js/diff-match-patch.js') }}"></script>
  <script src="{{ asset('js/jquery.pretty-text-diff.js') }}"></script>
  <script>
    $(document).ready(function() {

      // habilitando popover
      $(function() {
        $('[data-toggle="popover"]').popover()
      })

      // mostrar-ocultar diffs *******************************
      var mostrarOcultarDiff = function() {
        if (mostrarDiff == true) {
          $('.diff').removeClass('d-none')
          $('ins').css('background-color', 'lightgreen')
          $('ins').css('text-decoration', 'underline')
        } else {
          $('.diff').addClass('d-none')
          $('ins').css('background-color', 'inherit')
          $('ins').css('text-decoration', 'inherit')
        }
      }

      // botão de mostrar/ocultar diffs
      $('#mostrar-ocultar-diff').on('click', function(e) {
        e.preventDefault();
        mostrarDiff = !mostrarDiff
        mostrarOcultarDiff()
      })

      // ao submeter form vamos salvar estado do diff
      $('form').on('submit', function() {
        sessionStorage.setItem('mostrarDiff', mostrarDiff == true ? 'sim' : 'nao');
      })

      // mostrar diff valor padrão
      var mostrarDiff = true

      // restaura estado do mostrarDiff ao carregar página
      var sessionDiff = sessionStorage.getItem('mostrarDiff')
      if (sessionDiff == 'sim') {
        mostrarDiff = true
      }
      if (sessionDiff == 'nao') {
        mostrarDiff = false
      }
      sessionStorage.removeItem('mostrarDiff');

      if (mostrarDiff == true) {
        mostrarOcultarDiff()
      }

      // calcula diff e aplica sempre que necessário ***********
      // https://github.com/arnab/jQuery.PrettyTextDiff
      var computarDiff = function(el) {
        el.closest('table').prettyTextDiff({
          cleanup: true,
          debug: false,
          originalContainer: false,
          changedContainer: false,
          originalContent: el.data('original') + ' ',
          changedContent: el.val() + ' ',
          diffContainer: '.diff',
        })
        mostrarOcultarDiff()
      }

      // calcula diff no carregamento da página
      $('textarea, input').each(function() {
        computarDiff($(this))
        mostrarOcultarDiff()
      })

      // aplica diff depois de 1000ms
      var diff_timer = null
      $('body').on('change keyup keydown paste cut', 'textarea, input', function() {
        if (diff_timer) {
          clearTimeout(diff_timer)
        }
        // var el = $(this)
        diff_timer = setTimeout(computarDiff, 1000, $(this))
      })

      // aplica diff ao mudar o foco do input (blur)
      $('body').on('blur', 'textarea, input', function() {
        computarDiff($(this))
      })

      // restaura posição do scroll ao carregar a página
      // https://stackoverflow.com/questions/17642872/refresh-page-and-keep-scroll-position
      var scrollpos = sessionStorage.getItem('scrollpos');
      if (scrollpos) {
        window.scrollTo(0, scrollpos);
        sessionStorage.removeItem('scrollpos');
      }

      // ao submeter form vamos salvar posição do scroll
      $('form').on('submit', function() {
        sessionStorage.setItem('scrollpos', window.scrollY);
      })

    })
  </script>
@endsection
