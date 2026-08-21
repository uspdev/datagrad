@extends('layouts.app')

@section('styles')
  <style>
    .hover:hover {
      background-color: gainsboro;
    }

    .hide {
      display: none;
    }

    .hover:hover .hide {
      display: inline;
      color: red;
    }

    .navbar {
      background-color: bisque;
      border-bottom: 3px solid red;
    }
  </style>
@endsection

@section('content')
  <div class="navbar navbar-light card-header-sticky justify-content-between mb-3 pb-1">
    <div>
      <h5 class="mb-1">Funções</h5>
      <p class="mb-0 text-muted">
        Esta interface permite visualizar e gerenciar as funções dos usuários no sistema.
        Todas as funções podem ser visualizadas, mas somente as funções para as quais o usuário
        possui permissão podem ser editadas.
      </p>
    </div>

  </div>

  <div class="row">
    <div class="col-md-4">
      @include('roles.partials.card-departamentos')
    </div>

    <div class="col-md-4">
      @include('roles.partials.card-coordenadores')
      <div class="my-3"></div>
      @include('roles.partials.card-biblioteca')
    </div>

    <div class="col-md-4">
      @include('roles.partials.card-cg')
    </div>
  @endsection
