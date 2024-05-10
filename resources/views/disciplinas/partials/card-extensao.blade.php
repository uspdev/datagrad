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
  <div class="card-header">Atividade extensionista</div>
  <div class="card-body">

    <div><b>Carga horária</b>: {{ $dr['cgahoratvext'] }}</div>

    <div class="row mt-3">
      <div class="col-6">
        <b>Grupo social alvo</b>
        <textarea class="form-control">{!! htmlspecialchars_decode($dr['grpavoatvext']) !!}</textarea>
      </div>
      <div class="col-6">
        <b>Grupo social alvo (ingles)</b>
        <textarea class="form-control">{{ $dr['grpavoatvextigl'] }}</textarea>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-6">
        <b>Objetivos</b>
        <textarea class="form-control">{!! htmlspecialchars_decode($dr['objatvext']) !!}</textarea>
      </div>
      <div class="col-6">
        <b>Objectives</b>
        <textarea class="form-control">{{ $dr['objatvextigl'] }}</textarea>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-6">
        <b>Descrição</b>
        <textarea class="form-control">{!! htmlspecialchars_decode($dr['dscatvext']) !!}</textarea>
      </div>
      <div class="col-6">
        <b>Description</b>
        <textarea class="form-control">{{ $dr['dscatvextigl'] }}</textarea>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-6">
        <b>Indicadores de avaliação</b>
        <textarea class="form-control">{!! htmlspecialchars_decode($dr['idcavlatvext']) !!}</textarea>
      </div>
      <div class="col-6">
        <b>Indicadores de avaliação (ING)</b>
        <textarea class="form-control">{{ $dr['idcavlatvextigl'] }}</textarea>
      </div>
    </div>
  </div>
</div>
