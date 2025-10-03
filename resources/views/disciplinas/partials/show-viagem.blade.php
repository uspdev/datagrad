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
  <div class="card-header">Viagens didáticas</div>
  <div class="card-body">
    <div class="ml-2">
      <b>Estruturante</b>: {{ $dr['staetr'] == 'S' ? 'Sim' : 'Não' }}<br>
      {{ $disc->meta['dscatvpvs']['titulo'] }}
    <textarea class="form-control autoexpand" disabled>{!! htmlspecialchars_decode($dr['dscatvpvs']) !!}</textarea>
      
    </div>
  </div>
</div>
