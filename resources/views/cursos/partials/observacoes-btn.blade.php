@if (!empty($curso['obscrl']))
  <button class="btn btn-link toggle-obscrl" title="Há observações do currículo: clique para ver."><i
      class="fas fa-info-circle"></i>
  </button>
  <div class="obscrl alert-info toggle-obscrl" style="display:none">
    <b>Observações do currículo</b><br>
    {!! $curso['obscrl'] !!}
  </div>
@endif
