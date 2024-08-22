@extends('layouts.app')

@section('styles')
  @parent
  <style>
    .check-with-label:checked+.label-for-check {
      background-color: silver;
    }

    .hab_com,
    p:hover {
      background-color: gainsboro;
    }
  </style>
@endsection

@section('content')
  <h4>
    <a href="{{ route('graduacao.cursos') }}">Cursos</a> <i class="fa fa-angle-right"></i>
    {{ $curso->codcur }} - {{ $curso->dr['nomcur'] }}: Habilidades e competências
    @canany(['disciplina-cg', 'disciplina-cg'])
      <a href="{{ route('cursos.edit', $curso->codcur) }}?codhab={{ $curso->codhab }}"
        class="btn btn-sm btn-primary">Editar</a>
    @endcanany
  </h4>

  <div>
    As habilidades e competências aqui listadas serão utilizadas no processo de alteração da disciplina.<br>
  </div>
  <br>

  <table class="table table-bordered table-sm hab_con">
    <tr style="background-color: aliceBlue">
      <th class="pl-3">Habilidades</th>
      <th class="pl-3">Competências</th>
    </tr>
    <tr>
      <td class="col-6 p-">
        @if (!empty($curso->habilidades))
          @foreach (explode(PHP_EOL, $curso->habilidades) as $hab)
            <p>H{{ $loop->index + 1 }}. {{ $hab }};</p>
          @endforeach
        @endif
      </td>
      <td class="col-6 p-2">
        @if (!empty($curso->competencias))
          @foreach (explode(PHP_EOL, $curso->competencias) as $com)
            <p>C{{ $loop->index + 1 }}. {{ $com }};</p>
          @endforeach
        @endif
      </td>
    </tr>
  </table>

  <br>
  <br>
  <br>
@endsection
