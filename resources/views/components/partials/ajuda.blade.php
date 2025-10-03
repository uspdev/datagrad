@if (isset($model->meta[$name]['ajuda']))
  <span class="text-primary d-print-none">
    <i class="fas fa-question-circle" data-toggle="popover" data-trigger="hover"
      data-content="{{ $model->meta[$name]['ajuda'] }}"></i>
  </span>
@endif
