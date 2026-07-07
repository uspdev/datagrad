@props([
    'class' => '',
    'name' => '',
    'dr' => [],
    'id' => $name . rand(10000, 99999),
])
@php
  $nameDr = \App\Models\Disciplina::campoDr($name);
  $nameIgl = $name . 'igl';
  $nameIglDr = \App\Models\Disciplina::campoDr($nameIgl);
  $content = $dr[$nameDr] ?? '';
  $contentIgl = $dr[$nameIglDr] ?? '';
@endphp
<div class="row {{ $class }}">
  <div class="col-6">
    <b>{{ $dr['meta'][$name]['titulo'] }}</b>
    <textarea class="form-control autoexpand">{!! htmlspecialchars_decode($content) !!}</textarea>
  </div>
  <div class="col-6">
    <b>{{ $dr['meta'][$nameIgl]['titulo'] }}</b>
    <textarea class="form-control autoexpand">{!! htmlspecialchars_decode($contentIgl) !!}</textarea>
  </div>
</div>
