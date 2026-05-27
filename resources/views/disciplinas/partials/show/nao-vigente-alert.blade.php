@if (empty($dr['dtaatvdis'] ?? null) || \Carbon\Carbon::parse($dr['dtaatvdis'])->isFuture())
  <div class="alert alert-warning">Atenção: esta versão ainda não está vigente. Consulte a versão atual no
    <a href="https://uspdigital.usp.br/jupiterweb/obterDisciplina?nomdis=&sgldis={{ $dr['coddis'] }}" class=""
      target="_BLANK">Jupiter Web <i class="fas fa-link"></i></a>
  </div>
@endif
