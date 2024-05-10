@props([
    'class' => '',
    'name' => '',
    'model' => '',
])

<table>
  <tr>
    <td style="width:1%"><b>{{ $model->meta[$name]['titulo'] }}</b></td>
    <td>{!! str_replace("\n", '&para;<br>', $model->diffs[$name]) !!}</td>
    <td> {!! str_replace("\n", '&para;<br>', $model->{$name}) !!}</td>
  </tr>
</table>
