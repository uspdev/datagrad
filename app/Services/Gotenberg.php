<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Serviço auxiliar para integração e verificação do serviço Gotenberg
 */
class Gotenberg
{
    /**
     * Retorna a URL base configurada para o Gotenberg
     */
    public static function url(): string
    {
        return rtrim(config('laravel-pdf.gotenberg.url', env('GOTENBERG_URL', 'http://localhost:3000')), '/');
    }

    /**
     * Retorna o usuário configurado para autenticação básica (se houver)
     */
    public static function username(): ?string
    {
        return config('laravel-pdf.gotenberg.username', env('GOTENBERG_USERNAME'));
    }

    /**
     * Retorna a senha configurada para autenticação básica (se houver)
     */
    public static function password(): ?string
    {
        return config('laravel-pdf.gotenberg.password', env('GOTENBERG_PASSWORD'));
    }

    /**
     * Retorna um cliente Http pré-configurado com autenticação e timeouts
     */
    public static function client(int $connectTimeout = 2, int $timeout = 15): PendingRequest
    {
        $request = Http::connectTimeout($connectTimeout)->timeout($timeout);

        $user = self::username();
        $pass = self::password();

        if ($user && $pass) {
            $request = $request->withBasicAuth($user, $pass);
        }

        return $request;
    }

    /**
     * Verifica rapidamente se o Gotenberg está online e saudável (/health).
     *
     * @param int $timeout Segundos limite para a verificação
     * @return bool True se o serviço responder com status 200
     */
    public static function isAvailable(int $timeout = 3): bool
    {
        try {
            $response = self::client(connectTimeout: 2, timeout: $timeout)
                ->get(self::url() . '/health');

            return $response->successful();
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Alias em português para isAvailable()
     */
    public static function disponivel(int $timeout = 3): bool
    {
        return self::isAvailable($timeout);
    }

    /**
     * Realiza uma verificação detalhada do serviço Gotenberg.
     * Útil para rotas de diagnóstico, testes e health-checks.
     *
     * @param int $timeout
     * @return array Resumo contendo ok, status, tempo_ms, body ou erro
     */
    public static function health(int $timeout = 5): array
    {
        $inicio = microtime(true);
        $url = self::url();

        try {
            $response = self::client(connectTimeout: 2, timeout: $timeout)
                ->get($url . '/health');

            return [
                'ok' => $response->successful(),
                'status' => $response->status(),
                'body' => $response->json() ?? $response->body(),
                'tempo_ms' => round((microtime(true) - $inicio) * 1000),
                'url' => $url,
            ];
        } catch (Throwable $e) {
            return [
                'ok' => false,
                'status' => null,
                'erro' => get_class($e),
                'mensagem' => $e->getMessage(),
                'tempo_ms' => round((microtime(true) - $inicio) * 1000),
                'url' => $url,
            ];
        }
    }

    /**
     * Converte HTML diretamente para PDF usando o endpoint de Chromium do Gotenberg
     *
     * @param string $html Conteúdo HTML
     * @param array $options Opções de formulário (ex: paperWidth, paperHeight, etc.)
     * @return string|null Conteúdo binário do PDF ou null em caso de falha
     */
    public static function htmlToPdf(string $html, array $options = []): ?string
    {
        try {
            $request = self::client(connectTimeout: 5, timeout: 60)
                ->attach('files', $html, 'index.html');

            foreach ($options as $key => $value) {
                $request = $request->attach($key, (string) $value);
            }

            $response = $request->post(self::url() . '/forms/chromium/convert/html');

            if ($response->successful()) {
                return $response->body();
            }

            return null;
        } catch (Throwable) {
            return null;
        }
    }
}

