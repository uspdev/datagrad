@php
  $permission = ['disciplina-cc', 'disciplina-cg'];
  $estilo = auth()->user()->canAny($permission) ? 'background-color: #eaf4fb; border-bottom: 3px solid #9ecae1;' : '';
@endphp
<div class="card">
  <form method="post" id="cc" action="{{ route('roles.update', 'cc') }}">
    @csrf
    @method('put')
    <div class="card-header py-1" style="{{ $estilo }}">
      <span class="h5">
        Coordenadores de cursos (CC)
        @canAny($permission)
          @include('disciplinas.partials.codpes-adicionar-btn')
        @endcanAny
      </span><br>
      <span class="text-secondary">
        Os coordenadores podem cadastrar as habilidades e competências dos cursos.
      </span>
    </div>
    <div class="card-body py-1">
      @foreach ($roleCC->users->sortBy('name') as $user)
        <div class="hover">
          <span>{{ $user->name }}</span>
          @canAny($permission)
            <span class="hide">
              @include('disciplinas.partials.codpes-remover-btn', ['codpes' => $user->codpes])
            </span>
          @endcanAny
        </div>
      @endforeach
    </div>
  </form>
</div>
