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

    /* * {
          scroll-behavior: smooth;
        } */
  </style>
@endsection

@section('content')
  <form method="post" action="{{ route('disciplinas.update', $disc->coddis) }}">
    @csrf
    @method('put')
    <input type="hidden" name="id" value={{ $disc->id }}>

    <div class="navbar bg-warning card-header-sticky justify-content-between">
      <div>
        <span class="h5">
          Formulário de alteração: {{ $disc['coddis'] }} - {{ $disc['nomdis'] }}
        </span>
        <button id="mostrar-ocultar-original" class="btn btn-sm ml-2" style="background-color: lightgray;">
          Mostrar/ocultar diferenças
        </button>
      </div>

      <div class="">
        <a href="{{ route('disciplinas.show', $disc['coddis']) }}" class="btn btn-sm btn-secondary"
          type="submit">Cancelar</a>
        <button class="btn btn-sm btn-primary ml-2" type="submit" name="submit" value="save">Salvar e continuar</button>
        <button class="btn btn-sm btn-success ml-2" type="submit" name="submit" value="preview">Salvar e visualizar documento</button>
      </div>

    </div>
    <div>
      <fieldset>
        @include('disciplinas.partials.edit-form')
        @include('disciplinas.partials.card-curso')
      </fieldset>
  </form>
  </div>
@endsection

@section('javascripts_bottom')
  @parent
  <script src="{{ asset('js/diff-match-patch.js') }}"></script>
  <script src="{{ asset('js/jquery.pretty-text-diff.js') }}"></script>
  <script>
    $(document).ready(function() {

      // textarea auto expand
      // https://stackoverflow.com/questions/2948230/auto-expand-a-textarea-using-jquery
      $('body').on('change keyup keydown paste cut', 'textarea', function() {
        $(this).height(0).height(this.scrollHeight)
      }).find('textarea').trigger('change')

      // habilitando popover
      $(function() {
        $('[data-toggle="popover"]').popover()
      })

      // mostrar ocultar original
      $('#mostrar-ocultar-original').on('click', function(e) {
        e.preventDefault();
        $('.diff').toggleClass('d-none')
        $('textarea').height(0).height(this.scrollHeight).trigger('change')
      })



      //   https://github.com/arnab/jQuery.PrettyTextDiff
      var computarDiff = function(el) {
        // console.log(el.val())
        el.closest('table').prettyTextDiff({
          cleanup: false,
          debug: false,
          originalContainer: false,
          changedContainer: false,
          originalContent: el.data('original') + ' ',
          changedContent: el.val() + ' ',
          diffContainer: '.diff',
        })
      }
      $('textarea').each(function() {
        computarDiff($(this))
      })

      var diff_timer = null
      $('body').on('change keyup keydown paste cut', 'textarea, input', function() {
        if (diff_timer) {
          clearTimeout(diff_timer)
        }
        var el = $(this)
        diff_timer = setTimeout(computarDiff, 1000, $(this))
      })

      $('body').on('blur', 'textarea', function() {
        computarDiff($(this))
      })


      // https://stackoverflow.com/questions/17642872/refresh-page-and-keep-scroll-position
      var scrollpos = sessionStorage.getItem('scrollpos');
      if (scrollpos) {
        window.scrollTo(0, scrollpos);
        sessionStorage.removeItem('scrollpos');
      }

      $('form').on('submit', function() {
        sessionStorage.setItem('scrollpos', window.scrollY);
      })



    })
  </script>
@endsection
