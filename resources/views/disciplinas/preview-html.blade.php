@extends('layouts.app')

@section('styles')
  @parent
  <style>
    .card {
      margin-top: 18px;
    }

    .card-header {
      font-size: 20px;
      padding-top: 6px;
      padding-bottom: 6px;
    }

    /* ----------------- */
    #card-basico {
      border: 1px solid Bisque;
      border-top: 3px solid Bisque;
    }

    /* Atividades extensionistas ficarão em um card com cor diferenciada */
    #card-extensao {
      border: 1px solid brown !important;
      border-top: 3px solid brown;
    }

    #card-extensao .card-header {
      text-align: center;
      font-weight: bold;
      background-color: bisque;
    }

    #card-extensao .titulo {
      background-color: bisque !important;
    }

    #card-avaliacao {
      border: 1px solid rosybrown;
      border-top: 3px solid rosybrown;
    }

    #card-avaliacao .card-header {
      text-align: center;
      font-weight: bold;
      background-color: beige;
    }

    #card-avaliacao .titulo {
      background-color: beige !important;
    }

    #card-viagem-didatica {
      border: 1px solid rosybrown;
      border-top: 3px solid rosybrown;
    }

    #card-viagem-didatica .card-header {
      text-align: center;
      font-weight: bold;
      background-color: beige;
    }

    #card-viagem-didatica .titulo {
      background-color: beige !important;
    }
  </style>
@endsection

