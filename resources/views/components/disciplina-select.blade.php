@props([
    'class' => '',
    'name' => '',
    'model' => '',
    'id' => $name . rand(10000, 99999),
    'options' => [],
    'selected' => [],
])
@php
  if (empty($options)) {
      $options = $model->meta[$name]['options'] ?? [];
  }
@endphp

<div class="form-inline my-1 {{ $class }}">
  {{ $model->meta[$name]['titulo'] }}
  <div class="input-group input-group-sm ml-2">
    <div class="input-group-prepend diff d-none">
      <span class="input-group-text px-3">
        @foreach ($options as $value => $label)
          {{ $model->dr[$name] == $value ? $label : '' }}
        @endforeach
      </span>
    </div>
    <select name="{{ $name }}" class='form-control'>
      <option value="">-- Selecione --</option>
      @foreach ($options as $value => $label)
        <option value="{{ $value }}" @selected($model->{$name} == $value)>
          {{ $label }}
        </option>
      @endforeach
    </select>
  </div>
</div>
