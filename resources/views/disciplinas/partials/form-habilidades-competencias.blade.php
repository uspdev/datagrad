@section('styles')
  @parent
  <style>
    .check-with-label:checked+.label-for-check {
      background-color: silver;
    }
    
    .label-for-check:hover {
      background-color: gainsboro;
    }
    
  </style>
@endsection

@foreach ($disc->cursos as $curso)
  <div class="card">
    <div class="card-header text-center" style="background-color: azure">
      Habilidades e competências para o curso {{ $curso['codcur'] }}: {{ $curso->dr['nomcur'] }}
    </div>
    <div class="card-body p-1">
      <div class="row">
        <div class="col-md-6">
          @if (empty($curso->habilidades))
            Habilidades não cadastradas!
          @else
            Habilidades<br>
            @foreach (explode(PHP_EOL, $curso->habilidades) as $hab)
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input check-with-label" name="habilidades[{{ $curso->codcur }}][]"
                  value="{{ $hab }}" id="check_habilidades_{{ $loop->index }}" {{ $disc->checkHabilidades($curso->codcur, $hab) }}>
                <label class="custom-control-label label-for-check"
                  for="check_habilidades_{{ $loop->index }}">H{{ $loop->index + 1 }}. {{ $hab }}</label>
              </div>
            @endforeach
          @endif
        </div>
        <div class="col-md-6">
          @if (empty($curso->competencias))
            Competências não cadastradas!
          @else
            Competências <br>
            @foreach (explode(PHP_EOL, $curso->competencias) as $con)
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input check-with-label" name="competencias[{{ $curso->codcur }}][]"
                  value="{{ $con }}" id="check_competencias_{{ $loop->index }}"  {{ $disc->checkCompetencias($curso->codcur, $con) }}>
                <label class="custom-control-label label-for-check"
                  for="check_competencias_{{ $loop->index }}">C{{ $loop->index + 1 }}. {{ $con }}</label>
              </div>
            @endforeach
          @endif
        </div>
      </div>
    </div>
  </div>
@endforeach
