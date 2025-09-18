@extends('layouts.app')

@section('content')
  <form method="POST" action="{{ route('cursos.update', $curso->id) }}">
    @csrf
    @method('PUT')

    <h4>
      Cursos <i class="fa fa-angle-right"></i>
      {{ $curso->codcur }} - {{ $curso->dr['nomcur'] }}: Habilidades e competências
      <input type="submit" class="btn btn-sm btn-primary" value="Salvar">
      <input type="button" class="btn btn-sm btn-secondary" onclick="history.go(-1)" value="Cancelar">
    </h4>

    <div>
      Cada habilidade/competência deve ser separada por linha.<br>
      Colocar somente o texto. Não inserir prefixos nem inserir pontuação no final!
    </div>
    <div class="text-danger">
      IMPORTANTE: caso haja alterações nas habilidades e competências, pode ser necessário revisar as disciplinas que
      estão em alteração.
    </div>
    <br>

    <table class="table table-bordered table-sm hab_con">
      <tr style="background-color: aliceBlue">
        <th class="pl-3">Competências</th>
        <th class="pl-3">Competences</th>
      </tr>
      <tr>
        <td class="col-6 p-">
          <textarea name="competencias" class="form-control autoexpand" rows="10">{{ $curso->competencias }}</textarea>
        </td>
        <td class="col-6 p-2">
          <textarea name="competencias_igl" class="form-control autoexpand" rows="10">{{ $curso->competencias_igl }}</textarea>
        </td>
      </tr>
    </table>

    <table class="table table-bordered table-sm hab_con">
      <tr style="background-color: aliceBlue">
        <th class="pl-3">Habilidades</th>
        <th class="pl-3">Skills</th>
      </tr>
      <tr>
        <td class="col-6 p-">
          <textarea name="habilidades" class="form-control autoexpand" rows="10">{{ $curso->habilidades }}</textarea>
        </td>
        <td class="col-6 p-2">
          <textarea name="habilidades_igl" class="form-control autoexpand" rows="10">{{ $curso->habilidades_igl }}</textarea>
        </td>
      </tr>
    </table>

    <br>
    <br>
    <br>
  </form>
@endsection
