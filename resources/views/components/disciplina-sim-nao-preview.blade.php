@props([
    'class' => '',
    'name' => '',
    'model' => '',
])

<div class="form-inline my-1 {{ $class }}">
  <div class=" font-weight-bold">{{ $model->meta[$name]['titulo'] }}</div>
  <div class="input-group input-group-sm ml-2">
    <div class="input-group-prepend">
      <span class="input-group-text diff d-none px-2 py-0">
        {{ $model->dr[$name] == 'S' ? 'Sim' : '' }}
        {{ $model->dr[$name] == 'N' ? 'Não' : '' }}
      </span>
      <span class="input-group-text bg-white px-2 py-0">
        {!! $model->{$name} == 'S' ? 'Sim' : '&nbsp;' !!}
        {!! $model->{$name} == 'N' ? 'Não' : '&nbsp;' !!}
      </span>
    </div>
  </div>
</div>
