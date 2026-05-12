<x-disciplina-text name="nomdis" :model="$disc"></x-disciplina-text>
<x-disciplina-text name="nomdisigl" :model="$disc"></x-disciplina-text>

<div class="d-flex flex-column align-items-center pb-2">
  <x-disciplina-numero name="creaul" min="0" max="10" :model="$disc"></x-disciplina-numero>
  <x-disciplina-numero name="cretrb" min="0" max="10" :model="$disc"></x-disciplina-numero>
  <x-disciplina-select name="tipdis" :model="$disc"></x-disciplina-select>
  <x-disciplina-numero name="numvagdis" min="0" max="100" :model="$disc"></x-disciplina-numero>
  <x-disciplina-select name="codlinegr" :model="$disc"></x-disciplina-select>

  {{-- duração fixa para graduação em 15 semanas --}}
  <input type="hidden" name="durdis" value="15">

  @if ($disc->dr)
    <div>
      Versão base: {{ $disc->verdis }}
      (atv: @date($disc->dr['dtaatvdis']) | dtv: @date($disc->dr['dtadtvdis']))
    </div>
  @endif
</div>

@can('admin')
  <div class="pl-2" style="border-left:4px solid red;">
    @include('disciplinas.partials.form-historico')
  </div>
@endcan
