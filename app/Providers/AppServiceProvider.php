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

        Blade::directive('limit', fn($e) => "<?php echo Str::limit($e); ?>");
        Blade::directive('upper', fn($e) => "<?php echo strtoupper($e); ?>");
        Blade::directive('lower', fn($e) => "<?php echo strtolower($e); ?>");
        Blade::directive('money', fn($e) => "<?php echo number_format($e, 2, ',', '.'); ?>");
        Blade::directive('date', fn($e) => "<?php echo date('d/m/Y', strtotime($e)); ?>");
        Blade::directive('datetime', fn($e) => "<?php echo date('d/m/Y H:i', strtotime($e)); ?>");
        Blade::directive('ago', fn($e) => "<?php echo \\Carbon\\Carbon::parse($e)->diffForHumans(); ?>");
    }
}
