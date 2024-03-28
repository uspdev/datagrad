@section('styles')
  @parent
  <style>
    #card-extensao {
      border: 1px solid brown;
      border-top: 3px solid brown;
    }
  </style>
@endsection

<div class="card" id="card-extensao">
  <div class="card-header">Atividades de extensão</div>
  <div class="card-body">

    <div><b>Carga horária</b>: {{ $disciplina['cgahoratvext'] }}</div>

    <div class="row mt-3">
      <div class="col-6">
        <b>Grupo social alvo</b>
        <textarea class="form-control">{!! htmlspecialchars_decode($disciplina['grpavoatvext']) !!}</textarea>
      </div>
      <div class="col-6">
        <b>Grupo social alvo (ingles)</b>
        <textarea class="form-control">{{ $disciplina['grpavoatvextigl'] }}</textarea>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-6">
        <b>Objetivos</b>
        <textarea class="form-control">{!! htmlspecialchars_decode($disciplina['objatvext']) !!}</textarea>
      </div>
      <div class="col-6">
        <b>Objectives</b>
        <textarea class="form-control">{{ $disciplina['objatvextigl'] }}</textarea>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-6">
        <b>Descrição</b>
        <textarea class="form-control">{!! htmlspecialchars_decode($disciplina['dscatvext']) !!}</textarea>
      </div>
      <div class="col-6">
        <b>Description</b>
        <textarea class="form-control">{{ $disciplina['dscatvextigl'] }}</textarea>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-6">
        <b>Indicadores de avaliação</b>
        <textarea class="form-control">{!! htmlspecialchars_decode($disciplina['idcavlatvext']) !!}</textarea>
      </div>
      <div class="col-6">
        <b>Indicadores de avaliação (ING)</b>
        <textarea class="form-control">{{ $disciplina['idcavlatvextigl'] }}</textarea>
      </div>
    </div>
  </div>
</div>
