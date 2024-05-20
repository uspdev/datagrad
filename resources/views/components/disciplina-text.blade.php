@props([
    'class' => '',
    'name' => '',
    'model' => '',
])

{{-- <div class="mb-3 {{ $class }}">
  <div class="input-group input-group-sm">
    <span class="mr-2">{{ $model->meta[$name]['titulo'] }}</span>
    <div class="input-group-prepend diff d-none">
      <span class="input-group-text px-3">{{ $model->dr[$name] }}</span>
    </div>
    <input type="text" name="{{ $name }}" value="{{ $model->{$name} }}" data-original="{!! $model->dr[$name] !!}"
      class="form-control">
  </div>
</div> --}}

<table class="table table-bordered table-sm {{ $model->meta[$name]['class'] ?? '' }}">
  <tr>
    <th colspan="2" class="titulo text-center" style="background-color: aliceBlue">
      {{ $model->meta[$name]['titulo'] }}
      @if (isset($model->meta[$name]['ajuda']))
        <span class="text-primary">
          <i class="fas fa-question-circle" data-toggle="popover"
            data-content="{{ $model->meta[$name]['ajuda'] }}"></i>
        </span>
      @endif
    </th>
  </tr>
  <tr>
    <td class="col-6 d-none diff" style="background-color: #e9ecef; padding: 12px">
      {!! str_replace("\n", '&para;<br>', $model->dr[$name]) !!}
    </td>
    <td class="col-6">
      <input name="{{ $name }}" type="text" class="form-control changed" data-original="{!! $model->dr[$name] !!}" value="{{ $model[$name] }}">
    </td>
  </tr>
</table>
