@section('styles')
  @parent
  <style>
    #card-bibliografia {
      border: 1px solid coral;
      border-top: 3px solid coral;
    }
  </style>
@endsection

<div class="card" id="card-bibliografia">
  <div class="card-header">
    Bibliografia/references
    <span class="badge badge-info">
      a partir de {{ formatarData($dr['dtainibbg']) }}
      @if ($dr['dtafimbbg'])
        até {{ formatarData($dr['dtafimbbg']) }}
      @endif
    </span>
  </div>
  <div class="card-body">
    <textarea class="form-control" disabled>{!! htmlspecialchars_decode($dr['dscbbgdis']) !!}</textarea>
  </div>
</div>
