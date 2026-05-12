@include('disciplinas.partials.edit.form-ano-semestre')

<div class="card">
  <div class="card-header text-center">Justificativa da alteração</div>
  <div class="card-body p-1">
    <textarea class="form-control changed autoexpand" rows="4" name="justificativa">{{ $disc->justificativa }}</textarea>
  </div>
</div>

<div class="my-1">&nbsp;</div>


@include('disciplinas.partials.edit.form-responsaveis')

