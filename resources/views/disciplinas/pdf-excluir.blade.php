@extends('layouts.app')

@section('menu')
@endsection



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

    ins {
      background-color: lightgreen;
    }

    del {
      /* background-color: lightsalmon; */
    }
  </style>
@endsection

@section('content')
  <div id="print">

    <div>Código: {{ $disc->coddis }}</div>
    <div>Nome/Title: {{ $disc->nomdis }} / {{ $disc->nomdisigl }}</div>
    <div>Atividade extensionista: {{ $disc->atividade_extensionista ? 'Sim':'Não' }}</div>
    <div>Alteração para o ano/semestre de {{ $disc->ano }} / {{ $disc->semestre }}</div>
    
    
    <x-diff-print name="objdis" :model="$disc"></x-diff-print>
    <x-diff-print name="objdisigl" :model="$disc"></x-diff-print>
    <x-diff-print name="pgmrsudis" :model="$disc"></x-diff-print>
    <x-diff-print name="pgmrsudisigl" :model="$disc"></x-diff-print>
    <x-diff-print name="pgmdis" :model="$disc"></x-diff-print>
    <x-diff-print name="pgmdisigl" :model="$disc"></x-diff-print>





    {{-- @include('disciplinas.partials.card-basico') --}}
    @include('disciplinas.partials.card-avaliacao')
    @include('disciplinas.partials.card-bibliografia')
    @includeWhen($disciplina['cgahoratvext'], 'disciplinas.partials.card-extensao')
    @include('disciplinas.partials.card-curso')

  </div>
@endsection


@section('javascripts_bottom')
  @parent
  <script src="{{ asset('js/diff-match-patch.js') }}"></script>
  <script src="{{ asset('js/jquery.pretty-text-diff.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
    integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script>
    $(document).ready(function() {

      var computarDiff = function(el) {
        el.prettyTextDiff({
          cleanup: true,
          debug: false,
          originalContainer: false,
          changedContainer: false,
          originalContent: el.find('.original').html() + ' ',
          changedContent: el.find('.changed').html() + ' ',
          diffContainer: '.diff',
        })
      }
      $('.com-diff').each(function() {
        computarDiff($(this))
      })


      var element = document.getElementById('print');
      var opt = {
        margin: 5,
        filename: 'myfile.pdf',
        image: {
          type: 'jpeg',
          quality: 0.98
        },
        pagebreak: {
          mode: 'legacy'
        },
        html2canvas: {
          scale: 2
        },
        jsPDF: {
          // unit: 'mm',
          // format: 'a4',
          orientation: 'landscape'
        }
      };
      html2pdf().set(opt).from(element).save().then(function() {
        history.back()
      })
      console.log('ok')


    })
  </script>
@endsection
