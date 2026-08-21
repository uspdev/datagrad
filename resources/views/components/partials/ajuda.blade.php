@if (isset($model::meta()[$name]['ajuda']))
  <br>
  <span class="d-print-none text-muted font-weight-normal pb-0" style="display:inline-block; width: 80%; font-size: 0.9rem;">
    {{ $model::meta()[$name]['ajuda'] }}
  </span>
@endif
