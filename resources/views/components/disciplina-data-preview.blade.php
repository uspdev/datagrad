@props([
    'class' => '',
    'name' => '',
    'model' => '',
])

<table class="table table-bordered table-sm {{ $model->meta[$name]['class'] ?? '' }}">
  <tr>
    <th colspan="2" class="titulo text-center" style="background-color: aliceBlue">
      {{ $model->meta[$name]['titulo'] }}
      @include('components.partials.copiar-btn')
    </th>
  </tr>
  <tr>
    <td class="col-6 d-none diff px-3">
      {!! str_replace("\n", '&para;<br>', $model->dr[$name]) !!}
    </td>
    <td class="col-6">
      <div class="input" data-original="{!! $model->dr[$name] !!}">{{ $model[$name] }}</div>
    </td>
  </tr>
</table>
