<x-disciplina-text name="nomdis" :model="$disc"></x-disciplina-text>
<x-disciplina-text name="nomdisigl" :model="$disc"></x-disciplina-text>

<div class="d-flex flex-column align-items-center pb-2">
  <x-disciplina-numero name="creaul" min="0" max="10" :model="$disc"></x-disciplina-numero>
  <x-disciplina-numero name="cretrb" min="0" max="10" :model="$disc"></x-disciplina-numero>
  <x-disciplina-select name="tipdis" :model="$disc"></x-disciplina-select>
  <x-disciplina-numero name="numvagdis" min="0" max="100" :model="$disc"></x-disciplina-numero>
  <x-disciplina-select name="codlinegr" :model="$disc"></x-disciplina-select>

  <div>
    Versão base: {{ $disc->verdis }}
    (atv: {{ formatarData($disc->dr['dtaatvdis'] ?? null) }} | dtv: {{ formatarData($disc->dr['dtadtvdis'] ?? null) }})
  </div>
</div>

@can('admin')
<div class="pl-2" style="border-left:4px solid red;">
  @include('disciplinas.partials.form-historico')
</div>
@endcan
