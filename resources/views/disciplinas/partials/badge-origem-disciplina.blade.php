@can('admin')
  <span @class([
      'badge border rounded-pill',
      'border-primary text-primary' => $disc->origem == 'replicado',
      'border-success text-success' => $disc->origem == 'local',
      'border-warning text-warning' => $disc->origem == 'ambos',
    ])
    title="{{ $disc->origem }}"
  >
    @initial($disc->origem)
  </span>
@endcan
