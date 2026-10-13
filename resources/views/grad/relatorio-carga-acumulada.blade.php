@extends('layouts.app')

@section('content')
    <h4>Relatório de Carga Horária Acumulada</h4>
    <div class="mb-3">
        Lista o detalhamento das cargas horárias acumuladas (Obrigatória, Optativa, Estágio, Complementar e Extensionista). <br> Selecione o curso e ano de ingresso OU uma lista que contenha nomes ou números USP.
    </div>

    <form method="POST" action="{{ route('graduacao.relatorio.carga-acumulada.post') }}">
        @csrf
        
        <div class="row align-items-end">
            <div class="col-md-8 form-group">
                <label for="cursoSelect"><b>Curso:</b></label>
                <select name="codcur" id="cursoSelect" class="form-control" required>
                    <option value="">Selecione o Curso</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso['codcur'] }}" {{ old('codcur') == $curso['codcur'] ? 'selected' : '' }}>
                            {{ $curso['codcur'] }} - {{ $curso['nomcur'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-3 form-group">
                <label for="anoInput"><b>Ano de Ingresso:</b></label>
                <select name="ano_ingresso" id="anoInput" class="form-control">
                    <option value="">Selecione o Ano</option>
                    @foreach (range(date('Y'), 2015) as $ano)
                        <option value="{{ $ano }}" {{ old('ano_ingresso') == $ano ? 'selected' : '' }}>
                            {{ $ano }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
           <b>OU</b>
        </div>

        <div class="row mt-2">
            <div class="col form-group">
                <label for="nuspsTextarea"><b>Forneça uma lista que contenha nomes ou números USP (1 por linha):</b></label>
                <textarea name="nusps" class="form-control" id="nuspsTextarea" rows="4" required>{{ old('nusps') }}</textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-sm btn-primary spinner mt-2">Enviar</button>
    </form> 

    @if(!empty($naoEncontrados))
        <div class="alert alert-warning mt-4">
            <b>Não encontrados ou inconsistentes (fora do curso/ano selecionado):</b>
            <ul class="mb-0">
                @foreach($naoEncontrados as $item)
                    <li>{!! $item !!}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(!empty($resultados))
        <hr>
        <div class="h4 mt-3">Resultados</div>
        
        <div class="table-responsive">
            <table class="table table-bordered mt-3 table-hover table-sm datatable-simples dt-buttons dt-fixed-header">
                <thead class="thead-light text-center">
                    <tr>
                        <th>Nº USP</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        
                        <th>Curso</th>
                        <th>Hab.</th>
                        
                        <th>Ingresso</th>
                        <th>Obrigatória</th>
                        <th>Optativa</th>
                        <th>Estágio</th>
                        <th>AAC</th>
                        <th>AEX</th>
                        <th class="table-primary text-dark">Total Acumulado</th>
                        <th>Exigida</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($resultados as $r)
                        <tr>
                            <td>{{ $r['codpes'] }}</td>
                            <td class="text-left">{{ $r['nompes'] }}</td>
                            <td class="text-left">{{ $r['email'] ?? 'N/C' }}</td>
                            
                            <td>{{ $r['codcur'] }}</td>
                            <td>{{ $r['codhab'] }}</td>
                            
                            <td>{{ $r['ano_ingresso'] }}</td>
                            <td>{{ $r['carga_obrigatoria'] }} h</td>
                            <td>{{ $r['carga_optativa'] }} h</td>
                            <td>{{ $r['carga_estagio'] }} h</td>
                            <td>{{ $r['carga_complementar'] }} h</td>
                            <td>{{ $r['carga_extensionista'] }} h</td>
                            <td class="table-primary font-weight-bold text-dark">{{ $r['total_acumulado'] }} h</td>
                            <td class="table-primary font-weight-bold text-dark">{{ $r['exigida'] }} h</td>
                            <td style="min-width: 120px; vertical-align: middle;">
                                @php
                                    $pctFloat = (float) str_replace(',', '.', $r['porcentagem']);
                                @endphp

                                <div class="progress position-relative" style="height: 24px; background-color: #003366; font-weight: bold; border-radius: 4px;">
                                    <div class="progress-bar bg-info" 
                                        role="progressbar" 
                                        style="width: {{ min($pctFloat, 100) }}%;" 
                                        aria-valuenow="{{ $pctFloat }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                    </div>
                                    <span class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center text-white" 
                                        style="top: 0; left: 0; pointer-events: none; font-size: 0.85rem; text-shadow: 0px 0px 3px rgba(0,0,0,0.8);">
                                        {{ $r['porcentagem'] }}%
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection