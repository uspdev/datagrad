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
  @include('disciplinas.partials.show-navbar')

  {{-- <span class="d-block alert alert-info">
    Exemplos com extensão: SHS0360, MAE0413, ERM0208, MAT0120<br />
    Prática com animais: VCI4104, ZMV1405
    Viagem didática: GAA0252
  </span> --}}

  @if ($dr)
    <form>
      <fieldset disabled>
        @include('disciplinas.partials.show-basico')
        @include('disciplinas.partials.show-avaliacao')
        @include('disciplinas.partials.show-bibliografia')
        @includeWhen($dr['cgahoratvext'], 'disciplinas.partials.show-extensao')
        @includeWhen($dr['stavgmdid'] ==  'S', 'disciplinas.partials.show-viagem')
        @includeWhen($dr['stapsuatvani'] ==  'S', 'disciplinas.partials.show-animais')
        @include('disciplinas.partials.show-curso')
      </fieldset>
    </form>
  @else
    Sem informações para a disciplina {{ $coddis }}.
  @endif
@endsection

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
