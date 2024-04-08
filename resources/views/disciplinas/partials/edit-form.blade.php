@section('styles')
  @parent
  <style>
    #card-basico {
      border: 1px solid Bisque;
      border-top: 3px solid Bisque;
    }

    ins {
      background-color: lightgreen;
    }

    del {
      background-color: lightsalmon;
    }
  </style>
@endsection

<div class="row mt-3">
  <div class="col-md-6">

    <div>Código: <b>{{ $disc['coddis'] }}</b></div>
    <div>Nome/Title: <b>{{ $disc['nomdis'] }} / {{ $disc['nomdisigl'] }}</b></div>
    <div>Tipo/Type:
      <b>
        @if ($disc['tipdis'] == 'S')
          Semestral
        @elseif($disc['tipdis'] == 'A')
          Anual
        @elseif($disc['tipdis'] == 'Q')
          Quadrimestral
        @endif
      </b>
    </div>
    <div>Créditos-aula/Semester hour credits: <b>{{ $disc['creaul'] }}</b></div>
    <div>Créditos-trabalho/Practice hour credits: <b>{{ $disc['cretrb'] }}</b></div>
    <div>Número de vagas/Number of places: <b>{{ $disc['numvagdis'] }}</b></div>
    <div>Duração (semanas): <b>{{ $disc['durdis'] }}</b></div>
    <div>
      Responsáveis/Professors:
      @foreach ($responsaveis as $r)
        <b>{{ $r['codpes'] }} - {{ $r['nompesttd'] }}</b>,
      @endforeach
    </div>

  </div>
  <div class="col-md-6">

    {{-- <div class="form-group row my-0">
      <label class="col-form-label" for="atividade-animais">
        Atividades práticas com animais e/ou materiais biológicos
      </label>
      <div>
        <select class="form-control form-control-sm mx-3" name="stapsuatvani" id="atividade-animais">
          <option value="S" {{ $disc['stapsuatvani'] == 'S' ? 'selected' : '' }}>Sim</option>
          <option value="N" {{ $disc['stapsuatvani'] != 'S' ? 'selected' : '' }}>Não</option>
        </select>
      </div>
    </div> --}}

    <div class="form-group row my-0">
      <label class="col-form-label" for="atividade-extensao">Atividades extensionistas</label>
      <div>
        <select class="form-control form-control-sm mx-3" name="atividade_extensionista" id="atividade-extensionista">
          <option value="1" {{ $disc->atividade_extensionista ? 'selected' : '' }}>Sim</option>
          <option value="0" {{ $disc->atividade_extensionista ? '' : 'selected' }}>Não</option>
        </select>
      </div>
    </div>

    <div class="form-group row my-0 form-inline">
      <label class="col-form-label">Alteração para o ano/semestre de </label>
      <select class="form-control form-control-sm mx-2" name="ano" id="ano">
        <option>Ano ..</option>
        <option {{ $disc->ano == '2024' ? 'selected' : '' }}>2024</option>
        <option {{ $disc->ano == '2025' ? 'selected' : '' }}>2025</option>
      </select>
      <select class="form-control form-control-sm mx-2" name="semestre" id="semestre">
        <option>Semestre ..</option>
        <option {{ $disc->semestre == '1' ? 'selected' : '' }}>1</option>
        <option {{ $disc->semestre == '2' ? 'selected' : '' }}>2</option>
      </select>
    </div>

    <div class="row mt-3">
      Criado por: {{ $disc->criadoPor->name }} em {{ $disc->created_at->format('d/m/Y') }}
    </div>
    <div class="row">
      Última alteração por: {{ $disc->atualizadoPor->name }} em {{ $disc->updated_at->format('d/m/Y') }}
    </div>

  </div>
</div>

</div>

<hr />

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

<div id="div-extensao">
  <x-disciplina-text name="cgahoratvext" :model="$disc"></x-disciplina-text>
  {{-- <x-disciplina-text titulo="Extensão: Carga horária (horas)" :campo="$disc['cgahoratvext']"></x-disciplina-text> --}}

  <x-disciplina-textarea name="grpavoatvext" :model="$disc"></x-disciplina-textarea>
  <x-disciplina-textarea name="grpavoatvextigl" :model="$disc"></x-disciplina-textarea>

  <x-disciplina-textarea name="objatvext" :model="$disc"></x-disciplina-textarea>
  <x-disciplina-textarea name="objatvextigl" :model="$disc"></x-disciplina-textarea>

  <x-disciplina-textarea name="dscatvext" :model="$disc"></x-disciplina-textarea>
  <x-disciplina-textarea name="dscatvextigl" :model="$disc"></x-disciplina-textarea>

  <x-disciplina-textarea name="idcavlatvext" :model="$disc"></x-disciplina-textarea>
  <x-disciplina-textarea name="idcavlatvextigl" :model="$disc"></x-disciplina-textarea>
</div>

<div class="card">
  <div class="card-header text-center">
    Proposta (justificativa)
  </div>
  <div class="card-body p-1">
    <textarea class="form-control changed" name="justificativa">{{ $disc->justificativa }}</textarea>
  </div>
</div>

{{-- <pre>
  {{ json_encode($disc, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
</pre> --}}


@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {

      var mostrarOcultarExtensao = function() {
        // console.log('extensao', $('#atividade-extensao').val())
        if ($('#atividade-extensionista').val() == 1) {
          $('#div-extensao').removeClass('d-none')
        } else {
          $('#div-extensao').addClass('d-none')
        }
      }
      mostrarOcultarExtensao()
      $('#atividade-extensionista').on('change', mostrarOcultarExtensao)



    })
  </script>
@endsection
