{{--
    Se passar $btn ele vai mostrar o botão,
    caso contrário mostrará o conteúdo da ajuda
 --}}
@if ($btn ?? false)
  <button type="button" id="btn-ajuda-bibliografia" class="btn btn-outline-info btn-sm" data-toggle="collapse"
    data-target="#ajuda-bibliografia" aria-expanded="true">
    <i class="fas fa-info-circle"></i> Sobre os dados
  </button>
@else
  <div class="alert alert-info shadow-sm collapse" role="alert" id="ajuda-bibliografia">
    <button type="button" class="close" data-toggle="collapse" data-target="#ajuda-bibliografia" aria-label="Fechar">
      <span aria-hidden="true">&times;</span>
    </button>

    <h5 class="alert-heading font-weight-bold">
      <i class="fas fa-info-circle mr-2"></i> Processamento e higienização do texto
    </h5>
    <p class="mb-2">
      Idealmente, não deveria ser necessário processar a bibliografia.
      Entretanto, devido a inconsistências e variações na forma como os dados são armazenados no sistema, são realizados
      tratamentos para melhor apresentação.<br>
      O sistema realiza a limpeza e a padronização da bibliografia das disciplinas, incluindo a preparação dos dados e a
      remoção de elementos indesejados, conforme as etapas descritas a seguir:
    </p>

    <hr class="my-2">

    <h6 class="font-weight-bold text-dark mb-1">1. Normalização e Estruturação de Texto</h6>
    <ul class="mb-2 pl-3">
      <li><strong>Decodificação de entidades HTML:</strong> Converte códigos como <code>&amp;quot;</code>,
        <code>&amp;amp;</code> e <code>&amp;para;</code> em seus respectivos caracteres visíveis.
      </li>
      <li><strong>Padronização de quebras de linha:</strong> Uniformiza quebras de linha do sistema (<code>\r\n</code> e
        <code>\r</code>) para <code>\n</code>.
      </li>
      <li><strong>Conversão de tags e caracteres em parágrafos:</strong> Transforma tags HTML de quebra
        (<code>&lt;br&gt;</code>, <code>&lt;p&gt;</code>, <code>&lt;/p&gt;</code>) e o símbolo pilcrow (<code>¶</code>)
        em
        novas linhas reais (<code>\n</code>).</li>
      <li><strong>Remoção de tags HTML:</strong> Elimina todas as demais tags HTML residuais do texto.</li>
    </ul>

    <h6 class="font-weight-bold text-dark mb-1 mt-3">2. Higienização de Prefixos e Rótulos (por linha)</h6>
    <ul class="mb-2 pl-3">
      <li><strong>Caracteres invisíveis e de controle:</strong> Remove códigos de controle/PUA, ícones especiais e o
        símbolo <code>¶</code> remanescente no início.</li>
      <li><strong>Marcadores e numerações:</strong> Elimina marcadores de lista (<code>-</code>, <code>*</code>,
        <code>•</code>, <code>●</code>), numerações (ex: <code>1.</code>, <code>1)</code>, <code>39-</code>),
        identificadores (ex: <code>[5] -</code>, <code>|2|</code>) e marcadores entre parênteses (ex: <code>(*)</code>).
      </li>
      <li><strong>Pontuações residuais:</strong> Remove pontos e espaços sobrando no início da linha (ex: <code>.
          Texto</code> &rarr; <code>Texto</code>).</li>
      <li><strong>Rótulos bilíngues:</strong> <code>Principal / Main</code>, <code>Complementar / Complementary</code>,
        <code>Bibliografia / References</code>.
      </li>
      <li><strong>Termos entre parênteses:</strong> <code>Bibliografia (principal)</code>, <code>Referências
          (básica)</code>, <code>Bibliografia (principal e complementar)</code>.</li>
      <li><strong>Categorias de bibliografia:</strong> <code>Bibliografia básica</code>, <code>Referências
          complementares</code>, <code>Bibliografia obrigatória</code>.</li>
      <li><strong>Avisos e pendências:</strong> <code>Notas de aula</code>, <code>Não indicada</code>, <code>Não
          informada</code>, <code>A indicar</code>.</li>
      <li><strong>Títulos simples e genéricos:</strong> <code>Bibliografia:</code>, <code>References:</code>,
        <code>Básica:</code>, <code>Complementar:</code>, <code>Principal:</code>.
      </li>
    </ul>

    <small class="d-block text-muted">
      <strong>Nota:</strong> A limpeza ignora diferenças entre maiúsculas/minúsculas, acentuação, pontuações finais
      (como
      <code>:</code> ou <code>/</code>) e pequenos erros de digitação.
    </small>

    <h6 class="font-weight-bold text-dark mb-1 mt-3"> 3. Como utilizar a tabela</h6>
    <ul class="mb-2 pl-3">
      <li>O carregamento da tabela pode levar alguns instantes devido à quantidade de dados.</li>
      <li>Para ordenar os dados, clique no cabeçalho da coluna desejada.</li>
      <li>Utilize o campo <em>Pesquisar...</em> para localizar e filtrar registros.</li>
      <li>Clique no código da disciplina para consultar a bibliografia conforme cadastrada no Júpiter.</li>
      <li>Utilize o botão de <em>Filtros</em> para escolher o semestre de referência para consulta das bibliografias.</li>
    </ul>

    <hr class="my-2">

    <button type="button" class="btn btn-outline-info btn-sm mt-3" data-toggle="collapse"
      data-target="#ajuda-bibliografia" aria-expanded="true">
      <i class="fas fa-times"></i>
      Fechar
    </button>
  </div>

  @push('styles')
    <style>
      #ajuda-bibliografia.collapsing {
        transition: height 0.25s ease;
      }
    </style>
  @endpush

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function() {

        const storageKey = 'bibliografia-ajuda-fechada';
        const ajuda = $('#ajuda-bibliografia');

        if (!ajuda.length) {
          return;
        }

        const fechada = localStorage.getItem(storageKey) === 'true';

        if (fechada) {
          ajuda.collapse('hide');
        } else {
          ajuda.collapse('show');
        }

        ajuda.on('shown.bs.collapse', function() {
          localStorage.setItem(storageKey, 'false');
        });

        ajuda.on('hidden.bs.collapse', function() {
          localStorage.setItem(storageKey, 'true');
        });
      });
    </script>
  @endpush
@endif
