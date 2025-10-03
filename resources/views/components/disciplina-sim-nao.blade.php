{{-- 
atividade_extensionista não possui DR xxx
 --}}
@props([
    'class' => '',
    'name' => '',
    'model' => '',
    'id' => $name . rand(10000, 99999),
])

<div class="form-inline my-1 {{ $class }}">
  {{ $model->meta[$name]['titulo'] }}
  <div class="input-group input-group-sm ml-2">
    <div class="input-group-prepend diff d-none">
      <span class="input-group-text px-3">
        {{ ($model->dr[$name]) == 'S' ? 'Sim' : '' }}
        {{ ($model->dr[$name]) == 'N' ? 'Não' : '' }}
      </span>
    </div>
    <select class="form-control" name="{{ $name }}" id="{{ $id }}">
      <option value="">Selecione..</option>
      <option value="S" {{ $model->{$name} == 'S' ? 'selected' : '' }}>Sim</option>
      <option value="N" {{ $model->{$name} == 'N' ? 'selected' : '' }}>Não</option>
    </select>
  </div>
</div>
