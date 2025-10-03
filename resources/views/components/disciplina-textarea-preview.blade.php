@props([
    'class' => '',
    'name' => '',
    'model' => '',
    'id' => $name . rand(10000, 99999),
])

@php
  $italico_igl = isset($model->meta[$name]['class']) && $model->meta[$name]['class'] == 'ingles' ? 'font-italic' : '';
@endphp

<table class="table table-bordered table-sm {{ $model->meta[$name]['class'] ?? '' }}" id="{{ $id }}">
  <tr>
    <th colspan="2" class="titulo text-center {{ $italico_igl }}" style="background-color: aliceBlue">
      {{ $model->meta[$name]['titulo'] }}
      @include('components.partials.copiar-btn')
    </th>
  </tr>
  <tr>
    <td class="d-none diff px-3" style="width: 50%;">
      {!! str_replace("\n", '&para;<br>', $model->dr[$name]) !!}
    </td>
    <td class="px-3" style="width: 50%;">
      {{-- o proximo div precisa ficar todo na mesma linha para não quebrar o diff --}}
      <div class="textarea {{ $italico_igl }} }}" data-original="{!! $model->dr[$name] !!}">{!! str_replace("\n", '&para;<br>', htmlspecialchars_decode($model[$name])) !!}</div>
    </td>
  </tr>
</table>
