{{-- Template para geração de PDF de disciplina --}}
<style>
  table {
    width: 100%;
    margin-top: 15px;
  }

  td {
    width: 50%;
    vertical-align: text-top;
    padding: 5px;
    line-height: 1.25;
  }

  table,
  th,
  td {
    border: 1px solid black;
    border-collapse: collapse;
    page-break-inside: avoid;
  }

  th {
    text-align: center;
    padding: 5px;
    font-weight: bold;
  }

  /*

  .float-container {
    border: 0px solid #fff;
    padding: 0px;
    margin-right: 24px;
  }

  .float-child {
    width: 50%;
    float: left;
    padding: 5px;
    border: 1px solid red;
  }

  table {
    width: 100%;
    margin-top: 15px;
  }

  td {
    width: 50%;
    vertical-align: text-top;
    padding: 5px;
    line-height: 1.25;
  }

  table,
  th,
  td {
    border: 1px solid black;
    border-collapse: collapse;
    page-break-inside: avoid;
  }

  th {
    text-align: center;
    padding: 5px;
    font-weight: bold;
  }




  .div-table {
    display: table;
    width: 100%;
    border: 1px solid black;
    margin-top: 15px;
    /* border-spacing: 5px; */
  /* cellspacing:poor IE support for  this */
  /* page-break-inside: avoid; */

  /* } */

  .pagebreak {
    page-break-before: always;
  }

  * {
    box-sizing: border-box;
  }

  div {
    line-height: 1.25;
  }

  /* Create two equal columns that floats next to each other */
  .column {
    float: left;
    width: 40%;
    padding: 10px;
    /* height: 300px; */
    /* Should be removed. Only for demonstration */
    border: 1px solid black;
    /* margin-right:10px; */
  }

  .titulo {
    width: 100%;
    padding: 10px;
    border: 1px solid black;
  }

  /* Clear floats after the columns */
  .row:after {
    content: "";
    display: table;
    clear: both;
  }

  .row {
    margin-right: 15px;
  }

  .pagenum:before {
        content: counter(page);
    }

</style>

{{-- @section('styles')
@endsection --}}

<span class="pagenum"></span>
<h3 style="text-align: center;">Alteração de disciplina</h3>
  <table style="border: 0px;"  cellspacing="0" cellpadding="0">
    <tr>
      <td style="font-size: 20px; vertical-align: middle; padding-left:20px;">Alteração para o ano/semestre: <b>{{ $disc->ano }} / {{ $disc->semestre }}</b></td>
      <td style="width: 25%; text-align: right;">Data do documento: {{ $disc->pdf['date'] }}<br>
        Hash: {{ $disc->pdf['hash'] }}</td>
    </tr>
  </table>
<br>
<div>Código/Code: <b>{{ $disc->coddis }}</b></div>
<div>Nome/Title: <b>{{ $disc->nomdis }} / {{ $disc->nomdisigl }}</b></div>
<div>Tipo: <b>{{ $disc->tipdis() }}</b></div>
<div>Créditos aula: <b>{{ $disc->creaul }}</b></div>
<div>Créditos trabalho: <b>{{ $disc->cretrb }}</b></div>
<div>Número de vagas: <b>{{ $disc->numvagdis }}</b></div>
<div>Atividade extensionista: <b>{{ $disc->atividade_extensionista ? 'Sim' : 'Não' }}</b></div>
<hr>

<x-pdf-text name="nomdis" :model="$disc"></x-pdf-text>
<x-pdf-text name="nomdisigl" :model="$disc"></x-pdf-text>


<table>
  <tr>
    <th>Justificativa da alteração</th>
  </tr>
  <tr>
    <td> {!! str_replace("\n", '<br>', $disc->justificativa) !!}</td>
  </tr>
</table>
<br>
<div>
  Responsáveis: {{ $disc->retornarListaResponsaveis() }}
</div>

<div class="pagebreak"></div>

<x-pdf-textarea name="objdis" :model="$disc"></x-pdf-textarea>
<x-pdf-textarea name="objdisigl" :model="$disc"></x-pdf-textarea>
<x-pdf-textarea name="pgmrsudis" :model="$disc"></x-pdf-textarea>
<x-pdf-textarea name="pgmrsudisigl" :model="$disc"></x-pdf-textarea>
<x-pdf-textarea name="pgmdis" :model="$disc"></x-pdf-textarea>
<x-pdf-textarea name="pgmdisigl" :model="$disc"></x-pdf-textarea>

