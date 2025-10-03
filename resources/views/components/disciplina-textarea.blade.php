@props([
    'class' => '',
    'name' => '',
    'model' => '',
    'id' => $name . rand(10000, 99999),
])

<table class="table table-bordered table-sm {{ $model->meta[$name]['class'] ?? '' }}" id="{{ $id }}">
  <tr>
    <th colspan="2" class="titulo text-center" style="background-color: aliceBlue">
      {{ $model->meta[$name]['titulo'] }}
      @include('components.partials.ajuda')
      @include('components.partials.copiar-btn')
    </th>
  </tr>
  <tr>
    <td class="d-none diff" style="background-color: #e9ecef; padding: 12px; width: 50%;">
      {!! str_replace("\n", '&para;<br>', $model->dr[$name]) !!}
    </td>
    <td class="" style="width: 50%;">
      <textarea name="{{ $name }}" class="form-control changed autoexpand w-100" data-original="{!! $model->dr[$name] !!}">{!! htmlspecialchars_decode($model[$name]) !!}</textarea>
    </td>
  </tr>
</table>