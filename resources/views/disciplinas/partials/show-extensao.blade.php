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
    <div><b>Carga horária (horas)</b>: {{ $dr['cgahoratvext'] }}</div>

    <x-disciplina-textarea-show name='grpavoatvext' :model=$disc></x-disciplina-textarea-show>
    <x-disciplina-textarea-show name='objatvext' :model=$disc></x-disciplina-textarea-show>
    <x-disciplina-textarea-show name='dscatvext' :model=$disc></x-disciplina-textarea-show>
    <x-disciplina-textarea-show name='idcavlatvext' :model=$disc></x-disciplina-textarea-show>
  </div>
</div>
