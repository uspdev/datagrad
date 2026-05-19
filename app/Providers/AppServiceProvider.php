<?php

namespace App\Providers;

use DateTime;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // URL temporária
        Storage::disk('local')->buildTemporaryUrlsUsing(
            function (string $path, DateTime $expiration, array $options) {
                return URL::temporarySignedRoute(
                    'arquivos.download',
                    $expiration,
                    array_merge($options, ['path' => $path])
                );
            }
        );


        // algumas dessas diretivas ainda não estão sendo usadas
        Blade::directive('limit', function ($e) {
            return "<?php echo '<span title=\"'.e($e).'\">'.e(Str::limit($e)).'</span>'; ?>";
        });
        Blade::directive('upper', fn($e) => "<?php echo strtoupper($e); ?>");
        Blade::directive('lower', fn($e) => "<?php echo strtolower($e); ?>");
        Blade::directive('money', fn($e) => "<?php echo number_format($e, 2, ',', '.'); ?>");
        Blade::directive('date', function ($e) {
            return "<?php
                try {
                    echo !empty($e)
                        ? \\Carbon\\Carbon::parse($e)->format('d/m/Y')
                        : '';
                } catch (\\Exception \$ex) {
                    echo '';
                }
            ?>";
        });
        Blade::directive('datetime', function ($e) {
            return "<?php echo (!empty($e) && strtotime($e)) ? date('d/m/Y H:i', strtotime($e)) : ''; ?>";
        });
        Blade::directive('ago', function ($e) {
            return "
            <?php
                try {
                    if (!empty($e)) {
                        echo \\Carbon\\Carbon::parse($e)->diffForHumans(['parts' => 1]);
                    }
                } catch (Exception \$ex) {
                    echo '';
                }
            ?>";
        });
        Blade::directive('initial', function ($expression) {
            return "<?php
                echo !empty($expression)
                    ? mb_strtoupper(mb_substr($expression, 0, 1))
                    : '';
            ?>";
        });
    }
}
