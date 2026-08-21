@php
  $permission = ['disciplina-biblioteca', 'disciplina-cg'];
  $estilo = auth()->user()->canAny($permission) ? 'background-color: #eaf4fb; border-bottom: 3px solid #9ecae1;' : '';
@endphp
<div class="card">
  <form method="post" id="biblioteca" action="{{ route('roles.update', 'biblioteca') }}">
    @csrf
    @method('put')
    <div class="card-header py-1" style="{{ $estilo }}">
      <span class="h5">
        Biblioteca
        @canAny($permission)
          @include('disciplinas.partials.codpes-adicionar-btn')
        @endcanAny
      </span><br>
      <span class="text-secondary">
        A função Biblioteca permite acesso à lista de bibliografia das disciplinas.
      </span>
    </div>
    <div class="card-body py-1">
      @foreach ($roleBiblioteca->users->sortBy('name') as $user)
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
