@extends('layouts.app')

@section('content')

  @isset($formRequest)
    <h4>
      <a href="{{ route('graduacao.relatorio.turma') }}">Relatório de evasão</a>
      <i class="fas fa-angle-right"></i>
      {{ $formRequest['coddis'] == 18 ? $formRequest['nomdis'] : "{$formRequest['coddis']} - {$formRequest['nomdis']}" }}
    </h4>
  @else
    <h4>
      Relatório de resultados da turma
    </h4>
  @endisset

  @include('grad.partials.turma-form')

@endsection
