@props([
    'class' => '',
    'name' => '',
    'model' => '',
    'id' => $name . rand(10000, 99999),
    'options' => [],
    'selected' => [],
])

<table class="table table-bordered table-sm {{ $model->meta[$name]['class'] ?? '' }}" id="{{ $id }}">
  <tr>
    <th colspan="2" class="titulo text-center" style="background-color: aliceBlue">
      {{ $model->meta[$name]['titulo'] }}
      @if (isset($model->meta[$name]['ajuda']))
        <span class="text-primary">
          <i class="fas fa-question-circle" data-toggle="popover" data-trigger="hover"
            data-content="{{ $model->meta[$name]['ajuda'] }}"></i>
        </span>
      @endif

      @include('components.partials.copiar-btn')

    </th>
  </tr>
  <tr>
    <td class="col-6 d-none diff" style="background-color: #e9ecef; padding: 12px">
      {!! str_replace("\n", '&para;<br>', $model->dr[$name]) !!}
    </td>
    <td class="col-6">
      <select name="{{ $name }}[]" multiple="multiple" class='form-control multiselect'>
        <option value="">-- Selecione --</option>
        <option value="">Masaki</option>
        @foreach ($model->meta[$name]['options'] as $value => $label)
          <option value="{{ $value }}" @if (in_array($value, old($name, $selected))) selected @endif>
            {{ $label }}
          </option>
        @endforeach
      </select>
    </td>
  </tr>
</table>

@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {
      document.addEventListener("DOMContentLoaded", function() {
        $('.multiselect').select2({
          tags: true, // permite adicionar opções não previstas
          tokenSeparators: [',', ' '],
          placeholder: "Selecione ou adicione...",
        });
      });
    })
  </script>
@endsection
