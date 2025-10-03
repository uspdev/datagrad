{{-- 
  Este checkbox verifica a existência de $name. 'igl' para criar e preencher esse campo.
  Compativel com 
  - mtdens e mtdensigl: metodologia de ensino (com ingles)
  - objdslsut: obj des sustentável (sem ingles)
 --}}

@props([
    'name',
    'options' => [], // array value => label
    'selected' => [], // valores já selecionados
    'class',
    'model',
    'custom' => true,
    'id' => $name . rand(10000, 99999),
])

@php
  if (isset($model->meta[$name . 'igl'])) {
      $nameIgl = $name . 'igl';
      $modalWidth = 'modal-lg';
      $titulo = $model->meta[$name]['titulo'] . ' / ' . $model->meta[$nameIgl]['titulo'];
  } else {
      $nameIgl = null;
      $modalWidth = '';
      $titulo = $model->meta[$name]['titulo'];
  }
@endphp

<table class="table table-bordered table-sm {{ $model->meta[$name]['class'] ?? '' }}" id="{{ $id }}">
  <tr>
    <th colspan="2" class="titulo text-center" style="background-color: aliceBlue">
      {{ $titulo }}
      @include('components.partials.checkbox-add')
      @include('components.partials.ajuda')
      @include('components.partials.copiar-btn')
    </th>
  </tr>
  <tr>
    <td class="col-6 d-none diff" style="padding: 12px" title="{{ $name }}, {!! $model->dr[$name] ?? '-' !!}">
      {!! str_replace("\n", '&para;<br>', $model->dr[$name] ?? '-') !!}
    </td>
    <td>
      <div class="selecionados textarea border rounded p-2 overflow-auto" data-original="{!! $model->dr[$name] ?? '' !!}" style="min-height: 2em;">
        {!! collect($model->{$name})->map(function ($val, $key) use ($model, $nameIgl) {
                $ingles = $model->{$nameIgl}[$key] ?? '';
                return $val . ($ingles ? ' | <i>' . $ingles . '</i>' : '');
            })->implode('<br>') !!}
      </div>
    </td>
  </tr>
</table>
