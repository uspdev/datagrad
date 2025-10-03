@section('styles')
  @parent
  <style>
    #card-bibliografia,
    #card-bibliografia-complementar {
      border: 1px solid darkMagenta;
      border-top: 3px solid darkMagenta;
    }
  </style>
@endsection

<div class="card" id="card-bibliografia">
  <div class="card-header">
    Bibliografia / Bibliography
    <span class="badge badge-info">
      a partir de {{ formatarData($dr['dtainibbg']) }}
      @if ($dr['dtafimbbg'])
        até {{ formatarData($dr['dtafimbbg']) }}
      @endif
    </span>
  </div>
  <div class="card-body">
    <b>Bibliografia Básica / Bibliography</b>

    <textarea class="form-control autoexpand" disabled>{!! htmlspecialchars_decode($dr['dscbbgdis']) !!}</textarea>

    <div class="mt-3 font-weight-bold">Bibliografia Complementar</div>
    <textarea class="form-control autoexpand" disabled>{!! htmlspecialchars_decode($dr['dscbbgdiscpl']) !!}</textarea>

  </div>
</div>
