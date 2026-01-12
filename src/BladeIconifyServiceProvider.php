<?php

declare(strict_types=1);

namespace RohitShakya\BladeIconify;

use BladeUI\Icons\Factory;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use RohitShakya\BladeIconify\Commands\IconifyExtractSvgs;

final class BladeIconifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();

        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container) {
            /** @var ConfigRepository $config */
            $config = $container->make(ConfigRepository::class);

            // Global prefix for all sets (default: "rsi")
            $globalSetPrefix = (string) ($config->get('blade-iconify.set_prefix') ?? 'rsi');

            $basePath = __DIR__ . '/../resources/svg';

            if (!File::isDirectory($basePath)) {
                return;
            }

            $factory->add('blade-iconify-icons', [
                'path' => $basePath,
                'prefix' => $globalSetPrefix,
            ]);
        });
    }

    public function boot(): void
    {
        $this->commands([IconifyExtractSvgs::class]);

        $this->publishes([
            __DIR__ . '/../config/blade-iconify.php' => $this->app->configPath('blade-iconify.php'),
        ], 'blade-iconify-config');

        // Optional: publish the svgs to public (only needed if you want public vendor assets)
        $this->publishes([
            __DIR__ . '/../resources/svg' => public_path('vendor/rohitshakya-iconify'),
        ], 'blade-iconify-assets');
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/blade-iconify.php', 'blade-iconify');
    }
}
