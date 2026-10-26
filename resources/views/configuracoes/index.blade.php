@extends('layouts.app')

@section('title', 'Configurações')

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
        <h4 class="mb-0">
          <i class="fas fa-cogs text-secondary mr-2"></i> Configurações do Sistema
        </h4>
        <span class="badge badge-pill badge-danger">Acesso Restrito: Administrador</span>
      </div>
    </div>
  </div>

  <div class="row">
    {{-- Card do Gotenberg --}}
    <div class="col-md-8">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex align-items-center justify-content-between">
          <span class="h5 mb-0 font-weight-bold text-dark">
            <i class="fas fa-file-pdf text-danger mr-2"></i> Gotenberg (Gerador de PDF)
          </span>
          <span id="badge-status-gotenberg">
            @if ($gotenbergInfo['disponivel'])
              <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Disponível</span>
            @else
              <span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i> Indisponível</span>
            @endif
          </span>
        </div>

        <div class="card-body">
          <p class="text-muted small mb-3">
            O Gotenberg é uma API stateless baseada em Docker utilizada para converter documentos HTML, Markdown e Office em arquivos PDF usando o Chromium e LibreOffice.
          </p>

          <table class="table table-sm table-bordered mb-4">
            <tbody>
              <tr>
                <th class="bg-light" style="width: 35%;">URL do Serviço:</th>
                <td><code>{{ $gotenbergInfo['url'] }}</code></td>
              </tr>
              <tr>
                <th class="bg-light">Driver Laravel-PDF:</th>
                <td>
                  <span class="badge badge-secondary">{{ $gotenbergInfo['driver'] ?? 'padrão' }}</span>
                  @if (($gotenbergInfo['driver'] ?? '') === 'gotenberg')
                    <span class="text-success small ml-1"><i class="fas fa-check"></i> Ativo como driver principal</span>
                  @else
                    <span class="text-warning small ml-1"><i class="fas fa-exclamation-triangle"></i> Driver não definido como gotenberg</span>
                  @endif
                </td>
              </tr>
              <tr>
                <th class="bg-light">Autenticação Básica:</th>
                <td>{{ $gotenbergInfo['temAutenticacao'] ? 'Configurada' : 'Não necessária / Não configurada' }}</td>
              </tr>
            </tbody>
          </table>

          <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
            <form id="form-teste-gotenberg" action="{{ route('configuracoes.gotenberg.teste') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" id="btn-testar-gotenberg" class="btn btn-primary">
                <i class="fas fa-sync-alt mr-1" id="icon-spinner-gotenberg"></i>
                <span id="btn-text-gotenberg">Testar Conectividade e Funcionamento</span>
              </button>
            </form>

            <a href="{{ route('configuracoes.gotenberg.pdf-teste') }}" target="_blank" class="btn btn-outline-danger ml-2" id="btn-pdf-teste">
              <i class="fas fa-file-pdf mr-1"></i> Visualizar PDF de Teste (Nova Aba)
            </a>
          </div>

          {{-- Container de resultados do teste --}}
          <div id="card-resultado-teste" style="display: {{ session('resultado_gotenberg') ? 'block' : 'none' }};">
            @php $res = session('resultado_gotenberg'); @endphp
            <div id="resultado-alert" class="alert {{ ($res['ok'] ?? false) ? 'alert-success' : 'alert-danger' }} p-3 mb-0">
              <div class="d-flex align-items-center mb-2">
                <i id="resultado-icone" class="fas {{ ($res['ok'] ?? false) ? 'fa-check-circle' : 'fa-exclamation-triangle' }} fa-lg mr-2"></i>
                <strong id="resultado-titulo" class="h6 mb-0">{{ $res['mensagem'] ?? '' }}</strong>
              </div>
              <ul class="mb-0 pl-3 small" id="resultado-detalhes">
                @if (isset($res['health']))
                  <li><strong>Status HTTP:</strong> {{ $res['health']['status'] ?? 'N/A' }} ({{ $res['health']['tempo_ms'] ?? 0 }} ms)</li>
                  @if (isset($res['health']['body']['details']))
                    <li><strong>Módulo Chromium:</strong> {{ $res['health']['body']['details']['chromium']['status'] ?? 'N/A' }}</li>
                    <li><strong>Módulo LibreOffice:</strong> {{ $res['health']['body']['details']['libreoffice']['status'] ?? 'N/A' }}</li>
                  @endif
                @endif
                @if (isset($res['pdf_gerado']))
                  <li><strong>Geração Real de PDF:</strong> {{ $res['pdf_gerado'] ? 'Sucesso (' . number_format(($res['tamanho_bytes'] ?? 0) / 1024, 2) . ' KB)' : 'Falha' }}</li>
                @endif
              </ul>
            </div>
          </div>

        </div>
      </div>
    </div>

    {{-- Card de instruções de ambiente --}}
    <div class="col-md-4">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
          <span class="font-weight-bold text-dark">
            <i class="fas fa-info-circle text-info mr-1"></i> Configurações no <code>.env</code>
          </span>
        </div>
        <div class="card-body small">
          <p class="mb-2">Variáveis de ambiente utilizadas:</p>
          <pre class="bg-light p-2 border rounded"><code>LARAVEL_PDF_DRIVER=gotenberg
