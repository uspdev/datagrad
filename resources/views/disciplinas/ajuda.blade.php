@extends('layouts.app')

@section('styles')
  @parent
  <style>
    .navbar-index {
      background-color: #d6eeff;
    }

    .navbar-index-cg {
      background-color: bisque;
      border-bottom: 3px solid red;
    }
  </style>
@endsection

@section('content')
  <div
    class="navbar navbar-light card-header-sticky justify-content-between mb-3">
    <div class="h5">
      {{-- {{ $visao == 'docente' ? 'Minhas' : '' }} --}}
      Disciplinas
      {{-- {{ $visao == 'cg' ? 'CG' : '' }} --}}
      {{-- {{ $visao == 'departamento' ? 'com prefixo(s) ' . Auth::user()->prefixos()->implode(', ') : '' }} --}}
    </div>

    <div class="form-inline">
      {{-- @include('disciplinas.partials.visoes-index') --}}
      @include('disciplinas.partials.consultar-form')
      <a href="{{ route('disciplinas.ajuda') }}"button class="btn btn-sm btn-info ml-2">
        Ajuda <i class="fas fa-question"></i>
      </a>
    </div>
  </div>
  <div>
    {!! md2html($md) !!}
  </div>
@endsection
