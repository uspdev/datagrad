Vigência <span class="badge badge-secondary">versão {{ $disciplina['verdis'] }}</span>:

@if ($disciplina['dtaatvdis'])
  a partir de <b>{{ formatarData($disciplina['dtaatvdis']) }}</b>
@endif
@if ($disciplina['dtadtvdis'])
  até <b>{{ formatarData($disciplina['dtadtvdis']) }}</b>
@endif
