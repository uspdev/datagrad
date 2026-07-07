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
      a partir de @date($dr['bibliografia.dtainibbg'])
      @if ($dr['bibliografia.dtafimbbg'] ?? null)
        até @date($dr['bibliografia.dtafimbbg'])
      @endif
    </span>
  </div>
  <div class="card-body">
    <b>{{ $dr['meta']['dscbbgdis']['titulo'] }}</b>

    <textarea class="form-control autoexpand" disabled>{!! htmlspecialchars_decode($dr['bibliografia.dscbbgdis']) !!}</textarea>

    <div class="mt-3 font-weight-bold">{{ $dr['meta']['dscbbgdiscpl']['titulo'] }}</div>
    <textarea class="form-control autoexpand" disabled>{!! htmlspecialchars_decode($dr['bibliografia.dscbbgdiscpl']) !!}</textarea>

  </div>
</div>
