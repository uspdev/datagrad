@extends('laravel-usp-theme::master')

{{-- Blocos do laravel-usp-theme --}}
{{-- Ative ou desative cada bloco --}}

{{-- Target:card-header; class:card-header-sticky --}}
@include('laravel-usp-theme::blocos.sticky')

{{-- Target: button, a; class: btn-spinner, spinner --}}
@include('laravel-usp-theme::blocos.spinner')

{{-- Target: table; class: datatable-simples --}}
@include('laravel-usp-theme::blocos.datatable-simples')

{{-- Fim de blocos do laravel-usp-theme --}}

{{-- a ser transferido para o usptheme --}}
@include('blocos.textarea-autoexpand')

@include('partials.custom-flash-message')

@section('title')
  @parent
@endsection

@section('styles')
  @parent
  <style>
    .gap-1>*+* {
      margin-left: .25rem;
    }

    .gap-2>*+* {
      margin-left: .5rem;
    }
  </style>

  {{-- permite usar @push('styles') --}}
  @stack('styles')
@endsection

@section('javascripts_bottom')
  @stack('modals')
  @parent
  @stack('scripts')
  <script>
    // habilita/desabilita mensagens de log no console
    // alguns scripts usam essa variável
    function dlog(...args) {
      if ({{ config('app.debug') ? 'true' : 'false' }}) {
        console.log("app.debug -", ...args);
      }
    }
    // Seu código .js
  </script>
@endsection