GOTENBERG_URL=http://192.168.100.207:3000
# GOTENBERG_USERNAME=
# GOTENBERG_PASSWORD=</code></pre>
          <p class="text-muted mb-0">
            Caso altere o IP ou a porta do Gotenberg, atualize a variável <code>GOTENBERG_URL</code> no arquivo <code>.env</code>.
          </p>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('javascripts_bottom')
  @parent
  <script>
    $(function() {
      $('#form-teste-gotenberg').on('submit', function(e) {
        e.preventDefault();

        const btn = $('#btn-testar-gotenberg');
        const icon = $('#icon-spinner-gotenberg');
        const btnText = $('#btn-text-gotenberg');
        const resultadoCard = $('#card-resultado-teste');
        const resultadoAlert = $('#resultado-alert');
        const resultadoIcone = $('#resultado-icone');
        const resultadoTitulo = $('#resultado-titulo');
        const resultadoDetalhes = $('#resultado-detalhes');
        const badgeStatus = $('#badge-status-gotenberg');

        // Estado carregando
        btn.prop('disabled', true);
        icon.addClass('fa-spin');
        btnText.text('Testando...');

        $.ajax({
          url: $(this).attr('action'),
          type: 'POST',
          data: $(this).serialize(),
          dataType: 'json',
          timeout: 10000,
          success: function(res) {
            resultadoCard.show();
            resultadoAlert.removeClass('alert-success alert-danger')
              .addClass(res.ok ? 'alert-success' : 'alert-danger');

            resultadoIcone.removeClass('fa-check-circle fa-exclamation-triangle')
              .addClass(res.ok ? 'fa-check-circle' : 'fa-exclamation-triangle');

            resultadoTitulo.text(res.mensagem);

            let detalhesHtml = '';
            if (res.health) {
              detalhesHtml += `<li><strong>Status HTTP:</strong> ${res.health.status || 'Falha'} (${res.health.tempo_ms} ms)</li>`;
              if (res.health.body && res.health.body.details) {
                detalhesHtml += `<li><strong>Módulo Chromium:</strong> ${res.health.body.details.chromium ? res.health.body.details.chromium.status : 'N/A'}</li>`;
                detalhesHtml += `<li><strong>Módulo LibreOffice:</strong> ${res.health.body.details.libreoffice ? res.health.body.details.libreoffice.status : 'N/A'}</li>`;
              }
              if (res.health.erro) {
                detalhesHtml += `<li><strong>Erro:</strong> ${res.health.mensagem}</li>`;
              }
            }

            if (res.pdf_gerado) {
              const kb = (res.tamanho_bytes / 1024).toFixed(2);
              detalhesHtml += `<li><strong>Geração Real de PDF:</strong> Sucesso (${kb} KB)</li>`;
            } else if (res.erro_pdf) {
              detalhesHtml += `<li><strong>Erro na geração do PDF:</strong> ${res.erro_pdf}</li>`;
            }

            resultadoDetalhes.html(detalhesHtml);

            // Atualiza badge de status
            if (res.ok) {
              badgeStatus.html('<span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Disponível</span>');
            } else {
              badgeStatus.html('<span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i> Indisponível</span>');
            }
          },
          error: function(xhr) {
            resultadoCard.show();
            resultadoAlert.removeClass('alert-success').addClass('alert-danger');
            resultadoIcone.removeClass('fa-check-circle').addClass('fa-exclamation-triangle');
            resultadoTitulo.text('Erro ao executar teste de conexão.');
            resultadoDetalhes.html('<li>Não foi possível completar a requisição para o servidor.</li>');
            badgeStatus.html('<span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i> Indisponível</span>');
          },
          complete: function() {
            btn.prop('disabled', false);
            icon.removeClass('fa-spin');
            btnText.text('Testar Conectividade e Funcionamento');
          }
        });
      });
    });
  </script>
@endsection
