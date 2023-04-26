@extends('layouts.app')

@section('content')
  @include('grad.partials.curso-menu', ['view' => 'Turmas'])
  <div class="mt-3 ml-3 d-inline-flex">

    <div class="border border-primary rounded" style="padding-top: 7px;">

      <form method="get" class="form-inline" id="form-semestre">
        <div class="mx-2"><b>Semestre</b>: de</div>
        <select class="border-0 input-small font-weight-bold" name="semestreIni" id="select-semestre-ini">
          @foreach ($turmaSelect as $t)
            <option {{ $t == $semestreIni ? 'selected' : '' }}>{{ $t }}</option>
          @endforeach
        </select>

        <div class="mx-2">a</div>
        <select class="border-0 input-small font-weight-bold" name="semestreFim" id="select-semestre-fim">
          @foreach ($turmaSelect as $t)
            <option {{ $t == $semestreFim ? 'selected' : '' }}>{{ $t }}</option>
          @endforeach
        </select>
        <div class="mx-2"></div>

        <input type="hidden" name="codhab" value="{{ $curso['codhab'] }}">
        <button class="btn btn-sm btn-primary d-none btn-spinner py-0 mr-2" type="submit">OK</button>
      </form>
    </div>

    <form method="POST" action="{{ route('graduacao.relatorio.sintese.post') }}">
      @csrf
      <input type="hidden" name="nomes" value="{!! $nomes !!}">
      <button class="btn btn-outline-info btn-spinner ml-3" type="submit"
        title="{{ $nomesCount }} nomes não repetidos">
        Relatório síntese <span class="badge badge-pill badge-primary">{{ $nomesCount }}</span>
      </button>
    </form>

    <form method="POST" action="{{ route('graduacao.relatorio.complementar.post') }}">
      @csrf
      <input type="hidden" name="nomes" value="{!! $nomes !!}">
      <button class="btn btn-outline-info btn-spinner ml-3" type="submit"
        title="{{ $nomesCount }} nomes não repetidos">
        Relatório complementar <span class="badge badge-pill badge-primary">{{ $nomesCount }}</span>
      </button>
    </form>

  </div>

  <div class="mt-3 ml-3">
    Obs.: Exclui as turmas "não ativas"<br>
    Obs2.: Carga hor: carga horária teórica/prática - contabiliza somente créditos aula, não contabiliza créditos trabalho
    uma vez que não são créditos ministrados pelos docentes.
  </div>
  <hr />

  <table class="table table-sm table-hover datatable-simples dt-buttons dt-fixed-header">
    <thead class="thead-light">
      <tr>
        <th>Cód Turma</th>
        <th>Cód dis</th>
        <th>Nome</th>
        <th>Professor</th>
        <th>Ativ. Didática</th>
        <th>Versão</th>
        <th>Carga Hor.</th>
        <th>Tipo</th>
        <th>Status</th>
        <th>Obs</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($turmas as $t)
        <tr>
          <td>{{ $t['codtur'] }}</td>
          <td>{{ $t['coddis'] }}</td>
          <td>{{ $t['nomdis'] }}</td>
          <td>
            @foreach ($t['ministrantes'] as $m)
              {!! $m['stamis'] == 'N' ? '' : '<span class="badge badge-info" title="Quinzenal">15d</span>' !!}
              @include('grad.partials.pessoa-btn-modal', ['pessoa' => $m])<br />
            @endforeach
          </td>
          <td>
            @foreach ($t['ativDidaticas'] as $a)
              ({{ $a['nomatv'] }})
              @include('grad.partials.pessoa-btn-modal', ['pessoa' => $a])<br />
            @endforeach
          </td>
          <td>{{ $t['verdis'] }}</td>
          <td>{{ $t['cgahorteo'] }}/{{ $t['cgahorpra'] }}</td>
          <td>{{ $t['tiptur'] }}</td>
          <td>{{ $graduacao::$statur[$t['statur']] }}</td>
          <td>{{ $t['obstur'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection

@section('javascripts_bottom')
  @parent
  <script>
    $('#form-semestre select').change(function() {
      $('#form-semestre').find(':submit').removeClass('d-none')
      console.log('mudou')
    })
  </script>
@endsection
