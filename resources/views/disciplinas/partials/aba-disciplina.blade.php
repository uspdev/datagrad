<x-disciplina-text name="nomdis" :model="$disc"></x-disciplina-text>
<x-disciplina-text name="nomdisigl" :model="$disc"></x-disciplina-text>

<div class="d-flex flex-column align-items-center pb-2">
  <x-disciplina-numero name="creaul" :model="$disc"></x-disciplina-numero>
  <x-disciplina-numero name="cretrb" :model="$disc"></x-disciplina-numero>
  <x-disciplina-select name="tipdis" :model="$disc"></x-disciplina-select>
  <x-disciplina-numero name="numvagdis" :model="$disc"></x-disciplina-numero>
  <x-disciplina-select name="codlinegr" :model="$disc"></x-disciplina-select>

  <div>
    Versão base: {{ $disc->verdis }}
    (atv: {{ formatarData($disc->dr['dtaatvdis']) }} | dtv: {{ formatarData($disc->dr['dtadtvdis']) }})
  </div>
</div>

{{-- @include('disciplinas.partials.form-historico') --}}
