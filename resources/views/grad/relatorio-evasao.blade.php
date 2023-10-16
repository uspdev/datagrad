@extends('layouts.app')


@section('content')



    @include('grad.partials.evasao-form')

    @isset($taxaEvasao)
        @isset($cursoRequest)
            <h4>

                <a href="{{ route('graduacao.relatorio.evasao') }}">Relatório de Evasão</a>
                <i class="fas fa-angle-right"></i> {{ $cursoRequest['cod'] }} - {{ $cursoRequest['nome'] }}

            </h4>
        @else
            <h4>

                <a href="{{ route('graduacao.relatorio.evasao') }}">Relatório de Evasão</a>
                <i class="fas fa-angle-right"></i> Unidade 18

            </h4>
        @endisset

        <br>

        <table class="table table-bordered table-hover">
            <tr>
                <th>Ano</th>
                <th>Permanência (%)</th>
                <th>Desistência (%)</th>
                <th>Conclusão (%)</th>
            </tr>
            @foreach ($taxaEvasao as $ano => $taxa)
                <tr>
                    <td>{{ $ano }}</td>
                    <td> {{ $taxa['txPermanencia'] }}</td>
                    <td> {{ $taxa['txDesistenciaAcc'] }}</td>
                    <td> {{ $taxa['txConclusaoAcc'] }}</td>
                </tr>
            @endforeach
        </table>
    @endisset
@endsection
