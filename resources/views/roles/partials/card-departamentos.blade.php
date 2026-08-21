@php
  $permission = ['disciplina-cg'];
  $estilo = auth()->user()->canAny($permission) ? 'background-color: #eaf4fb; border-bottom: 3px solid #9ecae1;' : '';
@endphp
<div class="card">
  <div class="card-header py-1 h5" style="{{ $estilo }}">
    Departamentos (Grupos de disciplinas)
  </div>
  <div class="card-body px-2">
    @foreach ($departamentos as $role)
      @php
        $estiloDepto = '';
        if (auth()->user()->hasRole($role) || auth()->user()->canAny($permission)) {
            $estiloDepto = 'background-color: #eaf4fb; border-bottom: 3px solid #9ecae1;';
        }
      @endphp

      @if ($estiloDepto)
        <div class="card mb-3">
          <form method="post" id="{{ $role->name }}" action="{{ route('roles.update', $role->name) }}">
            @csrf
            @method('put')
            <div class="card-header py-1" style = "{{ $estiloDepto }}">
              Prefixo {{ substr($role->name, 12) }}
              @includeWhen($estiloDepto, 'disciplinas.partials.codpes-adicionar-btn')
            </div>
            <div class="card-body py-1">
              @foreach ($role->users->sortBy('name') as $user)
                <div class="hover">
                  <span>{{ $user->name }}</span>
                  <span class="hide">
                    @includeWhen($estiloDepto, 'disciplinas.partials.codpes-remover-btn', ['codpes' => $user->codpes,])
                  </span>
                </div>
              @endforeach
            </div>
          </form>
        </div>
      @endif
    @endforeach
  </div>
</div>
