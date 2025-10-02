<form action="{{ route('graduacao.relatorio.turma.post') }}" method="POST" class="form-group">
  @csrf
  <div id=opcaoDisciplina>
    <label for="disciplina">Disciplina:</label>
    <select class="select2" id="disciplina" name="disciplina">
      <option value=''>Selecione</option>
      @foreach ($disciplinas as $disciplina)
        @if (isset($formRequest))
          <option value={{ $disciplina['coddis'] }} {{ $formRequest['disciplina'] == $disciplina['coddis'] ? 'selected' : '' }}>
            {{ $disciplina['coddis'] }} - {{ $disciplina['nomdis'] }} </option>
        @else
          <option value={{ $disciplina['coddis'] }} {{ old('disciplina') == $disciplina['coddis'] ? 'selected' : '' }}>
            {{ $disciplina['coddis'] }} - {{ $disciplina['nomdis'] }} </option>
        @endif
      @endforeach
    </select>
  </div>
  <label for="anoInicio">Ano de início do intervalo:</label>
  <select id="anoInicio" name="anoInicio">
    <option value=''></option>
    @foreach (range(2015, date('Y')) as $anoInicio)
      @if (isset($formRequest))
        <option value={{ $anoInicio }} {{ $formRequest['anoInicio'] == $anoInicio ? 'selected' : '' }}>
          {{ $anoInicio }}
        </option>
      @else
        <option value={{ $anoInicio }} {{ old('anoInicio') == $anoInicio ? 'selected' : '' }}>{{ $anoInicio }}</option>
      @endif
    @endforeach
  </select>
  <select id="anoFim" name="anoFim">
    <option value=''></option>
    @foreach (range(2015, (date('Y'))) as $anoFim)
      @if (isset($formRequest))
        <option value={{ $anoFim }} {{ $formRequest['anoFim'] == $anoFim ? 'selected' : '' }}>
          {{ $anoFim }}
        </option>
      @else
        <option value={{ $anoFim }} {{ old('anoFim') == $anoFim ? 'selected' : '' }}>{{ $anoFim }}</option>
      @endif
    @endforeach
  </select>
  <br><br>
  <button type="submit" class="btn btn-sm btn-primary spinner">Calcular</button>
</form>
