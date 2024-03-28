{{-- mostra badge de disciplina: vigente, futuro ou desativado --}}
<div class="dropdown ml-2">
  @if (
      $disciplina['dtaatvdis'] &&
          $disciplina['dtaatvdis'] < now() &&
          ($disciplina['dtadtvdis'] == null || $disciplina['dtadtvdis'] > now()))
    <button class="btn btn-sm btn-success dropdown-toggle py-0" type="button" id="dropdownMenuButton"
      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Vigente
    </button>
  @elseif($disciplina['dtaatvdis'] == null || $disciplina['dtaatvdis'] > now())
    <button class="btn btn-sm btn-warning dropdown-toggle py-0" type="button" id="dropdownMenuButton"
      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Futuro
    </button>
  @elseif($disciplina['dtadtvdis'] && $disciplina['dtadtvdis'] < now())
    <button class="btn btn-sm btn-secondary dropdown-toggle py-0" type="button" id="dropdownMenuButton"
      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Passado
    </button>
  @endif
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    @foreach (range(1, $disciplina['maxverdis']) as $v)
      <a class="dropdown-item {{ $disciplina['verdis'] == $v ? 'disabled' : '' }}"
        href="{{ Request::url() }}?v={{ $v }}">versão {{ $v }}</a>
    @endforeach
  </div>
</div>
