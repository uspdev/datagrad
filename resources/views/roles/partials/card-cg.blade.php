@php
  $permission = ['disciplina-cg'];
  $estilo = auth()->user()->canAny($permission) ? 'background-color: #eaf4fb; border-bottom: 3px solid #9ecae1;' : '';
@endphp
<div class="card">
  <form method="post" id="cg" action="{{ route('roles.update', 'cg') }}">
    @csrf
    @method('put')
    <div class="card-header py-1" style="{{ $estilo }}">
      <span class="h5">
        Comissão de graduação (CG)
        @canAny($permission)
          @include('disciplinas.partials.codpes-adicionar-btn')
        @endcanAny
      </span><br>
      <span class="text-secondary">
        A função CG permite acesso a todos os relatórios do sistema e a todas as disciplinas da Unidade.
      </span>
    </div>
    <div class="card-body py-1">
      @foreach ($roleCG->users->sortBy('name') as $user)
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
