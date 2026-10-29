<?php

namespace App\Http\Controllers;

use App\Services\Gotenberg;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\LaravelPdf\Facades\Pdf;
use Throwable;
use Uspdev\UspTheme\Facades\UspTheme;

class ConfiguracaoController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            Gate::authorize('admin');
            UspTheme::activeUrl('configuracoes');
            return $next($request);
        });
    }

    /**
     * Exibe a página principal de configurações do sistema.
     */
    public function index()
    {
        $gotenbergInfo = [
            'url' => Gotenberg::url(),
            'driver' => config('laravel-pdf.driver'),
            'temAutenticacao' => (bool) (Gotenberg::username() && Gotenberg::password()),
            'disponivel' => Gotenberg::isAvailable(timeout: 2),
        ];

        return view('configuracoes.index', compact('gotenbergInfo'));
    }

    /**
     * Executa o teste de conectividade e funcionamento do Gotenberg.
     */
    public function testarGotenberg(Request $request)
    {
        $health = Gotenberg::health(timeout: 5);
        $pdfGerado = false;
        $tamanhoPdf = 0;
        $erroPdf = null;

        if ($health['ok']) {
            try {
                $inicioPdf = microtime(true);
                $html = '<html><body style="font-family: sans-serif; text-align: center; padding: 40px;">'
                    . '<h1 style="color: #007bff;">Teste de Funcionamento Gotenberg</h1>'
                    . '<p>Documento gerado com sucesso em: <strong>' . now()->format('d/m/Y H:i:s') . '</strong></p>'
                    . '<p style="color: #28a745; font-size: 18px;">✔ Conectividade e renderização Chromium OK</p>'
                    . '</body></html>';

                $conteudo = Gotenberg::htmlToPdf($html);

                if ($conteudo) {
                    $pdfGerado = true;
                    $tamanhoPdf = strlen($conteudo);
                } else {
                    $erroPdf = 'Falha ao converter HTML para PDF via Chromium.';
                }
            } catch (Throwable $e) {
                $erroPdf = $e->getMessage();
            }
        }

        $resultado = [
            'ok' => $health['ok'] && $pdfGerado,
            'health' => $health,
            'pdf_gerado' => $pdfGerado,
            'tamanho_bytes' => $tamanhoPdf,
            'erro_pdf' => $erroPdf,
            'mensagem' => ($health['ok'] && $pdfGerado)
                ? 'Gotenberg está online, saudável e gerando PDFs com sucesso!'
                : ($health['ok'] ? 'Gotenberg respondeu ao health check, mas falhou ao gerar PDF.' : 'Não foi possível conectar ao Gotenberg.'),
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($resultado);
        }

        if ($resultado['ok']) {
            return redirect()->route('configuracoes.index')
                ->with('alert-success', $resultado['mensagem'])
                ->with('resultado_gotenberg', $resultado);
        }

        return redirect()->route('configuracoes.index')
            ->with('alert-danger', $resultado['mensagem'] . ($erroPdf ? ' (' . $erroPdf . ')' : ''))
            ->with('resultado_gotenberg', $resultado);
    }

    /**
     * Gera e exibe em nova aba um PDF de teste gerado pelo Gotenberg.
     */
    public function pdfTeste()
    {
        if (config('laravel-pdf.driver') === 'gotenberg' && ! Gotenberg::isAvailable()) {
            return redirect()
                ->route('configuracoes.index')
                ->with('alert-danger', 'Serviço Gotenberg indisponível no momento.');
        }

        $agora = now()->format('d/m/Y H:i:s');
        $url = Gotenberg::url();
        $driver = config('laravel-pdf.driver');

        $html = <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>PDF de Teste - Gotenberg</title>
  <style>
    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 40px; color: #333; }
    .box { border: 2px solid #28a745; border-radius: 8px; padding: 30px; text-align: center; background-color: #f8fff9; }
    h1 { color: #28a745; margin-bottom: 10px; }
    table { width: 100%; border-collapse: collapse; margin-top: 30px; text-align: left; }
    th, td { border: 1px solid #ddd; padding: 10px; }
    th { background-color: #f2f2f2; width: 35%; }
  </style>
</head>
<body>
  <div class="box">
    <h1>✔ Teste de Funcionamento do Gotenberg</h1>
    <p>Este documento foi gerado pelo serviço Gotenberg através do <strong>datagrad</strong>.</p>
  </div>

  <table>
    <tr><th>Data / Hora da Geração</th><td>{$agora}</td></tr>
    <tr><th>Driver Laravel-Pdf</th><td>{$driver}</td></tr>
    <tr><th>URL do Serviço Gotenberg</th><td>{$url}</td></tr>
    <tr><th>Status de Execução</th><td>Sucesso (Chromium PDF Engine OK)</td></tr>
  </table>
</body>
</html>
HTML;

        return Pdf::html($html)
            ->format('a4')
            ->orientation('portrait')
            ->name('teste-gotenberg.pdf')
            ->inline();
    }
}
