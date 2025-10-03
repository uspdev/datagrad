@section('styles')
  @parent
  <style>
    #card-animais {
      border: 1px solid brown;
      border-top: 3px solid brown;
    }
  </style>
@endsection

<div class="card" id="card-animais">
  <div class="card-header">Atividades práticas com animais e/ou materiais biológicos</div>
  <div class="card-body">
    <div class="ml-2">
      <b>Número do protocolo emitido pela CEUA:</b> {{ $dr['ptccmseiaani'] }}<br>
      <b>Data início da validade da proposta:</b> {{ formatarData($dr['dtainivalprp']) }}<br>
      <b>Data fim da validade da proposta:</b> {{ formatarData($dr['dtafimvalprp']) }}<br>
    </div>
  </div>
</div>
