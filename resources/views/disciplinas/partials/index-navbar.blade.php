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

@php
  $visaoClass = match ($visao) {
      'docente' => 'navbar-index',
      default => 'navbar-index-cg',
  };

  $titulo = match ($visao) {
      'docente' => 'Minhas Disciplinas',
      'cg' => 'Disciplinas CG',
      'departamento' => 'Disciplinas com prefixo(s) ' . Auth::user()->prefixos()->implode(', '),
      default => 'Disciplinas',
  };
@endphp

<div class="navbar navbar-light card-header-sticky justify-content-between mb-3 {{ $visaoClass }}">
  <div class="d-flex align-items-center flex-wrap gap-2">
    <span class="h5 mb-0 mr-2">{{ $titulo }}</span>
    @include('disciplinas.partials.criar-disciplina-btn')
  </div>

  <div class="d-flex align-items-center flex-wrap gap-1">
    @include('disciplinas.partials.visoes-index')
    @include('disciplinas.partials.consultar-form')
    @include('disciplinas.partials.ajuda-modal')
  </div>
</div>