<x-pdf-textarea name="dscmtdavl" :model="$disc"></x-pdf-textarea>
<x-pdf-textarea name="dscmtdavligl" :model="$disc"></x-pdf-textarea>

<x-pdf-textarea name="crtavl" :model="$disc"></x-pdf-textarea>
<x-pdf-textarea name="crtavligl" :model="$disc"></x-pdf-textarea>

<x-pdf-textarea name="dscnorrcp" :model="$disc"></x-pdf-textarea>
<x-pdf-textarea name="dscnorrcpigl" :model="$disc"></x-pdf-textarea>

<x-pdf-textarea name="dscbbgdis" :model="$disc"></x-pdf-textarea>

@if ($disc->atividade_extensionista)
  <div id="div-extensao">
    <x-disciplina-text name="cgahoratvext" :model="$disc"></x-disciplina-text>

    <x-pdf-textarea name="grpavoatvext" :model="$disc"></x-pdf-textarea>
    <x-pdf-textarea name="grpavoatvextigl" :model="$disc"></x-pdf-textarea>

    <x-pdf-textarea name="objatvext" :model="$disc"></x-pdf-textarea>
    <x-pdf-textarea name="objatvextigl" :model="$disc"></x-pdf-textarea>

    <x-pdf-textarea name="dscatvext" :model="$disc"></x-pdf-textarea>
    <x-pdf-textarea name="dscatvextigl" :model="$disc"></x-pdf-textarea>

    <x-pdf-textarea name="idcavlatvext" :model="$disc"></x-pdf-textarea>
    <x-pdf-textarea name="idcavlatvextigl" :model="$disc"></x-pdf-textarea>
  </div>
@endif
<br>
<br>
@foreach ($disc->cursos as $curso)
  <b>Habilidades e competências para o curso {{ $curso['codcur'] }} - {{ $curso->dr['nomcur'] }}</b><br>
  @if (empty($curso->habilidades))
    Habilidades não cadastradas!
  @else
    <b>Habilidades</b><br>
    @foreach (explode(PHP_EOL, $curso->habilidades) as $hab)
      @if ($disc->checkHabilidades($curso->codcur, $hab))
        H{{ $loop->index }}. {{ $hab }}<br>
      @endif
    @endforeach
  @endif
  <br>
  @if (empty($curso->competencias))
    Competências não cadastradas!
  @else
    <b>Competências</b> <br>
    @foreach (explode(PHP_EOL, $curso->competencias) as $con)
      @if ($disc->checkCompetencias($curso->codcur, $con))
        C{{ $loop->index }}. {{ $con }}<br>
      @endif
    @endforeach
  @endif
@endforeach

{{-- <br>
<b>Habilidades (conforme PPC)</b>
@foreach ($disc->habilidades as $cursos)
  @foreach ($cursos as $codcur => $hab)
  {{ $hab }}<br><br>
  @endforeach
@endforeach

<br>
<b>Competências</b><br>
@foreach ($disc->competencias as $cursos)
  @foreach ($cursos as $codcur => $con)
  {{ $con }}<br><br>
  @endforeach
@endforeach --}}



{{-- <table>
  <tr>
    <th colspan="2">Objetivos / Objectives</th>
  </tr>
  <tr>
    <td>{!! str_replace("\n", '<br>', $disc->objdis) !!}</td>
    <td>{!! str_replace("\n", '<br>', $disc->objdisigl) !!}</td>
  </tr>
</table>


<div class="float-container">
  <div style="text-align: center;">
    <b>Objetivos / Objectives</b>
  </div>
  <div class="float-child purple">
    {!! str_replace("\n", '<br>', $disc->objdis) !!}
  </div>

  <div class="float-child green">
    {!! str_replace("\n", '<br>', $disc->objdisigl) !!}
  </div>

</div>
<div>
  <p style="text-align: center;"><b>Objetivos / Objectives</b></p>
  <p style="border: 1px solid black; padding: 10px;">{!! str_replace("\n", '<br>', $disc->objdis) !!}</p>
  <p style="border: 1px solid red; padding: 10px; ">{!! str_replace("\n", '<br>', $disc->objdisigl) !!}</p>
</div> --}}
