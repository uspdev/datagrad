{{-- mostra badge de disciplina: vigente, futuro ou desativado --}}
<span class="dropdown ml-2">
  @if (
      $dr['dtaatvdis'] &&
          $dr['dtaatvdis'] < now() &&
          ($dr['dtadtvdis'] == null || $dr['dtadtvdis'] > now()))
    <button class="btn btn-sm btn-success dropdown-toggle py-0" type="button" id="dropdownMenuButton"
      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Vigente
    </button>
  @elseif($dr['dtaatvdis'] == null || $dr['dtaatvdis'] > now())
    <button class="btn btn-sm btn-warning dropdown-toggle py-0" type="button" id="dropdownMenuButton"
      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Futuro
    </button>
  @elseif($dr['dtadtvdis'] && $dr['dtadtvdis'] < now())
    <button class="btn btn-sm btn-secondary dropdown-toggle py-0" type="button" id="dropdownMenuButton"
      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Passado
    </button>
  @endif
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    @foreach (range(1, $dr['maxverdis']) as $v)
      <a class="dropdown-item {{ $dr['verdis'] == $v ? 'disabled' : '' }}"
        href="{{ Request::url() }}?v={{ $v }}">versão {{ $v }}</a>
    @endforeach
  </div>
</span>
