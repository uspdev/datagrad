@php
    $visaoClass = ($visao == 'docente') ? 'navbar-index' : 'navbar-index-cg';
@endphp
<div class="navbar navbar-light card-header-sticky justify-content-between mb-3 {{ $visaoClass }}">
    <div>
        <span class="h5 mb-0">
        {{ $visao == 'docente' ? 'Minhas' : '' }}
        Disciplinas
        {{ $visao == 'cg' ? 'CG' : '' }}
        {{ $visao == 'departamento' ? 'com prefixo(s) ' . Auth::user()->prefixos()->implode(', ') : '' }}
        </span>
        @include('disciplinas.partials.criar-disciplina-btn')
    </div>

    <div class="form-inline">
        @include('disciplinas.partials.visoes-index')
        @include('disciplinas.partials.consultar-form')
        @include('disciplinas.partials.ajuda-modal')
    </div>
</div>
