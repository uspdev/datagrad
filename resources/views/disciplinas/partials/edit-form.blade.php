@section('styles')
  @parent
  <style>
    #card-basico {
      border: 1px solid Bisque;
      border-top: 3px solid Bisque;
    }

    /* Atividades extensionistas ficarão em um card com cor diferenciada */
    #card-extensao {
      border: 1px solid brown;
      border-top: 3px solid brown;
    }

    #card-extensao .card-header {
      text-align: center;
      font-weight: bold;
      background-color: bisque;
    }

    #card-extensao .titulo {
      background-color: beige !important;
    }
  </style>
@endsection

<div class="row mt-3">
  <div class="col-md-6">

    @include('disciplinas.partials.form-ano-semestre')

    <hr class="my-1" />

    @include('disciplinas.partials.form-tipo')

    <x-disciplina-numero name="creaul" :model="$disc"></x-disciplina-numero>
    <x-disciplina-numero name="cretrb" :model="$disc"></x-disciplina-numero>
    <x-disciplina-numero name="numvagdis" :model="$disc"></x-disciplina-numero>
    {{-- <x-disciplina-numero name="durdis" :model="$disc"></x-disciplina-numero> --}}

    @include('disciplinas.partials.form-ativ-extensionista')

  </div>
  <div class="col-md-6">
    {{-- @include('disciplinas.partials.form-ativ-animais') --}}
    @include('disciplinas.partials.form-responsaveis')

  </div>
</div>
<x-disciplina-text name="nomdis" :model="$disc"></x-disciplina-text>
<x-disciplina-text name="nomdisigl" :model="$disc"></x-disciplina-text>

<div class="my-1">&nbsp;</div>

<x-disciplina-textarea name="objdis" :model="$disc"></x-disciplina-textarea>
<x-disciplina-textarea name="objdisigl" :model="$disc"></x-disciplina-textarea>

<x-disciplina-textarea name="pgmrsudis" :model="$disc"></x-disciplina-textarea>
<x-disciplina-textarea name="pgmrsudisigl" :model="$disc"></x-disciplina-textarea>

<x-disciplina-textarea name="pgmdis" :model="$disc"></x-disciplina-textarea>
<x-disciplina-textarea name="pgmdisigl" :model="$disc"></x-disciplina-textarea>

<x-disciplina-textarea name="dscmtdavl" :model="$disc"></x-disciplina-textarea>
<x-disciplina-textarea name="dscmtdavligl" :model="$disc"></x-disciplina-textarea>

<x-disciplina-textarea name="crtavl" :model="$disc"></x-disciplina-textarea>
<x-disciplina-textarea name="crtavligl" :model="$disc"></x-disciplina-textarea>

<x-disciplina-textarea name="dscnorrcp" :model="$disc"></x-disciplina-textarea>
<x-disciplina-textarea name="dscnorrcpigl" :model="$disc"></x-disciplina-textarea>

<x-disciplina-textarea name="dscbbgdis" :model="$disc"></x-disciplina-textarea>

<div class="card atividade-extensionista" id="card-extensao">
  <div class="card-header">Atividade extensionista</div>
  <div class="card-body">
    {{-- <x-disciplina-text name="cgahoratvext" :model="$disc"></x-disciplina-text> --}}

    <x-disciplina-textarea name="grpavoatvext" :model="$disc"></x-disciplina-textarea>
    <x-disciplina-textarea name="grpavoatvextigl" :model="$disc"></x-disciplina-textarea>

    <x-disciplina-textarea name="objatvext" :model="$disc"></x-disciplina-textarea>
    <x-disciplina-textarea name="objatvextigl" :model="$disc"></x-disciplina-textarea>

    <x-disciplina-textarea name="dscatvext" :model="$disc"></x-disciplina-textarea>
    <x-disciplina-textarea name="dscatvextigl" :model="$disc"></x-disciplina-textarea>

    <x-disciplina-textarea name="idcavlatvext" :model="$disc"></x-disciplina-textarea>
    <x-disciplina-textarea name="idcavlatvextigl" :model="$disc"></x-disciplina-textarea>
  </div>
</div>

<div class="card">
  <div class="card-header text-center">
    Justificativa da alteração
  </div>
  <div class="card-body p-1">
    <textarea class="form-control changed" name="justificativa">{{ $disc->justificativa }}</textarea>
  </div>
</div>
