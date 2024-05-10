@if (Auth::user()->can('senhaunica.docente') && Auth::user()->can('disciplina-cg'))
  <a href="{{ route('disciplinas.index') }}?visao=docente"
    class="btn btn-sm {{ $visao == 'docente' ? 'btn-primary' : 'btn-outline-primary' }} ml-2">
    Visão Docente
  </a>
@endif
@can('disciplina-cg')
  <a href="{{ route('disciplinas.index') }}?visao=cg"
    class="btn btn-sm {{ $visao == 'cg' ? 'btn-primary' : 'btn-outline-primary' }} ml-2">
    Visão CG
  </a>
@endcan
@can('disciplina-chefe')
  <a href="{{ route('disciplinas.index') }}?visao=chefe"
    class="btn btn-sm {{ $visao == 'chefe' ? 'btn-primary' : 'btn-outline-primary' }} ml-2">
    Visão Chefe
  </a>
@endcan
