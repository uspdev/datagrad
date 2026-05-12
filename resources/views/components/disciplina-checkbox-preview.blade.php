@props([
    'name',
    'selected' => [], // valores já selecionados
    'class',
    'model',
    'custom' => false,
    'options' => ['textarea' => false, 'diff' => true],
    'id' => $name . rand(10000, 99999),
])
@php
  $center = $options['diff'] ? '' : 'text-center';
  $dataOriginal = $options['diff'] ? $model->dr[$name] ?? '' : '';
  $width = $model->dr ? '50%' : '100%';
  $colspan = $model->dr ? 2 : 1;
@endphp

<table class="table table-bordered table-sm {{ $model::meta()[$name]['class'] ?? '' }}" id="{{ $id }}">
  <tr>
    <th colspan="{{ $colspan }}" class="titulo text-center" style="background-color: aliceBlue">
      {{ $model::meta()[$name]['titulo'] }}
      @include('components.partials.copiar-btn')
    </th>
  </tr>
  <tr>
    @if ($options['diff'] && $model->dr)
      <td class="d-none diff px-3" style="width: {{ $width }};">
        {!! str_replace("\n", '&para;<br>', implode("\n", $model->{$name})) !!}
      </td>
    @endif
    <td class="" style="width: {{ $width }};">
      <div class="textarea px-3 {{ $center }}" data-original="{!! $dataOriginal !!}">
        @foreach ($model->{$name} as $item)
          - {{ $item }};<br>
        @endforeach
      </div>
    </td>
  </tr>
</table>
