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

  {{-- <span class="d-block alert alert-info">Exemplos com extensão: SHS0360, MAE0413, ERM0208, MAT0120<br />
    Prática com animais: VCI4104, ZMV1405
  </span> --}}

  @if ($dr)
    <form>
      <fieldset disabled>
        @include('disciplinas.partials.card-basico')
        @include('disciplinas.partials.card-avaliacao')
        @include('disciplinas.partials.card-bibliografia')
        @includeWhen($dr['cgahoratvext'], 'disciplinas.partials.card-extensao')
        @include('disciplinas.partials.card-curso')
      </fieldset>
    </form>
  @else
    Sem informações para a disciplina {{ $coddis }}.
  @endif
@endsection

@include('blocos.textarea-autoexpand')
@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {

      $(function() {
        $('[data-toggle="popover"]').popover()
      })

    })
  </script>
@endsection
