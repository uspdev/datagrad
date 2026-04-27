@extends('layouts.app')

@section('content')
    <h4>Relatório de Carga Horária Extensionista</h4>
        <form method="POST" action="{{ route('graduacao.relatorio.carga-extensao') }}">
            @csrf
            <div class="row align-items-end">
                <div class="col-md-6">
                    <label>Curso:</label>
                    <select name="curso" class="form-control">
                        @foreach($cursoOpcao as $curso)
                            <option value="{{ $curso['codcur'] }}" {{ (isset($params['curso']) && $params['curso'] == $curso['codcur']) ? 'selected' : '' }}>
                                {{ $curso['codcur'] }} - {{ $curso['nomcur'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Ano de Ingresso:</label>
                    <input type="number" name="ano" class="form-control" value="{{ $params['ano'] ?? date('Y') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </div>
        </form>

        @if(isset($alunosCarga))
            <hr>
            <table class="table table-bordered table-hover table-sm datatable-simples dt-buttons dt-fixed-header">
                <thead>
                    <tr>
                        <th>Ano de Ingresso</th>
                        <th>Nº USP</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Total de Carga Extensão Cumprida (h)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alunosCarga as $aluno)
                    <tr>
                        <td>{{ $aluno['ano_ingresso'] }}</td>
                        <td>{{ $aluno['codpes'] }}</td>
                        <td>{{ $aluno['nompes'] }}</td>
                        <td>{{ $aluno['email'] ?? 'N/C' }}</td>
                        <td>{{ number_format($aluno['carga_total_extensao_horas'], 0, '', '') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

@endsection