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

            $basePath = $this->resolveIconSvgPath($config);

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

    /**
     * Decide where Blade Icons should read SVGs from.
     *
     * Mirrors the extraction command output selection:
     *  - export_to=package  => {package}/resources/svg
     *  - export_to=project  => {app}/resources/svg
     *  - export_to=custom   => base_path(custom_path)
     */
    private function resolveIconSvgPath(ConfigRepository $config): string
    {
        $mode = (string) ($config->get('blade-iconify.export_to') ?? 'package');

        if ($mode === 'project') {
            return resource_path('svg');
        }

        if ($mode === 'custom') {
            $rel = (string) ($config->get('blade-iconify.custom_path') ?? '');
            if (trim($rel) !== '') {
                return base_path(trim($rel));
            }

            // Misconfigured, fall back safely
            return __DIR__ . '/../resources/svg';
        }

        return __DIR__ . '/../resources/svg';
    }
}
