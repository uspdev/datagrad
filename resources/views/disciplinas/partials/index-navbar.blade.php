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
      'biblioteca' => 'Bibliografia',
      'departamento' => 'Disciplinas com prefixo(s) ' . implode(', ', Auth::user()->prefixos()),
      default => 'Disciplinas',
  };
@endphp

<div class="navbar navbar-light card-header-sticky justify-content-between mb-3 {{ $visaoClass }}">
  <div class="d-flex align-items-center flex-wrap gap-2">
    <span class="h5 mb-0">{{ $titulo }}</span>
    @if ($visao !== 'biblioteca')
      @include('disciplinas.partials.criar-disciplina-btn')
    @endif
    @if ($visao === 'biblioteca')
      <span class="h5 mb-0"><i class="fas fa-angle-right"></i> semestre {{ $semestre }}</span>

      @include('disciplinas.partials.bibliografia-filtros', ['btn' => true])
      @include('disciplinas.partials.bibliografia-explicacao', ['btn' => true])
    @endif
  </div>

  <div class="d-flex align-items-center flex-wrap gap-1">
    @include('disciplinas.partials.visoes-index')
    @include('disciplinas.partials.consultar-form')
    @include('disciplinas.partials.ajuda-modal')
  </div>
</div>
