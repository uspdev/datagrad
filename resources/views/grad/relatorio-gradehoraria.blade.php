@extends('layouts.app')

@section('content')
    <h4>Relatório grade horária</h4>

    <form method="POST" action="">
        @csrf
        <div class="form-group">
            <label for="nuspsTextarea">Forneça uma lista de números USP (1 por linha)</label>
            <textarea name="nusps" class="form-control" id="nuspsTextarea" rows="4">{{ old('nusps') }}</textarea>
        </div>
        <button type="submit" class="btn btn-sm btn-primary spinner mt-3">Enviar</button>
    </form>

    @if ($naoEncontrados)
        <hr>
        <div class="h4">Não encontrados</div>
        @foreach ($naoEncontrados as $nome)
            {{ $nome }}<br>
        @endforeach
    @endif

    @if ($alunos)
        <hr>
        <div class="h4 mt-3">Resultados</div>
        <table class="table table-bordered table-hover table-sm datatable-simples dt-buttons dt-fixed-header">
            <thead class="thead-light">
                <tr>
                    <th>Nome</th>
                    <th>Nº USP</th>
                    <th>Código Disciplina</th>
                    <th>Turma</th>
                    <th>Dia da Semana</th>
                    <th>Hora de Início</th>
                    <th>Hora de Término</th>
                </tr>
            </thead>
            <tbody>

            @foreach ($alunos as $aluno)
              <tr></tr><tr>
              @php
                $id = 0;
              @endphp
                @foreach ($aluno as $i)
                  @if (is_array($i))
                    @foreach ($i as $j => $k)
                      @if ($j == 'disciplina' && $id > 2)
                        <tr><td colspan=2></td><td>{{ $k }}</td>
                      @else
                        <td>{{ $k }}</td>
                        @php
                          $id++;
                        @endphp
                      @endif
                      @if ($j == 'horafim')
                        </tr>
                      @endif
                    @endforeach
                  @else
                    <td>{{ $i }}</td>
                    @php
                      $id++;
                    @endphp
                  @endif
                @endforeach
              </tr>
            @endforeach


    </tbody>
    </table>
    @endif

@endsection