@section('content')
  @include('disciplinas.partials.preview-navbar')

  <div class="h4 text-center my-3">
    Alteração da disciplina: <b>{{ $disc->coddis }} - {{ $disc->nomdis }}</b>
  </div>

  <div class="my-2">
    <b>Unidade</b>: {{ \Uspdev\Replicado\Estrutura::obterUnidade(config('datagrad.codundclgs')[0])['sglund'] }}
  </div>

  <div class="my-2">
    <b>Departamento</b>: {{ \App\Replicado\Pessoa::retornarSetorFormatado(Auth::user()->codpes) }}
  </div>

  <div class="my-2">
    <b>Alteração para o ano/semestre</b>
    <span class="border rounded px-2 py-1">
      {{ $disc->ano }} / {{ $disc->semestre }}
    </span>
  </div>

  <div class="my-2">
    <b>Justificativa da alteração</b><br>
    <div class="border rounded px-2 py-1">
      {{ $disc->justificativa }} &nbsp;
    </div>
  </div>

  <div>&nbsp;</div>

  <div class="mt-3">
    Data: {{ date('d/m/Y') }}<br>
    {{ Auth::user()->name ?? 'N/A' }}
  </div>

  <hr class="my-3" />

  <div class="">
    Documento original:
    <a href="{{ url()->current() }}">{{ url()->current() }}</a>
  </div>

  <div>
    Estado:&nbsp; @include('disciplinas.partials.badge-estado')
  </div>

  <div class="{{ $disc->estado == 'Em aprovação' ? 'd-print-none' : '' }} my-2">

    @if ($disc->estado == 'Em aprovação')
      @if (session('alert-success'))
        <div class="alert alert-info d-print-none">
          <i class="fas fa-info-circle"></i>
          O documento está pronto para os trâmites de aprovação.<br>
          Gere o PDF e encaminhe-o para os responsáveis pela aprovação.
        </div>
      @endif
      @can('disciplina-cg')
        @include('disciplinas.partials.preview-finalizar-btn')
      @endcan
    @endif

    @if ($disc->estado == 'Em aprovação' || $disc->estado == 'Finalizado')
      @can('admin')
        @include('disciplinas.partials.preview-admin')
      @endcan
    @endif

    @if ($disc->estado == 'Em edição')
      @include('disciplinas.partials.preview-em-aprovacao-btn')
    @endif

    {{-- @includeWhen($disc->estado == 'Em edição', 'disciplinas.partials.print-btn') --}}
  </div>

  <hr class="my-3" />

  <div class="row">
    <div class="col-7">
      <x-disciplina-numero-preview name="creaul" :model="$disc"></x-disciplina-numero-preview>
      <x-disciplina-numero-preview name="cretrb" :model="$disc"></x-disciplina-numero-preview>
      <x-disciplina-select-preview name="tipdis" :model="$disc"></x-disciplina-select-preview>
      <x-disciplina-numero-preview name="numvagdis" :model="$disc"></x-disciplina-numero-preview>
      <x-disciplina-select-preview name="codlinegr" :model="$disc"></x-disciplina-select-preview>
      <x-disciplina-select-preview name="atividade_extensionista" :model="$disc"></x-disciplina-select-preview>
      <x-disciplina-sim-nao-preview name="stavgmdid" :model="$disc"></x-disciplina-sim-nao-preview>
      <x-disciplina-sim-nao-preview name="stapsuatvani" :model="$disc"></x-disciplina-sim-nao-preview>
    </div>
    <div class="col-5">
      <div class=" font-weight-bold">Responsáveis/Professors</div>
      <div class="ml-2">
        @foreach ($disc->responsaveis as $r)
          @if ($r['status'] == 'mesmo')
            <div>{{ $r['codpes'] }} - {{ $r['nompesttd'] }}</div>
          @elseif($r['status'] == 'novo')
            <ins>{{ $r['codpes'] }} - {{ $r['nompesttd'] }}</ins><br>
          @elseif($r['status'] == 'removido')
            <del>{{ $r['codpes'] }} - {{ $r['nompesttd'] }}</del><br>
          @endif
        @endforeach
      </div>
    </div>
  </div>

  <div class="my-1">&nbsp;</div>

  <x-disciplina-text-preview name="nomdis" :model="$disc"></x-disciplina-text-preview>
  <x-disciplina-text-preview name="nomdisigl" :model="$disc"></x-disciplina-text-preview>

  <div class="my-1">&nbsp;</div>

  <x-disciplina-textarea-preview name="pgmrsudis" :model="$disc"></x-disciplina-textarea-preview>
  <x-disciplina-textarea-preview name="pgmrsudisigl" :model="$disc"></x-disciplina-textarea-preview>

  <x-disciplina-textarea-preview name="objdis" :model="$disc"></x-disciplina-textarea-preview>
  <x-disciplina-textarea-preview name="objdisigl" :model="$disc"></x-disciplina-textarea-preview>

  <x-disciplina-textarea-preview name="pgmdis" :model="$disc"></x-disciplina-textarea-preview>
  <x-disciplina-textarea-preview name="pgmdisigl" :model="$disc"></x-disciplina-textarea-preview>

  <x-disciplina-checkbox-preview name="mtdens" :model="$disc"></x-disciplina-checkbox-preview>
  <x-disciplina-checkbox-preview name="mtdensigl" :model="$disc"></x-disciplina-checkbox-preview>

  <div class="card mb-3" id="card-avaliacao">
    <div class="card-header">Instrumentos e critérios de avaliação</div>
    <div class="card-body p-1">
      <x-disciplina-textarea-preview name="dscmtdavl" :model="$disc"></x-disciplina-textarea-preview>
      <x-disciplina-textarea-preview name="dscmtdavligl" :model="$disc"></x-disciplina-textarea-preview>

      <x-disciplina-textarea-preview name="crtavl" :model="$disc"></x-disciplina-textarea-preview>
      <x-disciplina-textarea-preview name="crtavligl" :model="$disc"></x-disciplina-textarea-preview>

      <x-disciplina-textarea-preview name="dscnorrcp" :model="$disc"></x-disciplina-textarea-preview>
      <x-disciplina-textarea-preview name="dscnorrcpigl" :model="$disc"></x-disciplina-textarea-preview>
    </div>
  </div>

  <x-disciplina-textarea-preview name="dscbbgdis" :model="$disc"></x-disciplina-textarea-preview>
  <x-disciplina-textarea-preview name="dscbbgdiscpl" :model="$disc"></x-disciplina-textarea-preview>

  <x-disciplina-checkbox-preview name="objdslsut" :model="$disc" :options="['diff' => false]"></x-disciplina-checkbox-preview>

  {{-- viagem didatica --}}
  @if ($disc->stavgmdid == 'S')
    <div class="card viagem-didatica" id="card-viagem-didatica">
      <div class="card-header">Viagem didática</div>
      <div class="card-body p-1">
        <div class="d-flex flex-column align-items-center pb-2">
          <x-disciplina-sim-nao-preview name="staetr" :model="$disc"></x-disciplina-sim-nao-preview>
        </div>
        <x-disciplina-textarea-preview name="dscatvpvs" :model="$disc"></x-disciplina-textarea-preview>
      </div>
    </div>
  @endif

  {{-- atividade-extensionista --}}
  @if ($disc->atividade_extensionista)
    <div class="card atividade-extensionista" id="card-extensao">
      <div class="card-header">Atividade extensionista</div>
      <div class="card-body p-1">
        <div class="d-flex flex-column align-items-center pb-2">
          <x-disciplina-numero-preview name="cgahoratvext" :model="$disc"></x-disciplina-numero-preview>
        </div>
        <x-disciplina-textarea-preview name="grpavoatvext" :model="$disc"></x-disciplina-textarea-preview>
        <x-disciplina-textarea-preview name="grpavoatvextigl" :model="$disc"></x-disciplina-textarea-preview>

        <x-disciplina-textarea-preview name="objatvext" :model="$disc"></x-disciplina-textarea-preview>
        <x-disciplina-textarea-preview name="objatvextigl" :model="$disc"></x-disciplina-textarea-preview>

        <x-disciplina-textarea-preview name="dscatvext" :model="$disc"></x-disciplina-textarea-preview>
        <x-disciplina-textarea-preview name="dscatvextigl" :model="$disc"></x-disciplina-textarea-preview>

        <x-disciplina-textarea-preview name="idcavlatvext" :model="$disc"></x-disciplina-textarea-preview>
        <x-disciplina-textarea-preview name="idcavlatvextigl" :model="$disc"></x-disciplina-textarea-preview>
      </div>
    </div>
  @endif

  {{-- pratica animais --}}
  @if ($disc->stapsuatvani == 'S')
    <div class="card animais" id="card-animais">
      <div class="card-header text-center font-weight-bold">
        Prática com animais e/ou materiais biológicos
      </div>
      <div class="card-body p-1">
        <x-disciplina-text-preview name="ptccmseiaani" :model="$disc"></x-disciplina-text-preview>
        <x-disciplina-data-preview name="dtainivalprp" :model="$disc"></x-disciplina-data-preview>
        <x-disciplina-data-preview name="dtafimvalprp" :model="$disc"></x-disciplina-data-preview>
      </div>
    </div>
  @endif

  {{-- habilidades e competencias --}}
  @include('disciplinas.partials.preview-cursos')

  @if ($disc->estado == 'Em edição')
    @include('disciplinas.partials.preview-em-aprovacao-btn')
  @endif
@endsection
