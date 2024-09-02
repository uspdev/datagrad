@section('styles')
  @parent
  <style>
    #card-curso {
      border: 1px solid chocolate;
      border-top: 3px solid chocolate;
    }
  </style>
@endsection

<div class="card" id="card-curso">
  <div class="card-header">Cursos</div>
  <div class="card-body">
    <div class="row">
      <div class="col-6">
        <b>Minha unidade</b><hr>
        @foreach ($disc->dr['cursos'] as $curso)
          @if (stripos(config('replicado.codundclgs'), $curso['codclg']) !== false)
            <div class="mb-3">
              Curso: <b>{{ $curso['sglfusclgund'] }} / ({{ $curso['codcur'] }}) {{ $curso['nomcur'] }}</b>
               - habilitação: <b>({{ $curso['codhab'] }}) {{ $curso['nomhab'] }}</b>
               (de {{ formatarData($curso['dtainicrl']) }} a {{ formatarData($curso['dtafimcrl']) }})
               <br />
              &nbsp; Período ideal: {{ $curso['numsemidl'] }} | Ciclo: {{ $curso['cicdisgdecrltxt'] }}
              | Tipo: {{ $curso['tipobgtxt'] }}
              {{-- | Requisitos: ainda não implementado<br> --}}
            </div>
          @endif
        @endforeach
      </div>
      <div class="col-6">
        <b>Outras unidades</b><hr>
        @foreach ($disc->dr['cursos'] as $curso)
          @if (stripos(config('replicado.codundclgs'), $curso['codclg']) === false)
            <div class="mb-3">
              Curso: <b>{{ $curso['sglfusclgund'] }} / {{ $curso['nomcur'] }}</b> 
              - habilitação: <b>{{ $curso['nomhab'] }}</b>
               (de {{ formatarData($curso['dtainicrl']) }} a {{ formatarData($curso['dtafimcrl']) }})
              <br />
              &nbsp; Período ideal: {{ $curso['numsemidl'] }} | Ciclo: {{ $curso['cicdisgdecrltxt'] }}
              | Tipo: {{ $curso['tipobgtxt'] }}
            </div>
          @endif
        @endforeach

      </div>
    </div>

  </div>
</div>
