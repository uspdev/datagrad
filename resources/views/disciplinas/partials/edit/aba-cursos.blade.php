@php
  $listaCursos = collect($cursos)->unique('codcur')->sortBy('nomcur');
  $dados = (array) ($disc->cursos_dados ?? []);

  // se já foi salvo pelo datagrad, vale o que foi salvo.
  // na primeira edição de uma disciplina do replicado, parte dos cursos atuais dela
  $selecionados = !empty($dados)
      ? array_map('strval', array_keys($dados))
      : collect($disc->cursos ?? [])->pluck('codcur')->map(fn($c) => (string) $c)->all();

  // mesma separação de linhas usada em Disciplina::marcarItens()
  $itens = fn($texto) => array_filter(
      preg_split('/\r\n|\r|\n/', trim((string) $texto)),
      fn($linha) => trim($linha) !== ''
  );
@endphp

{{-- marca que a aba foi enviada, para o update() saber que deve tratar os dados por curso --}}
<input type="hidden" name="aba_cursos" value="1">

<div class="card">
  <div class="card-header text-center" style="background-color: azure">
    Cursos em que a disciplina será oferecida
  </div>
  <div class="card-body p-2">
    <div class="row">
      @foreach ($listaCursos as $c)
        <div class="col-md-6">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input chk-curso" name="cursos_marcados[]"
              id="chk-curso-{{ $c['codcur'] }}" value="{{ $c['codcur'] }}"
              data-alvo="#bloco-curso-{{ $c['codcur'] }}" @checked(in_array((string) $c['codcur'], $selecionados))>
            <label class="custom-control-label" for="chk-curso-{{ $c['codcur'] }}">
              ({{ $c['codcur'] }}) {{ $c['nomcur'] }}
            </label>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

@foreach ($listaCursos as $c)
  @php
    $codcur = $c['codcur'];
    $local = $cursosLocais[$codcur] ?? null;
    $habs = $itens($local->habilidades ?? '');
    $comps = $itens($local->competencias ?? '');
  @endphp

  <div class="card d-none" id="bloco-curso-{{ $codcur }}">
    <div class="card-header" style="background-color: azure">
      ({{ $codcur }}) {{ $c['nomcur'] }}
    </div>
    <div class="card-body">
      <div class="form-row">
        <div class="form-group col-md-4">
          <label>Tipo da disciplina neste curso</label>
          <select name="cursos_dados[{{ $codcur }}][tipo]" class="form-control">
            <option value="">-- Selecione --</option>
            @foreach (\App\Models\Disciplina::$tiposCurso as $valor => $rotulo)
              <option value="{{ $valor }}" @selected(($dados[$codcur]['tipo'] ?? null) === $valor)>{{ $rotulo }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group col-md-3">
          <label>Período de oferecimento</label>
          <select name="cursos_dados[{{ $codcur }}][periodo]" class="form-control">
            <option value="">-- Selecione --</option>
            @foreach (range(1, 10) as $p)
              <option value="{{ $p }}" @selected((int) ($dados[$codcur]['periodo'] ?? 0) === $p)>{{ $p }}º período</option>
            @endforeach
          </select>
        </div>

        <div class="form-group col-md-5">
          <label>Pré-requisito(s)</label>
          <input type="text" class="form-control input-prereq" name="cursos_dados[{{ $codcur }}][prerequisitos]"
            value="{{ implode(', ', $dados[$codcur]['prerequisitos'] ?? []) }}" placeholder="Ex.: SET0199, SMM0100"
            autocomplete="off">
          <small class="form-text text-muted">Separe os códigos por vírgula.</small>
          <div class="prereq-feedback mt-1"></div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <strong>Habilidades</strong>
          @forelse ($habs as $i => $h)
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" name="habilidades[{{ $codcur }}][]"
                id="hab-{{ $codcur }}-{{ $i }}" value="{{ $h }}"
                {{ $disc->checkHabilidades($codcur, $h) }}>
              <label class="custom-control-label" for="hab-{{ $codcur }}-{{ $i }}">
                H{{ $i + 1 }}. {{ $h }}
              </label>
            </div>
          @empty
            <div class="text-muted small">Este curso não tem habilidades cadastradas.</div>
          @endforelse
        </div>

        <div class="col-md-6">
          <strong>Competências</strong>
          @forelse ($comps as $i => $comp)
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" name="competencias[{{ $codcur }}][]"
                id="comp-{{ $codcur }}-{{ $i }}" value="{{ $comp }}"
                {{ $disc->checkCompetencias($codcur, $comp) }}>
              <label class="custom-control-label" for="comp-{{ $codcur }}-{{ $i }}">
                C{{ $i + 1 }}. {{ $comp }}
              </label>
            </div>
          @empty
            <div class="text-muted small">Este curso não tem competências cadastradas.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endforeach

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // mostra/oculta o bloco do curso e desabilita os campos do bloco oculto (não são enviados)
    function alternar(chk) {
      var bloco = document.querySelector(chk.dataset.alvo);
      bloco.classList.toggle('d-none', !chk.checked);
      bloco.querySelectorAll('input, select').forEach(function(el) {
        el.disabled = !chk.checked;
      });
    }
    document.querySelectorAll('.chk-curso').forEach(function(chk) {
      alternar(chk);
      chk.addEventListener('change', function() {
        alternar(chk);
      });
    });

    // validação dos códigos de pré-requisito
    var url = @json(route('disciplinas.validar-codigo'));
    var cache = {};

    function consultar(cod) {
      if (!(cod in cache)) {
        cache[cod] = fetch(url + '?coddis=' + encodeURIComponent(cod), {
            headers: {'Accept': 'application/json'}
          })
          .then(function(r) { return r.json(); })
          .catch(function() { return null; });
      }
      return cache[cod];
    }

    function validar(input) {
      var fb = input.parentElement.querySelector('.prereq-feedback');
      var codigos = input.value.toUpperCase().split(/[\s,;]+/).filter(Boolean);
      input.value = codigos.join(', ');
      fb.innerHTML = '';
      input.classList.remove('is-valid', 'is-invalid');
      if (!codigos.length) return;

      Promise.all(codigos.map(consultar)).then(function(res) {
        var invalido = false;
        codigos.forEach(function(cod, i) {
          var r = res[i], linha = document.createElement('div');
          if (r && r.existe) {
            linha.className = 'text-success small';
            linha.textContent = '✔ ' + cod + (r.nome ? ' — ' + r.nome : '');
          } else if (r) {
            invalido = true;
            linha.className = 'text-danger small';
            linha.textContent = '✘ ' + cod + ' não encontrada';
          } else {
            linha.className = 'text-muted small';
            linha.textContent = '? ' + cod + ' — não foi possível verificar agora';
          }
          fb.appendChild(linha);
        });
        input.classList.add(invalido ? 'is-invalid' : 'is-valid');
      });
    }

    document.querySelectorAll('.input-prereq').forEach(function(input) {
      input.addEventListener('change', function() { validar(input); });
      if (input.value) validar(input);
    });
  });
</script>