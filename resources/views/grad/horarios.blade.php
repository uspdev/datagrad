@extends('laravel-usp-theme::master')

@section('content')
    <h4>Horário de Aulas</h4>
    <div class="mb-3">
        Selecione o curso e o ano/semestre para visualizar a grade de horários por período letivo.
    </div>

    <form method="POST" action="{{ route('graduacao.horarios') }}">
        @csrf
        <div class="form-row align-items-end">
            <div class="col-md-7 form-group mb-3">
                <label for="select_curso_hab"><b>Curso / Habilitação:</b></label>
                <select name="curso_hab" id="select_curso_hab" class="form-control" required>
                    <option value="">Selecione o Curso...</option>
                    @foreach($cursosHabilitacoes as $ch)
                        @php 
                            $val = $ch['codcur'] . '-' . $ch['codhab']; 
                            $selected = (isset($codcur) && isset($codhab) && $codcur == $ch['codcur'] && $codhab == $ch['codhab']) ? 'selected' : '';
                        @endphp
                        <option value="{{ $val }}" {{ $selected }}>
                            {{ $ch['codcur'] }} / {{ $ch['codhab'] }} - {{ $ch['nomcur'] }} ({{ $ch['nomhab'] }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 form-group mb-3">
                <label for="semestreInput"><b>Ano/Semestre:</b></label>
                <select name="semestre" id="semestreInput" class="form-control" required>
                    @foreach($semestreSelect as $key => $val)
                        @php $semestreValor = is_numeric($key) ? $val : $key; @endphp
                        <option value="{{ $semestreValor }}" {{ (isset($semestre) && $semestre == $semestreValor) ? 'selected' : '' }}>
                            {{ $semestreValor }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 form-group mb-3">
                <button type="submit" class="btn btn-primary btn-block">Buscar Grade</button>
            </div>
        </div>
    </form> 

    @if(!empty($gradePorPeriodo))
        <hr>
        @foreach($gradePorPeriodo as $periodo => $dias)
            <div class="h5 mt-4 mb-2 font-weight-bold text-center text-uppercase">
                {{ substr($semestre, -1) == '2' ? '2º SEMESTRE DE ' . substr($semestre, 0, 4) : '1º SEMESTRE DE ' . substr($semestre, 0, 4) }} 
                &nbsp;&mdash;&nbsp; {{ $periodo }}º PERÍODO LETIVO
            </div>
            
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-sm text-center m-0" style="font-size: 0.85rem; table-layout: fixed; min-width: 950px;">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;" class="align-middle text-secondary">Dia</th>
                            <th style="width: 9.5%;">07h20min</th>
                            <th style="width: 9.5%;">08h10min</th>
                            <th style="width: 9.5%;">09h20min</th>
                            <th style="width: 9.5%;">10h10min</th>
                            <th style="width: 9.5%;">11h10min</th>
                            <th style="width: 6px; background-color: #dee2e6; padding: 0;"></th>
                            <th style="width: 9.5%;">13h20min</th>
                            <th style="width: 9.5%;">14h20min</th>
                            <th style="width: 9.5%;">15h10min</th>
                            <th style="width: 9.5%;">16h20min</th>
                            <th style="width: 9.5%;">17h10min</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $indexDia = 0; @endphp
                        @foreach($dias as $dia => $turnos)
                            @php
                                $diasAbreviados = [
                                    'Segunda-feira' => 'SEG',
                                    'Terça-feira'   => 'TER',
                                    'Quarta-feira'  => 'QUA',
                                    'Quinta-feira'  => 'QUI',
                                    'Sexta-feira'   => 'SEX',
                                    'Sábado'        => 'SÁB',
                                    'Sabado'        => 'SÁB',
                                ];
                                $nomeDiaCurto = $diasAbreviados[$dia] ?? $dia;

                                $linhasManha = $turnos['manha'] ?? [];
                                $linhasTarde = $turnos['tarde'] ?? [];
                                $totalLinhas = max(count($linhasManha), count($linhasTarde), 1);

                                $bgClass = ($indexDia % 2 === 0) ? 'style="background-color: #f8f9fa;"' : 'style="background-color: #ffffff;"';
                                $indexDia++;
                            @endphp

                            @for($row = 0; $row < $totalLinhas; $row++)
                                <tr {!! $bgClass !!}>
                                    @if($row === 0)
                                        <th rowspan="{{ $totalLinhas }}" class="align-middle font-weight-bold" style="background-color: rgba(0,0,0,0.03);">
                                            {{ $nomeDiaCurto }}
                                        </th>
                                    @endif

                                    {{-- PERÍODO DA MANHÃ (Slots 1 a 5) --}}
                                    @php 
                                        $linhaManha = $linhasManha[$row] ?? null;
                                    @endphp
                                    @if(!$linhaManha && $row > 0 && count($linhasManha) > 0)
                                        <td colspan="5"></td>
                                    @else
                                        @php $s = 1; @endphp
                                        @while($s <= 5)
                                            @php $cell = $linhaManha[$s] ?? null; @endphp

                                            @if($cell === 'OCUPADO')
                                                @php $s++; @endphp
                                            @elseif(is_array($cell))
                                                <td colspan="{{ $cell['colspan'] }}" class="align-middle p-1">
                                                    <div class="text-left px-1">
                                                        <strong class="d-block text-dark" style="font-size: 0.90rem; font-weight: 700;">{{ $cell['coddis'] }}</strong>
                                                        <span class="d-block text-secondary text-truncate mb-1" title="{{ $cell['nomdis'] }}" style="font-size: 0.78rem; line-height: 1.2;">
                                                            {{ $cell['nomdis'] }}
                                                        </span>
                                                        
                                                        @if(!empty($cell['turmas']))
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @foreach($cell['turmas'] as $turma)
                                                                    <span class="badge badge-light border text-dark mr-1 mb-1" style="font-size: 0.70rem;">
                                                                        Turma {{ $turma }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                        @if(!empty($cell['salas']))
                                                            <div class="d-block">
                                                                @foreach($cell['salas'] as $sala)
                                                                    <span class="badge badge-light border text-muted mr-1" style="font-size: 0.70rem;">
                                                                        {{ $sala }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                @php $s += $cell['colspan']; @endphp
                                            @else
                                                <td class="align-middle p-1"></td>
                                                @php $s++; @endphp
                                            @endif
                                        @endwhile
                                    @endif

                                    {{-- DIVISOR DE ALMOÇO --}}
                                    @if($row === 0)
                                        <td rowspan="{{ $totalLinhas }}" style="background-color: #dee2e6; padding: 0;"></td>
                                    @endif

                                    {{-- PERÍODO DA TARDE (Slots 6 a 10) --}}
                                    @php 
                                        $linhaTarde = $linhasTarde[$row] ?? null;
                                    @endphp
                                    @if(!$linhaTarde && $row > 0 && count($linhasTarde) > 0)
                                        <td colspan="5"></td>
                                    @else
                                        @php $s = 6; @endphp
                                        @while($s <= 10)
                                            @php $cell = $linhaTarde[$s] ?? null; @endphp

                                            @if($cell === 'OCUPADO')
                                                @php $s++; @endphp
                                            @elseif(is_array($cell))
                                                <td colspan="{{ $cell['colspan'] }}" class="align-middle p-1">
                                                    <div class="text-left px-1">
                                                        <strong class="d-block text-dark" style="font-size: 0.90rem; font-weight: 700;">{{ $cell['coddis'] }}</strong>
                                                        <span class="d-block text-secondary text-truncate mb-1" title="{{ $cell['nomdis'] }}" style="font-size: 0.78rem; line-height: 1.2;">
                                                            {{ $cell['nomdis'] }}
                                                        </span>

                                                        @if(!empty($cell['turmas']))
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @foreach($cell['turmas'] as $turma)
                                                                    <span class="badge badge-light border text-dark mr-1 mb-1" style="font-size: 0.70rem;">
                                                                        Turma {{ $turma }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                        @if(!empty($cell['salas']))
                                                            <div class="d-block">
                                                                @foreach($cell['salas'] as $sala)
                                                                    <span class="badge badge-light border text-muted mr-1" style="font-size: 0.70rem;">
                                                                        {{ $sala }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                @php $s += $cell['colspan']; @endphp
                                            @else
                                                <td class="align-middle p-1"></td>
                                                @php $s++; @endphp
                                            @endif
                                        @endwhile
                                    @endif

                                </tr>
                            @endfor
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @elseif(request()->has('curso_hab'))
        <div class="alert alert-warning text-center mt-3">
            Nenhuma turma ou horário foi encontrado para os parâmetros selecionados.
        </div>
    @endif
@endsection