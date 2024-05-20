<div>
  Responsáveis/Professors
  @include('disciplinas.partials.codpes-adicionar-btn')
</div>

<div class="ml-2 font-weight-bold">
  @foreach ($disc->responsaveis as $r)
    @if ($r['status'] == 'mesmo')
      <div class="hover">
        {{ $r['codpes'] }} - {{ $r['nompesttd'] }}
        @include('disciplinas.partials.codpes-remover-btn', ['codpes' => $r['codpes']])
      </div>
    @elseif($r['status'] == 'novo')
      <div class="hover">
        <ins class="ins">{{ $r['codpes'] }} - {{ $r['nompesttd'] }}</ins>
        @include('disciplinas.partials.codpes-remover-btn', ['codpes' => $r['codpes']])
      </div>
    @elseif($r['status'] == 'removido')
      <div class="diff d-none hover">
        <del>{{ $r['codpes'] }} - {{ $r['nompesttd'] }}</del>
      </div>
    @endif
  @endforeach
</div>

@section('styles')
  @parent
  <style>
    .hover:hover {
      background-color: gainsboro;
    }
  </style>
@endsection
