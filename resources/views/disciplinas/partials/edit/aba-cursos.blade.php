@if ($disc->estado == 'Criar')
  <div class="card">
    <div class="card-body p-1">
      Ainda não existem cursos associados a esta disciplina. Para associar um curso, é necessário criar a disciplina
      primeiro.<br>
      Alternativamente as informações de curso podem ser adicionadas na justificativa de criação da disciplina.
    </div>
  </div>

  {{-- <div class="card">
    <div class="card-header text-center" style="background-color: azure">
      Selecione o curso alvo
    </div>
    <div class="card-body p-1">
      <select name="curso" class="form-control">
        <option value="">-- Selecione --</option>
        @foreach ($cursos as $curso)
          <option value="{{ $curso['codcur'] }}-{{ $curso['codhab'] }}">
            ({{ $curso['codcur'] }})
            {{ $curso['nomcur'] }}
            / ({{ $curso['codhab'] }}) {{ $curso['nomhab'] }}
          </option>
        @endforeach
      </select>
    </div>
  </div> --}}
@else
  @include('disciplinas.partials.form-habilidades-competencias')
@endif
