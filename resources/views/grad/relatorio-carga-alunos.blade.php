@extends('layouts.app')

@section('content')
    <h4>Relatório carga horária cumprida por aluno</h4>
    <div>
        Lista a carga horária cumprida pelo aluno, juntamente com a porcentagem.
    </div>

    <form method="POST" action="">
        @csrf
        <div class="form-group">
            <label for="nuspsTextarea">Forneça uma lista que contenha nomes ou números USP (1 por linha)</label>
            <textarea name="nusps" class="form-control" id="nuspsTextarea" rows="4">{{ old('nusps') }}</textarea>
        </div>
        <button type="submit" class="btn btn-sm btn-primary spinner mt-3">Enviar</button>
    </form> 

    @if($naoEncontrados)
        <div class="alert alert-warning mt-3">
            <b>Não encontrados:</b>
            <ul>
                @foreach($naoEncontrados as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($resultados)
        <hr>
        <div class="h4 mt-3">Resultados</div>
        <table class="table table-bordered mt-3 table-hover datatable-simples dt-buttons dt-fixed-header">
            <thead class="thead-light">
                <tr>
                    <th>Nº USP</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Curso</th>
                    <th>Complemento</th>
                    <th>Ingresso</th>
                    <th>Carga Exigida</th>
                    <th>Carga Cumprida</th>
                    <th>% Progresso</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resultados as $r)
                <tr>
                    <td>{{ $r['codpes'] }}</td>
                    <td>{{ $r['nompes'] }}</td>
                    <td>{{ $r['email'] }}</td>
                    <td>{{ $r['curso'] }}</td>
                    <td>{{ $r['habilitacao'] }}</td>
                    <td>{{ $r['ano'] }}</td>
                    <td>{{ $r['exigida'] }} h</td>
                    <td>{{ $r['cumprida'] }} h</td>
                    <td>
                        <div class="progress" style="height: 25px; background-color: #e9ecef; position: relative;">
                            <div class="progress-bar" role="progressbar" 
                                style="width: {{ str_replace(',', '.', $r['porcentagem']) }}%; background-color: #56a5ccc1;" 
                                aria-valuenow="{{ str_replace(',', '.', $r['porcentagem']) }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>

                            <div style="position: absolute; width: 100%; text-align: center; line-height: 25px; color: black; font-weight: bold; font-size: 1rem;">
                                {{ $r['porcentagem'] }}%
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection