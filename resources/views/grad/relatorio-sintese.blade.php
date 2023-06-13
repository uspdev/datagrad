@extends('layouts.app')

@section('content')
  <h4>Relatório síntese</h4>

  <form method="POST" action="">
    @csrf
    <div class="form-group">
      <label for="nomesTextarea">Forneça uma lista de nomes (1 por linha)</label>
      <textarea name="nomes" class="form-control" id="nomesTextarea" rows="4">{{ old('nomes') }}</textarea>
    </div>
    <button type="submit" class="btn btn-sm btn-primary spinner">Enviar</button>
  </form>

  @if ($naoEncontrados)
    <hr>
    <div class="h4">Não encontrados</div>
    @foreach ($naoEncontrados as $nome)
      {{ $nome }}<br>
    @endforeach
  @endif

  @if ($pessoas)
    <hr>
    <div class="h4 mt-3">Resultados</div>
    <div class="alert alert-info">
      <b>Observações</b><br />
      1. Dados de graduação, mestrado, etc foram obtidos à partir do currículo lattes.<br />
      2. A idade é a que completa no ano corrente.<br />
      {{-- <button class="btn btn-info btn-sm">OK</button> --}}
    </div>
    <table class="table table-bordered table-hover table-sm datatable-simples dt-buttons dt-fixed-header">
      <thead class="thead-light">
        <tr>
          <th>Unidade</th>
          <th>Depto</th>
          <th>No. USP</th>
          <th>Nome</th>
          <th>Idade</th>
          <th>Nome Função</th>
          <th>Tipo Jornada</th>
          <th>Lattes</th>
          <th>Data atual. Lattes</th>
          <th>Orcid</th>
          <th>Graduação</th>
          <th>Mestrado</th>
          <th>Doutorado</th>
          <th>Livre docencia</th>
          <th>Pós-doutorado</th>
          <th>Especialista</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($pessoas as $pessoa)
          <tr>
            <td>{{ $pessoa['unidade'] }}</td>
            <td>{{ $pessoa['departamento'] }}</td>
            <td>{{ $pessoa['codpes'] }}</td>
            <td>@include('grad.partials.pessoa-btn-modal')</td>
            <td>{{ $pessoa['idade'] }}</td>
            <td>{{ $pessoa['nomeFuncao'] }}</td>
            <td>{{ $pessoa['tipoJornada'] }}</td>
            <td>
              <a href="https://lattes.cnpq.br/{{ $pessoa['lattes'] }}" target="lattes">
                https://lattes.cnpq.br/{{ $pessoa['lattes'] }}
              </a>
            </td>
            <td data-sort="{{ Uspdev\Utils\Generic::data_en($pessoa['dtaultalt']) }}">{{ $pessoa['dtaultalt'] ?? '-' }}
            </td>
            <td>{!! $pessoa['linkOrcid'] !!}</td>
            <td>{{ $pessoa['graduacao'] ?? '-' }}</td>
            <td>{{ $pessoa['mestrado'] ?? '-' }}</td>
            <td>{{ $pessoa['doutorado'] ?? '-' }}</td>
            <td>{{ $pessoa['livre-docencia'] ?? '-' }}</td>
            <td>{{ $pessoa['pos-doutorado'] ?? '-' }}</td>
            <td>{{ $pessoa['especializacao'] ?? '-' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

@endsection
