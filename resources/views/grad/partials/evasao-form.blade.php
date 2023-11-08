<form action="{{ route('graduacao.relatorio.evasao.post') }}" method="POST" class="form-group">
  @csrf
  <div id=opcaoCursos>
    <label for="curso">Curso:</label>
    <select id="curso" name="curso">
      <option value=''>Todos os cursos</option>
      @foreach ($cursoOpcao as $curso)
        @if (isset($formRequest))
          <option value={{ $curso['codcur'] }} {{ $formRequest['codcur'] == $curso['codcur'] ? 'selected' : '' }}>
            {{ $curso['codcur'] }} - {{ $curso['nomcur'] }} </option>
        @else
          <option value={{ $curso['codcur'] }} {{ old('curso') == $curso['codcur'] ? 'selected' : '' }}>
            {{ $curso['codcur'] }} - {{ $curso['nomcur'] }} </option>
        @endif
      @endforeach
    </select>
  </div>
  <label for="anoIngresso">Ano de ingresso:</label>
  <select id="anoIngresso" name="ano">
    <option value=''></option>
    @foreach (range(2015, date('Y') - 1) as $ano)
      @if (isset($formRequest))
        <option value={{ $ano }} {{ $formRequest['anoIngresso'] == $ano ? 'selected' : '' }}>
          {{ $ano }}
        </option>
      @else
        <option value={{ $ano }} {{ old('ano') == $ano ? 'selected' : '' }}>{{ $ano }}</option>
      @endif
    @endforeach
  </select>
  <br><br>
  <button type="submit" class="btn btn-sm btn-primary spinner">Calcular</button>
</form>
