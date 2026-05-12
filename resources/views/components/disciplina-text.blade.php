@props([
    'class' => '',
    'name' => '', // nome do campo do model
    'model' => '',
    'maxlength' => 255,
])

@php
  if (isset($model::meta()[$name]['maxlength'])) {
    $maxlength = $model::meta()[$name]['maxlength'];
  } else {
    $maxlength = $maxlength;
  }
@endphp

<table class="table table-bordered table-sm {{ $model::meta()[$name]['class'] ?? '' }}">
  <tr>
    <th colspan="2" class="titulo text-center" style="background-color: aliceBlue">
      {{ $model::meta()[$name]['titulo'] }}
      @include('components.partials.ajuda')
      @include('components.partials.copiar-btn')
    </th>
  </tr>
  <tr>
    <td class="col-6 d-none diff" style="background-color: #e9ecef; padding: 12px">
      {!! str_replace("\n", '&para;<br>', $model->dr[$name] ?? '') !!}
    </td>
    <td class="col-6">
      <input name="{{ $name }}" type="text" class="form-control changed" data-original="{!! $model->dr[$name] ?? '' !!}"
        value="{{ $model[$name] }}" maxlength="{{ $maxlength }}">
    </td>
  </tr>
</table>
