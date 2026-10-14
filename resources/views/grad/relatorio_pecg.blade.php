@extends('layouts.app')

@section('content')
    <h4>Relatório de Alunos com Convênio PEC-G</h4>
    <div class="mb-3">
        Lista os alunos ingressantes com convênio PEC-G por intervalo de anos de ingresso.
    </div>

    <form method="POST" action="{{ route('graduacao.relatorio_pecg.post') }}">
        @csrf
        
        <div class="row align-items-end">
            <div class="col-md-3 form-group">
                <label for="anoInicioInput"><b>Ano Inicial:</b></label>
                <select name="ano_inicio" id="anoInicioInput" class="form-control @error('ano_inicio') is-invalid @enderror" required>
                    <option value="">Selecione o Ano Inicial</option>
                    @foreach (range(date('Y'), 2000) as $ano)
                        <option value="{{ $ano }}" {{ (old('ano_inicio', $anoInicio ?? '') == $ano) ? 'selected' : '' }}>
                            {{ $ano }}
                        </option>
                    @endforeach
                </select>
                @error('ano_inicio')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3 form-group">
                <label for="anoFimInput"><b>Ano Final:</b></label>
                <select name="ano_fim" id="anoFimInput" class="form-control @error('ano_fim') is-invalid @enderror" required>
                    <option value="">Selecione o Ano Final</option>
                    @foreach (range(date('Y'), 2000) as $ano)
                        <option value="{{ $ano }}" {{ (old('ano_fim', $anoFim ?? '') == $ano) ? 'selected' : '' }}>
                            {{ $ano }}
                        </option>
                    @endforeach
                </select>
                @error('ano_fim')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2 form-group">
                <button type="submit" class="btn btn-sm btn-primary spinner">Buscar</button>
            </div>
        </div>
    </form> 

    @if(!empty($alunos))
        <hr>
        <div class="h4 mt-3">Resultados</div>
        
        <div class="table-responsive">
            <table class="table table-bordered mt-3 table-hover table-sm datatable-simples dt-buttons dt-fixed-header">
                <thead class="thead-light text-center">
                    <tr>
                        <th>Ingresso</th>
                        <th>Nº USP</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Professor Tutor</th>
                        <th>País</th>
                        <th>Situação</th>
                        <th>Curso</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($alunos as $aluno)
                        <tr>
                            <td>{{ $aluno['ano_ingresso'] }}</td>
                            <td>{{ $aluno['numero_usp'] }}</td>
                            <td class="text-left">{{ $aluno['nome_aluno'] }}</td>
                            <td class="text-left">{{ $aluno['email_aluno'] ?? '-' }}</td>
                            <td class="text-left">{{ $aluno['professor_tutor'] ?? '-' }}</td>
                            <td>{{ $aluno['pais'] ?? '-' }}</td>
                            <td>{{ $aluno['situacao'] }}</td>
                            <td class="text-left">{{ $aluno['curso'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif(request()->isMethod('post'))
        <hr>
        <div class="alert alert-warning mt-3">
            Nenhum aluno com convênio PEC-G encontrado para o período informado.
        </div>
    @endif
@endsection