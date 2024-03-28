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

    * {
      scroll-behavior: smooth;
    }
  </style>
@endsection

@section('content')

  @include('disciplinas.partials.navbar')

  <span class="d-block alert alert-info">Exemplos com extensão: SHS0360, MAE0413, ERM0208, MAT0120<br />
    Prática com animais: VCI4104, ZMV1405
  </span>

  @if ($disciplina)
    <form>
      <fieldset disabled>
        @include('disciplinas.partials.card-basico')
        @include('disciplinas.partials.card-avaliacao')
        @include('disciplinas.partials.card-bibliografia')
        @includeWhen($disciplina['cgahoratvext'], 'disciplinas.partials.card-extensao')
        @include('disciplinas.partials.card-curso')
      </fieldset>
    </form>

    {{-- <pre>
      {{ json_encode($cursos[0], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
      {{ json_encode($disciplina, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
    </pre> --}}
  @else
    @if ($coddis)
      Sem informações para a disciplina {{ $coddis }}.
    @endif
  @endif

@endsection

@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {

      // redireciona para o show da disciplina do form
      $('#disciplina-form').on('submit', function(e) {
        e.preventDefault()
        var coddis = $(this).find('input[name=coddis]').val()
        window.location.href = "{{ route('disciplinas.index') }}/" + coddis
      })

      // textarea auto expand
      // https://stackoverflow.com/questions/2948230/auto-expand-a-textarea-using-jquery
      $('body').on('change keyup keydown paste cut', 'textarea', function() {
        $(this).height(0).height(this.scrollHeight)
      }).find('textarea').trigger('change')

      $(function() {
        $('[data-toggle="popover"]').popover()
      })

    })
  </script>
@endsection
