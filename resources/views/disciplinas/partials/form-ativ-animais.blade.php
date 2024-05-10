{{-- não está em uso: masaki 2/2024 --}}
<div class="form-group row my-0">
  <label class="col-form-label" for="atividade-animais">
    Atividades práticas com animais e/ou materiais biológicos
  </label>
  <div>
    <select class="form-control form-control-sm mx-3" name="stapsuatvani" id="atividade-animais">
      <option value="S" {{ $disc['stapsuatvani'] == 'S' ? 'selected' : '' }}>Sim</option>
      <option value="N" {{ $disc['stapsuatvani'] != 'S' ? 'selected' : '' }}>Não</option>
    </select>
  </div>
</div>
