@props([
    'class' => '',
    'name' => '',
    'model' => '',
])

@php
  $italico_igl = isset($model::meta()[$name]['class']) && $model::meta()[$name]['class'] == 'ingles' ? 'font-italic' : '';
  $width = $model->dr ? '50%' : '100%';
  $colspan = $model->dr ? 2 : 1;
@endphp

<table class="table table-bordered table-sm {{ $model::meta()[$name]['class'] ?? '' }}"
  style="width: 100%; table-layout: fixed;">
  <tr>
    <th class="titulo text-center {{ $italico_igl }}" style="background-color: aliceBlue" colspan="{{ $colspan }}">
      {{ $model::meta()[$name]['titulo'] }}
      @include('components.partials.copiar-btn')
    </th>
  </tr>

  <tr>
    @if ($model->dr)
      <td class="d-none diff px-3" style="width: {{ $width }};">
        {!! str_replace("\n", '&para;<br>', $model->dr[$name] ?? null) !!}
      </td>
    @endif
    <td class="px-3" style="width: {{ $width }};">
      <div class="input {{ $italico_igl }}" data-original="{!! $model->dr[$name] ?? null !!}">{!! str_replace("\n", '&para;<br>', $model->$name) !!}</div>
    </td>
  </tr>
</table>
