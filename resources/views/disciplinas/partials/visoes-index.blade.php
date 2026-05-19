@can('senhaunica.docente')
  <a href="{{ route('disciplinas.index') }}?visao=docente"
    class="btn btn-sm {{ $visao == 'docente' ? 'btn-primary' : 'btn-outline-primary' }}">
    Visão Docente
  </a>
@endcan

@can('disciplina-chefe')
  <a href="{{ route('disciplinas.index') }}?visao=departamento"
    class="btn btn-sm {{ $visao == 'departamento' ? 'btn-primary' : 'btn-outline-primary' }}">
    Visão Departamento
  </a>
@endcan

@can('disciplina-cg')
  <a href="{{ route('disciplinas.index') }}?visao=cg"
    class="btn btn-sm {{ $visao == 'cg' ? 'btn-primary' : 'btn-outline-primary' }}">
    Visão CG
  </a>
@endcan

@can('disciplina-cg')
  <a href="{{ route('disciplinas.index') }}?visao=finalizados"
    class="btn btn-sm {{ $visao == 'finalizados' ? 'btn-danger' : 'btn-outline-danger' }}">
    Disciplinas finalizadas
  </a>
@endcan
