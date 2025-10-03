@props([
    'class' => '',
    'name' => '',
    'model' => '',
    'id' => $name . rand(10000, 99999),
])
@php
  $c = $model->dr[$name];
  $cIgl = $model->dr[$name . 'igl'];
@endphp

<div class="row {{ $class }}">
  <div class="col-6">
    <b>{{ $model->meta[$name]['titulo'] }}</b>
    <textarea class="form-control autoexpand">{!! htmlspecialchars_decode($c) !!}</textarea>
  </div>
  <div class="col-6">
    <b>{{ $model->meta[$name . 'igl']['titulo'] }}</b>
    <textarea class="form-control autoexpand">{!! htmlspecialchars_decode($cIgl) !!}</textarea>
  </div>
</div>
