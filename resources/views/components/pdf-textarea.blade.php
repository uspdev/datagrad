@props([
    'class' => '',
    'name' => '',
    'model' => '',
])

@foreach (explode('__quebrar__', $model->{$name}) as $name_dividido)
  <table>
    <tr>
      <th colspan="2">
        {{ $model->meta[$name]['titulo'] }}
        {{ $loop->index > 0 ? '(continuação ..)' : '' }}
      </th>
    </tr>
    <tr>
      {{-- <td></td> --}}
      <td>{!! str_replace("\n", '&para;<br>', $model->diffs[$name]) !!}</td>
      <td> {!! str_replace("\n", '&para;<br>', $name_dividido) !!}</td>
    </tr>
  </table>
@endforeach


@once
  @section('styles')
    @parent
    <style>
      table {
        width: 100%;
        margin-top: 15px;
      }

      td {
        width: 50%;
        vertical-align: text-top;
        padding: 5px;
        line-height: 1.25;
      }

      table,
      th,
      td {
        border: 1px solid black;
        border-collapse: collapse;
      }

      th {
        text-align: center;
        padding: 10px;
        font-weight: bold;
      }
    </style>
  @endsection
@endonce
