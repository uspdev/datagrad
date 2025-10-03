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
  $dataOriginal = $options['diff'] ? $model->dr[$name] : '';
@endphp

<table class="table table-bordered table-sm {{ $model->meta[$name]['class'] ?? '' }}" id="{{ $id }}">
  <tr>
    <th colspan="2" class="titulo text-center" style="background-color: aliceBlue">
      {{ $model->meta[$name]['titulo'] }}
      @include('components.partials.copiar-btn')
    </th>
  </tr>
  <tr>
    @if ($options['diff'])
      <td class="d-none diff px-3">
        {!! str_replace("\n", '&para;<br>', implode("\n", $model->{$name})) !!}
      </td>
    @endif
    <td class="">
      <div class="textarea px-3 {{ $center }}" data-original="{!! $dataOriginal !!}">
        @foreach ($model->{$name} as $item)
          - {{ $item }};<br>
        @endforeach
      </div>
    </td>
  </tr>
</table>
