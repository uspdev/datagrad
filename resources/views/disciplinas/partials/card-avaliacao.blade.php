@section('styles')
  @parent
  <style>
    #card-avaliacao {
      border: 1px solid BurlyWood;
      border-top: 3px solid BurlyWood;
    }
  </style>
@endsection

<div class="card" id="card-avaliacao">
  <div class="card-header">
    Avaliação
    <span class="badge badge-info">
      a partir de {{ formatarData($disciplina['dtainiifmavl']) }}
      @if ($disciplina['dtafimifmavl'])
        até {{ formatarData($disciplina['dtafimifmavl']) }}
      @endif
    </span>
  </div>
  <div class="card-body">

    <div class="row">
      <div class="col-6">
        <b>Métodos de avaliação</b>
        <textarea class="form-control">{!! htmlspecialchars_decode($disciplina['dscmtdavl']) !!}</textarea>
      </div>
      <div class="col-6">
        <b>Evaluation methods</b>
        <textarea class="form-control">{{ $disciplina['dscmtdavligl'] }}</textarea>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-6">
        <b>Critérios de avaliação</b>
        <textarea class="form-control">{!! htmlspecialchars_decode($disciplina['crtavl']) !!}</textarea>
      </div>
      <div class="col-6">
        <b>Evaluation criterion</b>
        <textarea class="form-control">{{ $disciplina['crtavligl'] }}</textarea>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-6">
        <b>Norma de recuperação</b>
        <textarea class="form-control">{!! htmlspecialchars_decode($disciplina['dscnorrcp']) !!}</textarea>
      </div>
      <div class="col-6">
        <b>Recovery standard</b>
        <textarea class="form-control">{{ $disciplina['dscnorrcpigl'] }}</textarea>
      </div>
    </div>
  </div>
</div>
