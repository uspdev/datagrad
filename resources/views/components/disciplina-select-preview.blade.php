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
  <div class=" font-weight-bold">{{ $model->meta[$name]['titulo'] }}</div>
  <div class="input-group input-group-sm ml-2">
    <div class="input-group-prepend">
      <span class="input-group-text diff d-none px-2 py-0">
        @if (isset($model->dr[$name]))
          @foreach ($options as $value => $label)
            {{ $model->dr[$name] == $value ? $label : '' }}
          @endforeach
        @endif
      </span>
      <span class="input-group-text bg-white px-2 py-0">
        @foreach ($options as $value => $label)
          {{ $model->$name == $value ? $label : '' }}
        @endforeach
        &nbsp;
      </span>
    </div>
  </div>
</div>
