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
  <div class="col-md-5">

    @include('disciplinas.partials.form-ano-semestre')

    <hr class="my-1" />

    @include('disciplinas.partials.form-tipo')

    <x-disciplina-numero name="creaul" :model="$disc"></x-disciplina-numero>
    <x-disciplina-numero name="cretrb" :model="$disc"></x-disciplina-numero>
    <x-disciplina-numero name="numvagdis" :model="$disc"></x-disciplina-numero>

    @include('disciplinas.partials.form-ativ-extensionista')

  </div>
  <div class="col-md-4">
    {{-- @include('disciplinas.partials.form-ativ-animais') --}}
    @include('disciplinas.partials.form-responsaveis')

  </div>
  <div class="col-md-3">
    <div class="alert alert-info mt-3 small">
      <div>Estado atual:
        <b>{{ $disc->estado }}</b>, por {{ $disc->atualizadoPor->name }}<br>
        Chave: {{ $disc->hash() }}
      </div>
      <hr>

      <div><b>Histórico</b></div>
      <div>
        @forelse($disc->historico->reverse() as $h)
          {{ $h['estado'] }}, {{ $h['data'] }}, {{ $h['user'] }}<br>
          <div class="ml-3">
            @if ($h['comentario'])
              {{ $h['comentario'] }}
            @endif
          </div>
        @empty
        @endforelse
      </div>
    </div>
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
