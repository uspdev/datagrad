@props([
    'class' => '',
    'name' => '',
    'model' => '',
])

<div class="form-inline my-1 {{ $class }}">
  {{ $model->meta[$name]['titulo'] }}
  <div class="input-group input-group-sm ml-2">
    <div class="input-group-prepend diff d-none">
      <span class="input-group-text px-3">{{ $model->dr[$name] }}</span>
    </div>
    <input type="text" name="{{ $name }}" value="{{ $model->{$name} }}" class="form-control text-center"
    style="width:60px;">
  </div>
</div>
