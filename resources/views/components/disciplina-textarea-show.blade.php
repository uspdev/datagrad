@props([
    'class' => '',
    'name' => '',
    'dr' => [],
    'id' => $name . rand(10000, 99999),
])
@php
  $c = $dr[$name] ?? '';
  $cIgl = $dr[$name . 'igl'] ?? '';
@endphp
<div class="row {{ $class }}">
  <div class="col-6">
    <b>{{ $dr['meta'][$name]['titulo'] }}</b>
    <textarea class="form-control autoexpand">{!! htmlspecialchars_decode($c) !!}</textarea>
  </div>
  <div class="col-6">
    <b>{{ $dr['meta'][$name . 'igl']['titulo'] }}</b>
    <textarea class="form-control autoexpand">{!! htmlspecialchars_decode($cIgl) !!}</textarea>
  </div>
</div>
