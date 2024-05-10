<div>
  Responsáveis/Professors
  @include('disciplinas.partials.responsavel-adicionar-btn')
</div>
<div class="ml-2 font-weight-bold">
  @foreach ($disc->responsaveis as $r)
    @if ($r['status'] == 'mesmo')
      <div>
        {{ $r['codpes'] }} - {{ $r['nompesttd'] }}
        @include('disciplinas.partials.responsavel-remover-btn')
      </div>
    @elseif($r['status'] == 'novo')
      <div>
        <ins class="ins">{{ $r['codpes'] }} - {{ $r['nompesttd'] }}</ins>
        @include('disciplinas.partials.responsavel-remover-btn')
      </div>
    @elseif($r['status'] == 'removido')
      <div class="diff d-none">
        <del>{{ $r['codpes'] }} - {{ $r['nompesttd'] }}</del>
      </div>
    @endif
  @endforeach
</div>
