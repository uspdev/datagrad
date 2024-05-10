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
      a partir de {{ formatarData($dr['dtainiifmavl']) }}
      @if ($dr['dtafimifmavl'])
        até {{ formatarData($dr['dtafimifmavl']) }}
      @endif
    </span>
  </div>
  <div class="card-body">

    <div class="row">
      <div class="col-6">
        <b>Métodos de avaliação</b>
        <textarea class="form-control">{!! htmlspecialchars_decode($dr['dscmtdavl']) !!}</textarea>
      </div>
      <div class="col-6">
        <b>Evaluation methods</b>
        <textarea class="form-control">{{ $dr['dscmtdavligl'] }}</textarea>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-6">
        <b>Critérios de avaliação</b>
        <textarea class="form-control">{!! htmlspecialchars_decode($dr['crtavl']) !!}</textarea>
      </div>
      <div class="col-6">
        <b>Evaluation criterion</b>
        <textarea class="form-control">{{ $dr['crtavligl'] }}</textarea>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-6">
        <b>Norma de recuperação</b>
        <textarea class="form-control">{!! htmlspecialchars_decode($dr['dscnorrcp']) !!}</textarea>
      </div>
      <div class="col-6">
        <b>Recovery standard</b>
        <textarea class="form-control">{{ $dr['dscnorrcpigl'] }}</textarea>
      </div>
    </div>
  </div>
</div>
