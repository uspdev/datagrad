@props([
    'class' => '',
    'name' => '',
    'model' => '',
])

<table class="table table-bordered table-sm {{ $model::meta()[$name]['class'] ?? '' }}"
  style="width: 100%; table-layout: fixed;">
  <tr>
    <th class="titulo text-center" style="background-color: aliceBlue">
      {{ $model::meta()[$name]['titulo'] }}
      @include('components.partials.copiar-btn')
    </th>
  </tr>

  <tr>
    <td class="px-3" style="width: 100%;">
      <div class="input" data-original="{!! $model->dr[$name] ?? null !!}">{!! str_replace("\n", '&para;<br>', $model->$name) !!}</div>
    </td>
  </tr>
</table>
