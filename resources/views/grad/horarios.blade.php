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
                            @if($ch['codcur'] === 'DUPLA_CIVIL')
                                {{ $ch['nomcur'] }}
                            @else
                                {{ $ch['codcur'] }} / {{ $ch['codhab'] }} - {{ $ch['nomcur'] }} ({{ $ch['nomhab'] }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 form-group mb-3">
                <label for="semestreInput"><b>Ano/Semestre:</b></label>
                <select name="semestre" id="semestreInput" class="form-control" required>
                    @foreach($semestreSelect as $key => $val)
                        @php 
                            $semestreValor = is_numeric($key) ? $val : $key; 
                        @endphp
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
                        @foreach($dias as $dia => $slots)
                            @php
                                $diasAbreviados = [
                                    'Segunda-feira' => 'SEG',
                                    'Terça-feira'   => 'TER',
                                    'Quarta-feira'  => 'QUA',
                                    'Quinta-feira'  => 'QUI',
                                    'Sexta-feira'   => 'SEX',
                                    'Sábado'        => 'SAB',
                                    'Sabado'        => 'SAB',
                                ];
                                $nomeDiaCurto = $diasAbreviados[$dia] ?? $dia;
                            @endphp
                            <tr>
                                <th class="align-middle thead-light font-weight-bold">{{ $nomeDiaCurto }}</th>

                                {{-- PERÍODO DA MANHÃ (Slots 1 a 5) --}}
                                @php $s = 1; @endphp
                                @while($s <= 5)
                                    @php
                                        $slotContent = $slots[$s] ?? null;
                                        $aulas = [];
                                        if (is_array($slotContent)) {
                                            $aulas = isset($slotContent[0]) ? $slotContent : [$slotContent];
                                        }
                                    @endphp

                                    @if(count($aulas) > 0)
                                        @php
                                            $maxColspan = max(array_column($aulas, 'colspan') ?: [1]);
                                            if ($s + $maxColspan - 1 > 5) {
                                                $maxColspan = 5 - $s + 1;
                                            }
                                        @endphp
                                        <td colspan="{{ $maxColspan }}" class="align-middle p-1 bg-white">
                                            @foreach($aulas as $index => $item)
                                                @if($index > 0)
                                                    <hr class="my-1">
                                                @endif
                                                <div class="text-left px-1">
                                                    <strong class="d-block text-dark" style="font-size: 0.92rem; font-weight: 700;">{{ $item['coddis'] ?? '' }}</strong>
                                                    <span class="d-block text-secondary text-truncate mb-1" title="{{ $item['nomdis'] ?? '' }}" style="font-size: 0.80rem; line-height: 1.25;">
                                                        {{ $item['nomdis'] ?? '' }}
                                                    </span>
                                                    @if(!empty($item['codtur']))
                                                        <span class="badge badge-light border text-dark" style="font-size: 0.72rem;">
                                                            Turma {{ $item['codtur'] }}
                                                        </span>
                                                    @endif
                                                    @if(!empty($item['sala']))
                                                        <span class="badge badge-light border text-muted" style="font-size: 0.72rem;">
                                                            {{ $item['sala'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </td>
                                        @php $s += $maxColspan; @endphp
                                    @else
                                        <td class="align-middle p-1 bg-white"></td>
                                        @php $s++; @endphp
                                    @endif
                                @endwhile

                                {{-- DIVISOR DE ALMOÇO --}}
                                <td style="background-color: #dee2e6; padding: 0;"></td>

                                {{-- PERÍODO DA TARDE (Slots 6 a 10) --}}
                                @php $s = 6; @endphp
                                @while($s <= 10)
                                    @php
                                        $slotContent = $slots[$s] ?? null;
                                        $aulas = [];
                                        if (is_array($slotContent)) {
                                            $aulas = isset($slotContent[0]) ? $slotContent : [$slotContent];
                                        }
                                    @endphp

                                    @if(count($aulas) > 0)
                                        @php
                                            $maxColspan = max(array_column($aulas, 'colspan') ?: [1]);
                                            if ($s + $maxColspan - 1 > 10) {
                                                $maxColspan = 10 - $s + 1;
                                            }
                                        @endphp
                                        <td colspan="{{ $maxColspan }}" class="align-middle p-1 bg-white">
                                            @foreach($aulas as $index => $item)
                                                @if($index > 0)
                                                    <hr class="my-1">
                                                @endif
                                                <div class="text-left px-1">
                                                    <strong class="d-block text-dark" style="font-size: 0.92rem; font-weight: 700;">{{ $item['coddis'] ?? '' }}</strong>
                                                    <span class="d-block text-secondary text-truncate mb-1" title="{{ $item['nomdis'] ?? '' }}" style="font-size: 0.80rem; line-height: 1.25;">
                                                        {{ $item['nomdis'] ?? '' }}
                                                    </span>
                                                    @if(!empty($item['codtur']))
                                                        <span class="badge badge-light border text-dark" style="font-size: 0.72rem;">
                                                            Turma {{ $item['codtur'] }}
                                                        </span>
                                                    @endif
                                                    @if(!empty($item['sala']))
                                                        <span class="badge badge-light border text-muted" style="font-size: 0.72rem;">
                                                            {{ $item['sala'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </td>
                                        @php $s += $maxColspan; @endphp
                                    @else
                                        <td class="align-middle p-1 bg-white"></td>
                                        @php $s++; @endphp
                                    @endif
                                @endwhile

                            </tr>
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