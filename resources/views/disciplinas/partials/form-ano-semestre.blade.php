<div class="form-inline">
  <label class="col-form-label">Alteração para o ano/semestre de </label>
  <select class="form-control form-control-sm mx-2" name="ano" id="ano">
    <option>Ano ..</option>
    {{-- as opções são o ano corrente mais 2 anos subsequentes --}}
    @foreach (range(now()->format('Y'), now()->format('Y') + 2) as $ano)
      <option {{ $disc->ano == $ano ? 'selected' : '' }}>{{ $ano }}</option>
    @endforeach
  </select>
  <select class="form-control form-control-sm mx-2" name="semestre" id="semestre">
    <option>Semestre ..</option>
    <option {{ $disc->semestre == '1' ? 'selected' : '' }}>1</option>
    <option {{ $disc->semestre == '2' ? 'selected' : '' }}>2</option>
  </select>
</div>
