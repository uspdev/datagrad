@if (Auth::user()->can('senhaunica.docente'))
  <a href="{{ route('disciplinas.index') }}?visao=docente"
    class="btn btn-sm {{ $visao == 'docente' ? 'btn-primary' : 'btn-outline-primary' }} ml-2">
    Visão Docente
  </a>
@endif

@can('disciplina-chefe')
  <a href="{{ route('disciplinas.index') }}?visao=departamento"
    class="btn btn-sm {{ $visao == 'departamento' ? 'btn-primary' : 'btn-outline-primary' }} ml-2">
    Visão Departamento
  </a>
@endcan

@can('disciplina-cg')
  <a href="{{ route('disciplinas.index') }}?visao=cg"
    class="btn btn-sm {{ $visao == 'cg' ? 'btn-primary' : 'btn-outline-primary' }} ml-2">
    Visão CG
  </a>
@endcan